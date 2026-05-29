@extends('layouts.app')

@section('title', 'Custos Variáveis | LucroCerto')

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

        .filter-bar {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .filter-select {
            height: 40px;
            border: 1.5px solid #e2e6f0;
            border-radius: 9px;
            padding: 0 12px;
            background: #fff;
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

        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge.produto {
            background: #f0f5ff;
            color: #3730a3;
        }

        .badge.geral {
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

    <div class="page-title">Custos Variáveis</div>
    <div class="page-sub">Gerencie custos que variam conforme a produção (embalagens, comissões, energia elétrica
        proporcional...).</div>

    @if (session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-card">
        <div class="table-header">
            <div class="filter-bar">
                <form method="GET" action="{{ route('custos-variaveis.index') }}"
                    style="display: flex; gap: 8px; align-items: center;">
                    <select name="tipo" class="filter-select" onchange="this.form.submit()">
                        <option value="">Todos os tipos</option>
                        <option value="produto" {{ request('tipo') == 'produto' ? 'selected' : '' }}>Produto (vinculado a
                            receita)</option>
                        <option value="geral" {{ request('tipo') == 'geral' ? 'selected' : '' }}>Geral (rateado)</option>
                    </select>
                    @if (request('tipo'))
                        <a href="{{ route('custos-variaveis.index') }}" class="btn-clear"
                            style="background:#f4f5f7; border:1px solid #e2e6f0; padding:0 12px; height:40px; border-radius:9px; display:inline-flex; align-items:center;">Limpar</a>
                    @endif
                </form>
            </div>
            @if (!Auth::user()->isVisualizador())
                <a href="{{ route('custos-variaveis.create') }}" class="btn-primary"><i class="ti ti-plus"></i> Novo custo variável</a>
            @endif

        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Descrição</th>
                        <th>Tipo</th>
                        <th>Valor (R$)</th>
                        <th>Receita vinculada</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($custos as $custo)
                        <tr>
                            <td>{{ $custo->descricao }}</td>
                            <td><span
                                    class="badge {{ $custo->tipo == 'produto' ? 'produto' : 'geral' }}">{{ ucfirst($custo->tipo) }}</span>
                            </td>
                            <td>R$ {{ number_format($custo->valor, 2, ',', '.') }}</td>
                            <td>{{ $custo->receita ? $custo->receita->nome : '—' }}</td>
                            <td>
                                <a href="{{ route('custos-variaveis.show', $custo) }}" class="action-btn"
                                    style="color:#7c3aed;"><i class="ti ti-eye"></i></a>


                                @if (!Auth::user()->isVisualizador())
                                    <a href="{{ route('custos-variaveis.edit', $custo) }}" class="action-btn edit"><i
                                            class="ti ti-pencil"></i></a>
                                    <form action="{{ route('custos-variaveis.destroy', $custo) }}" method="POST"
                                        style="display:inline-block;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="action-btn del"
                                            onclick="return confirm('Remover este custo variável?')"><i
                                                class="ti ti-trash"></i></button>
                                    </form>
                                @endif

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">Nenhum custo variável cadastrado ainda. Clique em "Novo custo variável".</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination">{{ $custos->links() }}</div>
    </div>
@endsection
