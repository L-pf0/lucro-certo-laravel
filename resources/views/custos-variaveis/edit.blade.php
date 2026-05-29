@extends('layouts.app')

@section('title', 'Editar Custo Variável | LucroCerto')

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
    </style>

    <div class="page-title">Editar Custo Variável</div>
    <div class="page-sub">Altere os dados do custo variável.</div>

    <div class="form-card">
        <form action="{{ route('custos-variaveis.update', $custoVariavel) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <div class="form-group">
                    <label>Descrição <span>*</span></label>
                    <input type="text" name="descricao" class="form-input"
                        value="{{ old('descricao', $custoVariavel->descricao) }}" required>
                </div>
                <div class="form-group">
                    <label>Valor (R$) <span>*</span></label>
                    <input type="number" step="0.01" name="valor" class="form-input"
                        value="{{ old('valor', $custoVariavel->valor) }}" required>
                </div>
                <div class="form-group">
                    <label>Tipo <span>*</span></label>
                    <select name="tipo" id="tipo" class="form-select" required>
                        <option value="geral" {{ old('tipo', $custoVariavel->tipo) == 'geral' ? 'selected' : '' }}>Geral
                            (rateado)</option>
                        <option value="produto" {{ old('tipo', $custoVariavel->tipo) == 'produto' ? 'selected' : '' }}>
                            Produto (vinculado a uma receita)</option>
                    </select>
                </div>
                <div class="form-group" id="receita-group"
                    style="display: {{ old('tipo', $custoVariavel->tipo) == 'produto' ? 'flex' : 'none' }};">
                    <label>Receita vinculada</label>
                    <select name="receita_id" class="form-select">
                        <option value="">Selecione uma receita</option>
                        @foreach ($receitas as $receita)
                            <option value="{{ $receita->id }}"
                                {{ old('receita_id', $custoVariavel->receita_id) == $receita->id ? 'selected' : '' }}>
                                {{ $receita->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-actions">
                <a href="{{ route('custos-variaveis.index') }}" class="btn-cancel">Cancelar</a>
                <button type="submit" class="btn-primary"><i class="ti ti-device-floppy"></i> Atualizar custo
                    variável</button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('tipo').addEventListener('change', function() {
            var receitaGroup = document.getElementById('receita-group');
            receitaGroup.style.display = this.value === 'produto' ? 'flex' : 'none';
        });
    </script>
@endsection
