@extends('layouts.app')

@section('title', 'Insumos | LucroCerto')

@section('content')
    <style>
        /* Estilos específicos da página de insumos (baseado no design fornecido) */
        .page-title {
            font-size: 26px;
            font-weight: 700;
            color: #1a1a2e;
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
            margin-bottom: 24px;
        }

        .table-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .table-header h2 {
            font-size: 16px;
            font-weight: 700;
            color: #1a1a2e;
        }

        .search-inp {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f4f5f7;
            border: 1.5px solid #e2e6f0;
            border-radius: 9px;
            padding: 0 12px;
            height: 38px;
        }

        .search-inp input {
            border: none;
            background: transparent;
            outline: none;
            font-size: 14px;
            width: 200px;
        }

        .btn-primary {
            background: #7c3aed;
            color: #fff;
            border: none;
            padding: 0 18px;
            height: 38px;
            border-radius: 9px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            font-size: 14px;
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

        .food-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: #f3f0ff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
        }

        .td-name {
            display: flex;
            align-items: center;
        }

        .action-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 6px;
            border-radius: 6px;
            margin: 0 2px;
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

    <div class="page-title">Insumos</div>
    <div class="page-sub">Cadastre e gerencie todos os insumos utilizados nas suas receitas.</div>

    @if (session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-card">
        <div class="table-header">
            <h2>Lista de insumos cadastrados</h2>
            <div style="display: flex; gap: 12px;">
                <div class="search-inp">
                    <i class="ti ti-search"></i>
                    <input type="text" id="search" placeholder="Buscar insumo...">
                </div>
                @if (!Auth::user()->isVisualizador())
                    <a href="{{ route('insumos.create') }}" class="btn-primary">
                        <i class="ti ti-plus"></i> Novo insumo
                    </a>
                @endif

            </div>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Nome do insumo</th>
                        <th>Unidade</th>
                        <th>Preço unitário (R$)</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($insumos as $insumo)
                        <tr>
                            <td>
                                <div class="td-name">
                                    <span class="food-icon">🌾</span>
                                    {{ $insumo->nome }}
                                </div>
                            </td>
                            <td>{{ $insumo->unidade_medida }}</td>
                            <td>R$ {{ number_format($insumo->preco_unitario, 2, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('insumos.show', $insumo) }}" class="action-btn view" title="Ver">
                                    <i class="ti ti-eye"></i>
                                </a>
                                @if (!Auth::user()->isVisualizador())
                                    <a href="{{ route('insumos.edit', $insumo) }}" class="action-btn edit" title="Editar">
                                        <i class="ti ti-pencil"></i>
                                    </a>
                                    <form action="{{ route('insumos.destroy', $insumo) }}" method="POST"
                                        style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn del" title="Excluir"
                                            onclick="return confirm('Tem certeza que deseja excluir?')">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </form>
                                @endif


                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">Nenhum insumo cadastrado ainda.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">
            <span class="pag-info">Mostrando {{ $insumos->firstItem() }} a {{ $insumos->lastItem() }} de
                {{ $insumos->total() }} resultados</span>
            <div class="pag-btns">
                {{ $insumos->links() }}
            </div>
        </div>
    </div>
@endsection
