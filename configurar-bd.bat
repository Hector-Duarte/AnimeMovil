@echo off
echo ==========================================
echo    Configuración de Base de Datos
echo ==========================================
echo.

echo Este script te ayudará a configurar la base de datos para AnimeMovil
echo.

echo PASO 1: Asegurate de tener MySQL/MariaDB ejecutándose
echo ^(Si usas XAMPP, inicia MySQL desde el panel de control^)
echo.
pause

echo PASO 2: Abriendo phpMyAdmin...
start http://localhost/phpmyadmin
echo.

echo PASO 3: En phpMyAdmin, realiza los siguientes pasos:
echo.
echo 1. Haz clic en "Nueva" para crear una base de datos
echo 2. Nombra la base de datos: animemovil
echo 3. Selecciona la base de datos creada
echo 4. Ve a la pestaña "Importar"
echo 5. Selecciona el archivo: animemovil.sql
echo 6. Haz clic en "Continuar"
echo.

echo ¿Ya completaste la configuración de la base de datos? (S/N)
set /p dbcomplete=

if /i "%dbcomplete%"=="S" (
    echo.
    echo ✓ Base de datos configurada
    echo.
    echo Ahora puedes usar uno de estos métodos para ver tu sitio:
    echo.
    echo OPCIÓN A - Con XAMPP:
    echo   1. Ejecuta: instalar-completo.bat
    echo   2. Visita: http://localhost/AnimeMovil/
    echo.
    echo OPCIÓN B - Servidor local:
    echo   1. Ejecuta: servidor-local.bat  
    echo   2. Visita: http://localhost:8000
    echo.
) else (
    echo.
    echo Completa primero la configuración de la base de datos
    echo y luego ejecuta este script nuevamente.
)

echo.
pause
