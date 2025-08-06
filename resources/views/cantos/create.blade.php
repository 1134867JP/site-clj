<x-app-layout>
    @php
        $tipos = ['Entrada', 'Ato Penitencial', 'Glória', 'Ofertório', 'Santo', 'Cordeiro', 'Comunhão', 'Final', 'Abraço da Paz', 'Pai Nosso'];
    @endphp

    <div class="max-w-xl mx-auto py-8">
        <form method="POST" action="{{ route('cantos.store') }}" class="bg-white shadow rounded-lg p-8 space-y-6">
            @csrf
            <div>
                <label class="block font-semibold mb-1">Título</label>
                <input type="text" name="titulo" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block font-semibold mb-1">Tipo</label>
                <select name="tipo" class="w-full border rounded px-3 py-2" required>
                    <option value="">Selecione o tipo</option>
                    @foreach($tipos as $tipo)
                        <option value="{{ $tipo }}">{{ $tipo }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-semibold mb-1">Letra</label>
                <div class="relative">
                    <textarea name="letra" id="letra" rows="12" class="w-full border rounded px-3 py-2 font-mono resize-y min-h-[200px] focus:ring-2 focus:ring-blue-400" style="tab-size: 4;" required></textarea>
                    <div class="absolute right-2 top-2 flex flex-col gap-1">
                        <button type="button" onclick="document.getElementById('letra').rows += 2" class="bg-gray-200 rounded px-2 text-xs mb-1 hover:bg-gray-300">+</button>
                        <button type="button" onclick="if(document.getElementById('letra').rows > 4) document.getElementById('letra').rows -= 2" class="bg-gray-200 rounded px-2 text-xs hover:bg-gray-300">-</button>
                    </div>
                </div>
                <small class="text-gray-500">Dica: Use TAB para alinhar cifras e letras, como no Cifra Club.</small>
            </div>
            <div class="flex justify-end gap-2">
                <a href="{{ route('cantos.index') }}" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Cancelar</a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Salvar</button>
            </div>
        </form>
    </div>
</x-app-layout>
