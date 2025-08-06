<x-app-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-100 to-indigo-200">
        <div class="bg-white shadow-2xl rounded-2xl px-10 py-8 w-full max-w-md space-y-6 text-center">
            <h1 class="text-3xl font-extrabold text-indigo-700">Bem-vindo ao Painel</h1>

            <p class="text-gray-600">{{ __("Você está logado com sucesso!") }}</p>

            <a href="{{ route('cantos.index') }}"
               class="inline-block mt-4 px-6 py-3 bg-indigo-600 text-white font-semibold text-lg rounded-lg shadow-md hover:bg-indigo-700 transition">
                🎵 Acessar Cantos
            </a>
        </div>
    </div>
</x-app-layout>
