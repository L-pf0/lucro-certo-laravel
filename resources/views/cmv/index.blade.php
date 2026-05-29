@extends('layouts.app')

@section('title', 'Cálculos CMV | LucroCerto')

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

        .filter-card {
            background: #fff;
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            border: 1px solid #edf2f7;
        }

        .filter-bar {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            gap: 16px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-select,
        .filter-select {
            height: 42px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 0 16px;
            background: #fff;
            font-size: 14px;
        }

        .btn-primary {
            background: #7c3aed;
            color: #fff;
            border: none;
            padding: 0 20px;
            height: 42px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: 0.2s;
        }

        .btn-primary:hover {
            background: #6d28d9;
        }

        .btn-secondary {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            color: #334155;
            padding: 0 20px;
            height: 42px;
            border-radius: 12px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 20px;
            margin-top: 16px;
        }

        .cmv-card {
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s, box-shadow 0.2s;
            border: 1px solid #edf2f7;
        }

        .cmv-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%);
            padding: 16px 20px;
            border-bottom: 1px solid #e9d5ff;
        }

        .card-header h3 {
            font-size: 18px;
            font-weight: 700;
            color: #4c1d95;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-body {
            padding: 20px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 16px;
        }

        .info-label {
            font-size: 13px;
            font-weight: 500;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
        }

        .info-value small {
            font-size: 12px;
            font-weight: 400;
            color: #64748b;
        }

        .margin-badge {
            background: #dcfce7;
            color: #15803d;
            padding: 4px 10px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 600;
        }

        .card-footer {
            background: #f8fafc;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #eef2ff;
        }

        .action-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 6px 12px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 500;
            transition: 0.2s;
        }

        .action-btn.view {
            color: #4c1d95;
            background: #ede9fe;
        }

        .action-btn.view:hover {
            background: #e0d5ff;
        }

        .action-btn.refresh {
            color: #0f172a;
            background: #f1f5f9;
        }

        .action-btn.refresh:hover {
            background: #e2e8f0;
        }

        .pagination {
            margin-top: 32px;
            display: flex;
            justify-content: center;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            padding: 12px 20px;
            border-radius: 16px;
            margin-bottom: 24px;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            padding: 12px 20px;
            border-radius: 16px;
            margin-bottom: 24px;
        }

        @media (max-width: 700px) {
            .cards-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="page-title">Cálculos de CMV</div>
    <div class="page-sub">Histórico de custo da mercadoria vendida e precificação sugerida.</div>

    @if (session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert-error">{{ $errors->first() }}</div>
    @endif

    <div class="filter-card">
        <form method="GET" action="{{ route('cmv.index') }}" class="filter-bar">
            <div class="form-group">
                <label>Filtrar por receita</label>
                <select name="receita_id" class="form-select" style="min-width: 200px;">
                    <option value="">Todas as receitas</option>
                    @foreach ($receitas as $receita)
                        <option value="{{ $receita->id }}" {{ request('receita_id') == $receita->id ? 'selected' : '' }}>
                            {{ $receita->nome }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>&nbsp;</label>
                <button type="submit" class="btn-primary"><i class="fas fa-filter"></i> Filtrar</button>
            </div>
            @if (request('receita_id'))
                <div class="form-group">
                    <label>&nbsp;</label>
                    <a href="{{ route('cmv.index') }}" class="btn-secondary"><i class="fas fa-times"></i> Limpar</a>
                </div>
            @endif
        </form>
    </div>

    <div class="cards-grid">
        @forelse($calculos as $calculo)
            <div class="cmv-card">
                <div class="card-header">
                    <h3>
                        <i class="fas fa-receipt" style="font-size: 20px;"></i>
                        {{ $calculo->receita->nome }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-coins" style="margin-right: 6px;"></i> CMV unitário</span>
                        <span class="info-value">R$ {{ number_format($calculo->cmv_unitario, 4, ',', '.') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-tag" style="margin-right: 6px;"></i> Preço sugerido</span>
                        <span class="info-value" style="color: #7c3aed;">R$
                            {{ number_format($calculo->preco_sugerido, 2, ',', '.') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-chart-line" style="margin-right: 6px;"></i> Margem de
                            lucro</span>
                        <span class="margin-badge">{{ $calculo->percentual_lucro }}%</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-calendar-alt" style="margin-right: 6px;"></i> Data</span>
                        <span class="info-value"><small>{{ $calculo->created_at->format('d/m/Y H:i') }}</small></span>
                    </div>
                    <!-- Barra visual representando a relação CMV/Preço -->
                    @php
                        $percentCmv =
                            $calculo->preco_sugerido > 0
                                ? ($calculo->cmv_unitario / $calculo->preco_sugerido) * 100
                                : 0;
                    @endphp
                    <div class="progress-bar-container"
                        style="margin-top: 12px; background: #e2e8f0; border-radius: 20px; overflow: hidden; height: 6px;">
                        <div style="width: {{ min($percentCmv, 100) }}%; background: #a78bfa; height: 6px;"></div>
                    </div>
                    <div
                        style="display: flex; justify-content: space-between; font-size: 11px; color: #64748b; margin-top: 4px;">
                        <span><i class="fas fa-chart-pie"></i> CMV {{ number_format($percentCmv, 0) }}% do preço</span>
                        <span>Margem {{ 100 - $percentCmv }}%</span>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('cmv.show', $calculo->id) }}" class="action-btn view">
                        <i class="fas fa-eye"></i> Ver detalhes
                    </a>
                    @if (!Auth::user()->isVisualizador())
                        <button type="button" class="action-btn refresh" onclick="recalcular({{ $calculo->receita_id }})">
                            <i class="fas fa-sync-alt"></i> Recalcular
                        </button>
                    @endif

                </div>
            </div>
        @empty
            <div style="grid-column: 1/-1; text-align: center; padding: 60px; background: #fff; border-radius: 20px;">
                <i class="fas fa-chart-bar" style="font-size: 48px; color: #cbd5e1;"></i>
                <p style="margin-top: 16px; color: #64748b;">Nenhum cálculo de CMV encontrado. Clique em recalcular para
                    gerar.</p>
            </div>
        @endforelse
    </div>

    <div class="pagination">
        {{ $calculos->links() }}
    </div>

    <form id="recalcular-form" method="POST" style="display: none;">
        @csrf
    </form>

    <script>
        function recalcular(receitaId) {
            if (confirm('Recalcular o CMV para esta receita? Isso criará um novo registro histórico.')) {
                const form = document.getElementById('recalcular-form');
                form.action = `/receitas/${receitaId}/recalcular-cmv`;
                form.submit();
            }
        }
    </script>
@endsection
