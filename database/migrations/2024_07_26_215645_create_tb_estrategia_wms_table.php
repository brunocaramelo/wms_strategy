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

        Schema::create('tb_estrategia_wms', function (Blueprint $table) {

            $table->increments('cd_estrategia_wms')->generatedAs()->always();
            $table->string('ds_estrategia_wms');
            $table->integer('nr_prioridade')->index();;
            $table->timestamp('dt_registro')->index();;
            $table->timestamp('dt_modificado')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_estrategia_wms');
    }
};
