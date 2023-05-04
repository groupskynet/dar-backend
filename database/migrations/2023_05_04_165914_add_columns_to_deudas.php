<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::table('deudas', function (Blueprint $table) {
            $table->dropForeign('deudas_mantenimiento_foreign');
            $table->dropColumn('mantenimiento');
            $table->unsignedBigInteger('relation_id');
            $table->string('modelo');
        });
    }

    public function down()
    {
        Schema::table('deudas', function (Blueprint $table) {
            $table->foreignId('mantenimiento');
            $table->foreign('mantenimiento')->references('id')->on('mantenimientos');
            $table->dropColumn('relation_id');
            $table->dropColumn('modelo');
        });
    }
};
