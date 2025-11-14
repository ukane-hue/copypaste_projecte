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
    
    // Obtenir informació del fitxer
    $stmt = $db->prepare("SELECT fitxer_data, fitxer_info FROM portapapers WHERE codi_hex = ?");
    $stmt->execute([$codi]);
    $resultat = $stmt->fetch();
    
    if (!$resultat || !$resultat['fitxer_data']) {
        // Si no hi ha fitxer, mostrar pàgina d'error
        mostrarErrorFitxer($codi);
        exit;
    }
    
    $fitxerInfo = json_decode($resultat['fitxer_info'], true);
    $contingut = base64_decode($resultat['fitxer_data']);
    
    // Configurar headers per descàrrega
    header('Content-Type: ' . $fitxerInfo['tipus']);
    header('Content-Disposition: attachment; filename="' . $fitxerInfo['nom'] . '"');
    header('Content-Length: ' . strlen($contingut));
    header('Cache-Control: no-cache, must-revalidate');
    header('Pragma: no-cache');
    
    echo $contingut;
    exit;
    
} catch (Exception $e) {
    http_response_code(500);
    die('Error de connexió a la base de dades');
}

function mostrarErrorFitxer($codi) {
    ?>
    <!DOCTYPE html>
    <html lang="ca">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Fitxer no trobat - Portapapers</title>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            
            body {
                font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
                background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
                color: #1e293b;
                line-height: 1.6;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 2rem 1rem;
            }
            
            .container {
                max-width: 500px;
                text-align: center;
            }
            
            .error-icon {
                width: 80px;
                height: 80px;
                margin: 0 auto 2rem;
                background: #fef2f2;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #dc2626;
            }
            
            .error-icon svg {
                width: 40px;
                height: 40px;
            }
            
            .error-title {
                font-size: 2rem;
                font-weight: 600;
                color: #1e293b;
                margin-bottom: 1rem;
            }
            
            .error-message {
                color: #64748b;
                margin-bottom: 2rem;
                font-size: 1.1rem;
            }
            
            .code-info {
                background: #f8fafc;
                border: 1px solid #e2e8f0;
                border-radius: 8px;
                padding: 1rem;
                margin-bottom: 2rem;
                font-family: 'Courier New', monospace;
                font-size: 1.25rem;
                font-weight: 600;
                color: #2563eb;
                letter-spacing: 0.05em;
            }
            
            .actions {
                display: flex;
                gap: 1rem;
                justify-content: center;
                flex-wrap: wrap;
            }
            
            .btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 0.75rem 1.5rem;
                border: none;
                border-radius: 8px;
                font-family: 'Montserrat', sans-serif;
                font-size: 0.95rem;
                font-weight: 500;
                cursor: pointer;
                transition: all 0.2s ease;
                text-decoration: none;
                gap: 0.5rem;
            }
            
            .btn-primary {
                background: #2563eb;
                color: white;
            }
            
            .btn-primary:hover {
                background: #1d4ed8;
                transform: translateY(-1px);
            }
            
            .btn-secondary {
                background: #6b7280;
                color: white;
            }
            
            .btn-secondary:hover {
                background: #4b5563;
                transform: translateY(-1px);
            }
            
            @media (max-width: 768px) {
                .actions {
                    flex-direction: column;
                }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="error-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="15" y1="9" x2="9" y2="15"></line>
                    <line x1="9" y1="9" x2="15" y2="15"></line>
                </svg>
            </div>
            
            <h1 class="error-title">Fitxer no trobat</h1>
            <p class="error-message">
                No hi ha cap fitxer compartit amb aquest codi o el fitxer ja no està disponible.
            </p>
            
            <div class="code-info">
                Codi: <?php echo htmlspecialchars($codi); ?>
            </div>
            
            <div class="actions">
                <a href="index.php" class="btn btn-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9,22 9,12 15,12 15,22"></polyline>
                    </svg>
                    Tornar a l'aplicació
                </a>
                <button class="btn btn-secondary" onclick="window.location.reload()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="23,4 23,10 17,10"></polyline>
                        <polyline points="1,20 1,14 7,14"></polyline>
                        <path d="M20.49,9A9,9 0 0,0 5.64,5.64L1,10m22,4l-4.64,4.36A9,9 0 0,1 3.51,15"></path>
                    </svg>
                    Actualitzar
                </button>
            </div>
        </div>
    </body>
    </html>
    <?php
}
?>
