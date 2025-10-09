<?php
// Neteja automàtica de portapapers antics (més de 2 hores sense modificar)
require_once '../config/config.php';
$registresEliminats = netejarPortapapersAntics();
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portapapers - Comparteix text entre dispositius</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <h1 class="title">Copy&Paste Remot</h1>
            <p class="subtitle">Comparteix text i fitxers entre dispositius remots de forma instantània</p>
            <div class="how-it-works">
                <div class="steps">
                    <div class="step">
                        <span class="step-number">1</span>
                        <p>Crea un portapapers o connecta't amb un codi de 6 dígits</p>
                    </div>
                    <div class="step">
                        <span class="step-number">2</span>
                        <p>Introdueix el text/fitxer i es sincronitzarà automàticament</p>
                    </div>
                    <div class="step">
                        <span class="step-number">3</span>
                        <p>Comparteix el codi per poder obtenir el text o el fitxer</p>
                    </div>
                </div>
            </div>
        </header>

        <main class="main-content">
            <!-- Secció per crear nou portapapers -->
            <section class="section" id="create-section">
                <div class="card">
                    <h2 class="card-title">Crear nou portapapers</h2>
                    <p class="card-description">Genera un codi únic de 6 dígits per compartir text entre dispositius</p>
                    <div class="info-tip">
                        <p>💡 Després de crear el portapapers, comparteix el codi amb altres persones</p>
                    </div>
                    <button class="btn btn-primary" id="create-btn">Crear portapapers</button>
                </div>
            </section>

            <!-- Secció per connectar a portapapers existent -->
            <section class="section" id="connect-section">
                <div class="card">
                    <h2 class="card-title">Connectar a portapapers</h2>
                    <p class="card-description">Introdueix el codi de 6 dígits per accedir a un portapapers existent</p>
                    <div class="info-tip">
                        <p>🔗 Demana el codi a qui ha creat el portapapers o utilitza un codi que ja coneixes</p>
                    </div>
                    <div class="input-group">
                        <input type="text" id="connect-code" placeholder="Ex: A1B2C3" maxlength="6" class="input">
                        <button class="btn btn-secondary" id="connect-btn">Connectar</button>
                    </div>
                </div>
            </section>

            <!-- Secció del portapapers actiu -->
            <section class="section" id="portapapers-section" style="display: none;">
                <div class="card">
                    <div class="portapapers-header">
                        <h2 class="card-title">Portapapers actiu</h2>
                        <div class="code-display">
                            <span class="code-label">Codi:</span>
                            <span class="code-value" id="active-code"></span>
                            <button class="btn-copy" id="copy-code-btn" title="Copiar codi">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <div class="textarea-container">
                        <div class="info-tip">
                            <p>✏️ El text es sincronitzarà automàticament amb tots els dispositius connectats</p>
                        </div>
                        <textarea id="portapapers-content" placeholder="Escriu el teu text aquí..."></textarea>
                        <div class="textarea-footer">
                            <span class="char-count" id="char-count">0 caràcters</span>
                            <div class="typing-indicator" id="typing-indicator" style="display: none;">
                                <span class="typing-dots">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </span>
                                <span class="typing-text">Algú està escrivint...</span>
                            </div>
                            <span class="status" id="status">Desconnectat</span>
                        </div>
                    </div>
                    
                    <!-- Secció de fitxers -->
                    <div class="files-section" id="files-section">
                        <h3 class="files-title">Fitxers compartits</h3>
                        <div class="info-tip">
                            <p>📁 Puja documents, imatges o qualsevol fitxer per compartir-lo amb tots els dispositius</p>
                        </div>
                        <div class="file-upload-area" id="file-upload-area">
                            <div class="upload-placeholder" id="upload-placeholder">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="7,10 12,15 17,10"></polyline>
                                    <line x1="12" y1="15" x2="12" y2="3"></line>
                                </svg>
                                <p>Arrossega un fitxer aquí o fes clic per seleccionar</p>
                                <input type="file" id="file-input" accept="image/*,application/pdf,text/*,.doc,.docx,.xls,.xlsx,.zip,.rar" style="display: none;">
                            </div>
                            <div class="file-info" id="file-info" style="display: none;">
                                <div class="file-preview">
                                    <div class="file-icon" id="file-icon"></div>
                                    <div class="file-details">
                                        <div class="file-name" id="file-name"></div>
                                        <div class="file-size" id="file-size"></div>
                                        <div class="file-date" id="file-date"></div>
                                    </div>
                                </div>
                                <div class="file-actions">
                                    <button class="btn btn-primary btn-sm" id="download-file-btn">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                            <polyline points="7,10 12,15 17,10"></polyline>
                                            <line x1="12" y1="15" x2="12" y2="3"></line>
                                        </svg>
                                        Descarregar
                                    </button>
                                    <button class="btn btn-danger btn-sm" id="delete-file-btn">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="3,6 5,6 21,6"></polyline>
                                            <path d="M19,6v14a2,2 0 0,1 -2,2H7a2,2 0 0,1 -2,-2V6m3,0V4a2,2 0 0,1 2,-2h4a2,2 0 0,1 2,2v2"></path>
                                        </svg>
                                        Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="actions">
                        <button class="btn btn-danger" id="disconnect-btn">Desconnectar</button>
                        <button class="btn btn-secondary" id="new-portapapers-btn">Nou portapapers</button>
                    </div>
                </div>
            </section>
        </main>

        <!-- Notificacions -->
        <div class="notifications" id="notifications"></div>
    </div>

    <!-- Peu de pàgina -->
    <footer class="footer">
        <div class="footer-content">
            <p>&copy; 2024 INS Mollerussa - Dep. Informàtica. Tots els drets reservats.</p>
            <p class="license">Llicència MIT - Codi obert i lliure</p>
        </div>
    </footer>

    <script src="../assets/js/script.js"></script>
</body>
</html>
