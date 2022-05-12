<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('deudas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mantenimiento');
            $table->foreign('mantenimiento')->references('id')->on('mantenimientos');
            $table->double('valor');
            $table->string('estado');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('deudas');
    }
};
