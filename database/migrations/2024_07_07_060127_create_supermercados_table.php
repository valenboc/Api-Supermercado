<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupermercadosTable extends Migration{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('supermercados', function (Blueprint $table) {
            $table->id('ID_supermercado');
            $table->string('Nombre');
            $table->string('NIT')->unique();
            $table->string('Direccion');
            $table->string('Logo');
            $table->string('Longitud');
            $table->string('Latitud');
            $table->unsignedBigInteger('ID_ciudad');
            $table->timestamps();

            $table->foreign('ID_ciudad')->references('ID_ciudad')->on('ciudades')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supermercados');
    }
}
