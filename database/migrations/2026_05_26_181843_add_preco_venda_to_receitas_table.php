<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('receitas', function (Blueprint $table) {
            $table->decimal('preco_venda', 10, 2)->nullable()->after('tempo_preparo_horas');
        });
    }

    public function down()
    {
        Schema::table('receitas', function (Blueprint $table) {
            $table->dropColumn('preco_venda');
        });
    }
};
