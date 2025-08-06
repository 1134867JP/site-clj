<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cantos') }}
        </h2>
    </x-slot>

    <div class="max-w-5xl mx-auto py-8">
        <!-- Filtros estilo Cifra Club -->
        <div class="flex flex-wrap gap-2 mb-8">
            <a href="?" class="px-4 py-2 rounded-full border font-semibold transition
                {{ !request('tipo') ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-blue-700 border-blue-200 hover:bg-blue-100' }}">
                Todos
            </a>
            @foreach ($tipos as $tipo)
                <a href="?tipo={{ urlencode($tipo) }}" class="px-4 py-2 rounded-full border font-semibold transition
                    {{ request('tipo') == $tipo ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-blue-700 border-blue-200 hover:bg-blue-100' }}">
                    {{ $tipo }}
                </a>
            @endforeach
        </div>

        <!-- Botão para criar novo canto -->
        <div class="flex justify-end mb-4">
            <a href="{{ route('cantos.create') }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 font-semibold shadow">+ Adicionar Canto</a>
        </div>

        <!-- Lista estilo ranking -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h1 class="text-2xl font-bold mb-6 text-center">Mais acessados</h1>
            <div class="divide-y divide-blue-100">
                @forelse ($cantos as $index => $canto)
                    <div class="flex items-center py-4 gap-4 {{ $index == 0 ? 'bg-blue-50 rounded-lg shadow-md' : '' }}">
                        <div class="text-2xl font-extrabold text-blue-400 w-10 text-center">{{ $index + 1 }}</div>
                        <div class="flex-1">
                            <div class="text-lg font-semibold text-blue-800">{{ $canto->titulo }}</div>
                            <div class="text-sm text-gray-500">{{ $canto->tipo }}</div>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('cantos.show', $canto) }}" class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 text-xs">Ver</a>
                            <a href="{{ route('cantos.edit', $canto) }}" class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200 text-xs">Editar</a>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-gray-500">Nenhum canto encontrado.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
