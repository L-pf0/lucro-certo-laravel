<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();

            // 🔗 usuário que executou a ação
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // ⚙️ tipo de ação
            $table->enum('acao', ['insert', 'update', 'delete']);

            // 🧾 tabela afetada
            $table->string('tabela_afetada');

            // 🔢 id do registro afetado
            $table->unsignedBigInteger('registro_id');

            // 📝 descrição opcional
            $table->text('descricao')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
