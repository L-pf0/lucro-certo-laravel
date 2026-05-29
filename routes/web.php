<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InsumoController;
use App\Http\Controllers\ReceitaController;
use App\Http\Controllers\CustoFixoController;
use App\Http\Controllers\CustoVariavelController;
use App\Http\Controllers\MaoDeObraController;
use App\Http\Controllers\CmvCalculoController;
use App\Http\Controllers\SimulacaoController;
use App\Http\Controllers\RelatorioController;
use Illuminate\Support\Facades\Route;

// ==================== PÁGINA INICIAL PÚBLICA ====================
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// ==================== AUTENTICAÇÃO ====================
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==================== ROTAS PROTEGIDAS (REQUEREM LOGIN) ====================
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    Route::resource('custos-fixos', CustoFixoController::class)->parameters([
        'custos-fixos' => 'custoFixo'
    ]);
    Route::resource('custos-variaveis', CustoVariavelController::class)->parameters([
        'custos-variaveis' => 'custoVariavel'
    ]);
    // CRUD completo de todos os recursos (SEU FORMATO ORIGINAL, corrigido)
    Route::resources([
        'insumos'          => InsumoController::class,
        'receitas'         => ReceitaController::class,

        'mao-de-obra'      => MaoDeObraController::class,
        'cmv'              => CmvCalculoController::class,
        // RELATORIOS: corrigido de 'Rel::class' para o nome correto
        'relatorios'       => RelatorioController::class,
    ]);


    // Rotas extras para CMV
    Route::post('/receitas/{receita}/recalcular-cmv', [CmvCalculoController::class, 'recalcular'])->name('cmv.recalcular');
    Route::post('/cmv/comparar', [CmvCalculoController::class, 'comparar'])->name('cmv.comparar');

    Route::get('/relatorios/{relatorio}/download', [RelatorioController::class, 'download'])->name('relatorios.download');

    // ========== SIMULAÇÕES (rotas manuais - seu formato original) ==========
    Route::prefix('simulacoes')->name('simulacoes.')->group(function () {
        Route::get('/', [SimulacaoController::class, 'index'])->name('index');
        Route::get('/create', [SimulacaoController::class, 'create'])->name('create');
        Route::post('/', [SimulacaoController::class, 'store'])->name('store');
        Route::get('/{simulacao}', [SimulacaoController::class, 'show'])->name('show');
        Route::get('/{simulacao}/edit', [SimulacaoController::class, 'edit'])->name('edit');
        Route::put('/{simulacao}', [SimulacaoController::class, 'update'])->name('update');
        Route::post('/variacao-insumo', [SimulacaoController::class, 'simularVariacaoInsumo'])->name('variacao_insumo');
        Route::post('/aplicar', [SimulacaoController::class, 'aplicarSimulacao'])->name('aplicar');
        Route::post('/margem', [SimulacaoController::class, 'simularMargem'])->name('margem');
    });
});
