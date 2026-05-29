@extends('layouts.app')

@section('title', 'Receitas | LucroCerto')

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

        .search-card {
            background: #fff;
            border-radius: 14px;
            padding: 20px;
            margin-bottom: 24px;
        }

        .search-card h3 {
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .search-card p {
            font-size: 13px;
            color: #8b8fa8;
            margin-bottom: 16px;
        }

        .filter-bar {
            display: flex;
            align-items: flex-end;
            gap: 16px;
            flex-wrap: wrap;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-group label {
            font-size: 12px;
            font-weight: 600;
            color: #4b4f6b;
        }

        .search-inp {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f4f5f7;
            border: 1.5px solid #e2e6f0;
            border-radius: 9px;
            padding: 0 12px;
            height: 40px;
        }

        .search-inp input {
            border: none;
            background: transparent;
            outline: none;
            font-size: 14px;
            width: 220px;
        }

        .form-select {
            height: 40px;
            border: 1.5px solid #e2e6f0;
            border-radius: 9px;
            padding: 0 12px;
            background: #fff;
            width: 180px;
        }

        .btn-clear,
        .btn-primary {
            height: 40px;
            padding: 0 18px;
            border-radius: 9px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: none;
        }

        .btn-clear {
            background: #fff;
            border: 1.5px solid #e2e6f0;
            color: #4b4f6b;
        }

        .btn-primary {
            background: #7c3aed;
            color: #fff;
        }

        .table-card {
            background: #fff;
            border-radius: 14px;
            padding: 24px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            font-size: 12px;
            font-weight: 700;
            color: #8b8fa8;
            text-transform: uppercase;
            padding: 10px 12px;
            border-bottom: 2px solid #f0f0f5;
            text-align: left;
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
        }

        .badge.salgados {
            background: #fff0f0;
            color: #c53030;
        }

        .badge.doces {
            background: #fef9ec;
            color: #92400e;
        }

        .badge.bolos {
            background: #f0f5ff;
            color: #3730a3;
        }

        .margin-val {
            color: #10b981;
            font-weight: 600;
        }

        .action-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 6px;
            border-radius: 6px;
            margin: 0 2px;
        }

        .action-btn.view {
            color: #7c3aed;
        }

        .action-btn.edit {
            color: #7c3aed;
        }

        .action-btn.del {
            color: #e53e3e;
        }

        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }

        .pag-btns {
            display: flex;
            gap: 8px;
        }

        .pag-btn {
            border: 1px solid #e2e6f0;
            background: #fff;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            cursor: pointer;
        }

        .pag-btn.active {
            background: #7c3aed;
            color: #fff;
            border-color: #7c3aed;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
    </style>

    <div class="page-title">Receitas</div>
    <div class="page-sub">Cadastre suas receitas e consulte os detalhes de rendimento, ingredientes e custos.</div>

    @if (session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="search-card">
        <h3>Consultar receitas</h3>
        <p>Pesquise receitas cadastradas</p>
        <div class="filter-bar">
            <div class="form-group" style="flex:1">
                <div class="search-inp">
                    <i class="ti ti-search"></i>
                    <input type="text" id="search" placeholder="Buscar por nome da receita...">
                </div>
            </div>
            <div class="form-group">
                <!-- espaço para outros filtros, se houver -->
            </div>
            <button class="btn-primary" id="filterBtn">Filtrar</button>
            <button class="btn-clear" id="clearFilters">Limpar filtros</button>
            @if (!Auth::user()->isVisualizador())
                <!-- botões de criar, editar, excluir, recalcular etc. -->
                <a href="{{ route('receitas.create') }}" class="btn-primary"><i class="ti ti-plus"></i> Nova receita</a>
            @endif

        </div>
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Nome da receita</th>
                    <th>Categoria</th>
                    <th>Rendimento</th>
                    <th>CMV (unit.)</th>
                    <th>Preço de venda</th>
                    <th>Margem de lucro</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody id="receitas-tbody">
                @forelse($receitas as $receita)
                    @php
                        $ultimoCmv = $receita->cmvCalculos->first();
                    @endphp
                    <tr>
                        <td>
                            <div class="td-name">
                                <span class="food-icon">🍽️</span> {{ $receita->nome }}
                            </div>
                        </td>
                        <td>
                            <span class="badge salgados">Salgados</span> <!-- ajuste conforme sua lógica -->
                        </td>
                        <td>{{ $receita->rendimento_lote }} unid.</td>
                        <td>R$ {{ number_format($ultimoCmv->cmv_unitario ?? 0, 2, ',', '.') }}</td>
                        <td>R$ {{ number_format($ultimoCmv->preco_sugerido ?? 0, 2, ',', '.') }}</td>
                        <td><span class="margin-val">{{ number_format($ultimoCmv->percentual_lucro ?? 0, 0) }}%</span></td>
                        <td>
                            <a href="{{ route('receitas.show', $receita) }}" class="action-btn view"><i
                                    class="ti ti-eye"></i></a>
                            @if (!Auth::user()->isVisualizador())
                                <a href="{{ route('receitas.edit', $receita) }}" class="action-btn edit"><i
                                        class="ti ti-pencil"></i></a>
                                <form action="{{ route('receitas.destroy', $receita) }}" method="POST"
                                    style="display:inline-block;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="action-btn del"
                                        onclick="return confirm('Tem certeza?')"><i class="ti ti-trash"></i></button>
                                </form>
                            @endif

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">Nenhuma receita cadastrada ainda.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="pagination">
            {{ $receitas->links() }}
        </div>
    </div>

    <script>
        // Função de filtro (apenas pelo nome da receita)
        function filter() {
            let search = document.getElementById('search').value.toLowerCase();
            let rows = document.querySelectorAll('#receitas-tbody tr');
            rows.forEach(row => {
                let name = row.cells[0].innerText.toLowerCase();
                let show = name.includes(search);
                row.style.display = show ? '' : 'none';
            });
        }

        // Eventos
        document.getElementById('filterBtn').addEventListener('click', filter);
        document.getElementById('search').addEventListener('keyup', filter);
        document.getElementById('clearFilters').addEventListener('click', function() {
            document.getElementById('search').value = '';
            filter();
        });
    </script>
@endsection
