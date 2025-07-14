@echo off
echo ======================================
echo    Configurando AnimeMovil para XAMPP
echo ======================================

REM Verificar si estamos en la carpeta correcta
if not exist "animemovil.sql" (
    echo ERROR: No se encuentra animemovil.sql
    echo Asegurate de ejecutar este script desde la carpeta AnimeMovil
    pause
    exit
)

echo 1. Copiando archivos de configuracion para XAMPP...
if exist "config\config-xampp.php" (
    copy /y "config\config-xampp.php" "config\config.php" > nul
    echo    - config.php actualizado
) else (
    echo    ADVERTENCIA: config-xampp.php no encontrado
)

if exist "vars_info-xampp.php" (
    copy /y "vars_info-xampp.php" "vars_info.php" > nul
    echo    - vars_info.php actualizado
) else (
    echo    ADVERTENCIA: vars_info-xampp.php no encontrado
)

echo.
echo 2. Actualizando rutas en archivos PHP...
REM Actualizar ruta en index.php de pagues
powershell -Command "(Get-Content 'pagues\index.php') -replace 'c:/Users/Hector Duarte/Desktop/AnimeMovil/vars_info.php', 'C:/xampp/htdocs/AnimeMovil/vars_info.php' | Set-Content 'pagues\index.php'" 2>nul
echo    - Rutas actualizadas en index.php

echo.
echo ======================================
echo        Configuracion completada!
echo ======================================
echo.
echo PASOS PARA EJECUTAR:
echo.
echo 1. Copiar esta carpeta AnimeMovil a C:\xampp\htdocs\
echo 2. Iniciar Apache y MySQL en el panel de XAMPP
echo 3. Ir a http://localhost/phpmyadmin
echo 4. Crear base de datos 'animemovil'
echo 5. Importar el archivo animemovil.sql
echo 6. Visitar http://localhost/AnimeMovil/
echo.
echo ======================================
pause
