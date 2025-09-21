<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportAnalysisTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_superadmin_can_visit_report_analysis_of_product_sales(): void
    {
        $this->actingAs(User::factory()->superadmin()->create());
        $res = $this->get(route('report-analysis.product-sales'));
        $res->assertStatus(200);
        $res->assertViewIs('reportAnalysis.productSales');
    }

    public function test_superadmin_can_visit_report_analysis_of_service_history(): void
    {
        $this->actingAs(User::factory()->superadmin()->create());
        $res = $this->get(route('report-analysis.service-history'));
        $res->assertStatus(200);
        $res->assertViewIs('reportAnalysis.serviceHistory');
    }

    public function test_not_superadmin_cannot_visit_report_analysis_of_product_sales(): void
    {
        $this->actingAs(User::factory()->create());
        $res = $this->get(route('report-analysis.product-sales'));
        $res->assertStatus(403);
    }

    public function test_not_superadmin_cannot_visit_report_analysis_of_service_history(): void
    {
        $this->actingAs(User::factory()->create());
        $res = $this->get(route('report-analysis.service-history'));
        $res->assertStatus(403);
    }
}
