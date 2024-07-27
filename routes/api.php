<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\StrategyWmsController;


Route::controller(StrategyWmsController::class)
->prefix('/estrategiaWMS')
->group(function(){
    Route::post('/', 'storeStrategy');
    Route::get('/{codeStrategy}/{hour}/{instant}/prioridade', 'findByIdentityAndHourInstant');
});

