<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Gerencie seus cantos litúrgicos com o CifraDocs.">

    <title>CifraDocs</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="relative min-h-screen bg-cover bg-center text-white" style="background-image: url('https://images.unsplash.com/photo-1513883049090-d0b7439799bf?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');">

    <!-- Overlay escuro -->
    <div class="absolute inset-0 bg-black bg-opacity-60"></div>

    <main class="relative z-10 flex flex-col items-center justify-center w-full min-h-screen p-6 lg:p-20">
        <div class="bg-white bg-opacity-10 backdrop-blur-lg border border-white/10 shadow-2xl rounded-xl p-10 w-full max-w-md text-center text-white">
            <h1 class="text-3xl font-bold mb-4">Bem-vindo ao CifraDocs</h1>
            <p class="mb-6 text-gray-200">Gerencie seus cantos litúrgicos com facilidade e organização.</p>

            <div class="flex justify-center gap-4">
                <a href="{{ route('login') }}" class="px-6 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition">
                    Login
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="px-6 py-2 bg-white bg-opacity-20 text-white font-semibold rounded-lg shadow-md hover:bg-opacity-40 transition">
                        Registrar
                    </a>
                @endif
            </div>
        </div>
    </main>

</body>
</html>
