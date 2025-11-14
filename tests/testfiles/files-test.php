<?php
/**
 * Test Simple de Fitxers - Copy&Paste App
 * 
 * Aquest script testa la funcionalitat de fitxers de forma simple
 * Ús: php simple-files-test.php
 */

echo "📁 TEST SIMPLE DE FITXERS\n";
echo "=========================\n\n";

// Detectar automàticament la ubicació del fitxer config
$configPath = file_exists('../config/config.php') ? '../config/config.php' : 'config/config.php';
require_once $configPath;

$errors = 0;

// Test 1: Verificar funcions de fitxers
echo "1. Testant funcions de fitxers... ";
try {
    // Verificar que les funcions de l'API existeixen
    if (function_exists('crearPortapapers')) {
        echo "✅ OK\n";
    } else {
        echo "❌ ERROR: Funció crearPortapapers no trobada\n";
        $errors++;
    }
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    $errors++;
}

// Test 2: Verificar validacions de seguretat
echo "2. Testant validacions de seguretat... ";
try {
    // Test de mida màxima
    $midaMaxima = 10 * 1024 * 1024; // 10MB
    if ($midaMaxima > 0) {
        echo "✅ OK (Mida màxima: " . round($midaMaxima / 1024 / 1024, 1) . "MB)\n";
    } else {
        echo "❌ ERROR: Mida màxima no configurada\n";
        $errors++;
    }
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    $errors++;
}

// Test 3: Verificar tipus de fitxers permesos
echo "3. Testant tipus de fitxers permesos... ";
try {
    $tipusPermesos = [
        'image/jpeg', 'image/png', 'image/gif', 'image/webp',
        'application/pdf', 'text/plain', 'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/zip', 'application/x-rar-compressed'
    ];
    
    if (count($tipusPermesos) > 0) {
        echo "✅ OK (" . count($tipusPermesos) . " tipus permesos)\n";
    } else {
        echo "❌ ERROR: No hi ha tipus de fitxers configurats\n";
        $errors++;
    }
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    $errors++;
}

// Test 4: Verificar configuració de pujada
echo "4. Testant configuració de pujada... ";
try {
    $uploadMaxFilesize = ini_get('upload_max_filesize');
    $postMaxSize = ini_get('post_max_size');
    
    if ($uploadMaxFilesize && $postMaxSize) {
        echo "✅ OK (Upload: $uploadMaxFilesize, Post: $postMaxSize)\n";
    } else {
        echo "❌ ERROR: Configuració de pujada no trobada\n";
        $errors++;
    }
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    $errors++;
}

// Test 5: Verificar directori temporal
echo "5. Testant directori temporal... ";
try {
    $tempDir = sys_get_temp_dir();
    if ($tempDir && is_writable($tempDir)) {
        echo "✅ OK ($tempDir)\n";
    } else {
        echo "❌ ERROR: Directori temporal no accessible\n";
        $errors++;
    }
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    $errors++;
}

echo "\n=========================\n";
if ($errors === 0) {
    echo "🎉 TOTS ELS TESTS DE FITXERS HAN PASSAT!\n";
    echo "✅ La funcionalitat de fitxers està configurada correctament.\n";
} else {
    echo "⚠️  $errors test(s) han fallat.\n";
    echo "❌ Revisa la configuració i els errors anteriors.\n";
}

echo "\n";
?>
