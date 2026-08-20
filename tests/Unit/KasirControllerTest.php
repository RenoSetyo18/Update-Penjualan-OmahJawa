<?php

namespace Tests\Unit;

use App\Http\Controllers\KasirController;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\TestCase;

class KasirControllerTest extends TestCase
{
    public function test_receipt_only_includes_selected_transaction_for_paid_order(): void
    {
        $controller = new class extends KasirController {
            public function __construct()
            {
            }

            public function resolveReceiptTransaksisForTest(Transaksi $transaksi, $transaksis = null)
            {
                return $this->getReceiptTransaksis($transaksi, $transaksis);
            }
        };

        $selectedTransaksi = new Transaksi([
            'nomeja' => '10',
            'status' => 'lunas',
            'paid_at' => now(),
        ]);
        $selectedTransaksi->forceFill(['id' => 101]);

        $result = $controller->resolveReceiptTransaksisForTest($selectedTransaksi, collect([$selectedTransaksi]));

        $this->assertCount(1, $result);
        $this->assertSame(101, $result->first()->id);
    }

    public function test_receipt_includes_all_unpaid_transactions_in_same_table_group(): void
    {
        $controller = new class extends KasirController {
            public function __construct()
            {
            }

            public function resolveReceiptTransaksisForTest(Transaksi $transaksi, $transaksis = null)
            {
                return $this->getReceiptTransaksis($transaksi, $transaksis);
            }
        };

        $selectedTransaksi = new Transaksi([
            'nomeja' => '10',
            'status' => 'sedang_diproses',
        ]);
        $selectedTransaksi->forceFill(['id' => 201]);

        $relatedTransaksi = new Transaksi([
            'nomeja' => '10',
            'status' => 'makanan_siap',
        ]);
        $relatedTransaksi->forceFill(['id' => 202]);

        $anotherTableTransaksi = new Transaksi([
            'nomeja' => '11',
            'status' => 'sedang_diproses',
        ]);
        $anotherTableTransaksi->forceFill(['id' => 203]);

        $result = $controller->resolveReceiptTransaksisForTest(
            $selectedTransaksi,
            collect([$selectedTransaksi, $relatedTransaksi, $anotherTableTransaksi])
        );

        $this->assertCount(2, $result);
        $this->assertEqualsCanonicalizing([201, 202], $result->pluck('id')->all());
    }

    public function test_receipt_can_compare_paid_at_when_it_is_a_string(): void
    {
        $controller = new class extends KasirController {
            public function __construct()
            {
            }

            public function resolveReceiptTransaksisForTest(Transaksi $transaksi, $transaksis = null)
            {
                return $this->getReceiptTransaksis($transaksi, $transaksis);
            }
        };

        $paidAt = Carbon::parse('2026-07-06 10:30:00')->toDateTimeString();

        $selectedTransaksi = new Transaksi([
            'nomeja' => '10',
            'status' => 'lunas',
            'paid_at' => $paidAt,
        ]);
        $selectedTransaksi->forceFill(['id' => 301]);

        $samePaidAtTransaksi = new Transaksi([
            'nomeja' => '10',
            'status' => 'lunas',
            'paid_at' => $paidAt,
        ]);
        $samePaidAtTransaksi->forceFill(['id' => 302]);

        $differentPaidAtTransaksi = new Transaksi([
            'nomeja' => '10',
            'status' => 'lunas',
            'paid_at' => Carbon::parse('2026-07-06 11:00:00')->toDateTimeString(),
        ]);
        $differentPaidAtTransaksi->forceFill(['id' => 303]);

        $result = $controller->resolveReceiptTransaksisForTest(
            $selectedTransaksi,
            collect([$selectedTransaksi, $samePaidAtTransaksi, $differentPaidAtTransaksi])
        );

        $this->assertCount(2, $result);
        $this->assertEqualsCanonicalizing([301, 302], $result->pluck('id')->all());
    }

    public function test_payment_amount_prefers_persisted_value_when_request_has_no_amount(): void
    {
        $controller = new class extends KasirController {
            public function __construct()
            {
            }

            public function resolvePaymentAmountForTest(Request $request, Transaksi $transaksi, float $totalHarga)
            {
                return $this->resolvePaymentAmount($request, $transaksi, $totalHarga);
            }
        };

        $request = new Request();
        $transaksi = new Transaksi([
            'nomeja' => '10',
            'status' => 'lunas',
        ]);
        $transaksi->forceFill(['id' => 401, 'jumlah_bayar' => 150000.00]);

        $this->assertSame(150000.0, $controller->resolvePaymentAmountForTest($request, $transaksi, 120000.0));
    }
}
