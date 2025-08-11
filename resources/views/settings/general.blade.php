{{-- resources/views/settings/general.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                Configurações Gerais
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-slate-950 min-h-screen transition-colors duration-300">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="p-6 sm:p-8 bg-white/90 dark:bg-slate-900/90 border border-gray-100 dark:border-slate-800 shadow-sm sm:rounded-2xl transition-all duration-300 backdrop-blur">
                <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-100">Configurações do sistema</h3>
                <p class="text-gray-600 dark:text-gray-300 mb-4">Ajuste preferências gerais do sistema.</p>
                <div class="text-gray-400 italic">Mais opções em breve.</div>
            </div>

            {{-- Tipos de músicas (CantoTipo) --}}
            <div class="p-6 sm:p-8 bg-white/90 dark:bg-slate-900/90 border border-gray-100 dark:border-slate-800 shadow-sm sm:rounded-2xl transition-all duration-300 backdrop-blur">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Tipos de músicas</h3>
                </div>

                {{-- Create --}}
                <form action="{{ route('settings.tipos.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-6">
                    @csrf
                    <div class="md:col-span-3">
                        <label for="nome" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome</label>
                        <input id="nome" name="nome" type="text" required class="mt-1 w-full rounded-md border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ex.: Entrada, Ofertório, Comunhão">
                        @error('nome')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="ord" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ordem</label>
                        <input id="ord" name="ord" type="number" min="0" step="1" class="mt-1 w-full rounded-md border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 focus:border-indigo-500 focus:ring-indigo-500" placeholder="0">
                        @error('ord')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-1 flex items-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Adicionar</button>
                    </div>
                </form>

                {{-- List and manage --}}
                <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-slate-800">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-800">
                        <thead class="bg-gray-50 dark:bg-slate-800/60">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ordem</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nome</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white/60 dark:bg-slate-900/60 divide-y divide-gray-200 dark:divide-slate-800">
                            @forelse (($tipos ?? []) as $tipo)
                                <tr>
                                    <td class="px-4 py-2 align-middle w-24">
                                        <form action="{{ route('settings.tipos.update', $tipo) }}" method="POST" class="flex gap-2 items-center">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number" name="ord" value="{{ $tipo->ord }}" min="0" step="1" class="w-24 rounded-md border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 focus:border-indigo-500 focus:ring-indigo-500" />
                                            <input type="hidden" name="nome" value="{{ $tipo->nome }}" />
                                            <button type="submit" class="text-xs px-2 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700">Salvar</button>
                                        </form>
                                    </td>
                                    <td class="px-4 py-2">
                                        <form action="{{ route('settings.tipos.update', $tipo) }}" method="POST" class="flex gap-2 items-center">
                                            @csrf
                                            @method('PATCH')
                                            <input type="text" name="nome" value="{{ $tipo->nome }}" class="flex-1 rounded-md border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 focus:border-indigo-500 focus:ring-indigo-500" />
                                            <input type="hidden" name="ord" value="{{ $tipo->ord }}" />
                                            <button type="submit" class="text-xs px-2 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700">Salvar</button>
                                        </form>
                                    </td>
                                    <td class="px-4 py-2 text-right">
                                        <form action="{{ route('settings.tipos.destroy', $tipo) }}" method="POST" onsubmit="return confirm('Remover o tipo &quot;{{ $tipo->nome }}&quot;?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">Excluir</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">Nenhum tipo cadastrado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if (session('status'))
                    <div class="mt-4 text-sm text-green-700 bg-green-100/60 dark:text-green-300 dark:bg-green-900/30 rounded px-3 py-2">
                        {{ session('status') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
