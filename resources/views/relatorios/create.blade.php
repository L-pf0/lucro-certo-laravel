@extends('layouts.app')

@section('title', 'Gerar Relatório | LucroCerto')

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
            max-width: 600px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: 600;
            display: block;
            margin-bottom: 8px;
            color: #4b4f6b;
        }

        .form-input,
        .form-select {
            width: 100%;
            padding: 10px;
            border: 1.5px solid #e2e6f0;
            border-radius: 9px;
            background: #fff;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 24px;
        }

        .btn-cancel {
            background: #fff;
            border: 1.5px solid #e2e6f0;
            padding: 9px 18px;
            border-radius: 9px;
            cursor: pointer;
            text-decoration: none;
            color: #4b4f6b;
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

    <div class="page-title">Gerar Relatório</div>
    <div class="page-sub">Selecione o tipo, período e receita (opcional).</div>

    <div class="form-card">
        <div class="info-box">
            <strong><i class="ti ti-info-circle"></i> Tipos de relatório</strong><br>
            <strong>PDF:</strong> listagem de cálculos de CMV.<br>
            <strong>CSV:</strong> dados em formato para planilhas.<br>
            <strong>DRE:</strong> Demonstrativo de Resultados do Exercício (PDF).
        </div>

        <form action="{{ route('relatorios.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Tipo de relatório <span>*</span></label>
                <select name="tipo" class="form-select" required>
                    <option value="pdf" {{ old('tipo') == 'pdf' ? 'selected' : '' }}>PDF (Relatório de CMV)</option>
                    <option value="dre" {{ old('tipo') == 'dre' ? 'selected' : '' }}>DRE (Demonstrativo de Resultados)
                    </option>
                </select>
                @error('tipo')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Período (mês/ano) <span>*</span></label>
                <input type="month" name="periodo" class="form-input" value="{{ old('periodo', date('Y-m')) }}" required>
                @error('periodo')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group" id="receita-group" style="display: {{ old('tipo') == 'dre' ? 'none' : 'flex' }};">
                <label>Receita específica <span>(opcional)</span></label>
                <select name="receita_id" class="form-select">
                    <option value="">Todas as receitas</option>
                    @foreach ($receitas as $receita)
                        <option value="{{ $receita->id }}" {{ old('receita_id') == $receita->id ? 'selected' : '' }}>
                            {{ $receita->nome }}</option>
                    @endforeach
                </select>
                @error('receita_id')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-actions">
                <a href="{{ route('relatorios.index') }}" class="btn-cancel">Cancelar</a>
                <button type="submit" class="btn-primary"><i class="ti ti-device-floppy"></i> Gerar relatório</button>
            </div>
        </form>
    </div>

    <script>
        document.querySelector('select[name="tipo"]').addEventListener('change', function() {
            let receitaGroup = document.getElementById('receita-group');
            receitaGroup.style.display = this.value === 'dre' ? 'none' : 'flex';
        });
    </script>
@endsection
