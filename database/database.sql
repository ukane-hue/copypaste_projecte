-- =====================================================
-- PORTAPAPERS - Base de Dades Completa
-- =====================================================
-- Aplicació per compartir text i fitxers entre dispositius
-- Versió: 1.0
-- Data: 2024

-- Crear base de dades
CREATE DATABASE IF NOT EXISTS portapapers 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE portapapers;

-- =====================================================
-- TAULA PRINCIPAL: PORTAPAPERS
-- =====================================================
-- Emmagatzema tots els portapapers (text i fitxers)
CREATE TABLE IF NOT EXISTS portapapers (
    -- Identificadors
    id INT AUTO_INCREMENT PRIMARY KEY,
    codi_hex VARCHAR(6) NOT NULL UNIQUE,
    
    -- Contingut de text
    contingut TEXT,
    
    -- Fitxers (emmagatzemats com base64)
    fitxer_data LONGTEXT,
    fitxer_info JSON,
    
    -- Indicador de typing
    typing TINYINT(1) DEFAULT 0,
    typing_data TIMESTAMP NULL,
    
    -- Timestamps
    data_creacio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_modificacio TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB 
  DEFAULT CHARSET=utf8mb4 
  COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- ÍNDEXOS PER OPTIMITZAR RENDIMENT
-- =====================================================
-- Índex principal per codi_hex (més utilitzat)
CREATE INDEX idx_codi_hex ON portapapers(codi_hex);

-- Índex per neteja automàtica (data_modificacio)
CREATE INDEX idx_data_modificacio ON portapapers(data_modificacio);

-- Índex per typing (consulta ràpida d'estat)
CREATE INDEX idx_typing ON portapapers(typing);

-- =====================================================
-- DADES INICIALS (OPCIONAL)
-- =====================================================
-- Inserir un portapapers d'exemple (opcional)
-- INSERT INTO portapapers (codi_hex, contingut) VALUES ('DEMO01', 'Benvingut a Portapapers!');

-- =====================================================
-- CONFIGURACIÓ DE SEGURETAT
-- =====================================================
-- Assegurar que només l'usuari de l'aplicació pot accedir
-- (Configurar segons el teu entorn)

-- =====================================================
-- NOTES D'ÚS
-- =====================================================
-- 1. Codi hex: 6 dígits únics (A-F, 0-9)
-- 2. Contingut: Text pla sense format
-- 3. Fitxers: Base64 + metadades JSON
-- 4. Typing: Boolean per indicador en temps real
-- 5. Neteja automàtica: Registres > 2h es poden eliminar