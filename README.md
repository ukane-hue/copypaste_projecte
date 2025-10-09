# Copy&Paste - Aplicació per copiar i pegar Text/Fitxers entre dispositius remots

Una aplicació web PHP per copiar/pegar/compartir text i fitxers entre dispositius remots mitjançant codis hexadecimals únics.

## Característiques

- ✅ **Interfície moderna i minimalista** amb tipografia Montserrat
- ✅ **Codis hexadecimals únics** de 6 dígits per a cada portapapers
- ✅ **Sincronització en temps real** entre dispositius
- ✅ **Disseny responsive** per a mòbils i escriptori
- ✅ **API REST** per a la gestió de portapapers
- ✅ **Notificacions en temps real** per a l'usuari
- ✅ **Neteja automàtica** de portapapers antics (més de 2 hores sense ús)
- ✅ **Variables d'entorn** per a configuració segura
- ✅ **Indicador de typing** en temps real entre dispositius
- ✅ **Mode debug** configurable per desenvolupament i producció
- ✅ **Gestió d'errors** amb pàgina d'error genèrica
- ✅ **Sistema de logs** per registre d'errors en producció

## Requisits del Sistema

- PHP 7.4 o superior
- MySQL 5.7 o superior
- Servidor web (Apache/Nginx)

## Instal·lació

### 1. Configuració de la Base de Dades

1. Crea una base de dades MySQL:
```sql
CREATE DATABASE portapapers;
```

2. Executa el fitxer `database.sql` per crear les taules necessàries:
```bash
mysql -u root -p portapapers < database/database.sql
```

### 2. Configuració de l'Aplicació

1. **Opció A: Variables d'entorn (Recomanat)**
   
   Crea un fitxer `.env` basant-te en `env.example`:
   ```bash
   cp config/env.example .env
   ```
   
   Edita el fitxer `.env` amb les teves credencials:
   ```env
   DB_HOST=localhost
   DB_NAME=portapapers
   DB_USER=el_teu_usuari
   DB_PASS=la_teva_contrasenya
   HEX_LENGTH=6
   REFRESH_INTERVAL=2000
   DEBUG=true
   ```

2. **Opció B: Configuració directa**
   
   Si no vols utilitzar variables d'entorn, edita directament `config/config.php`:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'portapapers');
   define('DB_USER', 'el_teu_usuari');
   define('DB_PASS', 'la_teva_contrasenya');
   ...
   ```

3. Posa tots els fitxers al directori del teu servidor web.

### 3. Configuració del Servidor Web

L'aplicació utilitza una estructura organitzada amb carpetes separades:

- **`/public`**: Fitxers públics (PHP, HTML)
- **`/config`**: Configuració i variables d'entorn
- **`/database`**: Scripts de base de dades
- **`/assets`**: Recursos estàtics (CSS, JS)

El fitxer `.htaccess` configura automàticament les redireccions.

### 4. Permisos

Assegura't que el servidor web tingui permisos de lectura i escriptura al directori de l'aplicació.

## Ús de l'Aplicació

### Crear un Nou Portapapers

1. Obre l'aplicació al navegador
2. Fes clic a "Crear portapapers"
3. L'aplicació generarà automàticament un codi únic de 6 dígits
4. Comparteix aquest codi amb altres dispositius

### Connectar a un Portapapers Existent

1. Introdueix el codi de 6 dígits al camp corresponent
2. Fes clic a "Connectar"
3. L'aplicació carregarà el contingut del portapapers

### Funcionalitats

- **Sincronització automàtica**: Els canvis es sincronitzen automàticament cada 2 segons
- **Copiar codi**: Fes clic al botó de copiar per copiar el codi al porta-retalls
- **Comptador de caràcters**: Veu el nombre de caràcters escrits
- **Estat de connexió**: Visualitza si estàs connectat, desconnectat o sincronitzant

## Estructura del Projecte

```
copypaste/
├── index.php                    # Redirecció a /public
├── .htaccess                   # Configuració del servidor web
├── public/                     # Fitxers públics
│   ├── index.php              # Aplicació principal
│   ├── api.php                # API REST
│   ├── error.php              # Pàgina d'error genèrica
│   ├── text.php               # Ruta per text compartit
│   ├── fitxer.php             # Ruta per fitxers compartits
│   └── .htaccess              # Configuració de la carpeta public
├── config/                     # Configuració
│   ├── config.php             # Configuració de la base de dades
│   └── env.example            # Plantilla de variables d'entorn
├── database/                   # Base de dades
│   └── database.sql           # Esquema complet
├── assets/                     # Recursos estàtics
│   ├── css/
│   │   └── styles.css         # Estils CSS moderns
│   └── js/
│       └── script.js          # Funcionalitat JavaScript
├── tests/                      # Scripts de test
│   ├── README.md              # Documentació de tests
│   ├── web-test.php           # Test web (navegador)
│   ├── terminal-test.php      # Test terminal (executor principal)
│   └── testfiles/             # Subcarpeta amb tests individuals
│       ├── run-all-tests.php  # Executor original
│       ├── quick-test.php     # Test ràpid
│       ├── test-debug.php     # Test de debug
│       └── files-test.php     # Test de fitxers
└── README.md                   # Documentació completa
```

## API Endpoints

### Crear Portapapers
```
POST /api.php?action=crear
Content-Type: application/x-www-form-urlencoded

