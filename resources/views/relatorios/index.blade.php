@extends('layouts.app')

@section('title', 'Relatórios | LucroCerto')

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
        }

        .badge.pdf {
            background: #fdf0f5;
            color: #ec4899;
        }

        .badge.csv {
            background: #edfaf4;
            color: #10b981;
        }

        .badge.dre {
            background: #f3f0ff;
            color: #7c3aed;
        }

        .action-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 6px;
            margin: 0 4px;
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

    <div class="page-title">Relatórios Gerados</div>
    <div class="page-sub">Consulte e faça download dos relatórios de análise de custos e DRE.</div>

    @if (session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-card">
        <div class="table-header">
            <h2 style="font-size:16px; font-weight:700;">Lista de relatórios</h2>
            <a href="{{ route('relatorios.create') }}" class="btn-primary"><i class="ti ti-plus"></i> Gerar novo relatório</a>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Período</th>
                        <th>Data de geração</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($relatorios as $relatorio)
                        <tr>
                            <td><span class="badge {{ $relatorio->tipo }}">{{ strtoupper($relatorio->tipo) }}</span></td>
                            <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $relatorio->periodo)->format('m/Y') }}</td>
                            <td>{{ $relatorio->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('relatorios.download', $relatorio) }}" class="action-btn" title="Baixar">
                                    <i class="ti ti-download"></i>
                                </a>
                                <form action="{{ route('relatorios.destroy', $relatorio) }}" method="POST"
                                    style="display:inline-block;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="action-btn del"
                                        onclick="return confirm('Remover este relatório?')"><i
                                            class="ti ti-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">Nenhum relatório gerado ainda. Clique em "Gerar novo relatório".</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination">{{ $relatorios->links() }}</div>
    </div>
@endsection
