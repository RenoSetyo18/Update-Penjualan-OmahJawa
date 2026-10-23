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

    public function test_receipt_keeps_unpaid_transactions_separate_even_at_same_table(): void
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
            'status' => 'belum_bayar',
        ]);
        $selectedTransaksi->forceFill(['id' => 201]);

        $relatedTransaksi = new Transaksi([
            'nomeja' => '10',
            'status' => 'belum_bayar',
        ]);
        $relatedTransaksi->forceFill(['id' => 202]);

        $anotherTableTransaksi = new Transaksi([
            'nomeja' => '11',
            'status' => 'belum_bayar',
        ]);
        $anotherTableTransaksi->forceFill(['id' => 203]);

        $result = $controller->resolveReceiptTransaksisForTest(
            $selectedTransaksi,
            collect([$selectedTransaksi, $relatedTransaksi, $anotherTableTransaksi])
        );

        $this->assertCount(1, $result);
        $this->assertSame(201, $result->first()->id);
    }

    public function test_receipt_keeps_unassigned_table_orders_separate(): void
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
            'nomeja' => null,
            'status' => Transaksi::STATUS_BELUM_BAYAR,
        ]);
        $selectedTransaksi->forceFill(['id' => 211]);

        $anotherUnassignedTransaksi = new Transaksi([
            'nomeja' => null,
            'status' => Transaksi::STATUS_BELUM_BAYAR,
        ]);
        $anotherUnassignedTransaksi->forceFill(['id' => 212]);

        $result = $controller->resolveReceiptTransaksisForTest(
            $selectedTransaksi,
            collect([$selectedTransaksi, $anotherUnassignedTransaksi])
        );

        $this->assertCount(1, $result);
        $this->assertSame(211, $result->first()->id);
    }

    public function test_receipt_keeps_paid_transactions_separate_when_paid_at_matches(): void
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

        $this->assertCount(1, $result);
        $this->assertSame(301, $result->first()->id);
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

    public function test_transaction_status_labels_only_show_unpaid_or_paid(): void
    {
        $this->assertSame('Belum Bayar', Transaksi::transaksiStatusLabel(Transaksi::STATUS_BELUM_BAYAR));
        $this->assertSame('Belum Lunas', Transaksi::kasirStatusLabel(Transaksi::STATUS_BELUM_BAYAR));
        $this->assertSame('Lunas', Transaksi::kasirStatusLabel(Transaksi::STATUS_LUNAS));
    }
}
