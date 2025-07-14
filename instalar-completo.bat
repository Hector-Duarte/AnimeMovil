@echo off
echo ==========================================
echo    Configuracion RAPIDA para AnimeMovil
echo ==========================================
echo.

REM Copiar proyecto a XAMPP (requiere permisos de administrador)
echo 1. Copiando proyecto a XAMPP...
if not exist "C:\xampp\htdocs\" (
    echo ERROR: XAMPP no encontrado en C:\xampp\
    echo Instala XAMPP primero desde https://www.apachefriends.org/
    pause
    exit
)

xcopy /E /I /Y "." "C:\xampp\htdocs\AnimeMovil\" > nul
echo    ✓ Proyecto copiado a C:\xampp\htdocs\AnimeMovil\

echo.
echo 2. Configurando archivos...

REM Crear config.php para XAMPP
(
echo ^<?php
echo.
echo // Configuracion para XAMPP
echo define^('HTTP_PROTOCOL', 'http'^);
echo define^('SITE_NAME','Anime Móvil'^);
echo define^('STREAM_PATH','/AnimeMovil/stream/'^);
echo define^('API_PATH', '/AnimeMovil/api/'^);
echo define^('DOMAIN', 'localhost'^);
echo define^('STATIC_DOMAIN', 'localhost'^);
echo.
echo //base de datos
echo define^("HOST", "localhost"^);
echo define^("USER", "root"^);
echo define^("PASSWORD", ""^);
echo define^("DATABASE", "animemovil"^);
echo.
echo //claves de acceso
echo define^("KEYAPI", "b62G8GbE8wWkMUwN8177ugjemiHIkf7e1A"^);
echo define^("SIGNATURE_HASH", "bp2GGbE8wWkMU1wN8_DAIbQRmZkGTxyMmiHIkf+7e1A="^);
echo define^("SIGNATURE_HASH_USER", "R7KbpdZX6i/HwTQwRv/Pl1_V61vMsBfimVWlp/Ny8ooA="^);
echo.
echo //paths
echo define^("PATH_SYSTEM", "C:\\xampp\\htdocs\\AnimeMovil\\"^);
echo define^("CACHE_PATH", "C:\\xampp\\htdocs\\AnimeMovil\\assets\\cache\\"^);
echo.
echo //headers
echo header_remove^(^);
echo header^('cache-control: max-age=0, no-cache'^);
echo.
echo ?^>
) > "C:\xampp\htdocs\AnimeMovil\config\config.php"

echo    ✓ config.php actualizado

REM Crear vars_info.php para XAMPP
(
echo ^<?php
echo.
echo //vars para XAMPP
echo define^("HOST", "localhost"^);
echo define^("USER", "root"^);
echo define^("PASSWORD", ""^);
echo define^("DATABASE", "animemovil"^);
echo.
echo define^("KEYAPI", "b62G8GbE8wWkMUwN8177ugjemiHIkf7e1A"^);
echo define^("SIGNATURE_HASH", "bp2GGbE8wWkMU1wN81DAIbQRmZkGTxyMmiHIkf+7e1A="^);
echo define^("SIGNATURE_HASH_USER", "R7KbpdZX6i/HwTQwRv/Pl1pV61vMsBfimVWlp/Ny8ooA="^);
echo.
echo define^("CACHE_PATH", "C:\\xampp\\htdocs\\AnimeMovil\\assets\\cache\\"^);
echo define^("STREAM_PATH", "/AnimeMovil/stream/"^);
echo.
echo ?^>
) > "C:\xampp\htdocs\AnimeMovil\vars_info.php"

echo    ✓ vars_info.php actualizado

echo.
echo 3. Actualizando rutas en archivos PHP...

REM Actualizar index.php
powershell -Command "(Get-Content 'C:\xampp\htdocs\AnimeMovil\pagues\index.php') -replace 'C:\\\\xampp\\\\htdocs\\\\AnimeMovil\\\\vars_info.php', 'C:\\xampp\\htdocs\\AnimeMovil\\vars_info.php' | Set-Content 'C:\xampp\htdocs\AnimeMovil\pagues\index.php'" 2>nul

echo    ✓ Rutas actualizadas

echo.
echo ==========================================
echo          ¡CONFIGURACION COMPLETA!
echo ==========================================
echo.
echo SIGUIENTES PASOS:
echo.
echo 1. Abrir panel de control de XAMPP
echo 2. Iniciar Apache y MySQL
echo 3. Ir a: http://localhost/phpmyadmin
echo 4. Crear base de datos llamada: animemovil
echo 5. Importar archivo: animemovil.sql
echo 6. Visitar: http://localhost/AnimeMovil/
echo.
echo ==========================================

REM Abrir automáticamente las páginas necesarias
echo ¿Quieres abrir phpMyAdmin ahora? (S/N)
set /p choice=

if /i "%choice%"=="S" (
    start http://localhost/phpmyadmin
    echo.
    echo ✓ phpMyAdmin abierto
    echo   - Crear base de datos 'animemovil'
    echo   - Importar animemovil.sql
    echo.
    echo ¿Abrir el sitio web? (S/N)
    set /p choice2=
    if /i "%choice2%"=="S" (
        start http://localhost/AnimeMovil/
        echo ✓ Sitio web abierto
    )
)

echo.
echo Presiona cualquier tecla para salir...
pause > nul
