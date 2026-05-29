@extends('layouts.app')

@section('title', 'Editar Registro | Mão de Obra')

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
            background: #fff;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 16px;
        }

        .btn-cancel {
            background: #fff;
            border: 1.5px solid #e2e6f0;
            padding: 9px 18px;
            border-radius: 9px;
            cursor: pointer;
        }

        .btn-primary {
            background: #7c3aed;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 9px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .error-message {
            color: #e53e3e;
            font-size: 12px;
            margin-top: 4px;
        }

        .info-box {
            background: #f3f0ff;
            border-radius: 12px;
            padding: 16px;
            font-size: 13px;
            color: #5b21b6;
            margin-bottom: 20px;
        }
    </style>

    <div class="page-title">Editar Registro de Mão de Obra</div>
    <div class="page-sub">Altere os dados do registro.</div>

    <div class="form-card">
        <div class="info-box">
            <strong><i class="ti ti-info-circle"></i> Atenção</strong><br>
            Ao alterar horas ou valores, o campo complementar será recalculado automaticamente.
        </div>

        <form action="{{ route('mao-de-obra.update', $maoDeObra) }}" method="POST" id="formMaoObra">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <div class="form-group">
                    <label>Descrição <span>*</span></label>
                    <input type="text" name="descricao" class="form-input"
                        value="{{ old('descricao', $maoDeObra->descricao) }}" required>
                </div>
                <div class="form-group">
                    <label>Receita vinculada</label>
                    <select name="receita_id" class="form-select">
                        <option value="">Nenhuma (custo geral)</option>
                        @foreach ($receitas as $receita)
                            @php($receitaSelecionada = old('receita_id', optional($maoDeObra->receitas->first())->id))
                            <option value="{{ $receita->id }}"
                                {{ $receitaSelecionada == $receita->id ? 'selected' : '' }}>
                                {{ $receita->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Tempo (horas) <span>*</span></label>
                    <input type="number" step="0.5" name="tempo_horas" id="tempo_horas" class="form-input"
                        value="{{ old('tempo_horas', $maoDeObra->tempo_horas) }}" required>
                </div>
                <div class="form-group">
                    <label>Valor por hora (R$)</label>
                    <input type="number" step="0.01" name="valor_hora" id="valor_hora" class="form-input"
                        value="{{ old('valor_hora', $maoDeObra->valor_hora) }}">
                </div>
                <div class="form-group">
                    <label>Valor total (R$)</label>
                    <input type="number" step="0.01" name="valor_total" id="valor_total" class="form-input"
                        value="{{ old('valor_total', $maoDeObra->valor_total) }}">
                </div>
            </div>
            <div class="form-actions">
                <a href="{{ route('mao-de-obra.index') }}" class="btn-cancel">Cancelar</a>
                <button type="submit" class="btn-primary"><i class="ti ti-device-floppy"></i> Atualizar registro</button>
            </div>
        </form>
    </div>

    <script>
        const horas = document.getElementById('tempo_horas');
        const valorHora = document.getElementById('valor_hora');
        const valorTotal = document.getElementById('valor_total');

        function calcular() {
            let h = parseFloat(horas.value) || 0;
            let vh = parseFloat(valorHora.value) || 0;
            let vt = parseFloat(valorTotal.value) || 0;

            if (h > 0) {
                if (vh > 0 && vt === 0) {
                    valorTotal.value = (h * vh).toFixed(2);
                } else if (vt > 0 && vh === 0) {
                    valorHora.value = (vt / h).toFixed(2);
                }
            }
        }

        horas.addEventListener('input', calcular);
        valorHora.addEventListener('input', calcular);
        valorTotal.addEventListener('input', calcular);
    </script>
@endsection
