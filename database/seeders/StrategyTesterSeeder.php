<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StrategyTesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $listHours = [
            [
                "dsHorarioInicio" => "09:00",
                "dsHorarioFinal" => "10:00",
                "nrPrioridade" => 40
            ],
            [
                "dsHorarioInicio" => "10:01",
                "dsHorarioFinal" => "11:00",
                "nrPrioridade" => 30
            ],
            [
                "dsHorarioInicio" => "11:01",
                "dsHorarioFinal" => "12:00",
                "nrPrioridade" => 20
            ]
        ];


        $strategySeed = \App\Models\StrategyWms::create([
            'ds_estrategia_wms' => 'RETIRA',
            'nr_prioridade' => 10,
            'dt_registro' => now(),
        ]);

        foreach($listHours as $hourItem){
            $strategySeed->horariosPrioridade()->create([
                'cd_estrategia_wms' => $strategySeed->id,
                'ds_horario_inicio' => $hourItem['dsHorarioInicio'],
                'ds_horario_final' => $hourItem['dsHorarioFinal'],
                'nr_prioridade' => $hourItem['nrPrioridade'],
                'dt_registro' => now(),
            ]);

        }

    }
}
