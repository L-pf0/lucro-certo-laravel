@extends('layouts.app')

@section('title', 'Detalhes da Simulação | LucroCerto')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-purple-800 px-6 py-5 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-white">🔍 Simulação #{{ $simulacao->id }}</h2>
                    <p class="text-purple-100 text-sm mt-1">Detalhes do impacto causado pela simulação.</p>
                </div>
                <a href="{{ route('simulacoes.index') }}" class="text-white hover:text-purple-200 transition">← Voltar</a>
            </div>

            <div class="p-6">
                <!-- Card de informações da simulação -->
                <div class="bg-gray-50 rounded-xl p-5 mb-8 grid grid-cols-1 md:grid-cols-2 gap-4 shadow-inner">
                    <div><span class="font-semibold text-gray-600">Insumo:</span> <span
                            class="font-medium">{{ $simulacao->insumo->nome }}</span></div>
                    <div><span class="font-semibold text-gray-600">Data:</span> <span
                            class="font-medium">{{ $simulacao->created_at->format('d/m/Y H:i') }}</span></div>
                    <div><span class="font-semibold text-gray-600">Preço antigo:</span> <span class="font-medium">R$
                            {{ number_format($simulacao->preco_antigo, 2) }}</span></div>
                    <div><span class="font-semibold text-gray-600">Preço simulado:</span> <span class="font-medium">R$
                            {{ number_format($simulacao->preco_simulado, 2) }}</span></div>
                    <div class="md:col-span-2"><span class="font-semibold text-gray-600">Impacto no CMV médio:</span>
                        <span
                            class="font-bold {{ $simulacao->impacto_cmv >= 0 ? 'text-red-600' : 'text-green-600' }}">{{ number_format($simulacao->impacto_cmv, 2) }}%</span>
                    </div>
                </div>

                <!-- Receitas afetadas -->
                <h3 class="text-xl font-semibold mb-4 text-purple-700 flex items-center gap-2">🍽️ Receitas afetadas</h3>
                @if ($simulacoesRelacionadas->count())
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Receita</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Impacto CMV
                                        (%)</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Impacto
                                        Preço Venda (R$)</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach ($simulacoesRelacionadas as $sim)
                                    <tr class="hover:bg-purple-50 transition">
                                        <td class="px-6 py-4 text-sm font-medium">
                                            {{ $sim->receitaAfetada->nome ?? 'Receita não encontrada' }}</td>
                                        <td
                                            class="px-6 py-4 text-sm text-right font-semibold {{ $sim->impacto_cmv >= 0 ? 'text-red-600' : 'text-green-600' }}">
                                            {{ number_format($sim->impacto_cmv, 2) }}%
                                        </td>
                                        <td class="px-6 py-4 text-sm text-right">
                                            R$ {{ number_format($sim->impacto_preco_venda, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 text-sm">Nenhuma receita foi afetada nesta simulação (o insumo não está
                        vinculado a nenhuma receita).</p>
                @endif
                <div class="mt-6">
                    <a href="{{ route('simulacoes.edit', $simulacao) }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-md transition gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                            </path>
                        </svg>
                        Reexecutar simulação
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
