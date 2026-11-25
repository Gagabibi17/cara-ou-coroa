<?php
/**
 * Backend isolado: Giro da Moeda
 * Processa a lógica de sorteio, persistência via PDO e atualização das sessões nativas.
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/bd.php';

// Garante sessão nativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inicializa estrutura de sessão se não existir
if (!isset($_SESSION['historico_sessao'])) {
    $_SESSION['historico_sessao'] = [];
}
if (!isset($_SESSION['stats_sessao'])) {
    $_SESSION['stats_sessao'] = ['total' => 0, 'cara' => 0, 'coroa' => 0];
}

try {
    // Obter dados da requisição (JSON ou POST)
    $inputRaw = file_get_contents('php://input');
    $inputData = json_decode($inputRaw, true) ?: $_POST;

    // Validação da escolha do usuário (opcional: 'cara' ou 'coroa')
    $escolhaUsuario = null;
    if (!empty($inputData['escolha'])) {
        $escolhaNormalizada = strtolower(trim($inputData['escolha']));
        if (in_array($escolhaNormalizada, ['cara', 'coroa'], true)) {
            $escolhaUsuario = $escolhaNormalizada;
        }
    }

    // Sorteio aleatório justo: 0 = cara, 1 = coroa
    $sorteio = random_int(0, 1);
    $resultado = ($sorteio === 0) ? 'cara' : 'coroa';

    // Verificação de acerto caso o usuário tenha optado por escolher
    $acertou = null;
    if ($escolhaUsuario !== null) {
        $acertou = ($escolhaUsuario === $resultado) ? 1 : 0;
    }

    $sessionId = session_id();
    $dataHora = date('Y-m-d H:i:s');
    $dbGravado = false;
    $novoId = null;

    // Tentativa de persistência no MySQL via PDO com Prepared Statements
    $pdo = getConexao();
    if ($pdo !== null) {
        $sql = "INSERT INTO lancamentos (resultado, escolha_usuario, acertou, session_id, criado_em) 
                VALUES (:resultado, :escolha, :acertou, :session_id, :criado_em)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':resultado', $resultado, PDO::PARAM_STR);
        $stmt->bindValue(':escolha', $escolhaUsuario, $escolhaUsuario ? PDO::PARAM_STR : PDO::PARAM_NULL);
        $stmt->bindValue(':acertou', $acertou, $acertou !== null ? PDO::PARAM_INT : PDO::PARAM_NULL);
        $stmt->bindValue(':session_id', $sessionId, PDO::PARAM_STR);
        $stmt->bindValue(':criado_em', $dataHora, PDO::PARAM_STR);
        
        $stmt->execute();
        $novoId = (int)$pdo->lastInsertId();
        $dbGravado = true;
    }

    // Sempre mantém histórico e estatísticas na sessão nativa (garante resiliência)
    $registroSessao = [
        'id'              => $novoId ?: count($_SESSION['historico_sessao']) + 1,
        'resultado'       => $resultado,
        'escolha_usuario' => $escolhaUsuario,
        'acertou'         => $acertou,
        'criado_em'       => $dataHora
    ];
    array_unshift($_SESSION['historico_sessao'], $registroSessao);
    // Limita o histórico na sessão a 100 itens mais recentes
    if (count($_SESSION['historico_sessao']) > 100) {
        array_pop($_SESSION['historico_sessao']);
    }

    // Atualiza contadores da sessão
    $_SESSION['stats_sessao']['total']++;
    if ($resultado === 'cara') {
        $_SESSION['stats_sessao']['cara']++;
    } else {
        $_SESSION['stats_sessao']['coroa']++;
    }

    // Estatísticas consolidadas (do banco se conectado, senão da sessão)
    $stats = obterEstatisticasConsolidadas($pdo, $sessionId);

    echo json_encode([
        'sucesso'         => true,
        'resultado'       => $resultado,
        'escolha_usuario' => $escolhaUsuario,
        'acertou'         => $acertou,
        'data_hora'       => date('d/m/Y H:i:s', strtotime($dataHora)),
        'db_gravado'      => $dbGravado,
        'stats'           => $stats,
        'mensagem'        => 'Moeda girada com sucesso! Caiu ' . ucfirst($resultado) . '.'
    ]);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'sucesso'  => false,
        'erro'     => true,
        'mensagem' => 'Erro ao processar o giro da moeda: ' . $e->getMessage()
    ]);
}

/**
 * Função utilitária para consolidar estatísticas
 */
function obterEstatisticasConsolidadas($pdo, $sessionId) {
    if ($pdo !== null) {
        try {
            // Contagem geral
            $stmt = $pdo->prepare("SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN resultado = 'cara' THEN 1 ELSE 0 END) as cara,
                SUM(CASE WHEN resultado = 'coroa' THEN 1 ELSE 0 END) as coroa
                FROM lancamentos");
            $stmt->execute();
            $row = $stmt->fetch();

            $total = (int)($row['total'] ?? 0);
            $cara = (int)($row['cara'] ?? 0);
            $coroa = (int)($row['coroa'] ?? 0);
            $pctCara = $total > 0 ? round(($cara / $total) * 100, 1) : 0;
            $pctCoroa = $total > 0 ? round(($coroa / $total) * 100, 1) : 0;

            return [
                'total'     => $total,
                'cara'      => $cara,
                'coroa'     => $coroa,
                'pct_cara'  => $pctCara,
                'pct_coroa' => $pctCoroa,
                'origem'    => 'mysql'
            ];
        } catch (PDOException $e) {
            // Fallback para sessão se houver falha na query
        }
    }

    $total = $_SESSION['stats_sessao']['total'] ?? 0;
    $cara = $_SESSION['stats_sessao']['cara'] ?? 0;
    $coroa = $_SESSION['stats_sessao']['coroa'] ?? 0;
    $pctCara = $total > 0 ? round(($cara / $total) * 100, 1) : 0;
    $pctCoroa = $total > 0 ? round(($coroa / $total) * 100, 1) : 0;

    return [
        'total'     => $total,
        'cara'      => $cara,
        'coroa'     => $coroa,
        'pct_cara'  => $pctCara,
        'pct_coroa' => $pctCoroa,
        'origem'    => 'sessao'
    ];
}
