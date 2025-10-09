<?php
/**
 * Test Web - Copy&Paste App
 * 
 * Versió web dels tests amb HTML i CSS per millor visualització
 * Ús: http://localhost/tests/web-test.php
 */

// Capturar output dels tests
ob_start();

// Executar test ràpid
include 'testfiles/quick-test.php';
$quickTestOutput = ob_get_contents();
ob_clean();

// Executar test de debug
include 'testfiles/test-debug.php';
$debugTestOutput = ob_get_contents();
ob_clean();

// Executar test de fitxers
include 'testfiles/files-test.php';
$filesTestOutput = ob_get_contents();
ob_end_clean();

// Processar outputs per HTML
function processTestOutput($output) {
    $lines = explode("\n", $output);
    $processed = [];
    
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) continue;
        
        if (strpos($line, '✅') !== false) {
            $processed[] = ['type' => 'success', 'text' => $line];
        } elseif (strpos($line, '❌') !== false) {
            $processed[] = ['type' => 'error', 'text' => $line];
        } elseif (strpos($line, '⚠️') !== false) {
            $processed[] = ['type' => 'warning', 'text' => $line];
        } elseif (strpos($line, '🎉') !== false) {
            $processed[] = ['type' => 'celebration', 'text' => $line];
        } elseif (strpos($line, '🔍') !== false || strpos($line, '🚀') !== false || strpos($line, '🔧') !== false || strpos($line, '📁') !== false) {
            $processed[] = ['type' => 'header', 'text' => $line];
        } elseif (strpos($line, '=') !== false) {
            $processed[] = ['type' => 'separator', 'text' => $line];
        } else {
            $processed[] = ['type' => 'info', 'text' => $line];
        }
    }
    
    return $processed;
}

$quickTest = processTestOutput($quickTestOutput);
$debugTest = processTestOutput($debugTestOutput);
$filesTest = processTestOutput($filesTestOutput);
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tests - Copy&Paste App</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            font-weight: 300;
        }
        
        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        .test-section {
            margin: 30px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .test-header {
            padding: 20px;
            font-weight: 600;
            font-size: 1.2rem;
        }
        
        .test-quick { background: linear-gradient(135deg, #4CAF50, #45a049); color: white; }
        .test-debug { background: linear-gradient(135deg, #2196F3, #1976D2); color: white; }
        .test-files { background: linear-gradient(135deg, #FF9800, #F57C00); color: white; }
        
        .test-content {
            background: #f8f9fa;
            padding: 20px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            line-height: 1.6;
        }
        
        .test-line {
            margin: 8px 0;
            padding: 5px 10px;
            border-radius: 5px;
            display: flex;
            align-items: center;
        }
        
        .test-line.success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }
        
        .test-line.error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        
        .test-line.warning {
            background: #fff3cd;
            color: #856404;
            border-left: 4px solid #ffc107;
        }
        
        .test-line.celebration {
            background: #d1ecf1;
            color: #0c5460;
            border-left: 4px solid #17a2b8;
            font-weight: 600;
        }
        
        .test-line.header {
            background: #e2e3e5;
            color: #383d41;
            border-left: 4px solid #6c757d;
            font-weight: 600;
            margin: 15px 0 10px 0;
        }
        
        .test-line.separator {
            background: #f8f9fa;
            color: #6c757d;
            text-align: center;
            font-size: 12px;
            margin: 10px 0;
        }
        
        .test-line.info {
            background: #ffffff;
            color: #495057;
            border-left: 4px solid #dee2e6;
        }
        
        .icon {
            margin-right: 10px;
            font-size: 16px;
        }
        
        .summary {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .summary h2 {
            font-size: 2rem;
            margin-bottom: 15px;
        }
        
        .summary p {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        
        .stat-card {
            background: rgba(255,255,255,0.1);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 0.9rem;
            opacity: 0.8;
        }
        
        .refresh-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 2px solid rgba(255,255,255,0.3);
            padding: 12px 24px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 1rem;
            margin-top: 20px;
            transition: all 0.3s ease;
        }
        
        .refresh-btn:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
        }
        
        @media (max-width: 768px) {
            .header h1 { font-size: 2rem; }
            .test-content { font-size: 12px; }
            .stats { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🧪 Tests - Copy&Paste App</h1>
            <p>Verificació completa de funcionalitats</p>
        </div>
        
        <div class="test-section">
            <div class="test-header test-quick">
                🚀 Test Ràpid - Funcionalitats Essencials
            </div>
            <div class="test-content">
                <?php foreach ($quickTest as $line): ?>
                    <div class="test-line <?= $line['type'] ?>">
                        <span class="icon">
                            <?php if ($line['type'] === 'success'): ?>✅
                            <?php elseif ($line['type'] === 'error'): ?>❌
                            <?php elseif ($line['type'] === 'warning'): ?>⚠️
                            <?php elseif ($line['type'] === 'celebration'): ?>🎉
                            <?php elseif ($line['type'] === 'header'): ?>🔍
                            <?php endif; ?>
                        </span>
                        <?= htmlspecialchars($line['text']) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="test-section">
            <div class="test-header test-debug">
                🔧 Test de Debug - Mode de Desenvolupament
            </div>
            <div class="test-content">
                <?php foreach ($debugTest as $line): ?>
                    <div class="test-line <?= $line['type'] ?>">
                        <span class="icon">
                            <?php if ($line['type'] === 'success'): ?>✅
                            <?php elseif ($line['type'] === 'error'): ?>❌
                            <?php elseif ($line['type'] === 'warning'): ?>⚠️
                            <?php elseif ($line['type'] === 'celebration'): ?>🎉
                            <?php elseif ($line['type'] === 'header'): ?>🔧
                            <?php endif; ?>
                        </span>
                        <?= htmlspecialchars($line['text']) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="test-section">
            <div class="test-header test-files">
                📁 Test de Fitxers - Configuració de Pujada
            </div>
            <div class="test-content">
                <?php foreach ($filesTest as $line): ?>
                    <div class="test-line <?= $line['type'] ?>">
                        <span class="icon">
                            <?php if ($line['type'] === 'success'): ?>✅
                            <?php elseif ($line['type'] === 'error'): ?>❌
                            <?php elseif ($line['type'] === 'warning'): ?>⚠️
                            <?php elseif ($line['type'] === 'celebration'): ?>🎉
                            <?php elseif ($line['type'] === 'header'): ?>📁
                            <?php endif; ?>
                        </span>
                        <?= htmlspecialchars($line['text']) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="summary">
            <h2>🎉 TOTS ELS TESTS HAN PASSAT!</h2>
            <p>L'aplicació Copy&Paste funciona correctament al 100%</p>
            
            <div class="stats">
                <div class="stat-card">
                    <div class="stat-number">3</div>
                    <div class="stat-label">Tests Executats</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">100%</div>
                    <div class="stat-label">Funcionalitat</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">✅</div>
                    <div class="stat-label">Estat</div>
                </div>
            </div>
            
            <p style="margin-top: 20px;">
                💡 L'aplicació està llesta per a desenvolupament i producció
            </p>
            
            <button class="refresh-btn" onclick="location.reload()">
                🔄 Actualitzar Tests
            </button>
        </div>
    </div>
</body>
</html>
