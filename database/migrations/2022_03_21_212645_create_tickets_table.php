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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('nOrden');
            $table->date('fecha');
            $table->string('horometroInicial');
            $table->string('horometroFinal');
            $table->string('galones')->nullable();
            $table->string('costo')->nullable();
            $table->foreignId('cliente');
            $table->foreign('cliente')->references('id')->on('clientes');
            $table->foreignId('maquina');
            $table->foreign('maquina')->references('id')->on('maquinas');
            $table->foreignId('accesorio')->nullable();
            $table->foreign('accesorio')->references('id')->on('accesorios');
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
        Schema::dropIfExists('tickets');
    }
};
