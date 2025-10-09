<?php
// Carregar variables d'entorn des del fitxer .env
if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}

// Configuració de la base de dades des de variables d'entorn
define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'portapapers');
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');

// Configuració de l'aplicació
define('HEX_LENGTH', (int)($_ENV['HEX_LENGTH'] ?? 6));
define('REFRESH_INTERVAL', (int)($_ENV['REFRESH_INTERVAL'] ?? 2000)); // 2 segons

class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        try {
            $this->connection = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            die("Error de connexió: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
}

// Funció per generar codi hexadecimal aleatori
function generarCodiHex($longitud = HEX_LENGTH) {
    return strtoupper(substr(bin2hex(random_bytes($longitud / 2)), 0, $longitud));
}

// Funció per validar codi hexadecimal
function validarCodiHex($codi) {
    return preg_match('/^[0-9A-Fa-f]{6}$/', $codi);
}

// Funció per netejar portapapers antics (més de 2 hores sense modificar)
function netejarPortapapersAntics() {
    try {
        $db = Database::getInstance()->getConnection();
        
        // Eliminar registres que no s'han modificat en més de 2 hores
        $stmt = $db->prepare("DELETE FROM portapapers WHERE data_modificacio < DATE_SUB(NOW(), INTERVAL 2 HOUR)");
        $resultat = $stmt->execute();
        
        $registresEliminats = $stmt->rowCount();
        
        // Log de l'activitat (opcional)
        if ($registresEliminats > 0) {
            error_log("Portapapers: S'han eliminat $registresEliminats registres antics");
        }
        
        return $registresEliminats;
    } catch (Exception $e) {
        error_log("Error en neteja automàtica: " . $e->getMessage());
        return 0;
    }
}

// Funció per obtenir estadístiques de neteja
function obtenirEstadistiquesNeteja() {
    try {
        $db = Database::getInstance()->getConnection();
        
        // Comptar registres que seran eliminats
        $stmt = $db->prepare("SELECT COUNT(*) as total FROM portapapers WHERE data_modificacio < DATE_SUB(NOW(), INTERVAL 2 HOUR)");
        $stmt->execute();
        $resultat = $stmt->fetch();
        
        return [
            'registres_antics' => $resultat['total'],
            'data_neteja' => date('Y-m-d H:i:s')
        ];
    } catch (Exception $e) {
        return [
            'registres_antics' => 0,
            'error' => $e->getMessage()
        ];
    }
}
?>
