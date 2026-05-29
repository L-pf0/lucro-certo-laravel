@extends('layouts.app')

@section('title', $receita->nome . ' | LucroCerto')

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
            border-radius: 14px;
            padding: 24px;
            margin-bottom: 24px;
        }

        .detail-top {
            display: grid;
            grid-template-columns: 280px 1fr 280px;
            gap: 24px;
        }

        .detail-recipe-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .detail-recipe-img {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            background: #f3f0ff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
        }

        .detail-recipe-name {
            font-size: 17px;
            font-weight: 700;
            color: #1a1a2e;
        }

        .detail-recipe-yield {
            font-size: 13px;
            color: #8b8fa8;
        }

        .metrics-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-bottom: 12px;
        }

        .metric-box {
            background: #f8f9fb;
            border-radius: 10px;
            padding: 12px;
        }

        .metric-box label {
            font-size: 11px;
            color: #8b8fa8;
            font-weight: 600;
            text-transform: uppercase;
            display: block;
            margin-bottom: 4px;
        }

        .metric-box .val {
            font-size: 18px;
            font-weight: 700;
            color: #1a1a2e;
        }

        .metric-box .val.green {
            color: #10b981;
        }

        .extra-info {
            background: #f8f9fb;
            border-radius: 10px;
            padding: 16px;
            font-size: 13px;
            color: #4b4f6b;
            margin-top: 12px;
        }

        .ingredients-table {
            width: 100%;
            border-collapse: collapse;
        }

        .ingredients-table th,
        .ingredients-table td {
            font-size: 13px;
            padding: 8px 10px;
            border-bottom: 1px solid #f0f0f5;
            text-align: left;
        }

        .total-row td {
            font-weight: 700;
            border-top: 2px solid #f0f0f5;
        }

        .total-row .highlight {
            color: #7c3aed;
        }

        .prep-steps {
            list-style: none;
            padding: 0;
        }

        .prep-steps li {
            display: flex;
            gap: 10px;
            margin-bottom: 14px;
            font-size: 13px;
            color: #4b4f6b;
        }

        .step-num {
            width: 24px;
            height: 24px;
            min-width: 24px;
            border-radius: 50%;
            background: #7c3aed;
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .print-btn {
            background: #fff;
            border: 1.5px solid #e2e6f0;
            color: #4b4f6b;
            padding: 10px 18px;
            border-radius: 9px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 16px;
        }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            background: #fef9ec;
            color: #92400e;
        }

        @media (max-width: 900px) {
            .detail-top {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }
    </style>

    <div class="page-title">{{ $receita->nome }}</div>
    <div class="page-sub">Detalhes da receita, ingredientes e custos.</div>

    <div class="detail-card">
        <div class="detail-top">
            <div>
                <div class="detail-recipe-header">
                    <div class="detail-recipe-img">🍽️</div>
                    <div>
                        <div class="detail-recipe-name">{{ $receita->nome }} <span class="badge">Salgados</span></div>
                        <div class="detail-recipe-yield">Rendimento: {{ $receita->rendimento_lote }} unidades</div>
                    </div>
                </div>
                <div class="metrics-row">
                    @php $cmv = $receita->cmvCalculos->first(); @endphp
                    <div class="metric-box"><label>CMV (unitário)</label>
                        <div class="val">R$ {{ number_format($cmv->cmv_unitario ?? 0, 2, ',', '.') }}</div>
                    </div>
                    <div class="metric-box"><label>Preço de venda</label>
                        <div class="val">R$ {{ number_format($cmv->preco_sugerido ?? 0, 2, ',', '.') }}</div>
                    </div>
                    <div class="metric-box"><label>Margem de lucro</label>
                        <div class="val">{{ number_format($cmv->percentual_lucro ?? 0, 0) }}%</div>
                    </div>
                    <div class="metric-box"><label>Lucro (unitário)</label>
                        <div class="val green">R$
                            {{ number_format(($cmv->preco_sugerido ?? 0) - ($cmv->cmv_unitario ?? 0), 2, ',', '.') }}</div>
                    </div>
                </div>

                <!-- Composição do Preço de Venda -->
                @php
                    if ($cmv && $cmv->preco_sugerido > 0) {
                        $preco = $cmv->preco_sugerido;
                        $custoInsumosUnit = $cmv->custo_insumos_total / $receita->rendimento_lote;
                        $custoMaoObraUnit =
                            $receita->maosDeObra->sum(function ($mod) {
                                return $mod->pivot->horas * $mod->valor_hora;
                            }) / $receita->rendimento_lote;
                        $lucroUnit = $preco - ($custoInsumosUnit + $custoMaoObraUnit);
                        $pctInsumos = ($custoInsumosUnit / $preco) * 100;
                        $pctMaoObra = ($custoMaoObraUnit / $preco) * 100;
                        $pctLucro = ($lucroUnit / $preco) * 100;
                    } else {
                        $custoInsumosUnit = $custoMaoObraUnit = $lucroUnit = $pctInsumos = $pctMaoObra = $pctLucro = 0;
                        $preco = 0;
                    }
                @endphp
                <div style="background: #f8fafc; border-radius: 12px; padding: 16px; margin-top: 20px;">
                    <h4 style="font-size: 14px; font-weight: 600; margin-bottom: 12px;">📊 Composição do Preço de Venda</h4>
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="border-bottom: 2px solid #e2e8f0;">
                                <th style="text-align: left; padding: 8px 0;">Componente</th>
                                <th style="text-align: right; padding: 8px 0;">% do Preço</th>
                                <th style="text-align: right; padding: 8px 0;">Valor (R$)</th>
                            <tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding: 8px 0;">🍞 Insumos</td>
                                <td style="text-align: right;">{{ number_format($pctInsumos, 1) }}%</td>
                                <td style="text-align: right;">R$ {{ number_format($custoInsumosUnit, 2, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0;">👩‍🍳 Mão de obra</td>
                                <td style="text-align: right;">{{ number_format($pctMaoObra, 1) }}%</td>
                                <td style="text-align: right;">R$ {{ number_format($custoMaoObraUnit, 2, ',', '.') }}</td>
                            </tr>
                            @if (($custoFixoRateado ?? 0) > 0 || ($custoVariavelRateado ?? 0) > 0)
                                <tr>
                                    <td style="padding: 8px 0;">🏢 Custos fixos + variáveis</td>
                                    <td style="text-align: right;">
                                        {{ number_format((($custoFixoRateado + $custoVariavelRateado) / $preco) * 100, 1) }}%
                                    </td>
                                    <td style="text-align: right;">R$
                                        {{ number_format($custoFixoRateado + $custoVariavelRateado, 2, ',', '.') }}</td>
                                </tr>
                            @endif
                            <tr style="border-top: 2px solid #e2e8f0; font-weight: 700;">
                                <td style="padding: 8px 0;">💰 Lucro ({{ number_format($cmv->percentual_lucro ?? 30, 0) }}%
                                    margem)</td>
                                <td style="text-align: right;">{{ number_format($pctLucro, 1) }}%</td>
                                <td style="text-align: right;">R$ {{ number_format($lucroUnit, 2, ',', '.') }}</td>
                            </tr>
                            <tr style="border-top: 2px solid #cbd5e1; font-weight: 800;">
                                <td style="padding: 8px 0;">📌 Preço de venda final</td>
                                <td style="text-align: right;">100%</td>
                                <td style="text-align: right;">R$ {{ number_format($preco, 2, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <p style="font-size: 11px; color: #64748b; margin-top: 12px;">* Baseado no CMV (insumos + mão de obra) e
                        margem de lucro definida.</p>
                </div>

                <div class="extra-info">
                    <div><strong>Data de criação:</strong> {{ $receita->created_at->format('d/m/Y') }}</div>
                    <div><strong>Última atualização:</strong> {{ $receita->updated_at->format('d/m/Y') }}</div>
                    <div><strong>Tempo de preparo:</strong> {{ $receita->tempo_preparo_horas }} horas</div>
                    <div>
                        <strong>Custo mão de obra:</strong> R$
                        {{ number_format($receita->maosDeObra->sum(function ($mod) {return $mod->pivot->horas * $mod->valor_hora;}),2,',','.') }}
                    </div>
                    @if ($receita->maosDeObra->count())
                        <div><strong>Detalhamento:</strong>
                            @foreach ($receita->maosDeObra as $mod)
                                <div class="text-sm">
                                    {{ $mod->descricao }}: {{ $mod->pivot->horas }}h
                                    (R$ {{ number_format($mod->pivot->horas * $mod->valor_hora, 2, ',', '.') }})
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div>
                <div style="font-size:15px; font-weight:700; margin-bottom:12px;">Ingredientes</div>
                <table class="ingredients-table">
                    <thead>
                        <tr>
                            <th>Ingrediente</th>
                            <th>Qtd.</th>
                            <th>Un.</th>
                            <th>Custo unit.</th>
                            <th>Custo total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($receita->insumos as $insumo)
                            <tr>
                                <td>{{ $insumo->nome }}</td>
                                <td>{{ number_format($insumo->pivot->quantidade, 3, ',', '.') }}</td>
                                <td>{{ $insumo->unidade_medida }}</td>
                                <td>R$ {{ number_format($insumo->pivot->custo_unitario, 2, ',', '.') }}</td>
                                <td>R$ {{ number_format($insumo->pivot->custo_total, 2, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">Nenhum insumo vinculado.</td>
                            </tr>
                        @endforelse
                        <tr class="total-row">
                            <td colspan="4">Custo total da receita</td>
                            <td class="highlight">R$
                                {{ number_format($receita->insumos->sum('pivot.custo_total'), 2, ',', '.') }}</td>
                        </tr>
                        <tr class="total-row">
                            <td colspan="4">CMV por unidade ({{ $receita->rendimento_lote }} un.)</td>
                            <td class="highlight">R$ {{ number_format($cmv->cmv_unitario ?? 0, 2, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div>
                <div style="font-size:15px; font-weight:700; margin-bottom:12px;">Modo de preparo</div>
                <ol class="prep-steps">
                    <li><span class="step-num">1</span>Prepare os ingredientes conforme a receita.</li>
                    <li><span class="step-num">2</span>Siga o passo a passo específico da sua produção.</li>
                    <li><span class="step-num">3</span>Controle o tempo e os custos de produção.</li>
                </ol>
                <button class="print-btn" onclick="window.print()"><i class="ti ti-printer"></i> Imprimir ficha
                    técnica</button>
            </div>
        </div>
    </div>

    <a href="{{ route('receitas.index') }}" class="btn-cancel"
        style="display:inline-flex; align-items:center; gap:6px; text-decoration:none;">
        <i class="ti ti-arrow-left"></i> Voltar
    </a>
@endsection
