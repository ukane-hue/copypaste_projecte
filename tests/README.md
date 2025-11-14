# Tests - Copy&Paste App

Aquesta carpeta conté tots els scripts de test per a l'aplicació Copy&Paste.

## 📁 Estructura de Tests

```
tests/
├── README.md              # Documentació de tests
├── web-test.php           # Test web (navegador)
├── terminal-test.php      # Test terminal (executor principal)
└── testfiles/             # Subcarpeta amb tests individuals
    ├── run-all-tests.php  # Executor original
    ├── quick-test.php     # Test ràpid
    ├── test-debug.php     # Test de debug
    └── files-test.php     # Test de fitxers
```

## 📁 Fitxers de Test Disponibles

### 🚀 **Scripts Principals**

| Fitxer | Descripció | Ús |
|--------|------------|-----|
| `terminal-test.php` | **Executor principal** - Executa tots els tests | `php terminal-test.php` |
| `web-test.php` | **Test web** - Versió HTML amb CSS per al navegador | `http://localhost/tests/web-test.php` |

### 🔧 **Tests Individuals (testfiles/)**

| Fitxer | Descripció | Ús |
|--------|------------|-----|
| `testfiles/quick-test.php` | **Test ràpid** - Test bàsic de funcionalitats essencials | `php testfiles/quick-test.php` |
| `testfiles/test-debug.php` | **Test de debug** - Verifica funcionalitat de debug | `php testfiles/test-debug.php` |
| `testfiles/files-test.php` | **Test de fitxers** - Verifica funcionalitat de fitxers | `php testfiles/files-test.php` |

## 🚀 Com Executar els Tests

### Opció 1: Executar Tots els Tests (Terminal)
```bash
cd tests
php terminal-test.php
```

### Opció 1b: Test Web (Navegador)
```bash
# Obre al navegador
http://localhost/tests/web-test.php
```

### Opció 2: Executar Tests Individuals
```bash
cd tests

# Test ràpid (recomanat per a verificacions diàries)
php testfiles/quick-test.php

# Test de debug (per verificar funcionalitat de debug)
php testfiles/test-debug.php

# Test de fitxers (per verificar funcionalitat de fitxers)
php testfiles/files-test.php

```

### Opció 3: Executar des del Directori Arrel
```bash
# Des del directori arrel del projecte
php tests/terminal-test.php
php tests/web-test.php
php tests/testfiles/quick-test.php
php tests/testfiles/test-debug.php
php tests/testfiles/files-test.php
```

## 📊 Què Verifiquen els Tests

### 🔍 **Test Ràpid (`quick-test.php`)**
- ✅ Connexió a base de dades
- ✅ Variables d'entorn
- ✅ API endpoints bàsics
- ✅ Funcions de neteja
- ✅ Pàgina d'error

### 🔧 **Test de Debug (`test-debug.php`)**
- ✅ Mode DEBUG=true/false
- ✅ Error reporting
- ✅ Display errors
- ✅ Gestió d'errors
- ✅ API responses segons mode
- ✅ Pàgina d'error
- ✅ Logs d'error

### 📁 **Test de Fitxers (`test-files.php`)**
- ✅ Pujada de fitxers
- ✅ Descàrrega de fitxers
- ✅ Eliminació de fitxers
- ✅ Validacions de seguretat
- ✅ Tipus de fitxers
- ✅ Mida màxima

### 🧪 **Test Terminal (`terminal-test.php`)**
- ✅ Executa tots els tests individuals
- ✅ Resum complet de resultats
- ✅ Temps d'execució
- ✅ Recomanacions finals
- ✅ Test exhaustiu de totes les funcionalitats

## 🎨 Característiques dels Tests

- **🎨 Sortida amb colors** per a millor visualització
- **⏱️ Mesura de temps** d'execució
- **📈 Percentatge d'èxit** calculat automàticament
- **🧹 Neteja automàtica** de dades de test
- **🔍 Detecció d'errors** detallada
- **💡 Recomanacions** per a solucionar problemes

## 🔧 Requisits

- PHP 7.4 o superior
- Base de dades MySQL configurada
- Variables d'entorn configurades (fitxer `.env`)
- Permisos de lectura i escriptura

## 📝 Notes Importants

1. **Neteja Automàtica**: Els tests netegen automàticament les dades de test
2. **Colors**: Els tests utilitzen colors ANSI per a millor visualització
3. **Rutes Relatives**: Tots els tests funcionen des de la carpeta `tests/`
4. **Seguretat**: Els tests no afecten les dades reals de l'aplicació

## 🚨 Solució de Problemes

### Error: "No s'ha pogut connectar a la base de dades"
- Verifica la configuració al fitxer `.env`
- Assegura't que MySQL estigui executant-se
- Comprova que la base de dades existeix

### Error: "Variables d'entorn no definides"
- Crea un fitxer `.env` basant-te en `config/env.example`
- Verifica que el fitxer `.env` estigui al directori arrel

### Error: "Fitxer de test no trobat"
- Assegura't d'estar a la carpeta `tests/`
- Verifica que tots els fitxers de test estiguin presents

## 📚 Documentació Adicional

- [README principal](../README.md) - Documentació completa de l'aplicació
- [Configuració](../config/env.example) - Plantilla de variables d'entorn
- [Base de dades](../database/database.sql) - Esquema de la base de dades
