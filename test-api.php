<?php
echo "<h1>Diagnóstico de API AnimeMovil</h1>";

// Probar configuración
echo "<h2>1. Configuración</h2>";
try {
    require_once 'config/config.php';
    echo "<p style='color: green;'>✓ config.php cargado correctamente</p>";
    echo "<p>HOST: " . HOST . "</p>";
    echo "<p>DATABASE: " . DATABASE . "</p>";
} catch(Exception $e) {
    echo "<p style='color: red;'>✗ Error al cargar config.php: " . $e->getMessage() . "</p>";
}

// Probar conexión a base de datos
echo "<h2>2. Conexión a Base de Datos</h2>";
try {
    $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
    if($mysqli->connect_errno){
        echo "<p style='color: red;'>✗ Error de conexión: " . $mysqli->connect_error . "</p>";
    } else {
        echo "<p style='color: green;'>✓ Conexión a base de datos exitosa</p>";
        
        // Contar registros
        $result = $mysqli->query("SELECT COUNT(*) as total FROM animes WHERE status = 1");
        $row = $result->fetch_assoc();
        echo "<p>Animes activos: " . $row['total'] . "</p>";
        
        $result = $mysqli->query("SELECT COUNT(*) as total FROM episodios WHERE status = 1");
        $row = $result->fetch_assoc();
        echo "<p>Episodios activos: " . $row['total'] . "</p>";
    }
    $mysqli->close();
} catch(Exception $e) {
    echo "<p style='color: red;'>✗ Error de base de datos: " . $e->getMessage() . "</p>";
}

// Probar archivos de API
echo "<h2>3. Archivos de API</h2>";
$api_files = [
    'api/api.php',
    'api/functions.php',
    'api/nodes/anime/api.php',
    'api/nodes/anime/methods/GET.php'
];

foreach($api_files as $file) {
    if(file_exists($file)) {
        echo "<p style='color: green;'>✓ $file existe</p>";
    } else {
        echo "<p style='color: red;'>✗ $file NO EXISTE</p>";
    }
}

// Probar llamadas a API
echo "<h2>4. Pruebas de API</h2>";
echo "<h3>Enlaces de prueba:</h3>";
echo "<ul>";
echo "<li><a href='/AnimeMovil/api/api.php?node=anime&action=get_all'>Obtener todos los animes</a></li>";
echo "<li><a href='/AnimeMovil/api/api.php?node=anime&action=get_by_id&value=1'>Obtener anime ID 1</a></li>";
echo "<li><a href='/AnimeMovil/api/api.php?node=episodio&action=get_all'>Obtener todos los episodios</a></li>";
echo "</ul>";

echo "<h3>Animes disponibles para probar:</h3>";
try {
    $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
    $result = $mysqli->query("SELECT id, title FROM animes WHERE status = 1 LIMIT 5");
    
    if($result->num_rows > 0) {
        echo "<ul>";
        while($row = $result->fetch_assoc()) {
            echo "<li>ID: " . $row['id'] . " - " . htmlspecialchars($row['title']);
            echo " [<a href='/AnimeMovil/api/api.php?node=anime&action=get_by_id&value=" . $row['id'] . "'>API</a>]";
            echo " [<a href='/AnimeMovil/pagues/animeIndex.php?id=" . $row['id'] . "'>Ver</a>]";
            echo "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No hay animes disponibles</p>";
    }
    $mysqli->close();
} catch(Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>
