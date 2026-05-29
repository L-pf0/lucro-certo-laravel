@extends('layouts.app')

@section('title', 'Novo Custo Fixo | LucroCerto')

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

        .form-input {
            height: 42px;
            border: 1.5px solid #e2e6f0;
            border-radius: 9px;
            padding: 0 12px;
        }

        .form-input:focus {
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
    </style>

    <div class="page-title">Novo Custo Fixo</div>
    <div class="page-sub">Preencha os dados do custo fixo mensal.</div>

    <div class="form-card">
        <h2>Dados do custo fixo</h2>
        <form action="{{ route('custos-fixos.store') }}" method="POST">
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
                    <label>Valor mensal (R$) <span>*</span></label>
                    <input type="number" step="0.01" name="valor_mensal"
                        class="form-input @error('valor_mensal') is-invalid @enderror" value="{{ old('valor_mensal') }}"
                        required>
                    @error('valor_mensal')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Mês referência (YYYY-MM) <span>*</span></label>
                    <input type="month" name="mes_referencia"
                        class="form-input @error('mes_referencia') is-invalid @enderror"
                        value="{{ old('mes_referencia', date('Y-m')) }}" required>
                    @error('mes_referencia')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-actions">
                <a href="{{ route('custos-fixos.index') }}" class="btn-cancel">Cancelar</a>
                <button type="submit" class="btn-primary"><i class="ti ti-device-floppy"></i> Salvar custo fixo</button>
            </div>
        </form>
    </div>
@endsection
