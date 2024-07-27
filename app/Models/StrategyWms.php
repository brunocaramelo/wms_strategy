<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StrategyWms extends Model
{
    protected $table = 'tb_estrategia_wms';
    protected $primaryKey = 'cd_estrategia_wms';
    protected $fillable = [
        'ds_estrategia_wms',
        'nr_prioridade',
        'dt_registro',
        'dt_modificado',
    ];
    public $timestamps = false;

    protected $dates = [
        'dt_registro',
        'dt_modificado',
    ];


    public function horariosPrioridade(): HasMany
    {
        return $this->hasMany(StrategyWmsHourPriority::class, 'cd_estrategia_wms');
    }
}
