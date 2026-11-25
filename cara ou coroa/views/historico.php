<?php
/**
 * View: Histórico de Lançamentos Recentes
 * Lista os giros com botão de reset protegido por Modal customizado
 */
?>
<section class="w-full">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-sm font-bold uppercase tracking-wider text-slate-400 flex items-center space-x-2">
            <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Lançamentos Recentes</span>
        </h2>
        <button id="btn-limpar-historico" type="button" class="text-xs font-semibold text-rose-400 hover:text-rose-300 transition flex items-center space-x-1 px-2.5 py-1 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/20">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            <span>Limpar Histórico</span>
        </button>
    </div>

    <!-- Lista de Histórico -->
    <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-3 sm:p-4 shadow-sm">
        
        <!-- Estado Vazio -->
        <div id="historico-vazio" class="py-10 text-center flex flex-col items-center justify-center">
            <div class="w-12 h-12 rounded-full bg-slate-800 text-slate-500 flex items-center justify-center mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            </div>
            <p class="text-sm font-semibold text-slate-300">Nenhum lançamento registrado</p>
            <p class="text-xs text-slate-500 mt-1">Clique no botão grande <strong class="text-amber-400">GIRAR</strong> para começar!</p>
        </div>

        <!-- Container dinâmico das linhas -->
        <div id="historico-lista" class="space-y-2.5">
            <!-- As linhas são inseridas via JavaScript -->
        </div>

    </div>
</section>
