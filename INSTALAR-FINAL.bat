@echo off
title AnimeMovil - Instalacion Final
color 0A

echo.
echo ================================
echo    ANIMEMOVIL - INSTALACION
echo ================================
echo.

echo [1/4] Copiando archivos del proyecto...
xcopy /s /e /y "c:\Users\Hector Duarte\Desktop\AnimeMovil\*" "C:\xampp\htdocs\AnimeMovil\" > nul

echo [2/4] Verificando servicios XAMPP...
tasklist /FI "IMAGENAME eq httpd.exe" 2>NUL | find /I /N "httpd.exe" > nul
if "%ERRORLEVEL%"=="0" (
    echo Apache esta ejecutandose ✓
) else (
    echo Apache NO esta ejecutandose ✗
    echo Por favor inicia XAMPP Control Panel y ejecuta Apache
    pause
    exit
)

tasklist /FI "IMAGENAME eq mysqld.exe" 2>NUL | find /I /N "mysqld.exe" > nul
if "%ERRORLEVEL%"=="0" (
    echo MySQL esta ejecutandose ✓
) else (
    echo MySQL NO esta ejecutandose ✗
    echo Por favor inicia XAMPP Control Panel y ejecuta MySQL
    pause
    exit
)

echo [3/4] Verificando archivos...
if exist "C:\xampp\htdocs\AnimeMovil\pagues\index.php" (
    echo Archivos principales ✓
) else (
    echo Error: Archivos no copiados correctamente ✗
    pause
    exit
)

echo [4/4] Verificacion final...
echo.
echo ================================
echo        INSTALACION COMPLETA
echo ================================
echo.
echo El sitio web AnimeMovil esta listo para usar:
echo.
echo 📱 Pagina principal: http://localhost/AnimeMovil/pagues/index.php
echo 🔍 Buscar animes: http://localhost/AnimeMovil/pagues/animeIndex.php
echo 📺 Ver episodios: http://localhost/AnimeMovil/pagues/episodio.php
echo.
echo 🛠️  Herramientas de diagnostico:
echo    - Probar API: http://localhost/AnimeMovil/test-api.php
echo    - Ver contenido: http://localhost/AnimeMovil/test-contenido.php
echo    - Insertar datos: http://localhost/AnimeMovil/insertar-datos.php
echo.
echo ¡Disfruta de AnimeMovil! 🎬
echo.
pause
