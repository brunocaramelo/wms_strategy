<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StrategyWmsHourPriority extends Model
{
    protected $table = 'tb_estrategia_wms_horario_prioridade';
    protected $primaryKey = 'cd_estrategia_wms';
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
}