contingut=text_inicial
```

### Obtenir Contingut
```
GET /api.php?action=obtenir&codi=A1B2C3
```

### Actualitzar Contingut
```
POST /api.php?action=actualitzar
Content-Type: application/x-www-form-urlencoded

codi=A1B2C3&contingut=nou_text
```

### Verificar Existència
```
GET /api.php?action=verificar&codi=A1B2C3
```

### Neteja Manual
```
POST /api.php?action=neteja
```

### Estadístiques de Neteja
```
GET /api.php?action=estadistiques
```

### Indicador de Typing
```
POST /api.php?action=typing
Content-Type: application/x-www-form-urlencoded

codi=A1B2C3&typing=1
```

## Rutes Directes

### Text Compartit
```
GET /text.php?codi=A1B2C3
```
Retorna només el text pla sense HTML.

### Fitxer Compartit
```
GET /fitxer.php?codi=A1B2C3
```
Descarrega automàticament el fitxer compartit.

## Seguretat

- Els codis hexadecimals són únics i aleatoris
- Validació d'entrada per a tots els paràmetres
- Prevenció d'injecció SQL mitjançant prepared statements
- Sanitització de dades d'entrada
- **Variables d'entorn per a credencials sensibles**
- **El fitxer `.env` no s'ha de pujar al repositori**
- **Mode debug configurable** per desenvolupament i producció
- **Gestió d'errors segura** amb pàgina d'error genèrica

### Recomanacions de Seguretat

1. **Mai commitis el fitxer `.env`** al control de versions
2. **Utilitza contrasenyes fortes** per a la base de dades
3. **Configura HTTPS** en producció
4. **Limita l'accés** al directori de l'aplicació
5. **Fes còpies de seguretat** regulars de la base de dades

## Neteja Automàtica

L'aplicació inclou un sistema de neteja automàtica que elimina els portapapers que no s'han modificat en més de 2 hores.

### Funcionament

- **Neteja automàtica**: S'executa cada vegada que es carrega `index.php`
- **Criteri d'eliminació**: Portapapers no modificats en més de 2 hores
- **Logs**: Els registres eliminats es registren als logs del servidor
- **API endpoints**: Disponibles per a neteja manual i estadístiques

### Configuració

Pots modificar l'interval de neteja al fitxer `.env`:

```env
# Interval en hores per considerar un portapapers com a antic
CLEANUP_HOURS=2
```

### Neteja Manual

Si necessites executar la neteja manualment:

```bash
# Via API
curl -X POST http://localhost/api.php?action=neteja

