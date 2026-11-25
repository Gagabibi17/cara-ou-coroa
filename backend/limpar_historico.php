<?php
/**
 * Backend isolado: Limpeza e Reset do Histórico
 * Executa exclusão segura com PDO e reinicializa sessões nativas.
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/bd.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'sucesso'  => false,
        'mensagem' => 'Método de requisição não permitido. Utilize POST.'
    ]);
    exit;
}

try {
    $pdo = getConexao();
    $bancoLimpo = false;

    if ($pdo !== null) {
        $stmt = $pdo->prepare("DELETE FROM lancamentos");
        $stmt->execute();
        $bancoLimpo = true;
    }

    // Limpa também as sessões nativas
    $_SESSION['historico_sessao'] = [];
    $_SESSION['stats_sessao'] = ['total' => 0, 'cara' => 0, 'coroa' => 0];

    echo json_encode([
        'sucesso'     => true,
        'banco_limpo' => $bancoLimpo,
        'mensagem'    => 'Histórico e estatísticas foram resetados com sucesso!'
    ]);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'sucesso'  => false,
        'erro'     => true,
        'mensagem' => 'Erro ao limpar histórico: ' . $e->getMessage()
    ]);
}
