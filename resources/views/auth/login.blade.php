{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - LucroCerto</title>
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

        .bg-login {
            background: linear-gradient(135deg, #130730 0%, #2c1557 50%, #6230a3 100%);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.2);
        }

        .input-glass {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
            color: white;
        }

        .input-glass:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: #fbbf24;
            outline: none;
            ring: 2px solid #fbbf24;
        }

        .input-glass::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .btn-login {
            background: linear-gradient(90deg, #fbbf24, #f59e0b);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.3);
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

        .animate-pulse-slow {
            animation: pulseSlow 4s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulseSlow {

            0%,
            100% {
                opacity: 0.4;
            }

            50% {
                opacity: 0.8;
            }
        }
    </style>
</head>

<body class="bg-login min-h-screen flex items-center justify-center relative overflow-hidden">

    <!-- Elementos decorativos flutuantes -->
    <div
        class="absolute top-20 left-10 w-64 h-64 bg-yellow-400 rounded-full mix-blend-overlay filter blur-3xl opacity-20 animate-float">
    </div>
    <div class="absolute bottom-20 right-10 w-80 h-80 bg-violet-600 rounded-full mix-blend-overlay filter blur-3xl opacity-20 animate-float"
        style="animation-delay: 2s;"></div>
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-md">
        <!-- Card Glassmorphism -->
        <div class="glass-card rounded-2xl p-8 md:p-10 mx-4 transition-all duration-300">
            <div class="text-center mb-8">
                <div class="flex justify-center mb-4">
                    <div class="bg-yellow-400/20 p-3 rounded-full inline-flex">
                        <i class="fas fa-chart-line text-4xl text-yellow-400"></i>
                    </div>
                </div>
                <h2 class="text-3xl font-bold text-white">Lucro<span class="text-yellow-400">Certo</span></h2>
                <p class="text-indigo-200 mt-2 text-sm">Gestão inteligente de custos</p>
            </div>

            @if ($errors->any())
                <div
                    class="bg-red-500/20 border border-red-400/50 text-red-200 px-4 py-3 rounded-xl mb-6 text-sm flex items-center gap-2">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-5">
                    <label class="block text-gray-200 text-sm font-medium mb-2">
                        <i class="fas fa-envelope mr-2 text-yellow-400"></i>E-mail
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="input-glass w-full px-4 py-3 rounded-xl focus:ring-2 focus:ring-yellow-400"
                        placeholder="seu@email.com" required autofocus>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-200 text-sm font-medium mb-2">
                        <i class="fas fa-lock mr-2 text-yellow-400"></i>Senha
                    </label>
                    <input type="password" name="password"
                        class="input-glass w-full px-4 py-3 rounded-xl focus:ring-2 focus:ring-yellow-400"
                        placeholder="••••••••" required>
                </div>
               
                <button type="submit"
                    class="btn-login w-full text-gray-900 font-bold py-3 rounded-xl flex items-center justify-center gap-2 text-lg shadow-lg">
                    <i class="fas fa-sign-in-alt"></i> Entrar
                </button>
            </form>

        
        </div>
        <div class="text-center text-indigo-300/60 text-xs mt-6">
            © 2026 LucroCerto · Casa do Salgado
        </div>
    </div>
</body>

</html>
