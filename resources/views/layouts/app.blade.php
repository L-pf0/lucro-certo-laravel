<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LucroCerto')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
   
   <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0
        }

        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: #f4f5f7;
            color: #1a1a2e;
            display: flex;
            height: 100vh;
            overflow: hidden
        }

        .sidebar {
            width: 210px;
            min-width: 210px;
            background: #1a1a2e;
            display: flex;
            flex-direction: column;
            padding: 0;
            height: 100vh
        }

        .logo {
            padding: 20px 20px 16px;
            display: flex;
            align-items: center;
            gap: 8px
        }

        .logo-icon {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #7c3aed, #a855f7);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center
        }

        .logo-icon svg {
            width: 18px;
            height: 18px;
            fill: none;
            stroke: #fff;
            stroke-width: 2.5;
            stroke-linecap: round;
            stroke-linejoin: round
        }

        .logo-text {
            font-size: 18px;
            font-weight: 700;
            color: #a855f7
        }

        .logo-text span {
            color: #fff
        }

        .nav {
            flex: 1;
            padding: 8px 12px
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 8px;
            cursor: pointer;
            color: #8b8fa8;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 2px;
            transition: all .15s;
            text-decoration: none;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, .07);
            color: #fff
        }

        .nav-item.active {
            background: #7c3aed;
            color: #fff
        }

        .nav-item i {
            font-size: 18px;
            width: 20px;
            text-align: center
        }

        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid rgba(255, 255, 255, .08)
        }

        .sidebar-mascot {
            text-align: center;
            margin-bottom: 12px
        }

        .mascot-icon {
            font-size: 32px
        }

        .sidebar-tagline {
            font-size: 11px;
            color: #6b6f85;
            text-align: center;
            line-height: 1.4
        }

        .nav-item.sair {
            color: #6b6f85;
            margin-top: 8px;
            background: transparent;
            border: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
        }

        .main {
            flex: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 28px 0;
            background: #f4f5f7
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 16px
        }

        .notif-btn {
            position: relative;
            background: #fff;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .1)
        }

        .notif-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: #7c3aed;
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center
        }

        .user-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #fff;
            border: none;
            padding: 6px 12px 6px 6px;
            border-radius: 10px;
            cursor: pointer;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .1)
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #7c3aed;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 13px;
            font-weight: 600
        }

        .user-name {
            font-size: 13px;
            font-weight: 600;
            color: #1a1a2e
        }

        .user-role {
            font-size: 11px;
            color: #8b8fa8
        }

        .content {
            padding: 24px 28px;
            flex: 1
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="logo">
            <div class="logo-icon">
                <svg viewBox="0 0 24 24">
                    <polyline points="3 17 7 9 11 13 15 7 19 12" />
                </svg>
            </div>
            <div class="logo-text"><span>Lucro</span>Certo</div>
        </div>
        <nav class="nav">
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="ti ti-home"></i> Dashboard
            </a>
            <a href="{{ route('insumos.index') }}"
                class="nav-item {{ request()->routeIs('insumos.*') ? 'active' : '' }}">
                <i class="ti ti-shopping-cart"></i> Insumos
            </a>
            <a href="{{ route('receitas.index') }}"
                class="nav-item {{ request()->routeIs('receitas.*') ? 'active' : '' }}">
                <i class="ti ti-clipboard-list"></i> Receitas
            </a>
            <div style="display: flex; flex-direction: column;">
                <a href="{{ route('custos-fixos.index') }}"
                    class="nav-item {{ request()->routeIs('custos-fixos.*') || request()->routeIs('custos-variaveis.*') ? 'active' : '' }}">
                    <i class="ti ti-currency-dollar"></i> Custos
                </a>
                <a href="{{ route('custos-fixos.index') }}" class="nav-item"
                    style="padding-left: 32px; font-size: 12px;"> Fixos</a>
                <a href="{{ route('custos-variaveis.index') }}" class="nav-item"
                    style="padding-left: 32px; font-size: 12px;"> Variáveis</a>
                <a href="{{ route('mao-de-obra.index') }}" class="nav-item"
                    style="padding-left: 32px; font-size: 12px;"> Mão de obra</a>
            </div>
            <a href="{{ route('cmv.index') }}" class="nav-item {{ request()->routeIs('cmv.*') ? 'active' : '' }}">
                <i class="ti ti-calculator"></i> Cálculos
            </a>
            <a href="{{ route('relatorios.index') }}"
                class="nav-item {{ request()->routeIs('relatorios.*') ? 'active' : '' }}">
                <i class="ti ti-chart-bar"></i> Relatórios
            </a>
            <a href="{{ route('simulacoes.index') }}"
                class="nav-item {{ request()->routeIs('simulacoes.*') ? 'active' : '' }}">
                <i class="ti ti-trending-up"></i> Simulações
            </a>
            <form action="{{ route('logout') }}" method="POST" style="margin:0">
                @csrf
                <button type="submit" class="nav-item sair"><i class="ti ti-logout"></i> Sair</button>
            </form>
        </nav>
        <div class="sidebar-footer">
            <div class="sidebar-mascot"><span class="mascot-icon">🧁</span></div>
            <div class="sidebar-tagline">Controle seus custos,<br>precifique com segurança<br>e aumente seus lucros.
            </div>
        </div>
    </div>

    <div class="main">
        <div class="topbar">
            <div></div>
            <div class="topbar-right">

                <div class="user-btn">
                    <div class="user-avatar">{{ substr(Auth::user()->name ?? 'U', 0, 1) }}</div>
                    <div>
                        <div class="user-name">{{ Auth::user()->name ?? 'Usuário' }}</div>
                        <div class="user-role">
                            {{ Auth::user()->role == 'gestor' ? 'Administradora' : 'Visualizadora' }}</div>
                    </div>

                </div>
            </div>
        </div>
        <div class="content">
            @yield('content')
        </div>
    </div>
</body>

</html>
