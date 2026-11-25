-- ===================================================
-- Banco de Dados: cara_ou_coroa
-- Sistema de Giro de Moeda (Cara ou Coroa)
-- Compatível com MySQL 5.7+ e MariaDB 10+
-- ===================================================

CREATE DATABASE IF NOT EXISTS `cara_ou_coroa` 
DEFAULT CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE `cara_ou_coroa`;

-- Tabela principal de histórico de lançamentos da moeda
CREATE TABLE IF NOT EXISTS `lancamentos` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `resultado` ENUM('cara', 'coroa') NOT NULL,
    `escolha_usuario` ENUM('cara', 'coroa') NULL DEFAULT NULL,
    `acertou` TINYINT(1) NULL DEFAULT NULL,
    `session_id` VARCHAR(128) NULL,
    `criado_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_resultado` (`resultado`),
    INDEX `idx_session_id` (`session_id`),
    INDEX `idx_criado_em` (`criado_em`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
