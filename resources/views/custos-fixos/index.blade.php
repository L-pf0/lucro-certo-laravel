@extends('layouts.app')

@section('title', 'Custos Fixos | LucroCerto')

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

    <div class="page-title">Custos Fixos</div>
    <div class="page-sub">Gerencie os custos fixos mensais do seu negócio (aluguel, contas, salários administrativos...).
    </div>

    @if (session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-card">
        <div class="table-header">
            <h2 style="font-size:16px; font-weight:700;">Lista de custos fixos</h2>
            @if (!Auth::user()->isVisualizador())
                <!-- botões de criar, editar, excluir, recalcular etc. -->
                <a href="{{ route('custos-fixos.create') }}" class="btn-primary"><i class="ti ti-plus"></i> Novo custo fixo</a>
            @endif
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Descrição</th>
                        <th>Valor mensal</th>
                        <th>Mês referência</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($custos as $custo)
                        <tr>
                            <td>{{ $custo->descricao }}</td>
                            <td>R$ {{ number_format($custo->valor_mensal, 2, ',', '.') }}</td>
                            <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $custo->mes_referencia)->format('m/Y') }}</td>
                            <td>
                                <a href="{{ route('custos-fixos.show', $custo) }}" class="action-btn"
                                    style="color:#7c3aed;"><i class="ti ti-eye"></i></a>

                                @if (!Auth::user()->isVisualizador())
                                    <a href="{{ route('custos-fixos.edit', $custo) }}" class="action-btn edit"><i
                                            class="ti ti-pencil"></i></a>
                                    <form action="{{ route('custos-fixos.destroy', $custo) }}" method="POST"
                                        style="display:inline-block;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="action-btn del"
                                            onclick="return confirm('Remover este custo fixo?')"><i
                                                class="ti ti-trash"></i></button>
                                    </form>
                                @endif

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">Nenhum custo fixo cadastrado ainda. Clique em "Novo custo fixo" para começar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination">{{ $custos->links() }}</div>
    </div>
@endsection
