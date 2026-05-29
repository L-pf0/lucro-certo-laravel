{{-- resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LucroCerto - Gestão de Custos e Precificação</title>
    <!-- Tailwind CSS + Font Awesome + Google Fonts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,600;14..32,700&display=swap"
        rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        .bg-gradient-main {
            background: linear-gradient(135deg, #130730 0%, #2c1557 50%, #6230a3 100%);
        }

        .bg-glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .hover-lift {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -12px rgba(0, 0, 0, 0.2);
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-15px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        .fade-up {
            opacity: 0;
            transform: translateY(30px);
            animation: fadeUp 0.8s forwards;
        }

        @keyframes fadeUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .delay-1 {
            animation-delay: 0.2s;
        }

        .delay-2 {
            animation-delay: 0.4s;
        }

        .delay-3 {
            animation-delay: 0.6s;
        }
    </style>
</head>

<body class="bg-white text-gray-800">

    <!-- HERO SECTION -->
    <div class="bg-gradient-main text-white overflow-hidden relative">
        <!-- elemento decorativo flutuante -->
        <div
            class="absolute top-20 right-10 w-64 h-64 bg-yellow-300 rounded-full mix-blend-overlay filter blur-3xl opacity-20 animate-float">
        </div>
        <div class="absolute bottom-10 left-10 w-80 h-80 bg-violet-600 rounded-full mix-blend-overlay filter blur-3xl opacity-20 animate-float"
            style="animation-delay: 2s;"></div>

        <div class="container mx-auto px-6 py-12 md:py-20 relative z-10">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-12">
                <div class="flex-1 text-center lg:text-left fade-up">
                    <span
                        class="inline-block px-4 py-1 rounded-full bg-white/20 text-yellow-200 text-sm font-semibold backdrop-blur-sm mb-4">
                        <i class="fas fa-chart-line mr-2"></i> Controle total do seu negócio
                    </span>
                    <h1 class="text-4xl md:text-6xl font-extrabold leading-tight mb-4">
                        Lucro<span class="text-yellow-300">Certo</span>
                    </h1>
                    <p class="text-lg md:text-xl text-indigo-100 mb-8 max-w-2xl mx-auto lg:mx-0">
                        A ferramenta inteligente para calcular custos, precificar receitas e maximizar sua margem de
                        lucro.
                        Chega de chutar preços – tenha dados reais na palma da mão.
                    </p>
                    <div class="flex flex-wrap gap-4 justify-center lg:justify-start">
                        <a href="{{ route('login') }}"
                            class="bg-yellow-400 hover:bg-yellow-500 text-azulEscuro transition px-8 py-3 rounded-full font-bold text-lg shadow-lg hover-lift">
                            <i class="fas fa-sign-in-alt mr-2"></i> Acessar Sistema
                        </a>
                        <a href="#sobre"
                            class="bg-white/20 hover:bg-white/30 backdrop-blur-sm border border-white/30 transition px-8 py-3 rounded-full font-semibold text-white text-lg">
                            <i class="fas fa-play-circle mr-2"></i> Saiba Mais
                        </a>
                    </div>
                </div>
                <div class="flex-1 flex justify-center fade-up delay-1">
                    <div
                        class="relative w-80 h-80 md:w-96 md:h-96 bg-white/5 rounded-full flex items-center justify-center animate-float">
                        <div
                            class="absolute inset-0 bg-gradient-to-tr from-yellow-400/20 to-violet-500/20 rounded-full blur-2xl">
                        </div>
                        <i class="fas fa-chart-pie text-8xl text-yellow-300 drop-shadow-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
        <!-- onda decorativa -->
        <svg class="w-full text-white" viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M0 64L60 69.3C120 75 240 85 360 80C480 75 600 53 720 48C840 43 960 53 1080 58.7C1200 64 1320 64 1380 64L1440 64V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0V64Z"
                fill="currentColor" />
        </svg>
    </div>

    <!-- SEÇÃO SOBRE O SISTEMA -->
    <div id="sobre" class="py-20 bg-cream" style="background-color: #FEF9E6;">
        <div class="container mx-auto px-6">
            <div class="text-center max-w-3xl mx-auto fade-up">
                <span class="text-violet-600 font-semibold tracking-wide">✧ Por que usar o LucroCerto?</span>
                <h2 class="text-3xl md:text-4xl font-bold text-azulEscuro mt-2 mb-5" style="color: #1E1A5E;">Gestão de
                    custos que <span class="text-yellow-500">realmente funciona</span></h2>
                <p class="text-gray-600 text-lg mb-12">Quem empreende sabe: precificar errado é o maior vilão do lucro.
                    Nosso sistema ajuda você a enxergar todos os custos (insumos, mão de obra, fixos e variáveis) e a
                    definir preços justos e competitivos.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 mt-8">
                <div class="bg-white rounded-2xl p-8 shadow-md hover-lift border-l-8 border-violet-500 fade-up delay-1">
                    <div class="w-16 h-16 bg-violet-100 rounded-full flex items-center justify-center mb-5">
                        <i class="fas fa-calculator text-2xl text-violet-700"></i>
                    </div>
                    <h3 class="text-xl font-bold text-azulEscuro mb-3">CMV Preciso</h3>
                    <p class="text-gray-500">Calcule automaticamente o Custo da Mercadoria Vendida e descubra o valor
                        real de cada receita.</p>
                </div>
                <div class="bg-white rounded-2xl p-8 shadow-md hover-lift border-l-8 border-yellow-400 fade-up delay-2">
                    <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mb-5">
                        <i class="fas fa-chart-line text-2xl text-yellow-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-azulEscuro mb-3">Simulação de Cenários</h3>
                    <p class="text-gray-500">Veja o impacto de alterações no preço dos insumos ou na margem de lucro
                        antes de tomar decisões.</p>
                </div>
                <div class="bg-white rounded-2xl p-8 shadow-md hover-lift border-l-8 border-azulEscuro fade-up delay-3"
                    style="border-left-color: #1E1A5E;">
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mb-5">
                        <i class="fas fa-file-alt text-2xl text-indigo-700"></i>
                    </div>
                    <h3 class="text-xl font-bold text-azulEscuro mb-3">Relatórios Gerenciais</h3>
                    <p class="text-gray-500">Gere relatórios em PDF/CSV, DRE e acompanhe a evolução do seu negócio mês a
                        mês.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- SEÇÃO DESTAQUES COM CORES ROXO/VIOLETA -->
    <div class="py-20 bg-gradient-to-br from-[#2E1A5E] to-[#4C2A8A] text-white">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="fade-up">
                    <span class="text-yellow-300 text-sm uppercase tracking-wider">Desenvolvido para - Casa do Salgado</span>
                    <h2 class="text-3xl md:text-4xl font-bold mt-2 mb-4">Controle total sobre seus custos</h2>
                    <p class="text-indigo-100 mb-6">Com o LucroCerto você cadastra seus insumos, cria receitas
                        detalhadas, define custos fixos e variáveis e obtém automaticamente o preço ideal para maximizar
                        o lucro sem perder competitividade.</p>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-3"><i class="fas fa-check-circle text-yellow-400"></i>
                            <span>Dashboard com indicadores-chave</span></li>
                        <li class="flex items-center gap-3"><i class="fas fa-check-circle text-yellow-400"></i>
                            <span>Margem de Lucro sobre seus produtos</span></li>
                        <li class="flex items-center gap-3"><i class="fas fa-check-circle text-yellow-400"></i>
                            <span>Relatórios do seu negócio</span></li>
                    
                    </ul>
                    <div class="mt-8">
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center gap-2 bg-yellow-400 text-azulEscuro hover:bg-yellow-300 transition px-6 py-3 rounded-full font-semibold shadow-lg">
                            Entrar Agora <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <div class="relative flex justify-center fade-up delay-1">
                    <div class="bg-white/10 rounded-2xl p-2 backdrop-blur-sm w-full max-w-md">
                        <img src="{{ asset('images/dashboard-print.png') }}" alt="Preview do Dashboard" class="rounded-xl shadow-2xl">
                        <!-- você pode trocar por uma imagem real do dashboard -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SEÇÃO CTA COM CORES CREME E AMARELO -->
    <div class="py-20" style="background: #FFF6E5;">
        <div class="container mx-auto px-6 text-center">
            <div class="max-w-3xl mx-auto fade-up">
                <i class="fas fa-chart-simple text-5xl text-yellow-500 mb-4"></i>
                <h2 class="text-3xl md:text-4xl font-bold text-azulEscuro mb-4">Desenvolvido para - Casa do Salgado
                </h2>
                <p class="text-gray-600 text-lg mb-8">Sistema desenvolvido para controle de gestão da Casa do Salgado...</p>
                <div class="flex flex-wrap justify-center gap-4">
                
                </div>
                
            </div>
        </div>
    </div>

    <!-- RODAPÉ (FOOTER) -->
    <footer class="bg-[#1A1A2E] text-white py-8">
        <div class="container mx-auto px-6 text-center">
            <div class="flex flex-wrap justify-center gap-6 mb-4">
                <a href="#" class="text-gray-300 hover:text-yellow-400 transition">Sobre</a>
                <a href="#" class="text-gray-300 hover:text-yellow-400 transition">Entre como Gestora</a>
                <a href="#" class="text-gray-300 hover:text-yellow-400 transition">Bem-vindo</a>
                <a href="#" class="text-gray-300 hover:text-yellow-400 transition">Navegue</a>
            </div>
            <p class="text-gray-400 text-sm">© 2026 LucroCerto - Gestão Inteligente de Custos. Desenvolvimento para Casa do Salgado.</p>
            
        </div>
    </footer>
</body>

</html>
