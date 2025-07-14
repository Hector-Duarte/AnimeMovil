@echo off
echo =============================================
echo    ARREGLANDO RUTAS - AnimeMovil
echo =============================================
echo.

echo Arreglando rutas de assets en todos los archivos PHP...

REM Arreglar rutas en index.php
powershell -Command "(Get-Content 'pagues\index.php') -replace '/assets/', '/AnimeMovil/assets/' | Set-Content 'pagues\index.php'" 2>nul
powershell -Command "(Get-Content 'pagues\index.php') -replace 'href=\"/\"', 'href=\"/AnimeMovil/\"' | Set-Content 'pagues\index.php'" 2>nul

REM Arreglar rutas en episodio.php  
powershell -Command "(Get-Content 'pagues\episodio.php') -replace '/assets/', '/AnimeMovil/assets/' | Set-Content 'pagues\episodio.php'" 2>nul
powershell -Command "(Get-Content 'pagues\episodio.php') -replace 'href=\"/\"', 'href=\"/AnimeMovil/\"' | Set-Content 'pagues\episodio.php'" 2>nul

REM Arreglar rutas en animeIndex.php
powershell -Command "(Get-Content 'pagues\animeIndex.php') -replace '/assets/', '/AnimeMovil/assets/' | Set-Content 'pagues\animeIndex.php'" 2>nul
powershell -Command "(Get-Content 'pagues\animeIndex.php') -replace 'href=\"/\"', 'href=\"/AnimeMovil/\"' | Set-Content 'pagues\animeIndex.php'" 2>nul

REM Arreglar rutas en usuario-entrar.php
powershell -Command "(Get-Content 'pagues\usuario-entrar.php') -replace '/assets/', '/AnimeMovil/assets/' | Set-Content 'pagues\usuario-entrar.php'" 2>nul
powershell -Command "(Get-Content 'pagues\usuario-entrar.php') -replace 'href=\"/\"', 'href=\"/AnimeMovil/\"' | Set-Content 'pagues\usuario-entrar.php'" 2>nul

REM Arreglar rutas en usuario-registrar.php
powershell -Command "(Get-Content 'pagues\usuario-registrar.php') -replace '/assets/', '/AnimeMovil/assets/' | Set-Content 'pagues\usuario-registrar.php'" 2>nul
powershell -Command "(Get-Content 'pagues\usuario-registrar.php') -replace 'href=\"/\"', 'href=\"/AnimeMovil/\"' | Set-Content 'pagues\usuario-registrar.php'" 2>nul

REM Arreglar rutas en usuario-panel.php
powershell -Command "(Get-Content 'pagues\usuario-panel.php') -replace '/assets/', '/AnimeMovil/assets/' | Set-Content 'pagues\usuario-panel.php'" 2>nul
powershell -Command "(Get-Content 'pagues\usuario-panel.php') -replace 'href=\"/\"', 'href=\"/AnimeMovil/\"' | Set-Content 'pagues\usuario-panel.php'" 2>nul

echo ✓ Rutas de assets corregidas
echo.

echo Copiando archivos a XAMPP...
xcopy /E /I /Y "." "C:\xampp\htdocs\AnimeMovil\" > nul

echo ✓ Archivos actualizados en XAMPP
echo.
echo =============================================
echo              ¡LISTO!
echo =============================================
echo.
echo Ahora recarga la página: http://localhost/AnimeMovil/
echo.
pause
