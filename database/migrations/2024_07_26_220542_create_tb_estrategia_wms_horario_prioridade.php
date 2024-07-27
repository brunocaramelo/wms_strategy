<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tb_estrategia_wms_horario_prioridade', function (Blueprint $table) {

            $table->increments('cd_estrategia_wms_horario_prioridade')->generatedAs()->always();

            $table->integer('cd_estrategia_wms');

            $table->string('ds_horario_inicio')->index();;
            $table->string('ds_horario_final')->index();;
            $table->integer('nr_prioridade')->index();;

            $table->timestamp('dt_registro');
            $table->timestamp('dt_modificado')->nullable();

            $table->foreign('cd_estrategia_wms')->references('cd_estrategia_wms')->on('tb_estrategia_wms');

        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_estrategia_wms_horario_prioridade');
    }
};
