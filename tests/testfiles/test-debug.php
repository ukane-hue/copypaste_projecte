<?php
/**
 * Test de Funcionalitat Debug - Copy&Paste App
 * 
 * Aquest script testa específicament la funcionalitat de debug
 * Ús: php test-debug.php
 */

echo "🔧 TEST DE FUNCIONALITAT DEBUG\n";
echo "===============================\n\n";

// Detectar automàticament la ubicació del fitxer config
$configPath = file_exists('../config/config.php') ? '../config/config.php' : 'config/config.php';
require_once $configPath;

// Test 1: Verificar configuració actual (mode desenvolupament)
echo "1. Testant configuració actual (mode desenvolupament)...\n";

if (DEBUG === true) {
    echo "   ✅ DEBUG constant = true (mode desenvolupament)\n";
} else {
    echo "   ❌ ERROR: DEBUG hauria de ser true en mode desenvolupament\n";
}

if (error_reporting() !== 0) {
    echo "   ✅ Error reporting actiu (correcte per desenvolupament)\n";
} else {
    echo "   ❌ ERROR: Error reporting hauria d'estar actiu en desenvolupament\n";
}

if (ini_get('display_errors') == 1) {
    echo "   ✅ Display errors actiu (correcte per desenvolupament)\n";
} else {
    echo "   ❌ ERROR: Display errors hauria d'estar actiu en desenvolupament\n";
}

echo "\n";

// Test 2: Simular mode producció (sense canviar la configuració real)
echo "2. Testant gestió d'errors (simulant mode producció)...\n";

// Crear un gestor d'errors temporal per simular mode producció
$originalErrorHandler = set_error_handler(function($severity, $message, $file, $line) {
    echo "   ✅ Error capturat correctament: $message\n";
    return true; // No mostrar l'error, només capturar-lo
});

// Provocar error per testar la captura
$undefinedVariable = $nonExistentVariable;

// Restaurar gestor d'errors original
restore_error_handler();

echo "\n";

// Test 3: Pàgina d'error
echo "3. Testant pàgina d'error...\n";
$errorPath = file_exists('../public/error.php') ? '../public/error.php' : 'public/error.php';
if (file_exists($errorPath)) {
    echo "   ✅ Pàgina d'error existeix\n";
    
    // Verificar contingut bàsic
    $errorContent = file_get_contents($errorPath);
    if (strpos($errorContent, 'S\'ha produït un error') !== false) {
        echo "   ✅ Contingut de pàgina d'error correcte\n";
    } else {
        echo "   ❌ ERROR: Contingut de pàgina d'error incorrecte\n";
    }
} else {
    echo "   ❌ ERROR: Pàgina d'error no trobada\n";
}

echo "\n";

// Test 4: Logs d'error
echo "4. Testant logs d'error...\n";
$logFile = ini_get('error_log');
$logErrors = ini_get('log_errors');

if ($logErrors == 1) {
    echo "   ✅ Log d'errors activat correctament\n";
    
    if ($logFile && file_exists($logFile)) {
        echo "   ✅ Fitxer de log d'errors existeix: $logFile\n";
    } else {
        echo "   ℹ️  INFO: Fitxer de log es crearà automàticament quan hi hagi errors\n";
        echo "   ℹ️  Ruta configurada: " . ($logFile ?: 'sistema per defecte') . "\n";
    }
} else {
    echo "   ⚠️  WARNING: Log d'errors no està activat\n";
}

echo "\n";

// Test 5: Verificar funcions de gestió d'errors
echo "5. Testant funcions de gestió d'errors...\n";

// Verificar que les funcions existeixen
if (function_exists('gestionarError')) {
    echo "   ✅ Funció gestionarError() disponible\n";
} else {
    echo "   ❌ ERROR: Funció gestionarError() no trobada\n";
}

if (function_exists('redirigirError')) {
    echo "   ✅ Funció redirigirError() disponible\n";
} else {
    echo "   ❌ ERROR: Funció redirigirError() no trobada\n";
}

echo "\n===============================\n";
echo "🎯 TEST DE DEBUG COMPLETAT\n";
echo "Revisa els resultats anteriors per assegurar-te que tot funciona correctament.\n\n";
?>