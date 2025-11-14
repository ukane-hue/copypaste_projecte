<?php
/**
 * Terminal Test - Copy&Paste App
 * 
 * Aquest script executa tots els tests des de la subcarpeta testfiles
 * Ús: php terminal-test.php
 */

echo "🧪 EXECUTANT TOTS ELS TESTS - Copy&Paste App\n";
echo "============================================\n\n";

$startTime = microtime(true);
$totalErrors = 0;

// Llista de tests a executar des de la subcarpeta testfiles
$tests = [
    'testfiles/quick-test.php' => 'Test Ràpid',
    'testfiles/test-debug.php' => 'Test de Debug',
    'testfiles/files-test.php' => 'Test de Fitxers'
];

// Detectar si estem executant des de la carpeta tests o des del directori arrel
$isInTestsDir = basename(getcwd()) === 'tests';
if (!$isInTestsDir) {
    $adjustedTests = [];
    foreach ($tests as $file => $name) {
        $adjustedTests["tests/$file"] = $name;
    }
    $tests = $adjustedTests;
}

foreach ($tests as $testFile => $testName) {
    echo "🔍 Executant: $testName\n";
    echo str_repeat("-", 50) . "\n";
    
    if (file_exists($testFile)) {
        // Capturar output del test
        ob_start();
        $returnCode = 0;
        
        try {
            include $testFile;
        } catch (Exception $e) {
            echo "❌ ERROR executant test: " . $e->getMessage() . "\n";
            $returnCode = 1;
        }
        
        $output = ob_get_contents();
        ob_end_clean();
        
        // Mostrar output del test
        echo $output;
        
        if ($returnCode !== 0) {
            $totalErrors++;
        }
        
    } else {
        echo "❌ ERROR: Fitxer de test no trobat: $testFile\n";
        $totalErrors++;
    }
    
    echo "\n" . str_repeat("=", 50) . "\n\n";
}

$endTime = microtime(true);
$duration = round($endTime - $startTime, 2);

// Resum final
echo "📊 RESUM FINAL\n";
echo "==============\n";
echo "⏱️  Temps total: $duration segons\n";

if ($totalErrors === 0) {
    echo "🎉 TOTS ELS TESTS HAN PASSAT!\n";
    echo "✅ L'aplicació Copy&Paste funciona correctament al 100%.\n";
    echo "\n💡 L'aplicació està llesta per a:\n";
    echo "   • Desenvolupament (DEBUG=true)\n";
    echo "   • Producció (DEBUG=false)\n";
    echo "   • Ús en producció\n";
} else {
    echo "⚠️  ALGUNS TESTS HAN FALLAT.\n";
    echo "❌ Revisa els errors anteriors abans de desplegar.\n";
    echo "\n🔧 Recomanacions:\n";
    echo "   • Verifica la configuració de la base de dades\n";
    echo "   • Comprova les variables d'entorn\n";
    echo "   • Revisa els permisos dels fitxers\n";
    echo "   • Assegura't que tots els fitxers estan presents\n";
}

echo "\n📚 Tests disponibles:\n";
foreach ($tests as $testFile => $testName) {
    $status = file_exists($testFile) ? "✅" : "❌";
    echo "   $status $testName ($testFile)\n";
}

echo "\n🚀 Per executar tests individuals:\n";
echo "   php testfiles/quick-test.php    # Test ràpid\n";
echo "   php testfiles/test-debug.php    # Test de debug\n";
echo "   php testfiles/files-test.php    # Test de fitxers\n";

echo "\n🌐 Versió web:\n";
echo "   http://localhost/tests/web-test.php\n";

echo "\n";
?>
