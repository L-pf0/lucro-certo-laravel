<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('relatorios', function (Blueprint $table) {
            $table->id();

            // tipo de relatório
            $table->enum('tipo', ['pdf', 'csv', 'dre']);

            // período analisado
            $table->string('periodo', 7); // YYYY-MM

            //  arquivo gerado
            $table->text('arquivo_url');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('relatorios');
    }
};