@extends('layouts.app')

@section('title', 'Reexecutar Simulação | LucroCerto')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-purple-800 px-6 py-5">
                <h2 class="text-2xl font-bold text-white">🔄 Reexecutar Simulação</h2>
                <p class="text-purple-100 text-sm mt-1">Altere o preço novamente e veja o novo impacto.</p>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Insumo</label>
                        <input type="text" readonly value="{{ $simulacao->insumo->nome }}"
                            class="mt-1 block w-full bg-gray-100 rounded-lg p-2 border border-gray-200">
                        <input type="hidden" name="insumo_id" value="{{ $simulacao->insumo_id }}" id="insumoId">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Novo Preço (R$)</label>
                        <input type="number" step="0.01" name="novo_preco" id="novo_preco"
                            value="{{ $simulacao->preco_simulado }}"
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500 transition"
                            required>
                    </div>
                </div>
                <div>
                    <button type="button" id="simularBtn"
                        class="inline-flex items-center px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg shadow-md transition">
                        🔄 Simular Novamente
                    </button>
                </div>

                <div id="resultados" class="hidden mt-8 border-t border-gray-200 pt-6">
                    <h3 class="text-xl font-semibold mb-4 text-purple-700">📊 Receitas Afetadas</h3>
                    <div class="overflow-x-auto shadow rounded-lg">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="py-3 px-4 text-left">Receita</th>
                                    <th class="py-3 px-4 text-right">CMV Atual</th>
                                    <th class="py-3 px-4 text-right">Novo CMV</th>
                                    <th class="py-3 px-4 text-right">Variação</th>
                                    <th class="py-3 px-4 text-right">Preço Sugerido</th>
                                </tr>
                            </thead>
                            <tbody id="resultadoTbody" class="divide-y divide-gray-100"></tbody>
                        </table>
                    </div>
                    <!-- Botões -->
                    <div class="mt-6 flex justify-end gap-3">
                        <button id="cancelarBtn"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition">Cancelar</button>
                        <button id="salvarBtn"
                            class="px-4 py-2 bg-purple-700 hover:bg-purple-800 text-white text-sm font-medium rounded-lg shadow-sm transition">Salvar
                            e voltar</button>
                    </div>
                    <div class="mt-2 text-sm text-gray-500 italic">
                        💡 Esta é apenas uma simulação. Para alterar o preço do insumo, edite-o diretamente na lista de
                        insumos.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            let simData = null;

            // Simular Novamente
            $('#simularBtn').click(function() {
                let insumoId = $('#insumoId').val();
                let novoPreco = $('#novo_preco').val();
                if (!novoPreco || novoPreco <= 0) {
                    alert('Informe um novo preço válido.');
                    return;
                }

                let $btn = $(this);
                $btn.prop('disabled', true).html(
                    '<svg class="animate-spin h-5 w-5 inline mr-2" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg> Processando...'
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
                            let tbody = $('#resultadoTbody');
                            tbody.empty();
                            if (response.receitas_afetadas.length === 0) {
                                tbody.html(
                                    '<tr><td colspan="5" class="text-center py-4 text-gray-500">Nenhuma receita utiliza este insumo.</td></tr>'
                                    );
                            } else {
                                $.each(response.receitas_afetadas, function(i, rec) {
                                    let variacaoClass = parseFloat(rec
                                            .impacto_percentual) > 0 ? 'text-red-600' :
                                        'text-green-600';
                                    tbody.append(`
                                <tr class="hover:bg-gray-50">
                                    <td class="py-3 px-4">${rec.receita_nome}</td>
                                    <td class="py-3 px-4 text-right">R$ ${rec.cmv_atual}</td>
                                    <td class="py-3 px-4 text-right">R$ ${rec.cmv_novo}</td>
                                    <td class="py-3 px-4 text-right ${variacaoClass} font-semibold">${rec.impacto_percentual}%</td>
                                    <td class="py-3 px-4 text-right">R$ ${rec.preco_sugerido_novo}</td>
                                </tr>
                            `);
                                });
                            }
                            $('#resultados').removeClass('hidden');
                        } else {
                            alert('Erro na simulação.');
                        }
                    },
                    error: function() {
                        alert('Erro ao comunicar com o servidor.');
                    },
                    complete: function() {
                        $btn.prop('disabled', false).html('🔄 Simular Novamente');
                    }
                });
            });

            // Botão "Cancelar" – esconde os resultados
            $('#cancelarBtn').click(function() {
                $('#resultados').addClass('hidden');
                // Opcional: limpa o campo do novo preço
                $('#novo_preco').val('');
            });

            // Botão "Salvar e voltar" – apenas redireciona para o histórico (não altera preço)
            $('#salvarBtn').click(function() {
                window.location.href = '{{ route('simulacoes.index') }}';
            });
        });
    </script>
@endsection
