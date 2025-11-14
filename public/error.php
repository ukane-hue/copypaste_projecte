<?php
// Pàgina d'error genèrica per quan debug=false
require_once '../config/config.php';
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - Portapapers</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        .error-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            text-align: center;
            padding: 20px;
        }
        
        .error-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: #e74c3c;
        }
        
        .error-title {
            font-size: 2.5rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 1rem;
        }
        
        .error-message {
            font-size: 1.2rem;
            color: #7f8c8d;
            margin-bottom: 2rem;
            max-width: 600px;
            line-height: 1.6;
        }
        
        .error-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: #3498db;
            color: white;
        }
        
        .btn-primary:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background: #95a5a6;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #7f8c8d;
            transform: translateY(-2px);
        }
        
        .error-details {
            margin-top: 2rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #e74c3c;
            max-width: 600px;
            text-align: left;
        }
        
        .error-details h4 {
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }
        
        .error-details p {
            color: #7f8c8d;
            margin: 0;
        }
        
        @media (max-width: 768px) {
            .error-title {
                font-size: 2rem;
            }
            
            .error-message {
                font-size: 1rem;
            }
            
            .error-actions {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 100%;
                max-width: 300px;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">⚠️</div>
        <h1 class="error-title">S'ha produït un error</h1>
        <p class="error-message">
            Ho sentim, però s'ha produït un error inesperat. El nostre equip ha estat notificat i treballarà per a resoldre el problema.
        </p>
        
        <div class="error-actions">
            <a href="/" class="btn btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9,22 9,12 15,12 15,22"></polyline>
                </svg>
                Tornar a l'inici
            </a>
            <button onclick="history.back()" class="btn btn-secondary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7"></path>
                </svg>
                Tornar enrere
            </button>
        </div>
        <div class="error-actions-text">
            
        </div>
        
    </div>
    
    <script>
        // Auto-redirect després de 30 segons si l'usuari no fa res
        setTimeout(function() {
            window.location.href = '/';
        }, 30000);
        
        // Mostrar comptador de redirecció
        let countdown = 30;
        const countdownElement = document.createElement('div');
        countdownElement.style.cssText = 'margin-top: 1rem; color: #7f8c8d; font-size: 0.9rem;';
        countdownElement.textContent = `Redirecció automàtica en ${countdown} segons...`;
        document.querySelector('.error-actions-text').appendChild(countdownElement);
        
        const interval = setInterval(function() {
            countdown--;
            countdownElement.textContent = `Redirecció automàtica en ${countdown} segons...`;
            if (countdown <= 0) {
                clearInterval(interval);
            }
        }, 1000);
    </script>
</body>
</html>
