<?php

namespace App\Providers;

use App\Models\CmvCalculo;
use App\Models\CustoFixo;
use App\Models\CustoVariavel;
use App\Models\Insumo;
use App\Models\MaoDeObra;
use App\Models\Receita;
use App\Models\Simulacao;
use App\Policies\InsumoPolicy;
use App\Policies\ReceitaPolicy;
use App\Policies\CustoFixoPolicy;
use App\Policies\CustoVariavelPolicy;
use App\Policies\MaoDeObraPolicy;
use App\Policies\CmvCalculoPolicy;
use App\Policies\SimulacaoPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Insumo::class => InsumoPolicy::class,
        Receita::class => ReceitaPolicy::class,
        CustoFixo::class => CustoFixoPolicy::class,
        CustoVariavel::class => CustoVariavelPolicy::class,
        MaoDeObra::class => MaoDeObraPolicy::class,
        CmvCalculo::class => CmvCalculoPolicy::class,
        Simulacao::class => SimulacaoPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
