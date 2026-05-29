<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Demonstrativo de Resultados - Casa do Salgado</title>
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

        .badge {
            display: inline-block;
            background: #fbbf24;
            color: #4c1d95;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
        }

        /* Tabela DRE */
        .dre-table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
            font-size: 14px;
        }

        .dre-table th {
            background: #4c1d95;
            color: #fbbf24;
            padding: 10px;
            text-align: left;
            border: 1px solid #6d28d9;
        }

        .dre-table td {
            padding: 10px;
            border: 1px solid #ddd6fe;
        }

        .totals {
            font-weight: bold;
            background-color: #f3e8ff;
        }

        .positive {
            color: #10b981;
            font-weight: bold;
        }

        .negative {
            color: #dc2626;
            font-weight: bold;
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
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>CASA DO SALGADO</h1>
            <p><span class="badge">LucroCerto</span> | Demonstrativo de Resultados (DRE)</p>
        </div>

        <div class="info-box">
            <p><strong>* Período analisado:</strong>
                {{ \Carbon\Carbon::createFromFormat('Y-m', $periodo)->format('m/Y') }}</p>
            <p><strong>* Usuário responsável:</strong> {{ $usuario }}</p>
            <p><strong>* Gerado em:</strong> {{ $data_geracao }}</p>
        </div>

        <table class="dre-table">
            <thead>
                <tr>
                    <th colspan="2">DEMONSTRAÇÃO DE RESULTADOS</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1. Receita Bruta</td>
                    <td style="text-align: right;">R$ {{ number_format($dre['receitas_brutas'], 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>2. Custos Variáveis (CMV + Mão de Obra)</td>
                    <td style="text-align: right;">(R$ {{ number_format($dre['custos_variaveis'], 2, ',', '.') }})</td>
                </tr>
                <tr class="totals">
                    <td style="font-weight: bold;">(=) Lucro Bruto</td>
                    <td style="text-align: right; font-weight: bold;">R$
                        {{ number_format($dre['receitas_brutas'] - $dre['custos_variaveis'], 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>3. Despesas Fixas</td>
                    <td style="text-align: right;">(R$ {{ number_format($dre['despesas_fixas'], 2, ',', '.') }})</td>
                </tr>
                <tr>
                    <td>4. Despesas Variáveis</td>
                    <td style="text-align: right;">(R$ {{ number_format($dre['despesas_variaveis'], 2, ',', '.') }})
                    </td>
                </tr>
                <tr class="totals">
                    <td style="font-weight: bold;">(=) Lucro Líquido</td>
                    <td style="text-align: right; font-weight: bold;"
                        class="{{ $dre['lucro_liquido'] >= 0 ? 'positive' : 'negative' }}">
                        R$ {{ number_format($dre['lucro_liquido'], 2, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <p><strong>Casa do Salgado</strong> – Transparência e controle financeiro</p>
            <p><small>Demonstrativo gerado pelo sistema <strong>LucroCerto</strong> | © {{ date('Y') }}</small></p>
        </div>
    </div>
</body>

</html>
