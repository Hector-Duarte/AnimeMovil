@echo off
echo ==========================================
echo    Servidor LOCAL para AnimeMovil
echo ==========================================
echo.

REM Verificar si PHP está instalado
php --version > nul 2>&1
if errorlevel 1 (
    echo ERROR: PHP no está instalado o no está en el PATH
    echo.
    echo Opciones:
    echo 1. Instalar XAMPP desde: https://www.apachefriends.org/
    echo 2. O instalar PHP desde: https://www.php.net/downloads
    echo.
    pause
    exit
)

echo ✓ PHP encontrado

REM Verificar si hay un servidor MySQL/MariaDB ejecutándose
echo.
echo IMPORTANTE: Asegurate de tener MySQL/MariaDB ejecutándose
echo ^(puedes usar XAMPP, WAMP, o instalar MySQL por separado^)
echo.

REM Actualizar configuración para servidor local
echo Configurando para servidor local...

REM Crear config.php temporal
(
echo ^<?php
echo define^('HTTP_PROTOCOL', 'http'^);
echo define^('SITE_NAME','Anime Móvil'^);
echo define^('STREAM_PATH','/stream/'^);
echo define^('API_PATH', '/api/'^);
echo define^('DOMAIN', 'localhost'^);
echo define^('STATIC_DOMAIN', 'localhost'^);
echo define^("HOST", "localhost"^);
echo define^("USER", "root"^);
echo define^("PASSWORD", ""^);
echo define^("DATABASE", "animemovil"^);
echo define^("KEYAPI", "b62G8GbE8wWkMUwN8177ugjemiHIkf7e1A"^);
echo define^("SIGNATURE_HASH", "bp2GGbE8wWkMU1wN8_DAIbQRmZkGTxyMmiHIkf+7e1A="^);
echo define^("SIGNATURE_HASH_USER", "R7KbpdZX6i/HwTQwRv/Pl1_V61vMsBfimVWlp/Ny8ooA="^);
echo define^("PATH_SYSTEM", __DIR__ . "/"^);
echo define^("CACHE_PATH", __DIR__ . "/assets/cache/"^);
echo header_remove^(^);
echo header^('cache-control: max-age=0, no-cache'^);
echo ?^>
) > "config\config.php"

REM Crear vars_info.php temporal
(
echo ^<?php
echo define^("HOST", "localhost"^);
echo define^("USER", "root"^);
echo define^("PASSWORD", ""^);
echo define^("DATABASE", "animemovil"^);
echo define^("KEYAPI", "b62G8GbE8wWkMUwN8177ugjemiHIkf7e1A"^);
echo define^("SIGNATURE_HASH", "bp2GGbE8wWkMU1wN81DAIbQRmZkGTxyMmiHIkf+7e1A="^);
echo define^("SIGNATURE_HASH_USER", "R7KbpdZX6i/HwTQwRv/Pl1_V61vMsBfimVWlp/Ny8ooA="^);
echo define^("CACHE_PATH", __DIR__ . "/assets/cache/"^);
echo define^("STREAM_PATH", "/stream/"^);
echo ?^>
) > "vars_info.php"

echo ✓ Configuración actualizada

echo.
echo ==========================================
echo         INICIANDO SERVIDOR LOCAL
echo ==========================================
echo.
echo Tu sitio estará disponible en:
echo 👉 http://localhost:8000
echo.
echo Para detener el servidor: Ctrl+C
echo.
echo RECUERDA:
echo 1. Tener MySQL ejecutándose
echo 2. Crear base de datos 'animemovil' 
echo 3. Importar animemovil.sql
echo.
echo ==========================================

timeout /t 3 > nul

REM Iniciar servidor PHP
php -S localhost:8000
