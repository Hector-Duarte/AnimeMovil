# 🎬 AnimeMovil - Guía de Instalación Paso a Paso

## ✅ MÉTODO FÁCIL (Sin scripts complicados)

### 1. Descargar e Instalar XAMPP

1. Ve a: https://www.apachefriends.org/
2. Descarga XAMPP para Windows
3. Instálalo en `C:\xampp\`

### 2. Copiar tu proyecto

1. Copia toda la carpeta `AnimeMovil` 
2. Pégala en: `C:\xampp\htdocs\`
3. Debe quedar así: `C:\xampp\htdocs\AnimeMovil\`

### 3. Configurar archivos manualmente

Abre el archivo `C:\xampp\htdocs\AnimeMovil\config\config.php` y cambia estas líneas:

```php
// ANTES:
define("PATH_SYSTEM", "c:/Users/Hector Duarte/Desktop/AnimeMovil/");
define("CACHE_PATH", "c:/Users/Hector Duarte/Desktop/AnimeMovil/assets/cache/");

// DESPUÉS:
define("PATH_SYSTEM", "C:\\xampp\\htdocs\\AnimeMovil\\");
define("CACHE_PATH", "C:\\xampp\\htdocs\\AnimeMovil\\assets\\cache\\");

// Y también cambia:
define('STREAM_PATH','/AnimeMovil/stream/');
define('API_PATH', '/AnimeMovil/api/');
```

Abre el archivo `C:\xampp\htdocs\AnimeMovil\vars_info.php` y cambia:

```php
// ANTES:
define("CACHE_PATH", "c:/Users/Hector Duarte/Desktop/AnimeMovil/assets/cache/");
define("STREAM_PATH", "/stream/");

// DESPUÉS:
define("CACHE_PATH", "C:\\xampp\\htdocs\\AnimeMovil\\assets\\cache\\");
define("STREAM_PATH", "/AnimeMovil/stream/");
```

### 4. Iniciar servicios

1. Abre el **Panel de Control de XAMPP**
2. Haz clic en **"Start"** junto a **Apache**
3. Haz clic en **"Start"** junto a **MySQL**

### 5. Crear base de datos

1. Ve a: http://localhost/phpmyadmin
2. Haz clic en **"Nueva"**
3. Nombre: `animemovil`
4. Haz clic en **"Crear"**
5. Selecciona la base de datos `animemovil`
6. Ve a **"Importar"**
7. Selecciona el archivo `animemovil.sql`
8. Haz clic en **"Continuar"**

### 6. ¡Listo!

Visita: **http://localhost/AnimeMovil/**

---

## 🔧 Si algo no funciona:

1. **Error de base de datos**: Verifica que MySQL esté iniciado en XAMPP
2. **Página no carga**: Verifica que Apache esté iniciado en XAMPP
3. **Errores de rutas**: Revisa que los archivos estén en `C:\xampp\htdocs\AnimeMovil\`

## 📱 URLs del sitio:

- **Inicio**: http://localhost/AnimeMovil/
- **Buscar**: http://localhost/AnimeMovil/anime
- **Login**: http://localhost/AnimeMovil/entrar
- **Registro**: http://localhost/AnimeMovil/registrar
