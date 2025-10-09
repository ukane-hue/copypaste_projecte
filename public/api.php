<?php
// Només enviar headers si no estem en mode test
if (!defined('TESTING_MODE')) {
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Content-Type');
}

// Detectar automàticament la ubicació del fitxer config
$configPath = file_exists('../config/config.php') ? '../config/config.php' : 'config/config.php';
require_once $configPath;

$db = Database::getInstance()->getConnection();
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'crear':
            if ($method === 'POST') {
                crearPortapapers();
            }
            break;
            
        case 'obtenir':
            if ($method === 'GET') {
                obtenirPortapapers();
            }
            break;
            
        case 'actualitzar':
            if ($method === 'POST') {
                actualitzarPortapapers();
            }
            break;
            
        case 'verificar':
            if ($method === 'GET') {
                verificarPortapapers();
            }
            break;
            
        case 'neteja':
            if ($method === 'POST') {
                executarNeteja();
            }
            break;
            
        case 'estadistiques':
            if ($method === 'GET') {
                obtenirEstadistiques();
            }
            break;
            
        case 'typing':
            if ($method === 'POST') {
                actualitzarTyping();
            }
            break;
            
        case 'pujar_fitxer':
            if ($method === 'POST') {
                pujarFitxer();
            }
            break;
            
        case 'descarregar_fitxer':
            if ($method === 'GET') {
                descarregarFitxer();
            }
            break;
            
        case 'eliminar_fitxer':
            if ($method === 'POST') {
                eliminarFitxer();
            }
            break;
            
        default:
            throw new Exception('Acció no vàlida');
    }
} catch (Exception $e) {
    http_response_code(400);
    
    if (DEBUG) {
        // En mode debug, mostrar informació detallada
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage(),
            'debug' => [
                'action' => $action,
                'method' => $method,
                'codi' => $_GET['codi'] ?? $_POST['codi'] ?? 'no_provided',
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]
        ]);
    } else {
        // En mode producció, mostrar missatge genèric
        echo json_encode([
            'success' => false,
            'error' => 'S\'ha produït un error. Torna a intentar-ho més tard.',
            'redirect' => '/error.php'
        ]);
    }
}

function crearPortapapers() {
    global $db;
    
    $codi = generarCodiHex();
    $contingut = $_POST['contingut'] ?? '';
    
    $stmt = $db->prepare("INSERT INTO portapapers (codi_hex, contingut) VALUES (?, ?)");
    $stmt->execute([$codi, $contingut]);
    
    echo json_encode([
        'success' => true,
        'codi' => $codi,
        'missatge' => 'Portapapers creat correctament'
    ]);
}

function obtenirPortapapers() {
    global $db;
    
    $codi = $_GET['codi'] ?? '';
    
    if (!validarCodiHex($codi)) {
        throw new Exception('Codi hexadecimal no vàlid');
    }
    
    // Normalitzar codi a majúscules
    $codi = strtoupper($codi);
    
    $stmt = $db->prepare("SELECT contingut, data_modificacio, typing, typing_data, fitxer_info FROM portapapers WHERE codi_hex = ?");
    $stmt->execute([$codi]);
    $resultat = $stmt->fetch();
    
    if (!$resultat) {
        throw new Exception('Portapapers no trobat');
    }
    
    $fitxerInfo = null;
    if ($resultat['fitxer_info']) {
        $fitxerInfo = json_decode($resultat['fitxer_info'], true);
    }
    
    echo json_encode([
        'success' => true,
        'contingut' => $resultat['contingut'],
        'data_modificacio' => $resultat['data_modificacio'],
        'typing' => (bool)$resultat['typing'],
        'typing_data' => $resultat['typing_data'],
        'fitxer_info' => $fitxerInfo
    ]);
}

function actualitzarPortapapers() {
    global $db;
    
    $codi = $_POST['codi'] ?? '';
    $contingut = $_POST['contingut'] ?? '';
    
    if (!validarCodiHex($codi)) {
        throw new Exception('Codi hexadecimal no vàlid');
    }
    
    // Normalitzar codi a majúscules
    $codi = strtoupper($codi);
    
    $stmt = $db->prepare("UPDATE portapapers SET contingut = ?, data_modificacio = CURRENT_TIMESTAMP WHERE codi_hex = ?");
    $resultat = $stmt->execute([$contingut, $codi]);
    
    if ($stmt->rowCount() === 0) {
        throw new Exception('Portapapers no trobat');
    }
    
    echo json_encode([
        'success' => true,
        'missatge' => 'Portapapers actualitzat correctament'
    ]);
}

function verificarPortapapers() {
    global $db;
    
    $codi = $_GET['codi'] ?? '';
    
    if (!validarCodiHex($codi)) {
        throw new Exception('Codi hexadecimal no vàlid');
    }
    
    // Normalitzar codi a majúscules
    $codi = strtoupper($codi);
    
    $stmt = $db->prepare("SELECT id FROM portapapers WHERE codi_hex = ?");
    $stmt->execute([$codi]);
    $resultat = $stmt->fetch();
    
    if (!$resultat) {
        throw new Exception('Portapapers no trobat');
    }
    
    echo json_encode([
        'success' => true,
        'existeix' => true
    ]);
}

function executarNeteja() {
    $registresEliminats = netejarPortapapersAntics();
    
    echo json_encode([
        'success' => true,
        'registres_eliminats' => $registresEliminats,
        'missatge' => "S'han eliminat $registresEliminats registres antics"
    ]);
}