# Via PHP directe
php -r "require_once 'config.php'; echo netejarPortapapersAntics() . ' registres eliminats';"
```

### Estadístiques

Consulta les estadístiques de neteja:

```bash
curl http://localhost/api.php?action=estadistiques
```

## Mode Debug i Gestió d'Errors

L'aplicació inclou un sistema de debug configurable que permet controlar com es mostren els errors segons l'entorn (desenvolupament o producció).

### Configuració del Mode Debug

Configura la variable `DEBUG` al fitxer `.env`:

```env
# Mode Debug (true per desenvolupament, false per producció)
DEBUG=true
```

### Comportament segons el Mode

#### 🔧 **Mode Debug (`DEBUG=true`)**
- **Errors visibles**: Tots els errors es mostren per pantalla
- **Informació detallada**: Stack traces, fitxers, línies d'error
- **API responses**: Inclou informació de debug completa
- **Desenvolupament**: Ideal per a desenvolupament i depuració

#### 🛡️ **Mode Producció (`DEBUG=false`)**
- **Errors ocults**: Els errors no es mostren als usuaris
- **Pàgina d'error**: Redirecció automàtica a `/error.php`
- **Logs**: Els errors es registren al log del servidor
- **Seguretat**: Informació sensible protegida

### Pàgina d'Error Genèrica

Quan `DEBUG=false` i es produeix un error:
- **Redirecció automàtica** a `/error.php`
- **Disseny professional** amb missatges en català
- **Redirecció automàtica** després de 30 segons
- **Botons d'acció** per tornar a l'inici o enrere
- **Responsive** per a dispositius mòbils

### Gestió d'Errors

L'aplicació inclou gestors d'errors personalitzats que:
- **Capturen tots els errors** PHP i excepcions
- **Redirigeixen automàticament** en mode producció
- **Registren errors** al fitxer `logs/error.log`
- **Mostren informació detallada** en mode debug

### Sistema de Logs

Els errors es registren automàticament al fitxer `logs/error.log`:
- **Format**: `[data hora] PHP Warning: missatge a fitxer:línia`
- **Ubicació**: `./logs/error.log` (relativa al directori de l'aplicació)
- **Permisos**: La carpeta `logs/` es crea automàticament si no existeix
- **Rotació**: Es recomana configurar rotació de logs per a producció

### Recomanacions

- **Desenvolupament**: Utilitza `DEBUG=true` per veure errors detallats
- **Producció**: Utilitza `DEBUG=false` per ocultar errors als usuaris
- **Logs**: Revisa els logs del servidor per errors en producció
- **Seguretat**: Mai exposis informació sensible als usuaris finals

## Indicador de Typing

L'aplicació inclou un indicador visual que mostra quan algú està escrivint en altres dispositius connectats al mateix portapapers.

### Funcionament

- **Activació automàtica**: L'indicador s'activa quan algú comença a escriure
- **Desactivació automàtica**: Es desactiva després de 2 segons d'inactivitat
- **Sincronització**: Tots els dispositius veuen l'indicador en temps real
- **Animació**: Punts animats que indiquen activitat

### Característiques

- **Visual atractiu**: Punts animats amb text descriptiu
- **No intrusiu**: No interfereix amb l'escriptura
- **Responsive**: S'adapta a diferents mides de pantalla
- **Performance**: Optimitzat per a múltiples usuaris

### Com Funciona

1. **Quan escrius**: L'aplicació envia l'estat de typing a la base de dades
2. **Altres dispositius**: Reben l'actualització i mostren l'indicador
3. **Timeout**: L'indicador desapareix automàticament després de 2 segons
4. **Sincronització**: Es sincronitza cada 2 segons amb la base de dades

## Personalització

### Colors i Estils
Modifica les variables CSS al fitxer `styles.css`:

```css
:root {
    --primary-color: #2563eb;
    --secondary-color: #6b7280;
    --background: #ffffff;
    /* ... més variables */
}
```

### Interval de Sincronització
Modifica la constant `REFRESH_INTERVAL` al fitxer `config.php`:

```php
define('REFRESH_INTERVAL', 2000); // mil·lisegons
```

## Tests

L'aplicació inclou un sistema complet de tests per verificar que totes les funcionalitats funcionen correctament.

### 🧪 **Scripts de Test Disponibles**

| Script | Descripció | Ús |
|--------|------------|-----|
| `tests/terminal-test.php` | **Executor principal** - Executa tots els tests | `php tests/terminal-test.php` |
| `tests/web-test.php` | **Test web** - Versió HTML amb CSS per al navegador | `http://localhost/tests/web-test.php` |
| `tests/testfiles/quick-test.php` | **Test ràpid** - Test bàsic de funcionalitats essencials | `php tests/testfiles/quick-test.php` |
| `tests/testfiles/test-debug.php` | **Test de debug** - Verifica funcionalitat de debug | `php tests/testfiles/test-debug.php` |
| `tests/testfiles/files-test.php` | **Test de fitxers** - Verifica funcionalitat de fitxers | `php tests/testfiles/files-test.php` |

