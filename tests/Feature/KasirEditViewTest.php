<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\Transaksi;
use Illuminate\Support\MessageBag;
use Tests\TestCase;

class KasirEditViewTest extends TestCase
{
    public function test_transaction_edit_view_renders_for_an_unpaid_order(): void
    {
        $transaksi = new Transaksi([
            'kode' => 'TRX-TEST123',
            'atasnama' => 'Pelanggan',
            'status' => Transaksi::STATUS_BELUM_BAYAR,
        ]);
        $transaksi->forceFill(['id' => 1]);
        $detail = new \App\Models\DetailTransaksi([
            'menu_id' => 1,
            'jumlah' => 2,
            'note' => null,
        ]);
        $detail->setRelation('menu', new Menu(['nama' => 'Nasi Goreng', 'harga' => 15000]));
        $transaksi->setRelation('details', collect([$detail]));

        $menu = new Menu(['nama' => 'Nasi Goreng', 'harga' => 15000]);
        $menu->forceFill(['id' => 1]);

        $this->view('pages.kasir.edit', [
            'transaksi' => $transaksi,
            'menus' => collect([$menu]),
            'errors' => new MessageBag(),
        ])
            ->assertSee('Edit Transaksi')
            ->assertSee('Atas Nama Konsumen')
            ->assertSee('Pilih Menu untuk Ditambahkan')
            ->assertSee('Cari menu...')
            ->assertSee('Jumlah')
            ->assertSee('transactionMenuPreviousPage')
            ->assertSee('transactionMenuNextPage')
            ->assertSee('add-transaction-menu')
            ->assertSee('Simpan Menu')
            ->assertSee('Nasi Goreng')
            ->assertSee('name="items[0][menu_id]"', false)
            ->assertSee('name="items[0][jumlah]"', false)
            ->assertSee('name="items[0][note]"', false)
            ->assertDontSee('<select class="form-control transaction-menu"')
            ->assertDontSee('Nomor Meja')
            ->assertDontSee('Status Transaksi')
            ->assertDontSee('Metode Pembayaran')
            ->assertDontSee('Jumlah Bayar')
            ->assertDontSee('Waktu Pembayaran');
    }
}
