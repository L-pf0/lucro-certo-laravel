@extends('layouts.app')

@section('title', 'Novo Insumo | LucroCerto')

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

        .form-card p {
            font-size: 13px;
            color: #8b8fa8;
            margin-bottom: 20px;
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

        .form-group label span {
            color: #e53e3e;
        }

        .form-input,
        .form-select {
            height: 42px;
            border: 1.5px solid #e2e6f0;
            border-radius: 9px;
            padding: 0 12px;
            font-size: 14px;
            background: #fff;
            outline: none;
        }

        .form-input:focus,
        .form-select:focus {
            border-color: #7c3aed;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
        }

        .form-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
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

        .info-box {
            background: #f3f0ff;
            border-radius: 12px;
            padding: 16px;
            font-size: 13px;
            color: #5b21b6;
        }

        .error-message {
            color: #e53e3e;
            font-size: 12px;
            margin-top: 4px;
        }
    </style>

    <div class="page-title">Novo Insumo</div>
    <div class="page-sub">Preencha os dados do insumo e clique em salvar.</div>

    <div class="form-card" style="display:grid; grid-template-columns:1fr 280px; gap:24px; align-items:start">
        <div>
            <h2>Dados do insumo</h2>
            <p>Campos com <span>*</span> são obrigatórios.</p>

            <form action="{{ route('insumos.store') }}" method="POST">
                @csrf
                <div class="form-grid">
                    <div class="form-group">
                        <label>Nome do insumo <span>*</span></label>
                        <input type="text" name="nome" class="form-input" value="{{ old('nome') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Unidade de medida <span>*</span></label>
                        <select name="unidade_medida" class="form-select" required>
                            <option value="kg">kg (quilograma)</option>
                            <option value="g">g (grama)</option>
                            <option value="l">l (litro)</option>
                            <option value="ml">ml (mililitro)</option>
                            <option value="unidade">unidade</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Quantidade padrão (opcional)</label>
                        <input type="number" step="0.001" name="quantidade_padrao" class="form-input"
                            value="{{ old('quantidade_padrao') }}" placeholder="Ex: 300 (para 300g)">
                        <small class="text-gray-400">Quantidade que você compra de uma vez (ex: 300, 12, 1)</small>
                    </div>
                    <div class="form-group">
                        <label>Preço total (R$) da quantidade padrão <span>* se usar quantidade padrão</span></label>
                        <input type="number" step="0.01" name="preco_total" class="form-input"
                            value="{{ old('preco_total') }}" placeholder="Ex: 6.00">
                        <small class="text-gray-400">Preço que você pagou pela quantidade padrão (ex: 6 reais por
                            300g)</small>
                    </div>
                    <div class="form-group">
                        <label>OU preço unitário (R$) <span>*</span></label>
                        <input type="number" step="0.01" name="preco_unitario" class="form-input"
                            value="{{ old('preco_unitario') }}" required>
                        <small class="text-gray-400">Preço por 1 unidade (ex: por kg, por litro, por unidade)</small>
                    </div>
                </div>
                <div class="form-actions">
                    <a href="{{ route('insumos.index') }}" class="btn-cancel">Cancelar</a>
                    <button type="submit" class="btn-primary">Salvar insumo</button>
                </div>
            </form>
        </div>
        <div class="info-box">
            <strong><i class="ti ti-info-circle"></i> Informações</strong>
            Mantenha seus insumos sempre atualizados para cálculos precisos do CMV.
        </div>
    </div>
@endsection
