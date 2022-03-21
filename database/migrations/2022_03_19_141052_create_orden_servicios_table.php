<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orden_servicios', function (Blueprint $table) {
            $table->id();
            $table->string('horometroInicial');
            $table->string('horasPromedio');
            $table->string('valorXhora');
            $table->string('Descuento')->nullable();
            $table->string('pagare');
            $table->string('valorIda');
            $table->string('valorVuelta')->nullable();
            $table->foreignId('maquina');
            $table->foreign('maquina')->references('id')->on('maquinas');
            $table->foreignId('cliente');
            $table->foreign('cliente')->references('id')->on('clientes');
            $table->timestamps();
            $table->softDeletes();  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orden_servicios');
    }
};
