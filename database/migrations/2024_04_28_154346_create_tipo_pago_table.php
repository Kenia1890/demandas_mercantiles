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
        Schema::create('tipo_pago', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('pago')->nullable(false);
            $table->timestamps();

        });
        DB::table('tipo_pago')->insert([
            'pago' => 'Banco',
            'created_at' => now(),
            'updated_at' => now()
        ]);
 DB::table('tipo_pago')->insert([
            'pago' => 'Efectivo',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_pago');
    }
};
