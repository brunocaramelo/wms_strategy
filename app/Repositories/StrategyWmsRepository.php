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
            'cdEstrategia' => $codeStrategy,
            'rangeHour' => "$hour:$instant"
        ]);
    }

    public function searchDataGet(array $filters)
    {
        return $this->filterBuildData($filters)
                    ->get();
    }
    public function searchAndPaginate(array $filters)
    {
        return $this->filterBuildData($filters)
                    ->paginate($filters['page_size'] ?? 10);
    }

    public function filterBuildData(array $filters)
    {
        return $this->model::with(['horariosPrioridade' => function($query) use ($filters) {
            $query->when(!empty($filters['rangeHour']), function ($query) use ($filters) {
                    $hourInt = str_pad($filters['rangeHour'], 4, '0', STR_PAD_LEFT);

                    $query->whereRaw("CAST(ds_horario_inicio  AS TIME) <= CAST(?  AS TIME)  AND CAST(ds_horario_final  AS TIME) >= CAST(?  AS TIME) ", ["'$hourInt:00'", "'$hourInt:59'"]);
                });
            }])
            ->when(!empty($filters['cdEstrategia']), function ($query) use ($filters) {
                $query->where('cd_estrategia_wms', '=' ,$filters['cdEstrategia']);
            });
    }

    public function create(array $data)
    {
        $instance = $this->model::create(array_merge(['dt_registro' => now()], $data));

        foreach ($data['horarios'] as $horario) {
            $instance->horariosPrioridade()->create(array_merge(['dt_registro' => now()], $horario));
        }

        return $instance;
    }
}
