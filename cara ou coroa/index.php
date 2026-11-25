<?php
/**
 * Ponto de Entrada Principal (Front Controller / View Orchestrator)
 * 
 * Regras Globais Atendidas:
 * - Sem usuário e senha para entrar no site
 * - Separação lógica total entre conexão (bd.php), scripts de backend e views
 * - Não monolítico, facilitando futuras manutenções e extensões
 */

// Inicia sessão nativa PHP
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Carrega as views modulares
require_once __DIR__ . '/views/header.php';
require_once __DIR__ . '/views/moeda.php';
require_once __DIR__ . '/views/placar.php';
require_once __DIR__ . '/views/historico.php';
require_once __DIR__ . '/views/footer.php';
