<?php

namespace Tests\Feature\Http\Controllers;

use App\Exceptions\PrioriyNotFoundException;
use App\Services\StrategyWmsService;
use App\Repositories\StrategyWmsRepository;

use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;

class StrategyWmsUnitTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        \Artisan::call('migrate:fresh');
        \Artisan::call('db:seed');
    }

    public function test_create_strategy_success()
    {
        $response = (new StrategyWmsService(new StrategyWmsRepository()))->store([
                "dsEstrategia" => "RETIRA",
                "nrPrioridade" => 10,
                "horarios" => [
                    [
                        "dsHorarioInicio" => "09:10",
                        "dsHorarioFinal" => "10:30",
                        "nrPrioridade" => 70
                    ],
                    [
                        "dsHorarioInicio" => "10:40",
                        "dsHorarioFinal" => "12:30",
                        "nrPrioridade" => 50
                    ],
            ]
        ]);

        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('message', $response);

        $this->assertEquals('success', $response['status']);
    }

    public function test_find_fail_priority()
    {
        $this->expectException(PrioriyNotFoundException::class);

        (new StrategyWmsService(new StrategyWmsRepository()))->findByHourInstant(999, 10, 25);

    }

    public function test_find_success_priority()
    {
        $response = (new StrategyWmsService(new StrategyWmsRepository()))->findByHourInstant(1, 10, 25);

        $this->assertArrayHasKey('nrPrioridade', $response);
        $this->assertEquals(30, $response['nrPrioridade']);
    }

    public function test_find_success_default_priority()
    {
        $response = (new StrategyWmsService(new StrategyWmsRepository()))->findByHourInstant(1, 22, 35);

        $this->assertArrayHasKey('nrPrioridade', $response);
        $this->assertEquals(10, $response['nrPrioridade']);
    }

    public function test_search_success_with_results()
    {
        $response = (new StrategyWmsService(new StrategyWmsRepository()))->searchAndPaginate([]);

        $this->assertArrayHasKey('current_page', $response);
        $this->assertArrayHasKey('data', $response);
        $this->assertArrayHasKey('per_page', $response);
        $this->assertArrayHasKey('to', $response);
        $this->assertArrayHasKey('total', $response);

        $this->assertEquals(1, $response['total']);
        $this->assertEquals(40, $response['data'][0]['horariosPrioridade'][0]['nrPrioridade']);
        $this->assertEquals('09:00', $response['data'][0]['horariosPrioridade'][0]['dsHorarioInicio']);
    }

}
