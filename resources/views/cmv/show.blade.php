@extends('layouts.app')

@section('title', 'Detalhes do CMV | LucroCerto')

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

        .detail-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            max-width: 1000px;
            margin: 0 auto;
        }

        .detail-header {
            background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
            padding: 20px 24px;
            color: white;
        }

        .detail-header h3 {
            font-size: 18px;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .detail-header p {
            margin: 5px 0 0;
            font-size: 13px;
            opacity: 0.9;
        }

        .detail-body {
            padding: 24px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 32px;
        }

        .info-item {
            background: #f9fafb;
            border-radius: 16px;
            padding: 16px;
            border: 1px solid #f0f0f5;
            transition: all 0.2s;
        }

        .info-item:hover {
            border-color: #e0e7ff;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
        }

        .info-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .info-label i {
            font-size: 14px;
            color: #7c3aed;
        }

        .info-value {
            font-size: 22px;
            font-weight: 700;
            color: #111827;
            line-height: 1.2;
        }

        .info-value small {
            font-size: 13px;
            font-weight: 400;
            color: #6b7280;
        }

        .highlight-value {
            color: #7c3aed;
            font-size: 26px;
        }

        .badge-margin {
            display: inline-block;
            background: #e0e7ff;
            color: #4338ca;
            padding: 4px 10px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 600;
        }

        .divider {
            border-top: 1px solid #f0f0f5;
            margin: 20px 0;
        }

        .btn-back {
            background: #7c3aed;
            color: #fff;
            border: none;
            padding: 10px 22px;
            border-radius: 40px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: background 0.2s;
        }

        .btn-back:hover {
            background: #6d28d9;
            color: #fff;
        }

        @media (max-width: 640px) {
            .info-grid {
                grid-template-columns: 1fr;
            }

            .info-value {
                font-size: 18px;
            }

            .highlight-value {
                font-size: 22px;
            }
        }
    </style>

    <div class="page-title">Detalhes do CMV</div>
    <div class="page-sub">Análise completa do custo e precificação da receita.</div>

    <div class="detail-card">
        <div class="detail-header">
            <h3><i class="fas fa-chart-line"></i> {{ $calculo->receita->nome }}</h3>
            <p><i class="far fa-calendar-alt"></i> Cálculo realizado em
                {{ $calculo->created_at->format('d/m/Y \à\s H:i:s') }}</p>
        </div>
        <div class="detail-body">
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label"><i class="fas fa-boxes"></i> Custo de insumos</div>
                    <div class="info-value">R$ {{ number_format($calculo->custo_insumos_total, 4, ',', '.') }}</div>
                    <small>total por lote</small>
                </div>
                <div class="info-item">
                    <div class="info-label"><i class="fas fa-user-cog"></i> Mão de obra</div>
                    <div class="info-value">R$ {{ number_format($calculo->custo_mao_obra, 4, ',', '.') }}</div>
                    <small>total por lote</small>
                </div>
                @if (($calculo->custo_fixo_rateado ?? 0) > 0 || ($calculo->custo_variavel_rateado ?? 0) > 0)
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-charging-station"></i> Custos fixos rateados</div>
                        <div class="info-value">R$ {{ number_format($calculo->custo_fixo_rateado, 4, ',', '.') }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-waveform"></i> Custos variáveis rateados</div>
                        <div class="info-value">R$ {{ number_format($calculo->custo_variavel_rateado, 4, ',', '.') }}</div>
                    </div>
                @endif
            </div>

            <div class="divider"></div>

            <div class="info-grid" style="margin-bottom: 0;">
                <div class="info-item">
                    <div class="info-label"><i class="fas fa-calculator"></i> CMV unitário</div>
                    <div class="info-value highlight-value">R$ {{ number_format($calculo->cmv_unitario, 4, ',', '.') }}
                    </div>
                    <small>custo por unidade da receita</small>
                </div>
                <div class="info-item">
                    <div class="info-label"><i class="fas fa-tag"></i> Preço sugerido</div>
                    <div class="info-value highlight-value">R$ {{ number_format($calculo->preco_sugerido, 2, ',', '.') }}
                    </div>
                    <small>com margem de <span class="badge-margin">{{ $calculo->percentual_lucro }}%</span></small>
                </div>
                <div class="info-item">
                    <div class="info-label"><i class="fas fa-chart-simple"></i> Margem de contribuição</div>
                    <div class="info-value">R$ {{ number_format($calculo->margem_contribuicao, 4, ',', '.') }}</div>
                    <small>preço – CMV unitário</small>
                </div>
                <div class="info-item">
                    <div class="info-label"><i class="fas fa-percent"></i> Rentabilidade estimada</div>
                    <div class="info-value">
                        {{ number_format((($calculo->preco_sugerido - $calculo->cmv_unitario) / $calculo->preco_sugerido) * 100, 1, ',', '.') }}%
                    </div>
                    <small>sobre o preço sugerido</small>
                </div>
            </div>

            <div class="divider"></div>

            <div style="display: flex; justify-content: flex-end; margin-top: 16px;">
                <a href="{{ route('cmv.index') }}" class="btn-back"><i class="fas fa-arrow-left"></i> Voltar ao
                    histórico</a>
            </div>
        </div>
    </div>
@endsection
