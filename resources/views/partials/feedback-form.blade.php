<div x-data="{
  open:false,
  msg:'',
  email:'',
  loading:false,
  errors:{},
  success:false,
  successMsg:'',
  async submit() {
    this.loading = true; this.errors = {};
    try {
      const form = this.$refs.form;
      const url = form.getAttribute('action');
      const fd = new FormData(form);
      const res = await fetch(url, {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.getAttribute('content') || '' ,
          'Accept': 'application/json'
        },
        body: fd
      });
      if (res.ok) {
        this.successMsg = 'Obrigado pelo feedback!';
        this.success = true;
        this.msg=''; this.email=''; this.open = false;
        setTimeout(() => { this.success = false; }, 3500);
      } else if (res.status === 422) {
        const data = await res.json().catch(() => ({}));
        this.errors = data.errors || {};
      } else {
        this.successMsg = 'Enviado!';
        this.success = true;
        this.msg=''; this.email=''; this.open = false;
        setTimeout(() => { this.success = false; }, 3500);
      }
    } catch (e) {
      /* no-op */
    } finally {
      this.loading = false;
    }
  }
}" class="fixed bottom-4 right-4 z-40">
  <button @click="open=!open"
          class="px-3 py-2 rounded-xl shadow bg-amber-500 hover:bg-amber-600 text-white font-semibold">
    Feedback
  </button>

  <div x-show="open" x-transition
       class="mt-2 w-80 rounded-2xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-3 shadow-xl">
    <form x-ref="form" method="POST" action="{{ route('feedback.store') }}" @submit.prevent="submit">
      @csrf
      <input type="hidden" name="page_url" value="{{ url()->full() }}">
      <input type="hidden" name="page_title" value="{{ trim($__env->yieldContent('title') ?? ($title ?? '')) }}">
      <label class="block text-sm font-semibold mb-1 text-gray-800 dark:text-gray-100">O que podemos melhorar?</label>
      <textarea x-model="msg" name="message" rows="4" required
                class="w-full rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-900 px-3 py-2 text-gray-800 dark:text-gray-100"></textarea>
      <template x-if="errors.message"><p class="mt-1 text-xs text-red-600" x-text="errors.message?.[0]"></p></template>
      <label class="block text-xs mt-2 text-gray-500 dark:text-gray-400">Email (opcional)</label>
      <input x-model="email" name="email" type="email" class="w-full rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-900 px-3 py-2 text-gray-800 dark:text-gray-100">
      <template x-if="errors.email"><p class="mt-1 text-xs text-red-600" x-text="errors.email?.[0]"></p></template>
      <div class="mt-2 flex justify-end gap-2">
        <button type="button" @click="open=false" class="px-3 py-2 rounded-lg border bg-white dark:bg-slate-800">Fechar</button>
        <button type="submit" :disabled="!msg.trim() || loading" class="px-3 py-2 rounded-lg bg-blue-600 text-white disabled:opacity-50" x-text="loading ? 'Enviando…' : 'Enviar'"></button>
      </div>
    </form>
  </div>

  <div x-show="success" x-transition
       class="fixed bottom-4 left-1/2 -translate-x-1/2 z-50 px-4 py-2 rounded-xl bg-green-600 text-white shadow">
    <span x-text="successMsg"></span>
  </div>
</div>
