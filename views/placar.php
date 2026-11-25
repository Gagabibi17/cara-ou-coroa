<?php
/**
 * View: Placar e Estatísticas Consolidadas
 * Layout Mobile-First com cartões métricos e barras comparativas
 */
?>
<section class="w-full">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-sm font-bold uppercase tracking-wider text-slate-400 flex items-center space-x-2">
            <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            <span>Estatísticas em Tempo Real</span>
        </h2>
        <span id="streak-badge" class="hidden px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">
            🔥 Sequência: 0x
        </span>
    </div>

    <!-- Grid de Métricas Mobile-First (1 col em celular, 3 cols em desktop) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
        
        <!-- Total de Giros -->
        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-4 flex flex-col justify-between shadow-sm">
            <div class="flex items-center justify-between text-slate-400 mb-1">
                <span class="text-xs font-semibold uppercase">Total de Giros</span>
                <span class="text-lg">🎯</span>
            </div>
            <div class="flex items-baseline space-x-2">
                <span id="stat-total" class="text-3xl font-extrabold text-white">0</span>
                <span class="text-xs text-slate-400">lançamentos</span>
            </div>
        </div>

        <!-- Cara -->
        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-4 flex flex-col justify-between shadow-sm">
            <div class="flex items-center justify-between text-slate-400 mb-1">
                <span class="text-xs font-semibold uppercase text-amber-400">Cara</span>
                <span id="stat-pct-cara" class="text-xs font-bold text-amber-400">0%</span>
            </div>
            <div class="flex items-baseline justify-between">
                <span id="stat-cara" class="text-3xl font-extrabold text-amber-400">0</span>
                <span class="text-xs text-slate-400">vezes</span>
            </div>
            <!-- Barra de Progresso Cara -->
            <div class="w-full h-1.5 bg-slate-800 rounded-full mt-3 overflow-hidden">
                <div id="bar-cara" class="h-full bg-gradient-to-r from-amber-500 to-yellow-400 rounded-full transition-all duration-500" style="width: 0%;"></div>
            </div>
        </div>

        <!-- Coroa -->
        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-4 flex flex-col justify-between shadow-sm">
            <div class="flex items-center justify-between text-slate-400 mb-1">
                <span class="text-xs font-semibold uppercase text-sky-400">Coroa</span>
                <span id="stat-pct-coroa" class="text-xs font-bold text-sky-400">0%</span>
            </div>
            <div class="flex items-baseline justify-between">
                <span id="stat-coroa" class="text-3xl font-extrabold text-sky-400">0</span>
                <span class="text-xs text-slate-400">vezes</span>
            </div>
            <!-- Barra de Progresso Coroa -->
            <div class="w-full h-1.5 bg-slate-800 rounded-full mt-3 overflow-hidden">
                <div id="bar-coroa" class="h-full bg-gradient-to-r from-sky-500 to-blue-400 rounded-full transition-all duration-500" style="width: 0%;"></div>
            </div>
        </div>

    </div>
</section>
