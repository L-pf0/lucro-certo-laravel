@extends('layouts.app')

@section('title', 'Dashboard | LucroCerto')

@section('content')
    <style>
        /* Todos os estilos do dashboard */
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

        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .kpi-card {
            background: #fff;
            border-radius: 14px;
            padding: 18px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border: 1px solid #f0f0f5;
        }

        .kpi-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .kpi-icon.purple {
            background: #f3f0ff;
        }

        .kpi-icon.pink {
            background: #fdf0f5;
        }

        .kpi-icon.green {
            background: #edfaf4;
        }

        .kpi-icon.amber {
            background: #fef9ec;
        }

        .kpi-label {
            font-size: 12px;
            color: #8b8fa8;
            margin-bottom: 2px;
        }

        .kpi-value {
            font-size: 22px;
            font-weight: 700;
            color: #1a1a2e;
        }

        .kpi-delta {
            font-size: 12px;
            color: #10b981;
        }

        .kpi-delta.up::before {
            content: "↑ ";
        }

        .card {
            background: #fff;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border: 1px solid #f0f0f5;
            margin-bottom: 0;
        }

        .card-title {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cost-summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 12px;
        }

        .cost-item {
            background: #f8fafc;
            padding: 12px 16px;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .cost-label {
            font-size: 13px;
            font-weight: 600;
            color: #4b4f6b;
        }

        .cost-value {
            font-size: 18px;
            font-weight: 700;
            color: #1a1a2e;
        }

        .card-sub {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 8px;
            text-align: right;
        }

        .bottom-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 16px;
            margin-bottom: 24px;
        }

        .update-list .update-item,
        .recipe-item,
        .alert-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f5;
        }

        .update-icon,
        .recipe-img {
            width: 36px;
            height: 36px;
            background: #f3f0ff;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .update-name,
        .recipe-name {
            font-weight: 600;
        }

        .update-sub,
        .recipe-margin {
            font-size: 12px;
            color: #8b8fa8;
        }

        .update-time {
            font-size: 11px;
            color: #b0b3c6;
            text-align: right;
            white-space: nowrap;
        }

        .recipe-price {
            margin-left: auto;
            font-weight: 700;
        }

        .alert-icon {
            font-size: 18px;
        }

        .alert-text {
            flex: 1;
            font-size: 13px;
        }

        .alert-arrow {
            color: #b0b3c6;
        }

        .cta-banner {
            background: linear-gradient(135deg, #7c3aed, #a855f7);
            border-radius: 14px;
            padding: 20px 24px;
            display: flex;
            align-items: center;
            gap: 16px;
            color: #fff;
        }

        .cta-icon {
            font-size: 32px;
            background: rgba(255, 255, 255, 0.15);
            width: 52px;
            height: 52px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cta-text h3 {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .cta-text p {
            font-size: 13px;
            opacity: 0.8;
        }

        .cta-btn {
            margin-left: auto;
            background: #fff;
            color: #7c3aed;
            border: none;
            padding: 10px 18px;
            border-radius: 10px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
        }

        .cta-btn:hover {
            background: #f0f0ff;
        }

        @media (max-width: 900px) {
            .cost-summary-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .bottom-grid {
                grid-template-columns: 1fr;
            }

            .kpi-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 600px) {
            .cost-summary-grid {
                grid-template-columns: 1fr;
            }

            .kpi-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="page-title">Olá, {{ Auth::user()->name ?? 'Gestora' }}!</div>
    <div class="page-sub">Bem-vinda ao LucroCerto. Veja o resumo do seu negócio hoje.</div>

    <!-- KPIs -->
    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-icon purple"><i class="ti ti-currency-dollar" style="font-size:22px;color:#7c3aed"></i></div>
            <div>
                <div class="kpi-label">CMV Médio</div>
                <div class="kpi-value">R$ {{ number_format($cmvMedio, 2, ',', '.') }}</div>
                <div class="kpi-delta up">vs mês anterior</div>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon pink"><i class="ti ti-chart-pie" style="font-size:22px;color:#ec4899"></i></div>
            <div>
                <div class="kpi-label">Margem Média</div>
                <div class="kpi-value">{{ number_format($margemMedia, 0) }}%</div>
                <div class="kpi-delta up">vs mês anterior</div>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon green"><i class="ti ti-shopping-bag" style="font-size:22px;color:#10b981"></i></div>
            <div>
                <div class="kpi-label">Receitas Cadastradas</div>
                <div class="kpi-value">{{ $totalReceitas }}</div>
                <div class="kpi-delta up">ativas</div>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon amber"><i class="ti ti-trending-up" style="font-size:22px;color:#f59e0b"></i></div>
            <div>
                <div class="kpi-label">Lucro Estimado (mês)</div>
                <div class="kpi-value">R$ {{ number_format(max($lucroEstimado, 0), 2, ',', '.') }}</div>
                <div class="kpi-delta up">estimativa</div>
            </div>
        </div>
    </div>

    <!-- Resumo de Custos -->
    <div class="card" style="margin-bottom: 24px;">
        <div class="card-title">💰 Resumo de Custos (este mês)</div>
        <div class="cost-summary-grid">
            <div class="cost-item">
                <span class="cost-label">🔹 Custos Fixos</span>
                <span class="cost-value">R$ {{ number_format($totalCustosFixos, 2, ',', '.') }}</span>
            </div>
            <div class="cost-item">
                <span class="cost-label">🔹 Custos Variáveis</span>
                <span class="cost-value">R$ {{ number_format($totalCustosVariaveis, 2, ',', '.') }}</span>
            </div>
            <div class="cost-item">
                <span class="cost-label">🔹 Mão de Obra (total receitas)</span>
                <span class="cost-value">R$ {{ number_format($totalMaoObra, 2, ',', '.') }}</span>
            </div>
            <div class="cost-item">
                <span class="cost-label">🔹 Insumos (último CMV)</span>
                <span class="cost-value">R$ {{ number_format($totalInsumosCusto, 2, ',', '.') }}</span>
            </div>
        </div>
    
    </div>

    <!-- Grid inferior -->
    <div class="bottom-grid">
        <div class="card">
            <div class="card-title">🔄 Últimas atualizações de insumos</div>
            <div class="update-list">
                @forelse($ultimosInsumos as $insumo)
                    <div class="update-item">
                        <div class="update-icon">🌾</div>
                        <div>
                            <div class="update-name">{{ $insumo->nome }}</div>
                            <div class="update-sub">Preço alterado para R$
                                {{ number_format($insumo->preco_unitario, 2, ',', '.') }}</div>
                        </div>
                        <div class="update-time">{{ $insumo->updated_at->diffForHumans() }}</div>
                    </div>
                @empty
                    <div class="update-item">Nenhum insumo atualizado recentemente.</div>
                @endforelse
            </div>
            <div style="margin-top:12px;font-size:13px;color:#7c3aed;font-weight:600"><a
                    href="{{ route('insumos.index') }}" style="text-decoration:none; color:inherit;">Ver todos →</a></div>
        </div>

        <div class="card">
            <div class="card-title">⭐ Receitas mais lucrativas</div>
            @forelse($receitasLucrativas as $rec)
                <div class="recipe-item">
                    <div class="recipe-img">🧁</div>
                    <div>
                        <div class="recipe-name">{{ $rec['nome'] }}</div>
                        <div class="recipe-margin">Margem: {{ number_format($rec['margem'], 0) }}%</div>
                    </div>
                    <div class="recipe-price">R$ {{ number_format($rec['preco'], 2, ',', '.') }}</div>
                </div>
            @empty
                <div class="recipe-item">Nenhuma receita lucrativa encontrada.</div>
            @endforelse
            <div style="margin-top:12px;font-size:13px;color:#7c3aed;font-weight:600"><a
                    href="{{ route('receitas.index') }}" style="text-decoration:none; color:inherit;">Ver todas →</a></div>
        </div>

        
    </div>

    <!-- Chamada para ação -->
    <div class="cta-banner">
        <div class="cta-icon"><i class="ti ti-chart-line" style="font-size:24px;color:#fff"></i></div>
        <div class="cta-text">
            <h3>Simule variações de preços e aumente sua lucratividade!</h3>
            <p>Acesse a ferramenta de simulação e veja o impacto no seu negócio.</p>
        </div>
        <a href="{{ route('simulacoes.index') }}" class="cta-btn">Ir para Simulações →</a>
    </div>
@endsection
