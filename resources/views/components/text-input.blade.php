@props(['disabled' => false])

<input
  @disabled($disabled)
  {{ $attributes->merge([
    'class' => 'block w-full rounded-lg shadow-sm
                bg-white/10 text-white placeholder-white/60
                border border-white/20
                focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                disabled:opacity-50 disabled:cursor-not-allowed'
  ]) }}
>
