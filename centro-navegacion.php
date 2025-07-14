<?php
echo "<h1>🎯 Centro de Navegación AnimeMovil</h1>";

require_once 'config/config.php';

try {
    $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
    
    if($mysqli->connect_errno) {
        echo "<p style='color: red;'>Error de conexión: " . $mysqli->connect_error . "</p>";
        exit;
    }
    
    echo "<div style='max-width: 1200px; margin: 0 auto; padding: 20px;'>";
    
    echo "<h2>🏠 Páginas Principales</h2>";
    echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 10px; margin: 10px 0;'>";
    echo "<ul>";
    echo "<li><a href='/AnimeMovil/pagues/index.php' target='_blank'>📱 Página Principal Original</a></li>";
    echo "<li><a href='/AnimeMovil/' target='_blank'>🏠 Página Principal (URL amigable)</a></li>";
    echo "<li><a href='/AnimeMovil/diagnostico-completo.php' target='_blank'>🔧 Diagnóstico Completo</a></li>";
    echo "</ul>";
    echo "</div>";
    
    echo "<h2>🎬 Animes Disponibles</h2>";
    $result = $mysqli->query("SELECT id, title FROM animes WHERE status = 1 ORDER BY id LIMIT 10");
    
    if($result && $result->num_rows > 0) {
        echo "<div style='display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 15px; margin: 20px 0;'>";
        
        while($row = $result->fetch_assoc()) {
            echo "<div style='background: white; border: 1px solid #ddd; border-radius: 10px; padding: 15px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);'>";
            echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
            echo "<div style='display: flex; flex-direction: column; gap: 8px;'>";
            echo "<a href='/AnimeMovil/pagues/animeIndex.php?id=" . $row['id'] . "' target='_blank' style='background: #007bff; color: white; padding: 8px; text-decoration: none; border-radius: 5px; text-align: center;'>📺 Ver Original</a>";
            echo "<a href='/AnimeMovil/anime-simple.php?id=" . $row['id'] . "' target='_blank' style='background: #28a745; color: white; padding: 8px; text-decoration: none; border-radius: 5px; text-align: center;'>✨ Ver Simplificado</a>";
            echo "</div>";
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "<p>No hay animes disponibles. <a href='/AnimeMovil/insertar-datos.php'>Insertar datos de prueba</a></p>";
    }
    
    echo "<h2>📺 Episodios Disponibles</h2>";
    $result = $mysqli->query("SELECT e.id, e.title, e.numEpi, a.title as anime_title FROM episodios e JOIN animes a ON e.parentId = a.id WHERE e.status = 1 ORDER BY e.id LIMIT 15");
    
    if($result && $result->num_rows > 0) {
        echo "<div style='display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 15px; margin: 20px 0;'>";
        
        while($row = $result->fetch_assoc()) {
            echo "<div style='background: white; border: 1px solid #ddd; border-radius: 10px; padding: 15px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);'>";
            echo "<h4>Ep. " . $row['numEpi'] . ": " . htmlspecialchars($row['title']) . "</h4>";
            echo "<p style='margin: 5px 0; color: #666;'>Anime: " . htmlspecialchars($row['anime_title']) . "</p>";
            echo "<div style='display: flex; flex-direction: column; gap: 8px;'>";
            echo "<a href='/AnimeMovil/pagues/episodio.php?id=" . $row['id'] . "' target='_blank' style='background: #dc3545; color: white; padding: 8px; text-decoration: none; border-radius: 5px; text-align: center;'>🎬 Ver Original</a>";
            echo "<a href='/AnimeMovil/episodio-simple.php?id=" . $row['id'] . "' target='_blank' style='background: #ffc107; color: black; padding: 8px; text-decoration: none; border-radius: 5px; text-align: center;'>⚡ Ver Simplificado</a>";
            echo "</div>";
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "<p>No hay episodios disponibles. <a href='/AnimeMovil/insertar-datos.php'>Insertar datos de prueba</a></p>";
    }
    
    echo "<h2>🔧 Herramientas de Prueba</h2>";
    echo "<div style='background: #e9ecef; padding: 15px; border-radius: 10px; margin: 10px 0;'>";
    echo "<ul>";
    echo "<li><a href='/AnimeMovil/test-navegacion-simple.php' target='_blank'>🧪 Prueba de Navegación</a></li>";
    echo "<li><a href='/AnimeMovil/test-urls.php' target='_blank'>🔗 Prueba de URLs</a></li>";
    echo "<li><a href='/AnimeMovil/test-api.php' target='_blank'>📡 Prueba de APIs</a></li>";
    echo "<li><a href='/AnimeMovil/insertar-datos.php' target='_blank'>➕ Insertar Datos de Prueba</a></li>";
    echo "</ul>";
    echo "</div>";
    
    echo "<h2>📊 Estado del Sistema</h2>";
    $animes_count = $mysqli->query("SELECT COUNT(*) as total FROM animes WHERE status = 1")->fetch_assoc()['total'];
    $episodios_count = $mysqli->query("SELECT COUNT(*) as total FROM episodios WHERE status = 1")->fetch_assoc()['total'];
    
    echo "<div style='background: #d1ecf1; padding: 15px; border-radius: 10px; margin: 10px 0;'>";
    echo "<p>✅ <strong>Animes activos:</strong> $animes_count</p>";
    echo "<p>✅ <strong>Episodios activos:</strong> $episodios_count</p>";
    echo "<p>✅ <strong>Base de datos:</strong> Conectada</p>";
    echo "<p>✅ <strong>Apache:</strong> Funcionando</p>";
    echo "</div>";
    
    echo "</div>";
    
    $mysqli->close();
    
} catch(Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>

<style>
body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 20px; }
h1 { color: #333; text-align: center; }
h2 { color: #555; border-bottom: 2px solid #007bff; padding-bottom: 5px; }
a { color: #007bff; }
a:hover { text-decoration: none; }
</style>