### 🚀 **Executar Tests**

```bash
# Executar tots els tests (terminal)
php tests/terminal-test.php

# Test web (navegador)
http://localhost/tests/web-test.php

# Tests individuals
php tests/testfiles/quick-test.php    # Test ràpid
php tests/testfiles/test-debug.php    # Test de debug
php tests/testfiles/files-test.php    # Test de fitxers
```

### 📊 **Què Verifiquen els Tests**

- ✅ **Connexió a base de dades** - PDO, queries, constants
- ✅ **Variables d'entorn** - DEBUG, HEX_LENGTH, REFRESH_INTERVAL
- ✅ **Mode debug** - Error reporting, display errors, logs
- ✅ **API endpoints** - Crear, obtenir, actualitzar, verificar, typing
- ✅ **Gestió d'errors** - Codi invàlid, accions inexistents
- ✅ **Funcions de neteja** - Neteja automàtica, estadístiques
- ✅ **Operacions de fitxers** - Pujar, descarregar, eliminar
- ✅ **Validacions de seguretat** - Mida màxima, tipus de fitxers
- ✅ **Pàgina d'error** - Existència, contingut, funcionalitat

### 🎨 **Característiques dels Tests**

- **Sortida amb colors** per a millor visualització
- **Mesura de temps** d'execució
- **Percentatge d'èxit** calculat automàticament
- **Neteja automàtica** de dades de test
- **Detecció d'errors** detallada
- **Recomanacions** per a solucionar problemes

Consulta [`tests/README.md`](tests/README.md) per a documentació detallada dels tests.

## Solució de Problemes

### Error de Connexió a la Base de Dades
- Verifica les credencials a `config.php`
- Assegura't que MySQL estigui executant-se
- Comprova que la base de dades existeix

### Problemes de Sincronització
- Verifica que l'API estigui accessible
- Comprova els logs del servidor web
- Assegura't que JavaScript estigui habilitat

### Problemes de Permisos
- Verifica que el servidor web tingui permisos de lectura
- Comprova els permisos del directori de l'aplicació

### Executar Tests per Diagnòstic
```bash
# Test ràpid per verificar funcionalitats bàsiques
php tests/testfiles/quick-test.php

# Test complet per diagnòstic exhaustiu
php tests/terminal-test.php
```

## Contribucions

Les contribucions són benvingudes! Si vols contribuir al projecte:

### 🚀 Com Contribuir

1. **Fork** el repositori a GitHub
2. **Clona** el teu fork localment
3. **Crea una branca** per a la teva contribució
4. **Fes els canvis** i prova que funcionin
5. **Commit** i **push** els canvis
6. **Crea un Pull Request**

### 📋 Tipus de Contribucions

- 🐛 **Correcció de bugs**
- ✨ **Noves funcionalitats**
- 📚 **Millora de documentació**
- 🎨 **Millores de disseny**
- 🔧 **Optimització de configuració**

### 📖 Documentació Detallada

Consulta el fitxer [`CONTRIBUTING.md`](CONTRIBUTING.md) per a instruccions detallades sobre com contribuir al projecte.

### 🐛 Reportar Problemes

Si trobes errors o tens suggeriments:

1. Obre un **issue** al repositori
2. Descriu el problema detalladament
3. Inclou passos per reproduir-lo
4. Proposa millores mitjançant **pull requests**

## Llicència

Aquest projecte està sota llicència MIT. Pots utilitzar-lo lliurement per a projectes personals i comercials.
