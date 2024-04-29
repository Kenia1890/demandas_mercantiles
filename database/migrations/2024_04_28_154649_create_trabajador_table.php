<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trabajador', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('rfc')->nullable(false);
            $table->string('apaterno')->nullable(false);
            $table->string('amaterno')->nullable(false);
            $table->string('nombre')->nullable(false);
            $table->timestamps();
        });
        DB::table('trabajador')->insert([
            ['rfc' => 'AUSI950328AM9', 'apaterno' => 'AGUILAR', 'amaterno' => 'SOSA', 'nombre' => 'ITATI DE LA CRUZ', 'created_at' => now(), 'updated_at' => now()],
            ['rfc' => 'MUMS790517IA1', 'apaterno' => 'MUÑOZ', 'amaterno' => 'MONTERO', 'nombre' => 'SILVIA SEMEI', 'created_at' => now(), 'updated_at' => now()],
            ['rfc' => 'AARC950526IE1', 'apaterno' => 'ALVAREZ', 'amaterno' => 'ROMERO', 'nombre' => 'CARLOS ANTONIO', 'created_at' => now(), 'updated_at' => now()]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trabajador');
    }
};
