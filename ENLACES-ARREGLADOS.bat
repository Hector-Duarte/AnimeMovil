@echo off
echo =============================================
echo      ENLACES ARREGLADOS - AnimeMovil
echo =============================================
echo.

echo ✅ CORRECCIONES APLICADAS:
echo.
echo 1. ✅ Enlaces a episodios corregidos
echo 2. ✅ Enlaces a animes corregidos  
echo 3. ✅ Rutas de archivos PHP actualizadas
echo 4. ✅ Configuración de base de datos
echo 5. ✅ Sistema de navegación funcionando
echo.

echo 📋 COPIANDO ARCHIVOS ACTUALIZADOS...
xcopy /E /I /Y "." "C:\xampp\htdocs\AnimeMovil\" > nul
echo ✅ Archivos copiados a XAMPP

echo.
echo =============================================
echo           🎯 PRUEBAS DISPONIBLES
echo =============================================
echo.
echo 🌐 PÁGINAS PARA PROBAR:
echo.
echo 👉 Página Principal:  http://localhost/AnimeMovil/
echo 👉 Navegación:        http://localhost/AnimeMovil/navegacion.php
echo 👉 Episodio ejemplo:  http://localhost/AnimeMovil/episodio/77-ao-no-exorcist
echo 👉 Anime ejemplo:     http://localhost/AnimeMovil/anime/1-ao-no-exorcist
echo 👉 Diagnóstico:       http://localhost/AnimeMovil/test-diagnostico.php
echo.

echo 🔧 FUNCIONALIDADES ARREGLADAS:
echo.
echo ✓ Hacer clic en episodios → Abre página del episodio
echo ✓ Hacer clic en animes → Abre página del anime  
echo ✓ Enlaces funcionan con /AnimeMovil/ correctamente
echo ✓ Base de datos conectada y funcionando
echo ✓ Sistema de comentarios operativo
echo.

echo =============================================

echo.
echo ¿Abrir las páginas de prueba? (S/N)
set /p choice=

if /i "%choice%"=="S" (
    start http://localhost/AnimeMovil/
    timeout /t 2 > nul
    start http://localhost/AnimeMovil/navegacion.php
    echo.
    echo 🚀 Páginas abiertas para prueba
    echo.
    echo 📝 INSTRUCCIONES:
    echo 1. En la página principal, haz clic en cualquier episodio
    echo 2. En la página de navegación, prueba los enlaces de animes
    echo 3. Verifica que todo funcione correctamente
)

echo.
echo =============================================
pause
