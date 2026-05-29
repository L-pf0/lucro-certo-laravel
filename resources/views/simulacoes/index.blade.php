@extends('layouts.app')

@section('title', 'Histórico de Simulações | LucroCerto')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- cabeçalho -->
            <div
                class="px-6 py-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">📋 Histórico de Simulações</h2>
                    <p class="text-sm text-gray-500">Alterações de preço de insumos e impacto no CMV</p>
                </div>
                <a href="{{ route('simulacoes.create') }}"
                    class="inline-flex items-center justify-center gap-1 px-4 py-2 bg-purple-700 hover:bg-purple-800 text-white text-sm font-medium rounded-lg transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nova Simulação
                </a>
            </div>

            <!-- corpo -->
            <div class="p-6">
                @if ($simulacoes->count())
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                                <tr>
                                    <th class="px-4 py-3 text-left">Data</th>
                                    <th class="px-4 py-3 text-left">Insumo</th>
                                    <th class="px-4 py-3 text-right">Preço Antigo</th>
                                    <th class="px-4 py-3 text-right">Preço Simulado</th>
                                    <th class="px-4 py-3 text-right">Impacto CMV</th>
                                    <th class="px-4 py-3 text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($simulacoes as $sim)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 whitespace-nowrap">{{ $sim->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-4 py-3 font-medium">{{ $sim->insumo->nome }}</td>
                                        <td class="px-4 py-3 text-right">R$ {{ number_format($sim->preco_antigo, 2) }}</td>
                                        <td class="px-4 py-3 text-right font-medium">R$
                                            {{ number_format($sim->preco_simulado, 2) }}</td>
                                        <td
                                            class="px-4 py-3 text-right font-semibold {{ $sim->impacto_cmv >= 0 ? 'text-red-600' : 'text-green-600' }}">
                                            {{ number_format($sim->impacto_cmv, 2) }}%
                                        </td>
                                        <td class="px-4 py-3 text-center space-x-3">
                                            <a href="{{ route('simulacoes.show', $sim) }}"
                                                class="text-blue-600 hover:text-blue-800 inline-block">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                            <a href="{{ route('simulacoes.edit', $sim) }}"
                                                class="text-amber-600 hover:text-amber-800 inline-block">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6">
                        {{ $simulacoes->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhuma simulação</h3>
                        <p class="mt-1 text-sm text-gray-500">Comece criando uma nova simulação.</p>
                        <div class="mt-6">
                            <a href="{{ route('simulacoes.create') }}"
                                class="inline-flex items-center px-3 py-2 bg-purple-700 hover:bg-purple-800 text-white text-sm font-medium rounded-lg shadow-sm">Nova
                                Simulação</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
