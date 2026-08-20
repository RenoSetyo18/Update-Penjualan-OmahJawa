<?php

namespace Tests\Feature;

use App\Http\Controllers\KasirController;
use Illuminate\Http\Request;
use Tests\TestCase;

class KasirRekapViewTest extends TestCase
{
    public function test_rekap_view_shows_selected_date_range_in_period_label(): void
    {
        $controller = new class extends KasirController {
            public function __construct()
            {
            }
        };

        $request = new Request([
            'dari' => '2026-07-01',
            'sampai' => '2026-07-05',
        ]);

        $view = $controller->cetakRekap($request);
        $content = $view->render();

        $this->assertStringContainsString('01 Jul 2026 s.d. 05 Jul 2026', $content);
        $this->assertStringNotContainsString('Semua periode', $content);
    }
}
