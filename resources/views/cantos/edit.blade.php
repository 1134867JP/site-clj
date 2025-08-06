<x-app-layout>
    <div class="max-w-xl mx-auto py-8">
        <form method="POST" action="{{ route('cantos.update', $canto) }}" class="bg-white shadow rounded-lg p-8 space-y-6">
            @csrf
            @method('PUT')
            <div>
                <label class="block font-semibold mb-1">Título</label>
                <input type="text" name="titulo" value="{{ old('titulo', $canto->titulo) }}" class="w-full border rounded px-3 py-2" required>
            </div>
            @php
                $tipos = ['Entrada', 'Ato Penitencial', 'Glória', 'Ofertório', 'Santo', 'Cordeiro', 'Comunhão', 'Final', 'Abraço da Paz', 'Pai Nosso'];
            @endphp
            <div>
                <label class="block font-semibold mb-1">Tipo</label>
                <select name="tipo" class="w-full border rounded px-3 py-2" required>
                    <option value="">Selecione o tipo</option>
                    @foreach($tipos as $tipo)
                        <option value="{{ $tipo }}" {{ old('tipo', $canto->tipo ?? '') == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-semibold mb-1">Letra</label>
                <textarea name="letra" rows="6" class="w-full border rounded px-3 py-2" required>{{ old('letra', $canto->letra) }}</textarea>
            </div>
            <div class="flex justify-end gap-2">
                <a href="{{ route('cantos.index') }}" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Cancelar</a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Atualizar</button>
            </div>
        </form>
    </div>
</x-app-layout>
