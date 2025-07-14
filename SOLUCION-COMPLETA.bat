@echo off
echo =============================================
echo    SOLUCION COMPLETA - AnimeMovil
echo =============================================
echo.

echo 1. Verificando XAMPP...
if not exist "C:\xampp\mysql\bin\mysql.exe" (
    echo ❌ XAMPP no encontrado
    echo Por favor instala XAMPP desde: https://www.apachefriends.org/
    pause
    exit
)

echo ✅ XAMPP encontrado

echo.
echo 2. Copiando archivos actualizados...
xcopy /E /I /Y "." "C:\xampp\htdocs\AnimeMovil\" > nul
echo ✅ Archivos copiados

echo.
echo 3. Verificando servicios...
tasklist /FI "IMAGENAME eq httpd.exe" 2>NUL | find /I /N "httpd.exe" > nul
if "%ERRORLEVEL%"=="0" (
    echo ✅ Apache ejecutándose
) else (
    echo ❌ Apache NO está ejecutándose
    echo 👉 Inicia Apache desde el panel de XAMPP
)

tasklist /FI "IMAGENAME eq mysqld.exe" 2>NUL | find /I /N "mysqld.exe" > nul
if "%ERRORLEVEL%"=="0" (
    echo ✅ MySQL ejecutándose
) else (
    echo ❌ MySQL NO está ejecutándose  
    echo 👉 Inicia MySQL desde el panel de XAMPP
)

echo.
echo 4. Verificando archivos críticos...
if exist "C:\xampp\htdocs\AnimeMovil\assets\webApp\app.css" (
    echo ✅ CSS principal
) else (
    echo ❌ CSS principal NO encontrado
)

if exist "C:\xampp\htdocs\AnimeMovil\assets\webApp\logo.png" (
    echo ✅ Logo
) else (
    echo ❌ Logo NO encontrado
)

echo.
echo 5. Creando archivos faltantes...

REM Crear favicon si no existe
if not exist "C:\xampp\htdocs\AnimeMovil\assets\webApp\favicon.png" (
    copy "C:\xampp\htdocs\AnimeMovil\assets\webApp\logo.png" "C:\xampp\htdocs\AnimeMovil\assets\webApp\favicon.png" > nul 2>&1
    echo ✅ Favicon creado
)

REM Crear directorio cache si no existe
if not exist "C:\xampp\htdocs\AnimeMovil\assets\cache\" (
    mkdir "C:\xampp\htdocs\AnimeMovil\assets\cache\" > nul 2>&1
    echo ✅ Directorio cache creado
)

echo.
echo =============================================
echo              RESUMEN FINAL
echo =============================================
echo.
echo 🌐 URLS DISPONIBLES:
echo.
echo 👉 Página principal: http://localhost/AnimeMovil/
echo 👉 Diagnóstico:      http://localhost/AnimeMovil/test-diagnostico.php
echo 👉 phpMyAdmin:       http://localhost/phpmyadmin
echo.
echo 📋 CHECKLIST:
echo.
echo ✓ 1. XAMPP instalado
echo ✓ 2. Apache iniciado
echo ✓ 3. MySQL iniciado  
echo ✓ 4. Base de datos 'animemovil' creada
echo ✓ 5. Archivo animemovil.sql importado
echo.
echo ❓ Si algo no funciona:
echo 1. Revisa el diagnóstico: http://localhost/AnimeMovil/test-diagnostico.php
echo 2. Verifica que Apache y MySQL estén iniciados
echo 3. Confirma que la base de datos existe
echo.
echo =============================================

echo.
echo ¿Abrir el sitio web ahora? (S/N)
set /p choice=

if /i "%choice%"=="S" (
    start http://localhost/AnimeMovil/
    start http://localhost/AnimeMovil/test-diagnostico.php
    echo.
    echo 🚀 Sitio web abierto
    echo 🔧 Diagnóstico abierto
)

echo.
pause
