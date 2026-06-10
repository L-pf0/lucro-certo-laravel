# LucroCerto 📊

Sistema web de **precificação e gestão de custos** desenvolvido para a Casa do Salgado — uma pequena empresa do setor alimentício em Minas Gerais.

Projeto Integrador Interdisciplinar 1 — CST em Análise e Desenvolvimento de Sistemas · SENAI Ribeirão Preto · 2026/1

---

## Sobre o projeto

O LucroCerto centraliza o controle financeiro da produção em um único sistema, eliminando planilhas manuais e precificação empírica. A ferramenta calcula automaticamente o CMV (Custo de Mercadoria Vendida) de cada receita e sugere preços de venda com base na margem de lucro desejada.

---

## Funcionalidades

- Cadastro de insumos com preços e unidades de medida
- Cadastro de receitas com composição e rendimento por lote
- Registro de custos fixos, variáveis e mão de obra direta
- Cálculo automático do CMV por receita
- Sugestão de preço de venda com margem parametrizável
- Simulação de impacto de variação de preços de insumos
- Relatórios de rentabilidade exportáveis em PDF
- Dashboard com indicadores financeiros (CMV médio, margem, ponto de equilíbrio)
- DRE simplificado mensal
- Controle de acesso por perfil (Gestora / Visualizador)

---

## Stack

| Camada | Tecnologia |
|---|---|
| Backend | PHP · Laravel |
| Frontend | HTML · CSS · JavaScript · Bootstrap |
| Banco de Dados | MySQL · MySQL Workbench |
| Ambiente local | Laravel Herd |
| Versionamento | Git · GitHub |

---

## Como rodar localmente

```bash
# Clone o repositório
git clone https://github.com/L-pf0/lucro-certo-laravel.git
cd lucro-certo-laravel

# Instale as dependências
composer install
npm install

# Configure o ambiente
cp .env.example .env
php artisan key:generate

# Configure o banco de dados no .env e rode as migrations
php artisan migrate --seed

# Inicie o servidor (via Herd ou artisan)
php artisan serve
```

Acesse em `http://localhost:8000`

---

## Equipe

| Nome | GitHub |
|---|---|
| Breno Garcia de Deus Oliveira | — |
| Henrique Cabral Correia | — |
| Luan Verissimo Beltramini | — |
| Luísa Paes Franzoni | [@L-pf0](https://github.com/L-pf0) |

**Docente:** Prof. MSc. Gustavo Martins Nunes Avellar
