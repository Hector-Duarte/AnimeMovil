<?php
// Archivo de prueba simple para animes
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🧪 Prueba Simple de Anime</h1>";

require_once '../config/config.php';

$anime_id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

echo "<p>Intentando cargar anime ID: $anime_id</p>";

try {
    $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
    
    if($mysqli->connect_errno) {
        throw new Exception("Error de conexión: " . $mysqli->connect_error);
    }
    
    echo "<p>✅ Conexión a base de datos exitosa</p>";
    
    // Consulta simple
    $stmt = $mysqli->prepare("SELECT id, title, slug, sinopsis, status FROM animes WHERE id = ? AND status = 1");
    if(!$stmt) {
        throw new Exception("Error preparando consulta: " . $mysqli->error);
    }
    
    $stmt->bind_param('i', $anime_id);
    
    if(!$stmt->execute()) {
        throw new Exception("Error ejecutando consulta: " . $stmt->error);
    }
    
    $stmt->store_result();
    
    if($stmt->num_rows > 0) {
        $stmt->bind_result($id, $title, $slug, $sinopsis, $status);
        $stmt->fetch();
        
        echo "<div style='background: #e8f5e8; padding: 15px; border: 2px solid green; margin: 10px 0;'>";
        echo "<h2>✅ ANIME CARGADO EXITOSAMENTE</h2>";
        echo "<p><strong>ID:</strong> $id</p>";
        echo "<p><strong>Título:</strong> " . htmlspecialchars($title) . "</p>";
        echo "<p><strong>Slug:</strong> $slug</p>";
        echo "<p><strong>Sinopsis:</strong> " . htmlspecialchars(substr($sinopsis, 0, 200)) . "...</p>";
        echo "<p><strong>Status:</strong> $status</p>";
        echo "</div>";
        
        $stmt->close();
        
        // Obtener episodios de este anime
        echo "<h3>📺 Episodios de este anime:</h3>";
        $stmt2 = $mysqli->prepare("SELECT id, title, numEpi FROM episodios WHERE parentId = ? AND status = 1 ORDER BY numEpi LIMIT 10");
        $stmt2->bind_param('i', $anime_id);
        $stmt2->execute();
        $stmt2->store_result();
        
        if($stmt2->num_rows > 0) {
            $stmt2->bind_result($ep_id, $ep_title, $ep_num);
            echo "<ul>";
            while($stmt2->fetch()) {
                echo "<li><a href='test-episodio-simple.php?id=$ep_id'>Episodio $ep_num: " . htmlspecialchars($ep_title) . "</a></li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No hay episodios para este anime</p>";
        }
        $stmt2->close();
        
    } else {
        echo "<div style='background: #f5e8e8; padding: 15px; border: 2px solid red; margin: 10px 0;'>";
        echo "<h2>❌ NO SE ENCONTRÓ EL ANIME</h2>";
        echo "<p>No existe un anime con ID $anime_id</p>";
        echo "</div>";
    }
    
    $mysqli->close();
    
    echo "<h3>🔗 Enlaces de prueba:</h3>";
    echo "<ul>";
    echo "<li><a href='?id=1'>Anime 1</a></li>";
    echo "<li><a href='?id=2'>Anime 2</a></li>";
    echo "<li><a href='?id=3'>Anime 3</a></li>";
    echo "<li><a href='../pagues/animeIndex.php?id=$anime_id'>Ir a página oficial del anime</a></li>";
    echo "</ul>";
    
} catch(Exception $e) {
    echo "<div style='background: #f5e8e8; padding: 15px; border: 2px solid red; margin: 10px 0;'>";
    echo "<h2>❌ ERROR</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>Archivo:</strong> " . $e->getFile() . "</p>";
    echo "<p><strong>Línea:</strong> " . $e->getLine() . "</p>";
    echo "</div>";
}
?>