function obtenirEstadistiques() {
    $estadistiques = obtenirEstadistiquesNeteja();
    
    echo json_encode([
        'success' => true,
        'estadistiques' => $estadistiques
    ]);
}

function actualitzarTyping() {
    global $db;
    
    $codi = $_POST['codi'] ?? '';
    $typing = $_POST['typing'] ?? '0';
    
    if (!validarCodiHex($codi)) {
        throw new Exception('Codi hexadecimal no vàlid');
    }
    
    // Normalitzar codi a majúscules
    $codi = strtoupper($codi);
    
    // Convertir a boolean
    $typingBool = filter_var($typing, FILTER_VALIDATE_BOOLEAN);
    
    $stmt = $db->prepare("UPDATE portapapers SET typing = ?, typing_data = ? WHERE codi_hex = ?");
    $resultat = $stmt->execute([
        $typingBool ? 1 : 0,
        $typingBool ? date('Y-m-d H:i:s') : null,
        $codi
    ]);
    
    if ($stmt->rowCount() === 0) {
        throw new Exception('Portapapers no trobat');
    }
    
    echo json_encode([
        'success' => true,
        'typing' => $typingBool,
        'missatge' => $typingBool ? 'Indicador de typing activat' : 'Indicador de typing desactivat'
    ]);
}

function pujarFitxer() {
    global $db;
    
    $codi = $_POST['codi'] ?? '';
    
    if (!validarCodiHex($codi)) {
        throw new Exception('Codi hexadecimal no vàlid');
    }
    
    // Normalitzar codi a majúscules
    $codi = strtoupper($codi);
    
    // Verificar que el portapapers existeix
    $stmt = $db->prepare("SELECT id FROM portapapers WHERE codi_hex = ?");
    $stmt->execute([$codi]);
    if (!$stmt->fetch()) {
        throw new Exception('Portapapers no trobat');
    }
    
    // Verificar que s'ha pujat un fitxer
    if (!isset($_FILES['fitxer']) || $_FILES['fitxer']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Error al pujar el fitxer');
    }
    
    $fitxer = $_FILES['fitxer'];
    
    // Validacions de seguretat
    $midaMaxima = 10 * 1024 * 1024; // 10MB
    if ($fitxer['size'] > $midaMaxima) {
        throw new Exception('El fitxer és massa gran (màxim 10MB)');
    }
    
    // Tipus de fitxers permesos
    $tipusPermesos = [
        'image/jpeg', 'image/png', 'image/gif', 'image/webp',
        'application/pdf', 'text/plain', 'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/zip', 'application/x-rar-compressed'
    ];
    
    if (!in_array($fitxer['type'], $tipusPermesos)) {
        throw new Exception('Tipus de fitxer no permès');
    }
    
    // Llegir i convertir a base64
    $contingut = file_get_contents($fitxer['tmp_name']);
    $base64 = base64_encode($contingut);
    
    // Informació del fitxer
    $fitxerInfo = [
        'nom' => $fitxer['name'],
        'tipus' => $fitxer['type'],
        'mida' => $fitxer['size'],
        'data_pujada' => date('Y-m-d H:i:s')
    ];
    
    // Actualitzar la base de dades
    $stmt = $db->prepare("UPDATE portapapers SET fitxer_data = ?, fitxer_info = ?, data_modificacio = CURRENT_TIMESTAMP WHERE codi_hex = ?");
    $resultat = $stmt->execute([$base64, json_encode($fitxerInfo), $codi]);
    
    if (!$resultat) {
        throw new Exception('Error al guardar el fitxer');
    }
    
    echo json_encode([
        'success' => true,
        'missatge' => 'Fitxer pujat correctament',
        'fitxer_info' => $fitxerInfo
    ]);
}

function descarregarFitxer() {
    global $db;
    
    $codi = $_GET['codi'] ?? '';
    
    if (!validarCodiHex($codi)) {
        throw new Exception('Codi hexadecimal no vàlid');
    }
    
    // Normalitzar codi a majúscules
    $codi = strtoupper($codi);
    
    $stmt = $db->prepare("SELECT fitxer_data, fitxer_info FROM portapapers WHERE codi_hex = ?");
    $stmt->execute([$codi]);
    $resultat = $stmt->fetch();
    
    if (!$resultat || !$resultat['fitxer_data']) {
        throw new Exception('No hi ha cap fitxer pujat');
    }
    
    $fitxerInfo = json_decode($resultat['fitxer_info'], true);
    $contingut = base64_decode($resultat['fitxer_data']);
    
    // Configurar headers per descàrrega
    header('Content-Type: ' . $fitxerInfo['tipus']);
    header('Content-Disposition: attachment; filename="' . $fitxerInfo['nom'] . '"');
    header('Content-Length: ' . strlen($contingut));
    
    echo $contingut;
    exit;
}

function eliminarFitxer() {
    global $db;
    
    $codi = $_POST['codi'] ?? '';
    
    if (!validarCodiHex($codi)) {
        throw new Exception('Codi hexadecimal no vàlid');
    }
    
    // Normalitzar codi a majúscules
    $codi = strtoupper($codi);
    
    $stmt = $db->prepare("UPDATE portapapers SET fitxer_data = NULL, fitxer_info = NULL, data_modificacio = CURRENT_TIMESTAMP WHERE codi_hex = ?");
    $resultat = $stmt->execute([$codi]);
    
    if ($stmt->rowCount() === 0) {
        throw new Exception('Portapapers no trobat');
    }
    
    echo json_encode([
        'success' => true,
        'missatge' => 'Fitxer eliminat correctament'
    ]);
}
?>
