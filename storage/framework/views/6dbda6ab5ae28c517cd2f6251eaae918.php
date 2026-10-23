<script>
    window.printBluetoothReceipt = async function (receipt) {
        if (!receipt || !Array.isArray(receipt.items)) {
            throw new Error('Data struk tidak lengkap.');
        }
        if (!window.isSecureContext || !navigator.bluetooth?.requestDevice) {
            throw new Error('Bluetooth tidak tersedia. Gunakan browser yang mendukung Web Bluetooth melalui HTTPS atau localhost.');
        }

        const profiles = [
            {
                serviceUuid: '000018f0-0000-1000-8000-00805f9b34fb',
                characteristicUuids: ['00002af1-0000-1000-8000-00805f9b34fb']
            },
            {
                serviceUuid: '0000ffe0-0000-1000-8000-00805f9b34fb',
                characteristicUuids: ['0000ffe1-0000-1000-8000-00805f9b34fb']
            },
            {
                serviceUuid: '0000ff00-0000-1000-8000-00805f9b34fb',
                characteristicUuids: [
                    '0000ff02-0000-1000-8000-00805f9b34fb',
                    '0000ff01-0000-1000-8000-00805f9b34fb'
                ]
            },
            {
                serviceUuid: '0000fff0-0000-1000-8000-00805f9b34fb',
                characteristicUuids: [
                    '0000fff2-0000-1000-8000-00805f9b34fb',
                    '0000fff1-0000-1000-8000-00805f9b34fb'
                ]
            }
        ];

        let config = {};
        try {
            config = JSON.parse(localStorage.getItem('omah-jawa.bluetooth-printer') || '{}');
        } catch (error) {
            console.warn('Konfigurasi printer tersimpan tidak dapat dibaca:', error);
        }
        const width = Number.isInteger(Number(config.columns))
            ? Math.min(48, Math.max(24, Number(config.columns)))
            : 32;
        const separator = '-'.repeat(width);
        const ascii = value => String(value ?? '')
            .normalize('NFKD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/[^\x20-\x7E]/g, '?');
        const formatAmount = value => {
            const amount = Number(value);
            if (!Number.isFinite(amount)) {
                throw new Error('Nilai nominal pada data struk tidak valid.');
            }
            return Math.round(amount).toLocaleString('id-ID');
        };
        const formatDate = value => {
            const match = String(value || '').match(/^(\d{2})-(\d{2})-(\d{4})\s+(\d{2}:\d{2})$/);
            if (!match) return value || '-';
            const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            return `${match[1]} ${monthNames[Number(match[2]) - 1] || match[2]} ${match[3]}, ${match[4]}`;
        };
        const pair = (label, value) => {
            const left = ascii(label);
            const right = ascii(value);
            return left.length + right.length + 1 > width
                ? `${left}\n${right.padStart(width)}`
                : `${left}${' '.repeat(width - left.length - right.length)}${right}`;
        };
        const wrap = value => {
            const words = ascii(value).split(/\s+/).filter(Boolean);
            const lines = [];
            let line = '';
            words.forEach(word => {
                while (word.length > width) {
                    if (line) {
                        lines.push(line);
                        line = '';
                    }
                    lines.push(word.slice(0, width));
                    word = word.slice(width);
                }
                if (!line) {
                    line = word;
                } else if (`${line} ${word}`.length <= width) {
                    line += ` ${word}`;
                } else {
                    lines.push(line);
                    line = word;
                }
            });
            if (line) lines.push(line);
            return lines.length ? lines : [''];
        };
        const itemCount = receipt.items.reduce((count, item) => {
            const quantity = Number(item.quantity);
            if (!Number.isFinite(quantity) || quantity < 0) {
                throw new Error('Jumlah item pada struk tidak valid.');
            }
            return count + quantity;
        }, 0);
        const paymentMethod = String(receipt.payment_method || '-');
        const paymentLabel = paymentMethod.toLowerCase() === 'cash' ? 'Tunai' : paymentMethod;
        const lines = [
            '\x1B\x40\x1B\x61\x01\x1B\x45\x01Omah Jawa',
            'Pusat',
            '',
            'Instagram : warung_omah_jawa',
            '\x1B\x45\x00\x1B\x61\x00' + separator,
            pair('Kasir', receipt.cashier_name || 'Kasir'),
            pair('Waktu', formatDate(receipt.paid_at)),
            pair('No. Struk', receipt.transaction_code || '-'),
            ...(receipt.status === 'lunas' ? [pair('Bayar', paymentLabel)] : []),
            separator,
            `\x1B\x61\x01\x1B\x45\x01*** ${receipt.status_label || (receipt.status === 'lunas' ? 'Lunas' : 'Belum Lunas')} ***\x1B\x45\x00\x1B\x61\x00`,
            separator
        ];

        receipt.items.forEach(item => {
            const nameLines = wrap(item.name || 'Item');
            lines.push('\x1B\x61\x00' + nameLines[0], ...nameLines.slice(1));
            const quantity = Number(item.quantity);
            const price = Number(item.price);
            const subtotal = Number(item.subtotal);
            if (![quantity, price, subtotal].every(Number.isFinite)) {
                throw new Error('Ada rincian item struk yang tidak valid.');
            }
            lines.push(pair(`${formatAmount(price)} x ${quantity}`, formatAmount(subtotal)));
        });

        lines.push(
            separator,
            pair('Subtotal', formatAmount(receipt.subtotal ?? receipt.total)),
            pair(`PPN${receipt.ppn_rate !== null ? ` (${Number(receipt.ppn_rate).toFixed(2)}%)` : ''}`, formatAmount(receipt.ppn_amount)),
            separator,
            '\x1B\x45\x01' + pair(`Total (${itemCount})`, formatAmount(receipt.total)) + '\x1B\x45\x00',
            separator,
            ...(receipt.status === 'lunas'
                ? [
                    pair('Bayar', formatAmount(receipt.paid)),
                    pair('Kembali', formatAmount(receipt.change))
                ]
                : [pair('Status', 'Belum Lunas')]),
            '',
            '\x1B\x61\x01Panggonan Mewah, Regane Murah,',
            'Ning ati bungah',
            '',
            'Powered by Qasir',
            'www.qasir.id\x1B\x61\x00',
            '',
            '',
            '',
            '\x1D\x56\x00'
        );

        const device = await navigator.bluetooth.requestDevice({
            acceptAllDevices: true,
            optionalServices: profiles.map(profile => profile.serviceUuid)
        });
        if (!device.gatt) {
            throw new Error('Perangkat ini tidak menyediakan Bluetooth BLE GATT.');
        }

        const server = await device.gatt.connect();
        let characteristic = null;
        const services = await server.getPrimaryServices();
        for (const profile of profiles) {
            const service = services.find(item => item.uuid === profile.serviceUuid);
            if (!service) continue;
            const characteristics = await service.getCharacteristics();
            const writable = characteristics.filter(item =>
                item.properties.write || item.properties.writeWithoutResponse
            );
            characteristic = profile.characteristicUuids
                .map(uuid => writable.find(item => item.uuid === uuid))
                .find(Boolean) || writable[0] || null;
            if (characteristic) break;
        }
        if (!characteristic) {
            device.gatt.disconnect();
            throw new Error('Printer terhubung tetapi profil BLE ESC/POS tidak dikenali.');
        }

        const bytes = new TextEncoder().encode(lines.join('\n'));
        for (let offset = 0; offset < bytes.length; offset += 20) {
            const chunk = bytes.slice(offset, offset + 20);
            if (characteristic.properties.write &&
                typeof characteristic.writeValueWithResponse === 'function') {
                await characteristic.writeValueWithResponse(chunk);
            } else if (characteristic.properties.writeWithoutResponse &&
                typeof characteristic.writeValueWithoutResponse === 'function') {
                await characteristic.writeValueWithoutResponse(chunk);
            } else if (characteristic.properties.write &&
                typeof characteristic.writeValue === 'function') {
                await characteristic.writeValue(chunk);
            } else {
                device.gatt.disconnect();
                throw new Error('Printer tidak mendukung pengiriman data struk.');
            }
        }
    };
</script>
<?php /**PATH C:\laragon\www\omahjawa1Deploy\resources\views/components/receipt-printer-script.blade.php ENDPATH**/ ?>