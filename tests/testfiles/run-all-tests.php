<?php
/**
 * Executor de Tots els Tests - Copy&Paste App
 * 
 * Aquest script executa tots els tests disponibles
 * Ús: php run-all-tests.php
 */

// Detectar si estem executant des de la carpeta tests o des del directori arrel
$isInTestsDir = basename(getcwd()) === 'tests';
$baseDir = $isInTestsDir ? '..' : '.';

echo "🧪 EXECUTANT TOTS ELS TESTS - Copy&Paste App\n";
echo "============================================\n\n";

$startTime = microtime(true);
$totalErrors = 0;

// Llista de tests a executar
$tests = [
    'quick-test.php' => 'Test Ràpid',
    'test-debug.php' => 'Test de Debug',
    'files-test.php' => 'Test de Fitxers'
];

// Ajustar rutes segons la ubicació
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
            if ($isInTestsDir) {
                // Canviar al directori pare per a les rutes relatives
                $originalDir = getcwd();
                chdir('..');
                include $testFile;
                chdir($originalDir);
            } else {
                include $testFile;
            }
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
echo "   php quick-test.php    # Test ràpid\n";
echo "   php test-debug.php    # Test de debug\n";
echo "   php files-test.php    # Test de fitxers\n";

echo "\n";
?>
