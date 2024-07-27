<?php

namespace App\Repositories;

use App\Models\StrategyWms;
use App\Interfaces\StrategyWmsInterface;

class StrategyWmsRepository implements StrategyWmsInterface
{
    private $model = StrategyWms::class;

    public function findByHourInstant(int $codeStrategy, string $hour, string $instant)
    {
        return $this->searchDataGet([
            'cd_estrategia' => $codeStrategy,
            'range_hour' => "$hour:$instant"
        ]);
    }

    public function searchDataGet(array $filters)
    {
        return $this->filterBuildData($filters)
                ->get();
    }

    public function filterBuildData(array $filters)
    {
        return $this->model::with(['horariosPrioridade' => function($query) use ($filters) {
            $query->when(!empty($filters['range_hour']), function ($query) use ($filters) {
                    $hourInt = str_pad($filters['range_hour'], 4, '0', STR_PAD_LEFT);

                    $query->whereRaw("CAST(ds_horario_inicio  AS TIME) <= CAST(?  AS TIME)  AND CAST(ds_horario_final  AS TIME) >= CAST(?  AS TIME) ", ["'$hourInt:00'", "'$hourInt:59'"]);

                });
            }])
            ->when(!empty($filters['cd_estrategia']), function ($query) use ($filters) {
                $query->where('cd_estrategia_wms', '=' ,$filters['cd_estrategia']);
            });

    }

    public function create(array $data)
    {
        $instance = $this->model::create(array_merge(['dt_registro' => now()], $data));

        foreach($data['horarios'] as $horario) {
            $instance->horariosPrioridade()->create(array_merge(['dt_registro' => now()], $horario));
        }

        return $instance;
    }
}
