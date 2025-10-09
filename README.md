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
   ```

2. **Opció B: Configuració directa**
   
   Si no vols utilitzar variables d'entorn, edita directament `config/config.php`:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'portapapers');
   define('DB_USER', 'el_teu_usuari');
   define('DB_PASS', 'la_teva_contrasenya');
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
portapapers/
├── index.php                    # Redirecció a /public
├── .htaccess                   # Configuració del servidor web
├── public/                     # Fitxers públics
│   ├── index.php              # Aplicació principal
│   ├── api.php                # API REST
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
curl -X POST http://localhost/portapapers/api.php?action=neteja

# Via PHP directe
php -r "require_once 'config.php'; echo netejarPortapapersAntics() . ' registres eliminats';"
```

### Estadístiques

Consulta les estadístiques de neteja:

```bash
curl http://localhost/portapapers/api.php?action=estadistiques
```

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
