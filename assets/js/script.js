class PortapapersApp {
    constructor() {
        this.activeCode = null;
        this.isConnected = false;
        this.lastContent = '';
        this.syncInterval = null;
        this.typingTimeout = null;
        this.typingApiTimeout = null;
        this.isTyping = false;
        this.initializeElements();
        this.bindEvents();
    }

    initializeElements() {
        // Seccions
        this.createSection = document.getElementById('create-section');
        this.connectSection = document.getElementById('connect-section');
        this.portapapersSection = document.getElementById('portapapers-section');
        
        // Botons
        this.createBtn = document.getElementById('create-btn');
        this.connectBtn = document.getElementById('connect-btn');
        this.disconnectBtn = document.getElementById('disconnect-btn');
        this.newPortapapersBtn = document.getElementById('new-portapapers-btn');
        this.copyCodeBtn = document.getElementById('copy-code-btn');
        
        // Inputs
        this.connectCodeInput = document.getElementById('connect-code');
        this.portapapersContent = document.getElementById('portapapers-content');
        
        // Display elements
        this.activeCodeDisplay = document.getElementById('active-code');
        this.charCount = document.getElementById('char-count');
        this.status = document.getElementById('status');
        this.typingIndicator = document.getElementById('typing-indicator');
        this.notifications = document.getElementById('notifications');
        
        // Elements de fitxers
        this.filesSection = document.getElementById('files-section');
        this.fileUploadArea = document.getElementById('file-upload-area');
        this.uploadPlaceholder = document.getElementById('upload-placeholder');
        this.fileInfo = document.getElementById('file-info');
        this.fileInput = document.getElementById('file-input');
        this.fileIcon = document.getElementById('file-icon');
        this.fileName = document.getElementById('file-name');
        this.fileSize = document.getElementById('file-size');
        this.fileDate = document.getElementById('file-date');
        this.downloadFileBtn = document.getElementById('download-file-btn');
        this.deleteFileBtn = document.getElementById('delete-file-btn');
    }

    bindEvents() {
        // Crear portapapers
        this.createBtn.addEventListener('click', () => this.createPortapapers());
        
        // Connectar a portapapers
        this.connectBtn.addEventListener('click', () => this.connectToPortapapers());
        this.connectCodeInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') this.connectToPortapapers();
        });
        
        // Gestió del contingut
        this.portapapersContent.addEventListener('input', () => this.handleContentChange());
        
        // Accions
        this.disconnectBtn.addEventListener('click', () => this.disconnect());
        this.newPortapapersBtn.addEventListener('click', () => this.createPortapapers());
        this.copyCodeBtn.addEventListener('click', () => this.copyCode());
        
        // Validació del codi d'entrada
        this.connectCodeInput.addEventListener('input', (e) => {
            e.target.value = e.target.value.toUpperCase().replace(/[^A-F0-9]/g, '');
        });
        
        // Events de fitxers
        this.fileUploadArea.addEventListener('click', () => this.fileInput.click());
        this.fileInput.addEventListener('change', (e) => this.handleFileSelect(e));
        this.downloadFileBtn.addEventListener('click', () => this.downloadFile());
        this.deleteFileBtn.addEventListener('click', () => this.deleteFile());
        
        // Drag and drop
        this.fileUploadArea.addEventListener('dragover', (e) => this.handleDragOver(e));
        this.fileUploadArea.addEventListener('dragleave', (e) => this.handleDragLeave(e));
        this.fileUploadArea.addEventListener('drop', (e) => this.handleDrop(e));
    }

    async createPortapapers() {
        try {
            this.showLoading(this.createBtn, 'Creant...');
            
            const response = await fetch('api.php?action=crear', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'contingut='
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.activeCode = data.codi;
                this.showPortapapersSection();
                this.startSync();
                this.showNotification('Portapapers creat correctament', 'success');
            } else {
                throw new Error(data.error || 'Error desconegut');
            }
        } catch (error) {
            this.showNotification('Error al crear portapapers: ' + error.message, 'error');
        } finally {
            this.hideLoading(this.createBtn, 'Crear portapapers');
        }
    }

    async connectToPortapapers() {
        const code = this.connectCodeInput.value.trim().toUpperCase();
        
        if (!code || code.length !== 6) {
            this.showNotification('Introdueix un codi vàlid de 6 dígits', 'warning');
            return;
        }

        try {
            this.showLoading(this.connectBtn, 'Connectant...');
            
            const response = await fetch(`api.php?action=verificar&codi=${code}`);
            const data = await response.json();
            
            if (data.success && data.existeix) {
                this.activeCode = code;
                this.showPortapapersSection();
                await this.loadContent();
                this.startSync();
                this.showNotification('Connectat correctament', 'success');
            } else {
                throw new Error('Portapapers no trobat');
            }
        } catch (error) {
            this.showNotification('Error al connectar: ' + error.message, 'error');
        } finally {
            this.hideLoading(this.connectBtn, 'Connectar');
        }
    }

    async loadContent() {
        try {
            const response = await fetch(`api.php?action=obtenir&codi=${this.activeCode}`);
            const data = await response.json();
            
            if (data.success) {
                this.portapapersContent.value = data.contingut || '';
                this.lastContent = this.portapapersContent.value;
                this.updateCharCount();
                this.updateStatus('connected');
            }
        } catch (error) {
            console.error('Error al carregar contingut:', error);
        }
    }

    async handleContentChange() {
        this.updateCharCount();
        
        if (this.isConnected) {
            // Mantenir estat de connexió estable mentre s'escriu
            this.updateStatus('connected');
            
            // Gestionar indicador de typing
            this.setTyping(true);
            
            // Cancel·lar timeout anterior si existeix
            if (this.typingTimeout) {
                clearTimeout(this.typingTimeout);
            }
            
            // Desactivar typing després de 1.5 segons d'inactivitat
            this.typingTimeout = setTimeout(() => {
                this.setTyping(false);
            }, 1500);
            
            // Guardar contingut de forma asíncrona sense bloquejar la UI
            this.saveContentAsync();
        }
    }

    async saveContent() {
        try {
            const content = this.portapapersContent.value;
            
            const response = await fetch('api.php?action=actualitzar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `codi=${this.activeCode}&contingut=${encodeURIComponent(content)}`
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.lastContent = content;
                this.updateStatus('connected');
            } else {
                throw new Error(data.error || 'Error al guardar');
            }
        } catch (error) {
            this.updateStatus('disconnected');
            this.showNotification('Error al guardar: ' + error.message, 'error');
        }
    }

    // Funció per guardar contingut de forma asíncrona sense afectar l'estat visual
    async saveContentAsync() {
        try {
            const content = this.portapapersContent.value;
            
            const response = await fetch('api.php?action=actualitzar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `codi=${this.activeCode}&contingut=${encodeURIComponent(content)}`
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.lastContent = content;
                // No canviar l'estat visual aquí per evitar parpelleig
            } else {
                console.error('Error al guardar:', data.error);
            }
        } catch (error) {
            console.error('Error al guardar:', error);
        }
    }

    startSync() {
        this.isConnected = true;
        this.updateStatus('connected');
        
        // Sincronització cada 1 segon per resposta més ràpida
        this.syncInterval = setInterval(async () => {
            await this.checkForUpdates();
        }, 1000);
    }

    async checkForUpdates() {
        try {
            const response = await fetch(`api.php?action=obtenir&codi=${this.activeCode}`);
            const data = await response.json();
            
            if (data.success) {
                const remoteContent = data.contingut || '';
                
                // Gestionar indicador de typing d'altres usuaris
                if (data.typing && !this.isTyping) {
                    this.showTypingIndicator();
                } else if (!data.typing) {
                    this.hideTypingIndicator();
                }
                
                // Gestionar informació de fitxers
                if (data.fitxer_info) {
                    this.showFileInfo(data.fitxer_info);
                } else {
                    this.hideFileInfo();
                }
                
                // Només actualitzar si el contingut remot és diferent i l'usuari no està escrivint
                if (remoteContent !== this.lastContent && remoteContent !== this.portapapersContent.value) {
                    this.portapapersContent.value = remoteContent;
                    this.lastContent = remoteContent;
                    this.updateCharCount();
                    this.updateStatus('connected');
                }
            } else {
                console.error('Error API:', data.error, data.debug);
                this.updateStatus('disconnected');
            }
        } catch (error) {
            console.error('Error al sincronitzar:', error);
            this.updateStatus('disconnected');
        }
    }

    disconnect() {
        this.isConnected = false;
        this.activeCode = null;
        this.lastContent = '';
        
        if (this.syncInterval) {
            clearInterval(this.syncInterval);
            this.syncInterval = null;
        }
        
        if (this.typingTimeout) {
            clearTimeout(this.typingTimeout);
            this.typingTimeout = null;
        }
        
        if (this.typingApiTimeout) {
            clearTimeout(this.typingApiTimeout);
            this.typingApiTimeout = null;
        }
        
        this.hideTypingIndicator();
        this.isTyping = false;
        this.hideFileInfo();
        
        this.hidePortapapersSection();
        this.portapapersContent.value = '';
        this.connectCodeInput.value = '';
        this.updateCharCount();
        this.updateStatus('disconnected');
        
        this.showNotification('Desconnectat', 'warning');
    }

    showPortapapersSection() {
        this.createSection.style.display = 'none';
        this.connectSection.style.display = 'none';
        this.portapapersSection.style.display = 'block';
        this.activeCodeDisplay.textContent = this.activeCode;
    }

    hidePortapapersSection() {
        this.createSection.style.display = 'block';
        this.connectSection.style.display = 'block';
        this.portapapersSection.style.display = 'none';
    }

    updateCharCount() {
        const count = this.portapapersContent.value.length;
        this.charCount.textContent = `${count} caràcters`;
    }

    updateStatus(status) {
        this.status.textContent = this.getStatusText(status);
        this.status.className = `status ${status}`;
    }

    getStatusText(status) {
        const statusTexts = {
            'connected': 'Connectat',
            'disconnected': 'Desconnectat',
            'syncing': 'Sincronitzant...'
        };
        return statusTexts[status] || 'Desconnectat';
    }

    async copyCode() {
        try {
            await navigator.clipboard.writeText(this.activeCode);
            this.showNotification('Codi copiat al porta-retalls', 'success');
        } catch (error) {
            // Fallback per navegadors que no suporten clipboard API
            const textArea = document.createElement('textarea');
            textArea.value = this.activeCode;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            this.showNotification('Codi copiat al porta-retalls', 'success');
        }
    }

    showLoading(button, text) {
        button.disabled = true;
        button.textContent = text;
    }

    hideLoading(button, originalText) {
        button.disabled = false;
        button.textContent = originalText;
    }

    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        
        const icon = this.getNotificationIcon(type);
        notification.innerHTML = `
            <div class="notification-icon">${icon}</div>
            <div class="notification-message">${message}</div>
        `;
        
        this.notifications.appendChild(notification);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 5000);
    }

    getNotificationIcon(type) {
        const icons = {
            'success': '✓',
            'error': '✗',
            'warning': '⚠',
            'info': 'ℹ'
        };
        return icons[type] || 'ℹ';
    }

    async setTyping(typing) {
        if (this.isTyping === typing) return;
        
        this.isTyping = typing;
        
        // Per activar typing: enviar immediatament
        if (typing) {
            try {
                const response = await fetch('api.php?action=typing', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `codi=${this.activeCode}&typing=1`
                });
                
                const data = await response.json();
                
                if (!data.success) {
                    console.error('Error al actualitzar typing:', data.error);
                }
            } catch (error) {
                console.error('Error al actualitzar typing:', error);
            }
        } else {
            // Per desactivar typing: debounce per evitar crides excessives
            if (this.typingApiTimeout) {
                clearTimeout(this.typingApiTimeout);
            }
            
            this.typingApiTimeout = setTimeout(async () => {
                try {
                    const response = await fetch('api.php?action=typing', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `codi=${this.activeCode}&typing=0`
                    });
                    
                    const data = await response.json();
                    
                    if (!data.success) {
                        console.error('Error al actualitzar typing:', data.error);
                    }
                } catch (error) {
                    console.error('Error al actualitzar typing:', error);
                }
            }, 200); // Delay reduït a 200ms per desactivar
        }
    }

    showTypingIndicator() {
        this.typingIndicator.style.display = 'flex';
    }

    hideTypingIndicator() {
        this.typingIndicator.style.display = 'none';
    }

    // Funcions per gestionar fitxers
    handleFileSelect(event) {
        const file = event.target.files[0];
        if (file) {
            this.uploadFile(file);
        }
    }

    handleDragOver(event) {
        event.preventDefault();
        this.fileUploadArea.classList.add('dragover');
    }

    handleDragLeave(event) {
        event.preventDefault();
        this.fileUploadArea.classList.remove('dragover');
    }

    handleDrop(event) {
        event.preventDefault();
        this.fileUploadArea.classList.remove('dragover');
        
        const files = event.dataTransfer.files;
        if (files.length > 0) {
            this.uploadFile(files[0]);
        }
    }

    async uploadFile(file) {
        try {
            // Validacions
            const maxSize = 10 * 1024 * 1024; // 10MB
            if (file.size > maxSize) {
                this.showNotification('El fitxer és massa gran (màxim 10MB)', 'error');
                return;
            }

            const allowedTypes = [
                'image/jpeg', 'image/png', 'image/gif', 'image/webp',
                'application/pdf', 'text/plain', 'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/zip', 'application/x-rar-compressed'
            ];

            if (!allowedTypes.includes(file.type)) {
                this.showNotification('Tipus de fitxer no permès', 'error');
                return;
            }

            // Mostrar loading
            this.showLoading(this.uploadPlaceholder, 'Pujant fitxer...');

            // Crear FormData
            const formData = new FormData();
            formData.append('codi', this.activeCode);
            formData.append('fitxer', file);

            // Pujar fitxer
            const response = await fetch('api.php?action=pujar_fitxer', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                this.showFileInfo(data.fitxer_info);
                this.showNotification('Fitxer pujat correctament', 'success');
            } else {
                throw new Error(data.error || 'Error al pujar el fitxer');
            }
        } catch (error) {
            this.showNotification('Error al pujar fitxer: ' + error.message, 'error');
        } finally {
            this.hideLoading(this.uploadPlaceholder, 'Arrossega un fitxer aquí o fes clic per seleccionar');
        }
    }

    showFileInfo(fileInfo) {
        this.uploadPlaceholder.style.display = 'none';
        this.fileInfo.style.display = 'flex';
        
        this.fileName.textContent = fileInfo.nom;
        this.fileSize.textContent = this.formatFileSize(fileInfo.mida);
        this.fileDate.textContent = new Date(fileInfo.data_pujada).toLocaleString('ca-ES');
        
        // Icona segons tipus de fitxer
        this.fileIcon.textContent = this.getFileIcon(fileInfo.tipus);
    }

    hideFileInfo() {
        this.uploadPlaceholder.style.display = 'flex';
        this.fileInfo.style.display = 'none';
    }

    getFileIcon(mimeType) {
        if (mimeType.startsWith('image/')) return '🖼️';
        if (mimeType.includes('pdf')) return '📄';
        if (mimeType.includes('word')) return '📝';
        if (mimeType.includes('excel') || mimeType.includes('spreadsheet')) return '📊';
        if (mimeType.includes('zip') || mimeType.includes('rar')) return '📦';
        return '📁';
    }

    formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    async downloadFile() {
        try {
            const url = `api.php?action=descarregar_fitxer&codi=${this.activeCode}`;
            window.open(url, '_blank');
        } catch (error) {
            this.showNotification('Error al descarregar fitxer: ' + error.message, 'error');
        }
    }

    async deleteFile() {
        if (!confirm('Estàs segur que vols eliminar aquest fitxer?')) {
            return;
        }

        try {
            const response = await fetch('api.php?action=eliminar_fitxer', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `codi=${this.activeCode}`
            });

            const data = await response.json();

            if (data.success) {
                this.hideFileInfo();
                this.showNotification('Fitxer eliminat correctament', 'success');
            } else {
                throw new Error(data.error || 'Error al eliminar el fitxer');
            }
        } catch (error) {
            this.showNotification('Error al eliminar fitxer: ' + error.message, 'error');
        }
    }
}

// Inicialitzar l'aplicació quan el DOM estigui carregat
document.addEventListener('DOMContentLoaded', () => {
    new PortapapersApp();
});
