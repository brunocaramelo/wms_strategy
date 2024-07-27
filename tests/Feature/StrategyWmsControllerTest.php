<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StrategyWmsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        \Artisan::call('db:seed');
    }

    public function test_create_strategy_success()
    {
        $this->postJson('/api/estrategiaWMS',[
            "dsEstrategia" => "RETIRA",
            "nrPrioridade"=> 10,
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
        ])->assertStatus(200)
        ->assertJsonStructure([
            'status',
            'message',
            'data',
        ]);

    }
    public function test_search_list_with_result_success()
    {
        $this->getJson('/api/estrategiaWMS')
        ->assertStatus(200)
        ->assertJsonStructure([
            'current_page',
            'data',
            'per_page',
            'to',
            'total',
        ])->assertJsonPath('total', 1)
        ->assertJsonPath('data.0.dsEstrategiaWms', 'RETIRA')
        ->assertJsonPath('data.0.horariosPrioridade.0.nrPrioridade', 40);

    }

    public function test_search_list_without_results_success()
    {
        $this->get('/api/estrategiaWMS?'. \Arr::query([
            'cdEstrategia' => 99,
            'rangeHour' => "22:00",
        ]))->assertStatus(200)
        ->assertJsonStructure([
            'current_page',
            'data',
            'per_page',
            'to',
            'total',
        ])->assertJsonPath('total', 0);

    }

    public function test_get_priority_with_success()
    {
        $cdEstrategia = 1;
        $dsHora = '10';
        $dsMinuto = '30';

        $this->getJson("/api/estrategiaWMS/$cdEstrategia/$dsHora/$dsMinuto/prioridade")
        ->assertStatus(200)
        ->assertJsonStructure([
            'nrPrioridade',
        ])->assertJsonPath('nrPrioridade', 10);

    }
    public function test_get_priority_with_default_success()
    {
        $cdEstrategia = 1;
        $dsHora = '20';
        $dsMinuto = '30';

        $this->getJson("/api/estrategiaWMS/$cdEstrategia/$dsHora/$dsMinuto/prioridade")
        ->assertStatus(200)
        ->assertJsonStructure([
            'nrPrioridade',
        ])->assertJsonPath('nrPrioridade', 10);

    }
    public function test_get_priority_with_fail()
    {
        $cdEstrategia = 999;
        $dsHora = '20';
        $dsMinuto = '30';

        $this->getJson("/api/estrategiaWMS/$cdEstrategia/$dsHora/$dsMinuto/prioridade")
        ->assertStatus(404)
        ->assertJsonStructure([
            'message',
            'status',
        ])->assertJsonPath('message', 'Estratégia não encontrada')
        ->assertJsonPath('status', 'fail');
    }

}
