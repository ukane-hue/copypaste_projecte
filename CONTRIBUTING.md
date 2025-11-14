# Contribuir a Copy&Paste Remot

Gràcies per considerar contribuir a aquest projecte! Aquest document explica com pots ajudar a millorar l'aplicació.

## Com Contribuir

### 1. Fork del Repositori

1. Fes un **fork** d'aquest repositori a GitHub
2. Clona el teu fork localment:
   ```bash
   git clone https://github.com/TEUNOMBRE/copypaste.git
   cd copypaste
   ```

### 2. Configuració del Entorn de Desenvolupament

#### Requisits
- PHP 7.4 o superior
- MySQL 5.7 o superior
- Servidor web (Apache/Nginx)
- Git

#### Configuració Local
1. **Copia el fitxer d'entorn:**
   ```bash
   cp config/env.example .env
   ```

2. **Configura la base de dades:**
   - Crea una base de dades MySQL
   - Edita `.env` amb les teves credencials:
   ```env
   DB_HOST=localhost
   DB_NAME=portapapers
   DB_USER=el_teu_usuari
   DB_PASS=la_teva_contrasenya
   ```

3. **Executa l'esquema de la base de dades:**
   ```bash
   mysql -u root -p portapapers < database/database.sql
   ```

### 3. Crear una Branca per a la Teva Contribució

```bash
git checkout -b feature/nom-de-la-teva-feature
# o
git checkout -b fix/descripcio-del-bug
```

### 4. Fer Canvis

- **Codi**: Segueix les convencions de codi existents
- **Comentaris**: Escriu comentaris en català
- **Funcions**: Utilitza noms descriptius en català
- **Estils**: Mantén la consistència amb el disseny actual

### 5. Provar els Canvis

Abans de fer commit, assegura't que:

- [ ] L'aplicació funciona correctament
- [ ] No hi ha errors de PHP
- [ ] La base de dades es connecta
- [ ] Les funcionalitats existents no s'han trencat
- [ ] El disseny és responsive

### 6. Commit i Push

```bash
git add .
git commit -m "Descripció clara del canvi"
git push origin feature/nom-de-la-teva-feature
```

### 7. Crear un Pull Request

1. Vés al repositori original a GitHub
2. Clica "New Pull Request"
3. Selecciona la teva branca
4. Descriu els canvis que has fet
5. Envia el Pull Request

## Tipus de Contribucions

### 🐛 Correcció de Bugs
- Identifica i corregeix errors
- Millora el maneig d'errors
- Optimitza el rendiment

### ✨ Noves Funcionalitats
- Afegeix noves característiques
- Millora la interfície d'usuari
- Implementa noves APIs

### 📚 Documentació
- Millora el README
- Afegeix comentaris al codi
- Crea tutorials

### 🎨 Disseny
- Millora la interfície
- Optimitza per mòbils
- Afegeix animacions

### 🔧 Configuració
- Millora la configuració
- Afegeix noves variables d'entorn
- Optimitza Docker

## Convencions de Codi

### PHP
```php
// Funcions en camelCase
function nomDeLaFuncio() {
    // Codi aquí
}

// Variables descriptives
$nomDeLaVariable = "valor";

// Comentaris en català
// Aquesta funció fa...
```

### JavaScript
```javascript
// Variables en camelCase
const nomDeLaVariable = "valor";

// Funcions descriptives
function nomDeLaFuncio() {
    // Codi aquí
}
```

### CSS
```css
/* Classes en kebab-case */
.nom-de-la-classe {
    /* Propietats */
}

/* Comentaris en català */
/* Estil per al header */
```

## Estructura del Projecte

```
copypaste/
├── public/                 # Fitxers públics
│   ├── index.php         # Aplicació principal
│   ├── api.php           # API REST
│   ├── text.php          # Ruta per text
│   └── fitxer.php        # Ruta per fitxers
├── config/               # Configuració
│   ├── config.php       # Configuració de BD
│   └── env.example      # Variables d'entorn
├── database/            # Base de dades
│   └── database.sql     # Esquema complet
├── assets/              # Recursos estàtics
│   ├── css/            # Estils
│   └── js/             # JavaScript
└── README.md           # Documentació
```

## Proposar Canvis

### Abans de Començar
1. **Revisa els issues existents** per veure si algú ja està treballant-hi
2. **Crea un issue** per discutir canvis grans
3. **Pregunta** si tens dubtes sobre la implementació

### Per a Canvis Grans
1. **Discuteix** la idea en un issue abans de començar
2. **Proposa** l'arquitectura i l'aproximació
3. **Espera** feedback abans de implementar

## Reportar Bugs

### Com Reportar
1. **Crea un issue** a GitHub
2. **Descriu** el problema detalladament
3. **Inclou** passos per reproduir-lo
4. **Afegeix** captures de pantalla si cal
5. **Especifica** el teu entorn (PHP, MySQL, navegador)

### Informació Útil
- Versió de PHP
- Versió de MySQL
- Navegador i versió
- Sistema operatiu
- Logs d'error (si n'hi ha)

## Suggerir Funcionalitats

### Com Suggerir
1. **Crea un issue** amb l'etiqueta "enhancement"
2. **Descriu** la funcionalitat detalladament
3. **Explica** per què seria útil
4. **Proposa** com implementar-la

### Criteris
- **Útil**: Resol un problema real
- **Factible**: Tècnicament possible
- **Coherent**: S'adapta al disseny actual
- **Mantible**: No complica el codi

## Revisió de Codi

### Què Esperem
- **Codi net** i ben comentat
- **Funcionalitat** que funciona
- **Tests** si cal
- **Documentació** actualitzada

### Procés de Revisió
1. **Revisió automàtica** (si hi ha CI/CD)
2. **Revisió manual** per part dels mantenedors
3. **Feedback** i suggeriments
4. **Aprovació** i merge

## Llicència

Aquest projecte està sota llicència MIT. Contribuint, acceptes que les teves contribucions seran llicenciades sota la mateixa llicència.

## Contacte

Si tens preguntes o necessites ajuda:

- **GitHub Issues**: Per bugs i funcionalitats
- **GitHub Discussions**: Per preguntes generals
- **Email**: [contacte@insmollerussa.cat](mailto:contacte@insmollerussa.cat)

## Agraïments

Gràcies a tots els contribuents que fan possible aquest projecte! 🎉

---

**INS Mollerussa** - Desenvolupament de Software
