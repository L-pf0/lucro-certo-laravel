@extends('layouts.app')

@section('title', $insumo->nome . ' | LucroCerto')

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
            max-width: 600px;
        }

        .info-row {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f5;
        }

        .info-label {
            width: 160px;
            font-weight: 600;
            color: #4b4f6b;
        }

        .info-value {
            flex: 1;
            color: #1a1a2e;
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

    <div class="page-title">{{ $insumo->nome }}</div>
    <div class="page-sub">Detalhes do insumo cadastrado.</div>

    <div class="detail-card">
        <div class="info-row">
            <div class="info-label">Nome do insumo</div>
            <div class="info-value">{{ $insumo->nome }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Unidade de medida</div>
            <div class="info-value">{{ $insumo->unidade_medida }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Preço unitário</div>
            <div class="info-value">R$ {{ number_format($insumo->preco_unitario, 2, ',', '.') }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Data de criação</div>
            <div class="info-value">{{ $insumo->created_at->format('d/m/Y H:i') }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Última atualização</div>
            <div class="info-value">{{ $insumo->updated_at->format('d/m/Y H:i') }}</div>
        </div>

        <a href="{{ route('insumos.index') }}" class="btn-back">
            <i class="ti ti-arrow-left"></i> Voltar para lista
        </a>
    </div>
@endsection
