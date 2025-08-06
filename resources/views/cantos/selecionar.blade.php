<x-app-layout>
    <div class="max-w-5xl mx-auto py-8">
        <form method="GET" action="{{ route('cantos.pdf') }}">
            <div class="mb-6 flex flex-col sm:flex-row items-center gap-4">
                <label for="tipo" class="font-semibold">Filtrar por tipo:</label>
                <select name="tipo" id="tipo" onchange="this.form.submit()" class="border border-blue-300 rounded px-4 py-2 focus:ring-2 focus:ring-blue-400">
                    <option value="">Todos</option>
                    @foreach ($tipos as $tipo)
                        <option value="{{ $tipo }}" {{ request('tipo') == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                    @endforeach
                </select>
            </div>
            <div class="overflow-x-auto mb-6">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 border-b">Selecionar</th>
                            <th class="px-4 py-2 border-b">Título</th>
                            <th class="px-4 py-2 border-b">Tipo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cantos as $canto)
                            <tr class="hover:bg-blue-50">
                                <td class="px-4 py-2 border-b text-center">
                                    <input type="checkbox" name="ids[]" value="{{ $canto->id }}" class="accent-blue-600">
                                </td>
                                <td class="px-4 py-2 border-b font-semibold text-blue-800">{{ $canto->titulo }}</td>
                                <td class="px-4 py-2 border-b text-gray-600">{{ $canto->tipo }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-6 text-center text-gray-500">Nenhum canto encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-bold rounded-lg shadow hover:bg-blue-700 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    Gerar PDF com Selecionados
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
