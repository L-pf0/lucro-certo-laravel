@extends('layouts.app')

@section('title', 'Detalhes da Mão de Obra | LucroCerto')

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
            max-width: 700px;
        }

        .info-row {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f5;
        }

        .info-label {
            width: 200px;
            font-weight: 600;
            color: #4b4f6b;
        }

        .info-value {
            flex: 1;
            color: #1a1a2e;
        }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            background: #edfaf4;
            color: #10b981;
        }

        .btn-back {
            margin-top: 24px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #7c3aed;
            color: #fff;
            padding: 8px 18px;
            border-radius: 9px;
            text-decoration: none;
            font-size: 14px;
        }
    </style>

    <div class="page-title">Detalhes do Registro</div>
    <div class="page-sub">Informações completas do custo de mão de obra.</div>

    <div class="detail-card">
        <div class="info-row">
            <div class="info-label">Descrição</div>
            <div class="info-value">{{ $maoDeObra->descricao }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Receita vinculada</div>
            <div class="info-value">
                {{ optional($maoDeObra->receitas->first())->nome ?? 'Custo geral' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Tempo (horas)</div>
            <div class="info-value">{{ number_format($maoDeObra->tempo_horas, 2, ',', '.') }} h</div>
        </div>
        <div class="info-row">
            <div class="info-label">Valor por hora</div>
            <div class="info-value">R$ {{ number_format($maoDeObra->valor_hora, 2, ',', '.') }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Valor total</div>
            <div class="info-value">R$ {{ number_format($maoDeObra->valor_total, 2, ',', '.') }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Data de criação</div>
            <div class="info-value">{{ $maoDeObra->created_at->format('d/m/Y H:i') }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Última atualização</div>
            <div class="info-value">{{ $maoDeObra->updated_at->format('d/m/Y H:i') }}</div>
        </div>
        <a href="{{ route('mao-de-obra.index') }}" class="btn-back"><i class="ti ti-arrow-left"></i> Voltar</a>
    </div>
@endsection
