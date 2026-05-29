@extends('layouts.app')

@section('title', 'Novo Custo Variável | LucroCerto')

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

        .form-input:focus,
        .form-select:focus {
            border-color: #7c3aed;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
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

    <div class="page-title">Novo Custo Variável</div>
    <div class="page-sub">Cadastre um custo que pode variar conforme o volume de produção.</div>

    <div class="form-card">
        <div class="info-box">
            <strong><i class="ti ti-info-circle"></i> Tipos de custo variável</strong><br>
            <strong>Produto:</strong> vinculado a uma receita específica (ex: embalagem personalizada).<br>
            <strong>Geral:</strong> rateado entre todas as receitas (ex: comissão sobre vendas, energia elétrica variável).
        </div>

        <form action="{{ route('custos-variaveis.store') }}" method="POST">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label>Descrição <span>*</span></label>
                    <input type="text" name="descricao" class="form-input @error('descricao') is-invalid @enderror"
                        value="{{ old('descricao') }}" required>
                    @error('descricao')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Valor (R$) <span>*</span></label>
                    <input type="number" step="0.01" name="valor"
                        class="form-input @error('valor') is-invalid @enderror" value="{{ old('valor') }}" required>
                    @error('valor')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Tipo <span>*</span></label>
                    <select name="tipo" id="tipo" class="form-select" required>
                        <option value="geral" {{ old('tipo') == 'geral' ? 'selected' : '' }}>Geral (rateado)</option>
                        <option value="produto" {{ old('tipo') == 'produto' ? 'selected' : '' }}>Produto (vinculado a uma
                            receita)</option>
                    </select>
                    @error('tipo')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group" id="receita-group"
                    style="display: {{ old('tipo') == 'produto' ? 'flex' : 'none' }};">
                    <label>Receita vinculada <span>*</span></label>
                    <select name="receita_id" class="form-select">
                        <option value="">Selecione uma receita</option>
                        @foreach ($receitas as $receita)
                            <option value="{{ $receita->id }}" {{ old('receita_id') == $receita->id ? 'selected' : '' }}>
                                {{ $receita->nome }}</option>
                        @endforeach
                    </select>
                    @error('receita_id')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-actions">
                <a href="{{ route('custos-variaveis.index') }}" class="btn-cancel">Cancelar</a>
                <button type="submit" class="btn-primary"><i class="ti ti-device-floppy"></i> Salvar custo
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
