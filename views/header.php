<?php
/**
 * View: Cabeçalho da Aplicação (Header)
 * Configuração Mobile-First, Tailwind CSS CDN e Barra Superior
 */
require_once __DIR__ . '/../backend/bd.php';
$conexao = getConexao();
$dbStatus = $_SESSION['db_status'] ?? ['conectado' => ($conexao !== null), 'mensagem' => ''];
?>
<!DOCTYPE html>
<html lang="pt-BR" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#0f172a">
    <title>Cara ou Coroa - Jogo da Moeda Online</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        gold: {
                            50: '#fffbeb',
                            100: '#fef3c7',
                            200: '#fde68a',
                            300: '#fcd34d',
                            400: '#fbbf24',
                            500: '#f59e0b',
                            600: '#d97706',
                            700: '#b45309',
                            800: '#92400e',
                            900: '#78350f',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Estilos Customizados e Física 3D -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="min-h-full flex flex-col bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-slate-100 antialiased selection:bg-amber-500 selection:text-slate-950">

    <!-- Barra de Navegação Superior -->
    <header class="w-full border-b border-slate-800/80 bg-slate-950/80 backdrop-blur-md sticky top-0 z-40">
        <div class="max-w-4xl mx-auto px-4 h-16 flex items-center justify-between">
            
            <!-- Logo e Título -->
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center shadow-lg shadow-amber-500/20 text-slate-950 font-black text-xl font-serif-coin">
                    🪙
                </div>
                <div>
                    <h1 class="font-extrabold text-base sm:text-lg tracking-tight bg-gradient-to-r from-amber-200 via-amber-400 to-yellow-500 bg-clip-text text-transparent">
                        Cara ou Coroa
                    </h1>
                    <p class="text-[11px] text-slate-400 hidden sm:block">Sorteador dinâmico de moeda</p>
                </div>
            </div>

            <!-- Ações e Status -->
            <div class="flex items-center space-x-2 sm:space-x-3">
                
                <!-- Badge de Conexão com o Banco -->
                <?php if ($dbStatus['conectado']): ?>
                    <span class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20" title="Conexão com MySQL PDO ativa">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span class="hidden sm:inline">MySQL Conectado</span>
                        <span class="sm:hidden">MySQL</span>
                    </span>
                <?php else: ?>
                    <span class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20" title="Operando com Sessão Nativa PHP (MySQL em standby)">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                        <span class="hidden sm:inline">Sessão Nativa</span>
                        <span class="sm:hidden">Sessão</span>
                    </span>
                <?php endif; ?>

                <!-- Botão de Som -->
                <button id="btn-toggle-sound" type="button" class="p-2 rounded-xl bg-slate-800/80 hover:bg-slate-700 text-slate-300 hover:text-white transition border border-slate-700/60" title="Alternar som">
                    <span id="sound-icon-on">
                        <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/></svg>
                    </span>
                    <span id="sound-icon-off" class="hidden">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"/></svg>
                    </span>
                </button>

                <!-- Botão de Informações / Modal -->
                <button id="btn-info" type="button" class="p-2 rounded-xl bg-slate-800/80 hover:bg-slate-700 text-slate-300 hover:text-white transition border border-slate-700/60" title="Sobre o Sistema">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </button>
            </div>
        </div>
    </header>

    <main class="flex-1 max-w-4xl w-full mx-auto px-4 py-6 sm:py-8 flex flex-col space-y-8">
