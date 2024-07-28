<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\StrategyWms;
class StrategyWmsHourPriority extends Model
{
    protected $table = 'tb_estrategia_wms_horario_prioridade';
    protected $primaryKey = 'cd_estrategia_wms_horario_prioridade';
    protected $fillable = [
        'cd_estrategia_wms_horario_prioridade',
        'cd_estrategia_wms',
        'ds_horario_inicio',
        'ds_horario_final',
        'nr_prioridade',
        'dt_registro',
        'dt_modificado',
    ];

    public $timestamps = false;
    protected $dates = [
        'dt_registro',
        'dt_modificado',
    ];


    public function estrategia(): BelongsTo
    {
        return $this->belongsTo(StrategyWms::class, 'cd_estrategia_wms', 'cd_estrategia_wms');
    }
}
