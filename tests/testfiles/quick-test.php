<?php
/**
 * Test Ràpid - Copy&Paste App
 * 
 * Script de test bàsic per verificar funcionalitats essencials
 * Ús: php quick-test.php
 */

echo "🚀 TEST RÀPID - Copy&Paste App\n";
echo "===============================\n\n";

$errors = 0;

// Test 1: Connexió a la base de dades
echo "1. Testant connexió a la base de dades... ";
try {
    // Detectar si estem executant des de la carpeta tests o des del directori arrel
    $configPath = file_exists('../config/config.php') ? '../config/config.php' : 'config/config.php';
    require_once $configPath;
    $db = Database::getInstance()->getConnection();
    echo "✅ OK\n";
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    $errors++;
}

// Test 2: Variables d'entorn
echo "2. Testant variables d'entorn... ";
if (defined('DEBUG') && defined('HEX_LENGTH') && defined('REFRESH_INTERVAL')) {
    echo "✅ OK\n";
} else {
    echo "❌ ERROR: Variables d'entorn no definides\n";
    $errors++;
}

// Test 3: API endpoints
echo "3. Testant API endpoints... ";
try {
    // Test crear portapapers
    $_SERVER['REQUEST_METHOD'] = 'POST';
    $_GET['action'] = 'crear';
    $_POST['contingut'] = 'Test ràpid';
    
    ob_start();
    $apiPath = file_exists('../public/api.php') ? '../public/api.php' : 'public/api.php';
    
    // Definir mode de testing per evitar headers
    define('TESTING_MODE', true);
    
    include $apiPath;
    $output = ob_get_contents();
    ob_end_clean();
    
    $response = json_decode($output, true);
    if ($response && $response['success']) {
        $testCode = $response['codi'];
        echo "✅ OK\n";
        
        // Netejar dades de test
        $stmt = $db->prepare("DELETE FROM portapapers WHERE codi_hex = ?");
        $stmt->execute([$testCode]);
    } else {
        echo "❌ ERROR: API no respon correctament\n";
        $errors++;
    }
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    $errors++;
}

// Test 4: Funcions de neteja
echo "4. Testant funcions de neteja... ";
try {
    $resultat = netejarPortapapersAntics();
    if (is_numeric($resultat)) {
        echo "✅ OK\n";
    } else {
        echo "❌ ERROR: Funció de neteja no retorna número\n";
        $errors++;
    }
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    $errors++;
}

// Test 5: Pàgina d'error
echo "5. Testant pàgina d'error... ";
$errorPath = file_exists('../public/error.php') ? '../public/error.php' : 'public/error.php';
if (file_exists($errorPath)) {
    echo "✅ OK\n";
} else {
    echo "❌ ERROR: Pàgina d'error no trobada\n";
    $errors++;
}

// Resultats
echo "\n===============================\n";
if ($errors === 0) {
    echo "🎉 TOTS ELS TESTS HAN PASSAT!\n";
    echo "✅ L'aplicació funciona correctament.\n";
} else {
    echo "⚠️  $errors test(s) han fallat.\n";
    echo "❌ Revisa la configuració i els errors anteriors.\n";
}

echo "\n";
?>
