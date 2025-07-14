<?php
echo "<h1>🔧 Diagnóstico de Navegación</h1>";

require_once 'config/config.php';

try {
    $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
    
    if($mysqli->connect_errno) {
        echo "<p style='color: red;'>Error de conexión: " . $mysqli->connect_error . "</p>";
        exit;
    }
    
    echo "<h2>📺 Prueba de Episodios</h2>";
    
    // Probar episodio básico
    $episodio_id = isset($_GET['episodio']) ? $_GET['episodio'] : 1;
    
    echo "<p>Probando episodio ID: $episodio_id</p>";
    
    $stmt = $mysqli->prepare("SELECT id, title, slug, numEpi, parentId FROM episodios WHERE id = ? AND status = 1");
    $stmt->bind_param('i', $episodio_id);
    $stmt->execute();
    $stmt->store_result();
    
    if($stmt->num_rows > 0) {
        $stmt->bind_result($id, $title, $slug, $numEpi, $parentId);
        $stmt->fetch();
        
        echo "<div style='border: 1px solid green; padding: 10px; margin: 10px 0;'>";
        echo "<h3 style='color: green;'>✅ Episodio Encontrado</h3>";
        echo "<p><strong>ID:</strong> $id</p>";
        echo "<p><strong>Título:</strong> " . htmlspecialchars($title) . "</p>";
        echo "<p><strong>Slug:</strong> $slug</p>";
        echo "<p><strong>Número:</strong> $numEpi</p>";
        echo "<p><strong>Anime ID:</strong> $parentId</p>";
        echo "<p><strong>URL Directa:</strong> <a href='/AnimeMovil/pagues/episodio.php?id=$id' target='_blank'>Ver Episodio</a></p>";
        echo "<p><strong>URL Amigable:</strong> <a href='/AnimeMovil/episodio/$id-$slug' target='_blank'>Ver Episodio (Amigable)</a></p>";
        echo "</div>";
    } else {
        echo "<p style='color: red;'>❌ No se encontró el episodio</p>";
    }
    $stmt->close();
    
    echo "<h2>🎬 Prueba de Animes</h2>";
    
    // Probar anime básico
    $anime_id = isset($_GET['anime']) ? $_GET['anime'] : 1;
    
    echo "<p>Probando anime ID: $anime_id</p>";
    
    $stmt = $mysqli->prepare("SELECT id, title, slug, status FROM animes WHERE id = ? AND status = 1");
    $stmt->bind_param('i', $anime_id);
    $stmt->execute();
    $stmt->store_result();
    
    if($stmt->num_rows > 0) {
        $stmt->bind_result($id, $title, $slug, $status);
        $stmt->fetch();
        
        echo "<div style='border: 1px solid green; padding: 10px; margin: 10px 0;'>";
        echo "<h3 style='color: green;'>✅ Anime Encontrado</h3>";
        echo "<p><strong>ID:</strong> $id</p>";
        echo "<p><strong>Título:</strong> " . htmlspecialchars($title) . "</p>";
        echo "<p><strong>Slug:</strong> $slug</p>";
        echo "<p><strong>Status:</strong> $status</p>";
        echo "<p><strong>URL Directa:</strong> <a href='/AnimeMovil/pagues/animeIndex.php?id=$id' target='_blank'>Ver Anime</a></p>";
        echo "<p><strong>URL Amigable:</strong> <a href='/AnimeMovil/anime/$id-$slug' target='_blank'>Ver Anime (Amigable)</a></p>";
        echo "</div>";
    } else {
        echo "<p style='color: red;'>❌ No se encontró el anime</p>";
    }
    $stmt->close();
    
    echo "<h2>🔗 Enlaces de Prueba</h2>";
    echo "<ul>";
    echo "<li><a href='?episodio=1'>Probar Episodio 1</a></li>";
    echo "<li><a href='?episodio=2'>Probar Episodio 2</a></li>";
    echo "<li><a href='?anime=1'>Probar Anime 1</a></li>";
    echo "<li><a href='?anime=2'>Probar Anime 2</a></li>";
    echo "</ul>";
    
    echo "<h2>📋 Todos los Datos Disponibles</h2>";
    
    $result = $mysqli->query("SELECT id, title FROM animes WHERE status = 1 LIMIT 5");
    if($result && $result->num_rows > 0) {
        echo "<h3>Animes:</h3><ul>";
        while($row = $result->fetch_assoc()) {
            echo "<li><a href='/AnimeMovil/pagues/animeIndex.php?id=" . $row['id'] . "'>" . htmlspecialchars($row['title']) . "</a></li>";
        }
        echo "</ul>";
    }
    
    $result = $mysqli->query("SELECT e.id, e.title, a.title as anime_title FROM episodios e JOIN animes a ON e.parentId = a.id WHERE e.status = 1 LIMIT 5");
    if($result && $result->num_rows > 0) {
        echo "<h3>Episodios:</h3><ul>";
        while($row = $result->fetch_assoc()) {
            echo "<li><a href='/AnimeMovil/pagues/episodio.php?id=" . $row['id'] . "'>" . htmlspecialchars($row['title']) . " (" . htmlspecialchars($row['anime_title']) . ")</a></li>";
        }
        echo "</ul>";
    }
    
    $mysqli->close();
    
} catch(Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>
