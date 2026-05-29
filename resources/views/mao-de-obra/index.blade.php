@extends('layouts.app')

@section('title', 'Mão de Obra | LucroCerto')

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

        .table-card {
            background: #fff;
            border-radius: 14px;
            padding: 24px;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .btn-primary {
            background: #7c3aed;
            color: #fff;
            border: none;
            padding: 0 18px;
            height: 40px;
            border-radius: 9px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 12px;
            font-size: 12px;
            font-weight: 700;
            color: #8b8fa8;
            border-bottom: 2px solid #f0f0f5;
        }

        td {
            padding: 12px;
            font-size: 14px;
            border-bottom: 1px solid #f4f5f7;
            vertical-align: middle;
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

        .action-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 6px;
            margin: 0 4px;
        }

        .action-btn.edit {
            color: #7c3aed;
        }

        .action-btn.del {
            color: #e53e3e;
        }

        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
    </style>

    <div class="page-title">Mão de Obra</div>
    <div class="page-sub">Registre custos com mão de obra direta (produção) e indireta, vinculados ou não a receitas
        específicas.</div>

    @if (session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-card">
        <div class="table-header">
            <h2 style="font-size:16px; font-weight:700;">Registros de mão de obra</h2>
            @if (!Auth::user()->isVisualizador())
                <!-- botões de criar, editar, excluir, recalcular etc. -->
                <a href="{{ route('mao-de-obra.create') }}" class="btn-primary"><i class="ti ti-plus"></i> Novo registro</a>
            @endif
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Descrição</th>
                        <th>Receita vinculada</th>
                        <th>Tempo (horas)</th>
                        <th>Valor / hora</th>
                        <th>Valor total</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($maoDeObra as $item)
                        <tr>
                            <td>{{ $item->descricao }}</td>
                            <td>{{ optional($item->receitas->first())->nome ?? 'Geral' }}</td>
                            <td>{{ number_format($item->tempo_horas, 2, ',', '.') }} h</td>
                            <td>R$ {{ number_format($item->valor_hora, 2, ',', '.') }}</td>
                            <td>R$ {{ number_format($item->valor_total, 2, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('mao-de-obra.show', $item) }}" class="action-btn"
                                    style="color:#7c3aed;"><i class="ti ti-eye"></i></a>

                                @if (!Auth::user()->isVisualizador())
                                    <a href="{{ route('mao-de-obra.edit', $item) }}" class="action-btn edit"><i
                                            class="ti ti-pencil"></i></a>
                                    <form action="{{ route('mao-de-obra.destroy', $item) }}" method="POST"
                                        style="display:inline-block;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="action-btn del"
                                            onclick="return confirm('Remover este registro?')"><i
                                                class="ti ti-trash"></i></button>
                                    </form>
                                @endif

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">Nenhum registro de mão de obra ainda. Clique em "Novo registro".</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination">{{ $maoDeObra->links() }}</div>
    </div>
@endsection
