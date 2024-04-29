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
        Schema::create('demandas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('fecha');
            $table->string('oficio');
            $table->unsignedBigInteger('id_trabajador')->unsigned();
            $table->string('acreedor');
            $table->unsignedBigInteger('id_tipo_importe')->unsigned();
            $table->decimal('monto_descontar', 10, 2);
            $table->unsignedBigInteger('id_tipo_pago')->unsigned();
            $table->unsignedBigInteger('id_banco')->unsigned();
            $table->string('clabe'); 
            $table->timestamps();

            $table->foreign("id_trabajador")->references("id")->on("trabajador")->onDelete("restrict")->onUpdate("cascade");
            $table->foreign("id_tipo_importe")->references("id")->on("tipo_importe")->onDelete("restrict")->onUpdate("cascade");
            $table->foreign("id_tipo_pago")->references("id")->on("tipo_pago")->onDelete("restrict")->onUpdate("cascade");
            $table->foreign("id_banco")->references("id")->on("bancos")->onDelete("restrict")->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demandas');
    }
};
