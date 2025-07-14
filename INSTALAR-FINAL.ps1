# AnimeMovil - Instalacion Final PowerShell
Write-Host "================================" -ForegroundColor Green
Write-Host "   ANIMEMOVIL - INSTALACION" -ForegroundColor Green
Write-Host "================================" -ForegroundColor Green
Write-Host ""

Write-Host "[1/4] Copiando archivos del proyecto..." -ForegroundColor Yellow
try {
    Copy-Item "c:\Users\Hector Duarte\Desktop\AnimeMovil\*" "C:\xampp\htdocs\AnimeMovil\" -Recurse -Force
    Write-Host "Archivos copiados correctamente ✓" -ForegroundColor Green
} catch {
    Write-Host "Error copiando archivos ✗" -ForegroundColor Red
    Write-Host $_.Exception.Message
    Read-Host "Presiona Enter para continuar"
    exit
}

Write-Host "[2/4] Verificando servicios XAMPP..." -ForegroundColor Yellow

$apache = Get-Process -Name "httpd" -ErrorAction SilentlyContinue
if ($apache) {
    Write-Host "Apache esta ejecutandose ✓" -ForegroundColor Green
} else {
    Write-Host "Apache NO esta ejecutandose ✗" -ForegroundColor Red
    Write-Host "Por favor inicia XAMPP Control Panel y ejecuta Apache"
    Read-Host "Presiona Enter para continuar"
    exit
}

$mysql = Get-Process -Name "mysqld" -ErrorAction SilentlyContinue
if ($mysql) {
    Write-Host "MySQL esta ejecutandose ✓" -ForegroundColor Green
} else {
    Write-Host "MySQL NO esta ejecutandose ✗" -ForegroundColor Red
    Write-Host "Por favor inicia XAMPP Control Panel y ejecuta MySQL"
    Read-Host "Presiona Enter para continuar"
    exit
}

Write-Host "[3/4] Verificando archivos..." -ForegroundColor Yellow
if (Test-Path "C:\xampp\htdocs\AnimeMovil\pagues\index.php") {
    Write-Host "Archivos principales ✓" -ForegroundColor Green
} else {
    Write-Host "Error: Archivos no copiados correctamente ✗" -ForegroundColor Red
    Read-Host "Presiona Enter para continuar"
    exit
}

Write-Host "[4/4] Verificacion final..." -ForegroundColor Yellow
Write-Host ""
Write-Host "================================" -ForegroundColor Green
Write-Host "       INSTALACION COMPLETA" -ForegroundColor Green  
Write-Host "================================" -ForegroundColor Green
Write-Host ""
Write-Host "El sitio web AnimeMovil esta listo para usar:" -ForegroundColor Cyan
Write-Host ""
Write-Host "📱 Pagina principal: http://localhost/AnimeMovil/pagues/index.php" -ForegroundColor White
Write-Host "🔍 Buscar animes: http://localhost/AnimeMovil/pagues/animeIndex.php" -ForegroundColor White
Write-Host "📺 Ver episodios: http://localhost/AnimeMovil/pagues/episodio.php" -ForegroundColor White
Write-Host ""
Write-Host "🛠️  Herramientas de diagnostico:" -ForegroundColor Magenta
Write-Host "   - Probar API: http://localhost/AnimeMovil/test-api.php" -ForegroundColor Gray
Write-Host "   - Ver contenido: http://localhost/AnimeMovil/test-contenido.php" -ForegroundColor Gray
Write-Host "   - Insertar datos: http://localhost/AnimeMovil/insertar-datos.php" -ForegroundColor Gray
Write-Host ""
Write-Host "¡Disfruta de AnimeMovil! 🎬" -ForegroundColor Green
Write-Host ""

# Abrir automaticamente el navegador
Write-Host "Abriendo el sitio en el navegador..." -ForegroundColor Yellow
Start-Process "http://localhost/AnimeMovil/pagues/index.php"

Read-Host "Presiona Enter para cerrar"
