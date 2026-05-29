@extends('layouts.app')

@section('title', 'Simular Impacto | LucroCerto')

@section('content')
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="text-xl font-semibold text-gray-800">📈 Simular alteração de preço</h2>
                <p class="text-sm text-gray-500">Altere o preço de um insumo e veja o impacto no CMV e preço sugerido.</p>
            </div>

            <div class="p-6">
                <form id="simulationForm" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Insumo</label>
                            <select name="insumo_id" id="insumo_id"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                <option value="">Selecione um insumo</option>
                                @foreach ($insumos as $insumo)
                                    <option value="{{ $insumo->id }}" data-preco="{{ $insumo->preco_unitario }}">
                                        {{ $insumo->nome }} (R$ {{ number_format($insumo->preco_unitario, 2) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Novo Preço (R$)</label>
                            <input type="number" step="0.01" name="novo_preco" id="novo_preco"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        </div>
                    </div>
                    <div>
                        <button type="button" id="simularBtn"
                            class="inline-flex items-center px-4 py-2 bg-purple-700 hover:bg-purple-800 text-white text-sm font-medium rounded-lg shadow-sm transition">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            Simular Impacto
                        </button>
                    </div>
                </form>

                <!-- resultados -->
                <div id="resultados" class="hidden mt-8 border-t border-gray-100 pt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">📊 Receitas Afetadas</h3>
                    <div id="avisoSemReceitas" class="hidden bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4 rounded">
                        <div class="flex items-center">
                            <span class="text-yellow-600 mr-2">⚠️</span>
                            <span class="text-sm text-yellow-700">Nenhuma receita utiliza este insumo. A simulação não terá
                                impacto.</span>
                        </div>
                    </div>
                    <div class="overflow-x-auto shadow-sm rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left">Receita</th>
                                    <th class="px-4 py-3 text-right">CMV Atual</th>
                                    <th class="px-4 py-3 text-right">Novo CMV</th>
                                    <th class="px-4 py-3 text-right">Variação</th>
                                    <th class="px-4 py-3 text-right">Preço Sugerido (antes)</th>
                                    <th class="px-4 py-3 text-right">Preço Sugerido (depois)</th>
                                </tr>
                            </thead>
                            <tbody id="resultadoTbody" class="divide-y divide-gray-100"></tbody>
                        </table>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button id="cancelarBtn"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition">Cancelar</button>
                        <button id="aplicarBtn"
                            class="px-4 py-2 bg-purple-700 hover:bg-purple-800 text-white text-sm font-medium rounded-lg shadow-sm transition">Salvar
                            e voltar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            let simData = null;

            $('#simularBtn').click(function() {
                let insumoId = $('#insumo_id').val();
                let novoPreco = $('#novo_preco').val();

                if (!insumoId) {
                    alert('Selecione um insumo.');
                    return;
                }
                if (!novoPreco || novoPreco <= 0) {
                    alert('Informe um novo preço válido.');
                    return;
                }

                let $btn = $(this);
                $btn.prop('disabled', true).html(
                    '<svg class="animate-spin h-4 w-4 mr-1 inline" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg> Processando...'
                );

                $.ajax({
                    url: '{{ route('simulacoes.variacao_insumo') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        insumo_id: insumoId,
                        novo_preco: novoPreco
                    },
                    success: function(response) {
                        if (response.success) {
                            simData = response;
                            let tbody = $('#resultadoTbody');
                            tbody.empty();

                            if (response.receitas_afetadas.length === 0) {
                                $('#tabelaReceitas').addClass('hidden');
                                $('#avisoSemReceitas').removeClass('hidden');
                            } else {
                                $('#tabelaReceitas').removeClass('hidden');
                                $('#avisoSemReceitas').addClass('hidden');
                                $.each(response.receitas_afetadas, function(i, rec) {
                                    let variacaoClass = parseFloat(rec
                                            .impacto_percentual) > 0 ? 'text-red-600' :
                                        'text-green-600';
                                    let row = `<tr>
                                <td class="px-4 py-2 font-medium">${rec.receita_nome}</td>
                                <td class="px-4 py-2 text-right">R$ ${rec.cmv_atual}</td>
                                <td class="px-4 py-2 text-right">R$ ${rec.cmv_novo}</td>
                                <td class="px-4 py-2 text-right ${variacaoClass} font-semibold">${rec.impacto_percentual}%</td>
                                <td class="px-4 py-2 text-right">R$ ${rec.preco_sugerido_antigo || 'N/A'}</td>
                                <td class="px-4 py-2 text-right font-medium">R$ ${rec.preco_sugerido_novo}</td>
                            </tr>`;
                                    tbody.append(row);
                                });
                            }
                            $('#resultados').removeClass('hidden');
                        } else alert('Erro na simulação.');
                    },
                    error: function() {
                        alert('Erro ao comunicar com servidor.');
                    },
                    complete: function() {
                        $btn.prop('disabled', false).html(
                            '<svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg> Simular Impacto'
                        );
                    }
                });
            });

            // Botão "Salvar e voltar" – apenas redireciona (não altera preço)
            $('#aplicarBtn').click(function() {
                window.location.href = '{{ route('simulacoes.index') }}';
            });

            // Botão "Cancelar" – apenas esconde os resultados
            $('#cancelarBtn').click(function() {
                $('#resultados').addClass('hidden');
                $('#novo_preco').val('');
            });
        });
    </script>
@endsection
