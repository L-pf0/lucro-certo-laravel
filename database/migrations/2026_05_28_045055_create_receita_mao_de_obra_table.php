<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('receita_mao_de_obra', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receita_id')->constrained()->cascadeOnDelete();
            // Atenção: nome da tabela de mão de obra é 'mao_de_obra' (singular)
            $table->foreignId('mao_de_obra_id')->constrained('mao_de_obra')->cascadeOnDelete();
            $table->decimal('horas', 5, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('receita_mao_de_obra');
    }
};

