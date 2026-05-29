@extends('layouts.app')

@section('title', 'Simular Preço | LucroCerto')

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

        .sim-card {
            background: #fff;
            border-radius: 14px;
            padding: 24px;
            max-width: 700px;
            margin: 0 auto;
        }

        .result-box {
            background: #f8f9fb;
            border-radius: 14px;
            padding: 20px;
            margin-top: 20px;
        }

        .result-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e2e6f0;
        }

        .result-item:last-child {
            border-bottom: none;
        }

        .label {
            font-weight: 600;
            color: #4b4f6b;
        }

        .value {
            font-size: 20px;
            font-weight: 700;
        }

        .value.up {
            color: #10b981;
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
            text-decoration: none;
        }

        .btn-secondary {
            background: #fff;
            border: 1.5px solid #e2e6f0;
            padding: 10px 20px;
            border-radius: 9px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            color: #4b4f6b;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #4b4f6b;
        }

        .form-input {
            width: 100%;
            padding: 10px;
            border: 1.5px solid #e2e6f0;
            border-radius: 9px;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 20px;
        }
    </style>

    <div class="page-title">Simular Preço de Venda</div>
    <div class="page-sub">Calcule o novo preço com base na margem de lucro desejada.</div>

    <div class="sim-card">
        <form action="{{ route('cmv.comparar') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Selecione a receita</label>
                <select name="receita_id" class="form-input" required>
                    <option value="">-- Selecione --</option>
                    @foreach (Auth::user()->receitas as $receita)
                        <option value="{{ $receita->id }}"
                            {{ old('receita_id', $receita->id ?? '') == $receita->id ? 'selected' : '' }}>
                            {{ $receita->nome }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Margem de lucro desejada (%)</label>
                <input type="number" step="0.5" name="percentual_lucro" class="form-input"
                    value="{{ old('percentual_lucro', 30) }}" required>
            </div>
            <div class="form-actions">
                <a href="{{ route('cmv.index') }}" class="btn-secondary">Cancelar</a>
                <button type="submit" class="btn-primary"><i class="ti ti-calculator"></i> Calcular</button>
            </div>
        </form>

        @if (isset($novoPreco))
            <div class="result-box">
                <h3 style="font-size: 18px; margin-bottom: 16px;">Resultado da simulação</h3>
                <div class="result-item">
                    <span class="label">Receita:</span>
                    <span><strong>{{ $receita->nome }}</strong></span>
                </div>
                <div class="result-item">
                    <span class="label">CMV atual:</span>
                    <span>R$ {{ number_format($ultimoCmv->cmv_unitario, 4, ',', '.') }}</span>
                </div>
                <div class="result-item">
                    <span class="label">Preço atual sugerido ({{ $ultimoCmv->percentual_lucro }}%):</span>
                    <span>R$ {{ number_format($ultimoCmv->preco_sugerido, 2, ',', '.') }}</span>
                </div>
                <div class="result-item">
                    <span class="label">Novo preço com {{ $request->percentual_lucro }}% margem:</span>
                    <span class="value up">R$ {{ number_format($novoPreco, 2, ',', '.') }}</span>
                </div>
                <div class="result-item">
                    <span class="label">Diferença em relação ao preço atual:</span>
                    <span class="{{ $novoPreco > $ultimoCmv->preco_sugerido ? 'value up' : '' }}">
                        {{ $novoPreco > $ultimoCmv->preco_sugerido ? '+' : '' }}R$
                        {{ number_format($novoPreco - $ultimoCmv->preco_sugerido, 2, ',', '.') }}
                    </span>
                </div>
                <div class="result-item">
                    <span class="label">Nova margem de contribuição:</span>
                    <span>R$ {{ number_format($novaMargem, 4, ',', '.') }}</span>
                </div>
            </div>
        @endif
    </div>
@endsection
