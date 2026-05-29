@extends('layouts.app')

@section('title', 'Editar Receita | LucroCerto')

@section('content')
    <style>
        .page-title {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .page-sub {
            font-size: 14px;
            color: #8b8fa8;
            margin-bottom: 24px;
        }

        .form-card {
            background: #fff;
            border-radius: 14px;
            padding: 24px;
            margin-bottom: 24px;
        }

        .form-card h2 {
            font-size: 18px;
            font-weight: 700;
            color: #7c3aed;
            margin-bottom: 4px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 24px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 16px;
        }

        .form-group label {
            font-size: 13px;
            font-weight: 600;
            color: #4b4f6b;
        }

        .form-input,
        .form-select {
            height: 42px;
            border: 1.5px solid #e2e6f0;
            border-radius: 9px;
            padding: 0 12px;
            width: 100%;
        }

        .error-message {
            color: #e53e3e;
            font-size: 12px;
            margin-top: 4px;
        }

        .alert-error {
            background: #fee2e2;
            border-left: 4px solid #dc2626;
            padding: 12px;
            margin-bottom: 16px;
            border-radius: 8px;
        }

        .insumo-row {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-bottom: 10px;
        }

        .insumo-row select {
            flex: 2;
        }

        .insumo-row input {
            flex: 1;
        }

        .remove-insumo {
            background: #fee2e2;
            color: #dc2626;
            border: none;
            border-radius: 9px;
            width: 32px;
            cursor: pointer;
        }

        .add-insumo-btn {
            background: #e0e7ff;
            color: #4338ca;
            border: none;
            padding: 8px 16px;
            border-radius: 9px;
            cursor: pointer;
            margin-top: 8px;
        }

        .custo-preview {
            background: #f8f9fb;
            border-radius: 9px;
            padding: 12px;
            margin-top: 16px;
            text-align: right;
            font-weight: 600;
        }

        .btn-cancel {
            background: #fff;
            border: 1.5px solid #e2e6f0;
            padding: 9px 18px;
            border-radius: 9px;
            text-decoration: none;
        }

        .btn-primary {
            background: #7c3aed;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 9px;
            cursor: pointer;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 16px;
        }

        hr {
            margin: 20px 0;
        }

        .mod-card {
            border: 1px solid #e2e6f0;
            border-radius: 9px;
            padding: 12px;
            background: #fef9f5;
            margin-top: 8px;
        }

        .mod-item {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 10px;
            flex-wrap: wrap;
        }
    </style>

    <div class="page-title">Editar Receita</div>
    <div class="page-sub">Altere os dados da receita, adicione ou remova insumos e mãos de obra.</div>

    <div class="form-card">
        @if ($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('receitas.update', $receita) }}" method="POST" id="formReceita">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <div class="form-group">
                    <label>Nome da receita *</label>
                    <input type="text" name="nome" class="form-input" value="{{ old('nome', $receita->nome) }}"
                        required>
                    @error('nome')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Rendimento (unidades) *</label>
                    <input type="number" name="rendimento_lote" class="form-input"
                        value="{{ old('rendimento_lote', $receita->rendimento_lote) }}" required>
                    @error('rendimento_lote')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Tempo de preparo (horas) *</label>
                    <input type="number" step="0.5" name="tempo_preparo_horas" class="form-input"
                        value="{{ old('tempo_preparo_horas', $receita->tempo_preparo_horas) }}" required>
                    @error('tempo_preparo_horas')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- MÃO DE OBRA -->
                <div class="form-group">
                    <label>👩‍🍳 Mão de obra utilizada</label>
                    <div class="mod-card">
                        <p class="text-sm text-gray-500 mb-2">Selecione os profissionais e informe as horas trabalhadas.</p>
                        @forelse($maosDeObra as $mod)
                            @php
                                $vinculada = $maosVinculadas->firstWhere('id', $mod->id);
                                $checked = $vinculada ? 'checked' : '';
                                $horas = $vinculada ? $vinculada->pivot->horas : 0;
                            @endphp
                            <div class="mod-item">
                                <input type="checkbox" name="mao_de_obra[{{ $mod->id }}][selecionado]" value="1"
                                    {{ $checked }}>
                                <span style="min-width: 140px; font-weight: 500;">{{ $mod->descricao }}</span>
                                <span style="color:#6b7280;">R$
                                    {{ number_format($mod->valor_hora, 2, ',', '.') }}/hora</span>
                                <input type="number" step="0.5" name="mao_de_obra[{{ $mod->id }}][horas]"
                                    placeholder="Horas" value="{{ $horas }}"
                                    style="width: 100px; padding: 6px; border-radius: 8px; border: 1px solid #ccc;">
                            </div>
                        @empty
                            <p>Nenhuma mão de obra cadastrada. <a href="{{ route('mao-de-obra.index') }}">Cadastrar
                                    agora</a></p>
                        @endforelse
                    </div>
                </div>
            </div>

            <hr>
            <h2>🍽️ Ingredientes (Insumos)</h2>
            <p>Adicione os insumos utilizados e suas quantidades (mínimo 1). Use números decimais.</p>

            <div id="insumos-container"></div>
            <button type="button" class="add-insumo-btn" id="addInsumoBtn">+ Adicionar insumo</button>
            @error('insumos')
                <div class="error-message">{{ $message }}</div>
            @enderror

            <div class="custo-preview">
                Custo total dos insumos: R$ <span id="custoTotalInsumos">0,00</span>
            </div>

            <div class="form-actions">
                <a href="{{ route('receitas.index') }}" class="btn-cancel">Cancelar</a>
                <button type="submit" class="btn-primary">Atualizar receita</button>
            </div>
        </form>
    </div>

    <script>
        const insumos = @json($insumos);
        const oldInsumos = @json(old('insumos', []));
        const insumosVinculados = @json($receita->insumos);

        function atualizarCustoTotal() {
            let total = 0;
            document.querySelectorAll('.insumo-row').forEach(row => {
                const select = row.querySelector('select');
                const qtd = parseFloat(row.querySelector('.quantidade').value) || 0;
                if (select && select.selectedIndex > 0) {
                    const preco = parseFloat(select.options[select.selectedIndex].dataset.preco);
                    if (!isNaN(preco)) total += preco * qtd;
                }
            });
            document.getElementById('custoTotalInsumos').innerText = total.toFixed(2);
        }

        function criarLinhaInsumo(insumoId = null, quantidade = '') {
            const row = document.createElement('div');
            row.className = 'insumo-row';

            const select = document.createElement('select');
            select.className = 'form-select';
            select.innerHTML = '<option value="">Selecione um insumo</option>';
            insumos.forEach(ins => {
                const option = document.createElement('option');
                option.value = ins.id;
                option.textContent =
                    `${ins.nome} (R$ ${parseFloat(ins.preco_unitario).toFixed(2)} / ${ins.unidade_medida})`;
                option.dataset.preco = ins.preco_unitario;
                if (insumoId && ins.id == insumoId) option.selected = true;
                select.appendChild(option);
            });

            const qtdInput = document.createElement('input');
            qtdInput.type = 'number';
            qtdInput.step = '0.001';
            qtdInput.value = quantidade !== '' ? quantidade : '';
            qtdInput.className = 'form-input quantidade';
            qtdInput.placeholder = 'Quantidade';
            qtdInput.addEventListener('input', atualizarCustoTotal);

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.textContent = '✕';
            removeBtn.className = 'remove-insumo';
            removeBtn.addEventListener('click', () => {
                row.remove();
                atualizarCustoTotal();
            });

            row.appendChild(select);
            row.appendChild(qtdInput);
            row.appendChild(removeBtn);
            select.addEventListener('change', atualizarCustoTotal);
            return row;
        }

        const container = document.getElementById('insumos-container');
        if (oldInsumos.length > 0) {
            oldInsumos.forEach(item => {
                if (item.id && item.quantidade !== undefined && item.quantidade !== '') {
                    container.appendChild(criarLinhaInsumo(item.id, item.quantidade));
                }
            });
        } else if (insumosVinculados.length > 0) {
            insumosVinculados.forEach(ins => {
                container.appendChild(criarLinhaInsumo(ins.id, ins.pivot.quantidade));
            });
        } else {
            container.appendChild(criarLinhaInsumo());
        }

        document.getElementById('addInsumoBtn').addEventListener('click', () => {
            container.appendChild(criarLinhaInsumo());
        });

        document.getElementById('formReceita').addEventListener('submit', (e) => {
            const rows = document.querySelectorAll('.insumo-row');
            let valid = true;
            let insumosValidos = [];

            rows.forEach(row => {
                const select = row.querySelector('select');
                const qtdInput = row.querySelector('.quantidade');
                const insumoId = select.value;
                const quantidade = parseFloat(qtdInput.value);

                if (insumoId && !isNaN(quantidade) && quantidade > 0) {
                    insumosValidos.push({
                        id: insumoId,
                        quantidade: quantidade
                    });
                } else {
                    if (insumoId || (qtdInput.value !== '' && !isNaN(quantidade))) {
                        valid = false;
                    }
                }
            });

            if (insumosValidos.length === 0) {
                alert('Adicione pelo menos um insumo com quantidade válida.');
                e.preventDefault();
                return;
            }
            if (!valid) {
                alert('Existem linhas de insumo incompletas. Remova as linhas vazias ou preencha corretamente.');
                e.preventDefault();
                return;
            }

            const form = document.getElementById('formReceita');
            const oldInsumosInputs = form.querySelectorAll('input[name^="insumos["], select[name^="insumos["]');
            oldInsumosInputs.forEach(input => input.remove());

            insumosValidos.forEach((item, idx) => {
                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = `insumos[${idx}][id]`;
                idInput.value = item.id;
                form.appendChild(idInput);

                const qtdInput = document.createElement('input');
                qtdInput.type = 'hidden';
                qtdInput.name = `insumos[${idx}][quantidade]`;
                qtdInput.value = item.quantidade;
                form.appendChild(qtdInput);
            });
        });

        atualizarCustoTotal();
    </script>
@endsection
