<?php
require_once '../config/config.php';

// Obtenir codi del paràmetre GET
$codi = $_GET['codi'] ?? '';

// Validar codi
if (!validarCodiHex($codi)) {
    http_response_code(400);
    die('Codi hexadecimal no vàlid');
}

// Normalitzar codi a majúscules
$codi = strtoupper($codi);

try {
    $db = Database::getInstance()->getConnection();
    
    // Obtenir contingut del portapapers
    $stmt = $db->prepare("SELECT contingut FROM portapapers WHERE codi_hex = ?");
    $stmt->execute([$codi]);
    $resultat = $stmt->fetch();
    
    if (!$resultat) {
        http_response_code(404);
        die('Portapapers no trobat');
    }
    
    $contingut = $resultat['contingut'] ?? '';
    
    // Configurar headers per text pla
    header('Content-Type: text/plain; charset=utf-8');
    header('Cache-Control: no-cache, must-revalidate');
    header('Pragma: no-cache');
    
    // Retornar només el text
    echo $contingut;
    
} catch (Exception $e) {
    http_response_code(500);
    die('Error de connexió a la base de dades');
}
?>
