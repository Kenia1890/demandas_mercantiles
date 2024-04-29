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
        Schema::create('bancos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('banco')->nullable(false);
            $table->string('clabe_banco')->nullable(true);
            $table->timestamps();
        });
        DB::table('bancos')->insert([
            ['banco' => 'Bancomer', 'clabe_banco' => '012'],
            ['banco' => 'Santander', 'clabe_banco' => '014'],
            ['banco' => 'HSBC', 'clabe_banco' => '022'],
            ['banco' => 'Efectivo', 'clabe_banco' => null] 
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bancos');
    }
};
