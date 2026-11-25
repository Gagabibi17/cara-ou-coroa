<?php
/**
 * View: Componente da Moeda 3D e Botão de Ação Principal
 * Regras: Botão grande e chamativo escrito GIRAR; Moeda com 2 lados dinâmicos.
 */
?>
<section class="flex flex-col items-center justify-center pt-2 sm:pt-4">

    <!-- Seletor de Palpite (Opcional & Dinâmico) -->
    <div class="mb-6 flex flex-col items-center">
        <span class="text-xs uppercase tracking-wider text-slate-400 font-semibold mb-2">Faça sua escolha (opcional):</span>
        <div class="inline-flex p-1 bg-slate-900 border border-slate-800 rounded-2xl shadow-inner space-x-1">
            <button type="button" data-choice="cara" class="choice-btn px-4 py-1.5 rounded-xl text-xs sm:text-sm font-bold bg-slate-800/80 text-slate-300 hover:text-white transition flex items-center space-x-1.5">
                <span>🟡</span>
                <span>Cara</span>
            </button>
            <button type="button" data-choice="coroa" class="choice-btn px-4 py-1.5 rounded-xl text-xs sm:text-sm font-bold bg-slate-800/80 text-slate-300 hover:text-white transition flex items-center space-x-1.5">
                <span>👑</span>
                <span>Coroa</span>
            </button>
        </div>
    </div>

    <!-- Cenário 3D da Moeda -->
    <div class="coin-scene my-2">
        <div id="coin-object" class="coin-object">
            
            <!-- FACE FRONTAL: CARA -->
            <div class="coin-face coin-face-front">
                <div class="coin-shine"></div>
                <!-- Gráfico Vetorial em Relevo Dourado -->
                <div class="flex flex-col items-center justify-center text-amber-950 text-center p-3">
                    <svg class="w-14 h-14 sm:w-16 sm:h-16 text-amber-950/80 mb-1" viewBox="0 0 24 24" fill="currentColor">
                        <!-- Brasão / Águia Clássica de Moeda -->
                        <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" fill="currentColor"/>
                    </svg>
                    <span class="font-serif-coin text-xl sm:text-2xl font-black tracking-widest text-amber-950 drop-shadow-sm">
                        CARA
                    </span>
                    <span class="text-[9px] sm:text-[10px] font-bold text-amber-900/80 tracking-wider uppercase mt-0.5">
                        ★ BRASIL ★
                    </span>
                </div>
            </div>

            <!-- FACE TRASEIRA: COROA -->
            <div class="coin-face coin-face-back">
                <div class="coin-shine"></div>
                <!-- Gráfico Vetorial da Coroa Imperial -->
                <div class="flex flex-col items-center justify-center text-amber-950 text-center p-3">
                    <svg class="w-14 h-14 sm:w-16 sm:h-16 text-amber-950/80 mb-1" viewBox="0 0 24 24" fill="currentColor">
                        <!-- Coroa Imperial -->
                        <path d="M5 16L3 5L8.5 10L12 4L15.5 10L21 5L19 16H5M19 19C19 19.6 18.6 20 18 20H6C5.4 20 5 19.6 5 19V18H19V19Z" fill="currentColor"/>
                    </svg>
                    <span class="font-serif-coin text-xl sm:text-2xl font-black tracking-widest text-amber-950 drop-shadow-sm">
                        COROA
                    </span>
                    <span class="text-[9px] sm:text-[10px] font-bold text-amber-900/80 tracking-wider uppercase mt-0.5">
                        ★ 1 REAL ★
                    </span>
                </div>
            </div>

        </div>
    </div>

    <!-- Sombra Dinâmica 3D -->
    <div id="coin-shadow" class="coin-shadow"></div>

    <!-- Banner do Resultado Revelado -->
    <div id="result-banner" class="hidden mt-6 text-center">
        <div id="result-text" class="text-2xl sm:text-3xl font-extrabold text-amber-300 drop-shadow">
            DEU CARA!
        </div>
        <div id="result-badge" class="mt-2 hidden">
            <!-- Preenchido via JavaScript se o usuário fez palpite -->
        </div>
    </div>

    <!-- BOTÃO GRANDE E CHAMATIVO: GIRAR (Core Requirement) -->
    <div class="mt-8 w-full flex flex-col items-center px-4">
        <button id="btn-girar" type="button" class="btn-girar-super w-full max-w-sm py-4 px-8 rounded-2xl flex items-center justify-center space-x-3 text-slate-950 font-black text-xl sm:text-2xl tracking-wider cursor-pointer group uppercase">
            <svg class="w-7 h-7 text-slate-950 transition-transform group-hover:rotate-180 duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            <span>GIRAR</span>
        </button>
        <span class="text-xs text-slate-400 mt-2 font-medium">
            Dica: você também pode pressionar a tecla <kbd class="px-2 py-0.5 bg-slate-800 border border-slate-700 rounded text-slate-300 font-mono text-[10px]">Espaço</kbd>
        </span>
    </div>

</section>
