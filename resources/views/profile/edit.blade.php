<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                Perfil
            </h2>
        </div>
    </x-slot>

    <style>
      /* Garante contraste dos valores dos campos em dark mode */
      :root.dark input, :root.dark textarea {
        color: #f3f4f6 !important; /* gray-100 */
        background-color: #1e293b !important; /* slate-800 */
      }
      :root.dark input::placeholder, :root.dark textarea::placeholder {
        color: #d1d5db !important; /* gray-300 */
        opacity: 1;
      }
    </style>

    <div class="py-12 bg-gray-50 dark:bg-slate-950 min-h-screen transition-colors duration-300">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @php
                $cardClass = "p-6 sm:p-8 bg-white/90 dark:bg-slate-900/90 
                              border border-gray-100 dark:border-slate-800 
                              shadow-sm sm:rounded-2xl transition-all duration-300 backdrop-blur";
            @endphp

            <!-- Card: Dados do perfil -->
            <div class="{{ $cardClass }}">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Card: Alterar senha -->
            <div class="{{ $cardClass }}">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Card: Excluir conta -->
            <div class="{{ $cardClass }}">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

            @can('create', App\Models\CantoTipo::class)
            <!-- Card: Configurações Gerais -->
            <div class="{{ $cardClass }}">
                <div class="max-w-xl">
                    <a href="{{ route('settings.general') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold transition">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/></svg>
                        Configurações Gerais do Sistema
                    </a>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Acesse as configurações gerais do sistema, como idioma, tema padrão e preferências globais.</p>
                </div>
            </div>
            @endcan

        </div>
    </div>
</x-app-layout>
