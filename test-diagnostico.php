<?php
echo "<h1>🔧 Diagnóstico AnimeMovil</h1>";

// Test 1: Verificar configuración
echo "<h2>1. Configuración:</h2>";
require_once("C:\\xampp\\htdocs\\AnimeMovil\\vars_info.php");
echo "✅ vars_info.php cargado<br>";
echo "HOST: " . HOST . "<br>";
echo "DATABASE: " . DATABASE . "<br>";

// Test 2: Conexión a base de datos
echo "<h2>2. Conexión a Base de Datos:</h2>";
try {
    $pdo = new PDO("mysql:host=" . HOST . ";dbname=" . DATABASE, USER, PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Conexión a MySQL exitosa<br>";
    
    // Test 3: Verificar tablas
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "📋 Tablas encontradas: " . count($tables) . "<br>";
    echo "Tablas: " . implode(", ", $tables) . "<br>";
    
    // Test 4: Verificar datos de animes
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM animes");
    $result = $stmt->fetch();
    echo "🎬 Total de animes: " . $result['total'] . "<br>";
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM episodios");
    $result = $stmt->fetch();
    echo "📺 Total de episodios: " . $result['total'] . "<br>";
    
} catch(PDOException $e) {
    echo "❌ Error de conexión: " . $e->getMessage() . "<br>";
}

// Test 5: Verificar archivos
echo "<h2>3. Archivos:</h2>";
$files_to_check = [
    "C:\\xampp\\htdocs\\AnimeMovil\\assets\\webApp\\app.css",
    "C:\\xampp\\htdocs\\AnimeMovil\\assets\\webApp\\logo.png",
    "C:\\xampp\\htdocs\\AnimeMovil\\assets\\webApp\\favicon.png",
    "C:\\xampp\\htdocs\\AnimeMovil\\static\\mensaje.json"
];

foreach($files_to_check as $file) {
    if(file_exists($file)) {
        echo "✅ " . basename($file) . "<br>";
    } else {
        echo "❌ " . basename($file) . " NO ENCONTRADO<br>";
    }
}

// Test 6: Verificar permisos de escritura
echo "<h2>4. Permisos:</h2>";
$cache_dir = "C:\\xampp\\htdocs\\AnimeMovil\\assets\\cache\\";
if(is_writable($cache_dir)) {
    echo "✅ Directorio cache escribible<br>";
} else {
    echo "❌ Directorio cache NO escribible<br>";
}

echo "<hr>";
echo "<h2>🌐 Enlaces de prueba:</h2>";
echo '<a href="/AnimeMovil/">🏠 Inicio</a><br>';
echo '<a href="/AnimeMovil/anime">🔍 Buscar Anime</a><br>';
echo '<a href="/AnimeMovil/entrar">🔑 Login</a><br>';
echo '<a href="/AnimeMovil/registrar">📝 Registro</a><br>';

?>
