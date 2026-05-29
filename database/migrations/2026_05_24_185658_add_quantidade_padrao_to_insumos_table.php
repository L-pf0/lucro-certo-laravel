<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('insumos', function (Blueprint $table) {
            // Quantidade padrão (ex: 300 para 300g, 12 para 12 unidades)
            $table->decimal('quantidade_padrao', 10, 3)->nullable()->after('unidade_medida');
            // Preço total da quantidade padrão (ex: 6.00 para 300g)
            $table->decimal('preco_total', 10, 2)->nullable()->after('quantidade_padrao');
        });
    }

    public function down()
    {
        Schema::table('insumos', function (Blueprint $table) {
            $table->dropColumn(['quantidade_padrao', 'preco_total']);
        });
    }
};
