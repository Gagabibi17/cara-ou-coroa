<?php
/**
 * Backend isolado: Consulta de Histórico de Lançamentos
 * Regras: PDO seguro com Prepared Statement e fallback para sessão.
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/bd.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$limite = isset($_GET['limite']) ? min(max((int)$_GET['limite'], 1), 100) : 20;

try {
    $pdo = getConexao();
    $itens = [];
    $origem = 'sessao';

    if ($pdo !== null) {
        $sql = "SELECT id, resultado, escolha_usuario, acertou, criado_em 
                FROM lancamentos 
                ORDER BY id DESC 
                LIMIT :limite";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        
        $dados = $stmt->fetchAll();
        foreach ($dados as $d) {
            $itens[] = [
                'id'              => (int)$d['id'],
                'resultado'       => $d['resultado'],
                'escolha_usuario' => $d['escolha_usuario'],
                'acertou'         => $d['acertou'] !== null ? (bool)$d['acertou'] : null,
                'data_formatada'  => date('d/m/Y H:i:s', strtotime($d['criado_em']))
            ];
        }
        $origem = 'mysql';
    } else {
        $sessaoItens = $_SESSION['historico_sessao'] ?? [];
        $cortados = array_slice($sessaoItens, 0, $limite);
        foreach ($cortados as $d) {
            $itens[] = [
                'id'              => (int)$d['id'],
                'resultado'       => $d['resultado'],
                'escolha_usuario' => $d['escolha_usuario'],
                'acertou'         => $d['acertou'] !== null ? (bool)$d['acertou'] : null,
                'data_formatada'  => date('d/m/Y H:i:s', strtotime($d['criado_em']))
            ];
        }
    }

    echo json_encode([
        'sucesso' => true,
        'origem'  => $origem,
        'total'   => count($itens),
        'dados'   => $itens
    ]);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'sucesso'  => false,
        'erro'     => true,
        'mensagem' => 'Erro ao consultar histórico: ' . $e->getMessage()
    ]);
}
