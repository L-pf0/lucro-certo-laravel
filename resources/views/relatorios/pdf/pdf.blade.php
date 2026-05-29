<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Relatório de CMV - Casa do Salgado</title>
    <style>
        @page {
            margin: 25px;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            background: #fff;
            color: #1e1a2f;
        }

        .container {
            max-width: 100%;
            margin: 0 auto;
        }

        /* Cabeçalho */
        .header {
            background: linear-gradient(135deg, #2d1b4e 0%, #1e1a2f 100%);
            padding: 20px;
            border-radius: 12px 12px 0 0;
            margin-bottom: 25px;
            text-align: center;
        }

        .header h1 {
            color: #fbbf24;
            font-size: 28px;
            margin: 0;
            letter-spacing: 1px;
        }

        .header h1 span {
            color: #ffffff;
            font-weight: normal;
        }

        .header p {
            color: #e2d9f0;
            margin: 8px 0 0;
            font-size: 14px;
        }

        /* Informações */
        .info-box {
            background: #f8f5ff;
            border-left: 5px solid #7c3aed;
            padding: 12px 18px;
            margin-bottom: 25px;
            border-radius: 8px;
            font-size: 13px;
            color: #2d2a4a;
        }

        .info-box strong {
            color: #5b21b6;
        }

        /* Tabela */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 13px;
        }

        th {
            background: #4c1d95;
            color: #fbbf24;
            padding: 10px 8px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #6d28d9;
        }

        td {
            padding: 8px;
            border: 1px solid #ddd6fe;
            vertical-align: top;
        }

        tr:nth-child(even) {
            background-color: #faf5ff;
        }

        /* Rodapé */
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 11px;
            color: #6b21a5;
            border-top: 1px solid #e9d5ff;
            padding-top: 12px;
        }

        .footer small {
            color: #8b5cf6;
        }

        .badge {
            display: inline-block;
            background: #fbbf24;
            color: #4c1d95;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>CASA DO SALGADO</h1>
            <p><span class="badge">LucroCerto</span> | Relatório de Custo da Mercadoria Vendida (CMV)</p>
        </div>

        <div class="info-box">
            <p><strong>* Período analisado:</strong>
                {{ \Carbon\Carbon::createFromFormat('Y-m', $periodo)->format('m/Y') }}</p>
            <p><strong>* Usuário responsável:</strong> {{ $usuario }}</p>
            <p><strong>* Gerado em:</strong> {{ $data_geracao }}</p>
        </div>

        @if (count($dados) > 0)
            <table>
                <thead>
                    <tr>
                        <th>Receita</th>
                        <th>CMV Unitário</th>
                        <th>Preço Sugerido</th>
                        <th>Margem Contribuição</th>
                        <th>% Lucro</th>
                        <th>Data Cálculo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dados as $linha)
                        <tr>
                            <td>{{ $linha['Receita'] }}</td>
                            <td>{{ $linha['CMV Unitário'] }}</td>
                            <td>{{ $linha['Preço Sugerido'] }}</td>
                            <td>{{ $linha['Margem de Contribuição'] }}</td>
                            <td>{{ $linha['Percentual Lucro'] }}</td>
                            <td>{{ $linha['Data Cálculo'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="info-box" style="background: #fee2e2; border-left-color: #dc2626;">
                <p> Nenhum dado encontrado para o período selecionado.</p>
            </div>
        @endif

        <div class="footer">
            <p><strong>Casa do Salgado</strong> – Gestão de custos com precisão</p>
            <p><small>Relatório gerado automaticamente pelo sistema <strong>LucroCerto</strong> | ©
                    {{ date('Y') }}</small></p>
        </div>
    </div>
</body>

</html>
