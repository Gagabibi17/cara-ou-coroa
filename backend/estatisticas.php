<?php
/**
 * Backend isolado: Estatísticas Consolidadas
 * Calcula totais, porcentagens e sequências ativas.
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/bd.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    $pdo = getConexao();

    $total = 0;
    $cara = 0;
    $coroa = 0;
    $origem = 'sessao';
    $streakCount = 0;
    $streakLado = null;

    if ($pdo !== null) {
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
        $origem = 'mysql';

        // Calcula a sequência mais recente (streak)
        $stmtUltimos = $pdo->prepare("SELECT resultado FROM lancamentos ORDER BY id DESC LIMIT 50");
        $stmtUltimos->execute();
        $ultimos = $stmtUltimos->fetchAll(PDO::FETCH_COLUMN);
        
        if (!empty($ultimos)) {
            $streakLado = $ultimos[0];
            foreach ($ultimos as $l) {
                if ($l === $streakLado) {
                    $streakCount++;
                } else {
                    break;
                }
            }
        }
    } else {
        $total = $_SESSION['stats_sessao']['total'] ?? 0;
        $cara = $_SESSION['stats_sessao']['cara'] ?? 0;
        $coroa = $_SESSION['stats_sessao']['coroa'] ?? 0;

        $sessaoItens = $_SESSION['historico_sessao'] ?? [];
        if (!empty($sessaoItens)) {
            $streakLado = $sessaoItens[0]['resultado'];
            foreach ($sessaoItens as $item) {
                if ($item['resultado'] === $streakLado) {
                    $streakCount++;
                } else {
                    break;
                }
            }
        }
    }

    $pctCara = $total > 0 ? round(($cara / $total) * 100, 1) : 0;
    $pctCoroa = $total > 0 ? round(($coroa / $total) * 100, 1) : 0;

    echo json_encode([
        'sucesso'     => true,
        'origem'      => $origem,
        'total'       => $total,
        'cara'        => $cara,
        'coroa'       => $coroa,
        'pct_cara'    => $pctCara,
        'pct_coroa'   => $pctCoroa,
        'sequencia'   => [
            'lado'  => $streakLado,
            'vezes' => $streakCount
        ]
    ]);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'sucesso'  => false,
        'erro'     => true,
        'mensagem' => 'Erro ao calcular estatísticas: ' . $e->getMessage()
    ]);
}
