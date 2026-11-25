<?php
/**
 * Conexão com o Banco de Dados MySQL utilizando PDO
 * Regras:
 * - Uso obrigatório de PDO para prevenção de SQL Injection
 * - Desativação de emulação de prepared statements
 * - Tratamento seguro de exceções
 */

// Inicia sessão nativa caso ainda não esteja iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Configurações do Banco de Dados
define('DB_HOST', getenv('DB_HOST') ?: '127.0.0.1');
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_NAME', getenv('DB_NAME') ?: 'cara_ou_coroa');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_CHARSET', 'utf8mb4');

/**
 * Retorna uma instância de conexão PDO
 * @return PDO|null Retorna a instância PDO ou null caso o banco esteja indisponível
 */
function getConexao() {
    static $pdo = null;

    if ($pdo !== null) {
        return $pdo;
    }

    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $opcoes = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET
    ];

    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $opcoes);
        garantirEstruturaTabela($pdo);
        return $pdo;
    } catch (PDOException $e) {
        // Tentativa de conectar sem banco para criá-lo automaticamente caso não exista
        try {
            $dsnSemDb = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";charset=" . DB_CHARSET;
            $pdoRoot = new PDO($dsnSemDb, DB_USER, DB_PASS, $opcoes);
            $pdoRoot->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
            
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $opcoes);
            garantirEstruturaTabela($pdo);
            return $pdo;
        } catch (PDOException $e2) {
            // Registra erro para feedback de interface sem quebrar o carregamento da página
            $_SESSION['db_status'] = [
                'conectado' => false,
                'mensagem'  => 'MySQL não conectado: ' . $e2->getMessage()
            ];
            return null;
        }
    }
}

/**
 * Garante que a tabela necessária exista no banco de dados conectado
 * @param PDO $pdo
 */
function garantirEstruturaTabela($pdo) {
    $sql = "CREATE TABLE IF NOT EXISTS `lancamentos` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `resultado` ENUM('cara', 'coroa') NOT NULL,
        `escolha_usuario` ENUM('cara', 'coroa') NULL DEFAULT NULL,
        `acertou` TINYINT(1) NULL DEFAULT NULL,
        `session_id` VARCHAR(128) NULL,
        `criado_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX `idx_resultado` (`resultado`),
        INDEX `idx_session_id` (`session_id`),
        INDEX `idx_criado_em` (`criado_em`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    
    $pdo->exec($sql);
    $_SESSION['db_status'] = [
        'conectado' => true,
        'mensagem'  => 'Conexão PDO com MySQL estabelecida com sucesso.'
    ];
}
