# AnimeMovil - Reparacion Completa
$Host.UI.RawUI.WindowTitle = "AnimeMovil - Reparacion"

Write-Host "🔧 ANIMEMOVIL - REPARACION COMPLETA" -ForegroundColor Cyan
Write-Host "============================================" -ForegroundColor Cyan
Write-Host ""

# Paso 1: Verificar servicios
Write-Host "📋 [1/6] Verificando servicios XAMPP..." -ForegroundColor Yellow

$apache = Get-Process -Name "httpd" -ErrorAction SilentlyContinue
$mysql = Get-Process -Name "mysqld" -ErrorAction SilentlyContinue

if (!$apache) {
    Write-Host "❌ Apache no está ejecutándose" -ForegroundColor Red
    Write-Host "   Inicia XAMPP Control Panel y ejecuta Apache" -ForegroundColor Yellow
    Read-Host "Presiona Enter cuando Apache esté ejecutándose"
} else {
    Write-Host "✅ Apache ejecutándose" -ForegroundColor Green
}

if (!$mysql) {
    Write-Host "❌ MySQL no está ejecutándose" -ForegroundColor Red
    Write-Host "   Inicia XAMPP Control Panel y ejecuta MySQL" -ForegroundColor Yellow
    Read-Host "Presiona Enter cuando MySQL esté ejecutándose"
} else {
    Write-Host "✅ MySQL ejecutándose" -ForegroundColor Green
}

# Paso 2: Limpiar instalación anterior
Write-Host "🧹 [2/6] Limpiando instalación anterior..." -ForegroundColor Yellow
if (Test-Path "C:\xampp\htdocs\AnimeMovil") {
    Remove-Item "C:\xampp\htdocs\AnimeMovil" -Recurse -Force -ErrorAction SilentlyContinue
    Write-Host "✅ Instalación anterior eliminada" -ForegroundColor Green
}

# Paso 3: Copiar archivos frescos
Write-Host "📂 [3/6] Copiando archivos del proyecto..." -ForegroundColor Yellow
try {
    Copy-Item "c:\Users\Hector Duarte\Desktop\AnimeMovil\*" "C:\xampp\htdocs\AnimeMovil\" -Recurse -Force
    Write-Host "✅ Archivos copiados correctamente" -ForegroundColor Green
} catch {
    Write-Host "❌ Error copiando archivos: $_" -ForegroundColor Red
    Read-Host "Presiona Enter para continuar"
}

# Paso 4: Crear carpetas necesarias
Write-Host "📁 [4/6] Creando carpetas necesarias..." -ForegroundColor Yellow
$folders = @(
    "C:\xampp\htdocs\AnimeMovil\assets\media",
    "C:\xampp\htdocs\AnimeMovil\assets\cache",
    "C:\xampp\htdocs\AnimeMovil\stream\cache"
)

foreach ($folder in $folders) {
    if (!(Test-Path $folder)) {
        New-Item -ItemType Directory -Path $folder -Force | Out-Null
        Write-Host "✅ Creada: $folder" -ForegroundColor Green
    }
}

# Paso 5: Configurar permisos
Write-Host "🔐 [5/6] Configurando permisos..." -ForegroundColor Yellow
try {
    # Dar permisos de escritura a las carpetas de cache
    $acl = Get-Acl "C:\xampp\htdocs\AnimeMovil\assets\cache"
    $accessRule = New-Object System.Security.AccessControl.FileSystemAccessRule("Everyone","FullControl","Allow")
    $acl.SetAccessRule($accessRule)
    Set-Acl "C:\xampp\htdocs\AnimeMovil\assets\cache" $acl
    Write-Host "✅ Permisos configurados" -ForegroundColor Green
} catch {
    Write-Host "⚠️ Advertencia: No se pudieron configurar algunos permisos" -ForegroundColor Yellow
}

# Paso 6: Verificación final
Write-Host "🔍 [6/6] Verificación final..." -ForegroundColor Yellow

$files_critical = @(
    "C:\xampp\htdocs\AnimeMovil\pagues\index.php",
    "C:\xampp\htdocs\AnimeMovil\config\config.php",
    "C:\xampp\htdocs\AnimeMovil\.htaccess",
    "C:\xampp\htdocs\AnimeMovil\api\api.php"
)

$all_good = $true
foreach ($file in $files_critical) {
    if (Test-Path $file) {
        Write-Host "✅ $(Split-Path $file -Leaf)" -ForegroundColor Green
    } else {
        Write-Host "❌ $(Split-Path $file -Leaf) - NO ENCONTRADO" -ForegroundColor Red
        $all_good = $false
    }
}

Write-Host ""
Write-Host "============================================" -ForegroundColor Cyan

if ($all_good) {
    Write-Host "🎉 REPARACION COMPLETADA EXITOSAMENTE" -ForegroundColor Green
    Write-Host ""
    Write-Host "🌐 URLs para probar:" -ForegroundColor Cyan
    Write-Host "   📋 Diagnóstico: http://localhost/AnimeMovil/diagnostico-completo.php" -ForegroundColor White
    Write-Host "   🔗 Prueba URLs: http://localhost/AnimeMovil/test-urls.php" -ForegroundColor White
    Write-Host "   🏠 Página Principal: http://localhost/AnimeMovil/pagues/index.php" -ForegroundColor White
    Write-Host "   ➕ Insertar Datos: http://localhost/AnimeMovil/insertar-datos.php" -ForegroundColor White
    Write-Host ""
    Write-Host "🚀 Abriendo diagnóstico en el navegador..." -ForegroundColor Yellow
    Start-Process "http://localhost/AnimeMovil/diagnostico-completo.php"
} else {
    Write-Host "❌ REPARACION INCOMPLETA" -ForegroundColor Red
    Write-Host "   Algunos archivos críticos no se encontraron." -ForegroundColor Yellow
}

Write-Host ""
Read-Host "Presiona Enter para cerrar"
