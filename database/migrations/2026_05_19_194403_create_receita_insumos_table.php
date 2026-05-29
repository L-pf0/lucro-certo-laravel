<?php
// database/migrations/YYYY_MM_DD_HHMMSS_create_receita_insumos_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('receita_insumos', function (Blueprint $table) {
            $table->id();
            
            // 🔗 relacionamento com receitas
            $table->foreignId('receita_id')
                ->constrained()
                ->cascadeOnDelete();
            
            // 🔗 relacionamento com insumos
            $table->foreignId('insumo_id')
                ->constrained()
                ->cascadeOnDelete();
            
            // 📦 quantidade usada na receita
            $table->decimal('quantidade', 10, 3);
            
            // 💰 snapshot do custo no momento da criação
            $table->decimal('custo_unitario', 10, 2);
            
            // 💰 custo total (quantidade * custo_unitario)
            $table->decimal('custo_total', 10, 2);
            
            $table->timestamps();
            
            // 🔑 índice composto para evitar duplicidade
            $table->unique(['receita_id', 'insumo_id'], 'receita_insumo_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receita_insumos');
    }
};