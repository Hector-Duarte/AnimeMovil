<?php
// Archivo de prueba simple para episodios
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🧪 Prueba Simple de Episodio</h1>";

require_once '../config/config.php';

$episodio_id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

echo "<p>Intentando cargar episodio ID: $episodio_id</p>";

try {
    $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
    
    if($mysqli->connect_errno) {
        throw new Exception("Error de conexión: " . $mysqli->connect_error);
    }
    
    echo "<p>✅ Conexión a base de datos exitosa</p>";
    
    // Consulta simple
    $stmt = $mysqli->prepare("SELECT id, title, slug, numEpi, parentId FROM episodios WHERE id = ? AND status = 1");
    if(!$stmt) {
        throw new Exception("Error preparando consulta: " . $mysqli->error);
    }
    
    $stmt->bind_param('i', $episodio_id);
    
    if(!$stmt->execute()) {
        throw new Exception("Error ejecutando consulta: " . $stmt->error);
    }
    
    $stmt->store_result();
    
    if($stmt->num_rows > 0) {
        $stmt->bind_result($id, $title, $slug, $numEpi, $parentId);
        $stmt->fetch();
        
        echo "<div style='background: #e8f5e8; padding: 15px; border: 2px solid green; margin: 10px 0;'>";
        echo "<h2>✅ EPISODIO CARGADO EXITOSAMENTE</h2>";
        echo "<p><strong>ID:</strong> $id</p>";
        echo "<p><strong>Título:</strong> " . htmlspecialchars($title) . "</p>";
        echo "<p><strong>Slug:</strong> $slug</p>";
        echo "<p><strong>Número:</strong> $numEpi</p>";
        echo "<p><strong>Anime ID:</strong> $parentId</p>";
        echo "</div>";
        
        // Obtener información del anime padre
        $stmt2 = $mysqli->prepare("SELECT title FROM animes WHERE id = ?");
        $stmt2->bind_param('i', $parentId);
        $stmt2->execute();
        $stmt2->bind_result($anime_title);
        if($stmt2->fetch()) {
            echo "<p><strong>Anime:</strong> " . htmlspecialchars($anime_title) . "</p>";
        }
        $stmt2->close();
        
    } else {
        echo "<div style='background: #f5e8e8; padding: 15px; border: 2px solid red; margin: 10px 0;'>";
        echo "<h2>❌ NO SE ENCONTRÓ EL EPISODIO</h2>";
        echo "<p>No existe un episodio con ID $episodio_id</p>";
        echo "</div>";
    }
    
    $stmt->close();
    $mysqli->close();
    
    echo "<h3>🔗 Enlaces de prueba:</h3>";
    echo "<ul>";
    echo "<li><a href='?id=1'>Episodio 1</a></li>";
    echo "<li><a href='?id=2'>Episodio 2</a></li>";
    echo "<li><a href='?id=3'>Episodio 3</a></li>";
    echo "<li><a href='../pagues/episodio.php?id=$episodio_id'>Ir a página oficial del episodio</a></li>";
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
