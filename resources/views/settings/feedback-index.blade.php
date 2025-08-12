<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                Feedbacks do site
            </h2>
            <form method="GET" class="flex items-center gap-2">
                <input type="text" name="q" value="{{ $q }}" placeholder="Buscar..."
                       class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2" />
                <button class="px-3 py-2 rounded-xl bg-indigo-600 text-white">Filtrar</button>
            </form>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-slate-950 min-h-screen">
        <div class="max-w-6xl mx-auto px-4">
            <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-700 bg-white/90 dark:bg-slate-900/90 backdrop-blur">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-800/60">
                        <tr>
                            <th class="text-left px-3 py-2">Data</th>
                            <th class="text-left px-3 py-2">Mensagem</th>
                            <th class="text-left px-3 py-2">Email</th>
                            <th class="text-left px-3 py-2">Usuário</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($feedback as $f)
                        <tr class="border-t border-slate-100 dark:border-slate-800">
                            <td class="px-3 py-2 whitespace-nowrap">{{ $f->created_at?->format('d/m/Y H:i') }}</td>
                            <td class="px-3 py-2 max-w-xl"><div class="line-clamp-3">{{ $f->message }}</div></td>
                            <td class="px-3 py-2">{{ $f->email ?? '—' }}</td>
                            <td class="px-3 py-2">{{ optional($f->user)->name ?? 'Anônimo' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-3 py-8 text-center text-slate-500">Sem feedbacks.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $feedback->links() }}</div>
        </div>
    </div>
</x-app-layout>
