<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('insumos', function (Blueprint $table) {
            $table->id();

            // relacionamento com usuário
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // dados do insumo
            $table->string('nome');

            $table->enum('unidade_medida', ['kg', 'g', 'l', 'ml', 'unidade']);

            $table->decimal('preco_unitario', 10, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insumos');
    }
};
