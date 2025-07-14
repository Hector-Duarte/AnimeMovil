<?php
echo "<h1>🔗 Prueba de URLs y Navegación</h1>";

require_once 'config/config.php';

try {
    $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
    
    if($mysqli->connect_errno) {
        echo "<p style='color: red;'>Error de conexión: " . $mysqli->connect_error . "</p>";
        exit;
    }
    
    echo "<h2>📺 Episodios Disponibles</h2>";
    
    $result = $mysqli->query("SELECT e.id, e.title, e.slug, a.title as anime_title, a.id as anime_id, a.slug as anime_slug FROM episodios e JOIN animes a ON e.parentId = a.id WHERE e.status = 1 LIMIT 5");
    
    if($result && $result->num_rows > 0) {
        echo "<table border='1' style='width: 100%; border-collapse: collapse;'>";
        echo "<tr><th>Episodio</th><th>Anime</th><th>URL Directa</th><th>URL Amigable</th><th>Pruebas</th></tr>";
        
        while($row = $result->fetch_assoc()) {
            $url_directa = "/AnimeMovil/pagues/episodio.php?id=" . $row['id'];
            $url_amigable = "/AnimeMovil/episodio/" . $row['id'] . "-" . $row['slug'];
            
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
            echo "<td>" . htmlspecialchars($row['anime_title']) . "</td>";
            echo "<td><a href='$url_directa' target='_blank'>Directa</a></td>";
            echo "<td><a href='$url_amigable' target='_blank'>Amigable</a></td>";
            echo "<td>";
            echo "<a href='$url_directa' target='_blank' style='background: green; color: white; padding: 2px 5px; text-decoration: none; margin: 2px;'>✓ Test</a>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    echo "<h2>🎬 Animes Disponibles</h2>";
    
    $result = $mysqli->query("SELECT id, title, slug FROM animes WHERE status = 1 LIMIT 5");
    
    if($result && $result->num_rows > 0) {
        echo "<table border='1' style='width: 100%; border-collapse: collapse;'>";
        echo "<tr><th>Anime</th><th>URL Directa</th><th>URL Amigable</th><th>Pruebas</th></tr>";
        
        while($row = $result->fetch_assoc()) {
            $url_directa = "/AnimeMovil/pagues/animeIndex.php?id=" . $row['id'];
            $url_amigable = "/AnimeMovil/anime/" . $row['id'] . "-" . $row['slug'];
            
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
            echo "<td><a href='$url_directa' target='_blank'>Directa</a></td>";
            echo "<td><a href='$url_amigable' target='_blank'>Amigable</a></td>";
            echo "<td>";
            echo "<a href='$url_directa' target='_blank' style='background: green; color: white; padding: 2px 5px; text-decoration: none; margin: 2px;'>✓ Test</a>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    echo "<h2>🔧 Configuración Apache</h2>";
    
    // Verificar si mod_rewrite está habilitado
    if(function_exists('apache_get_modules')) {
        $modules = apache_get_modules();
        if(in_array('mod_rewrite', $modules)) {
            echo "<p style='color: green;'>✅ mod_rewrite está habilitado</p>";
        } else {
            echo "<p style='color: red;'>❌ mod_rewrite NO está habilitado</p>";
        }
    } else {
        echo "<p style='color: orange;'>⚠️ No se puede verificar mod_rewrite (función no disponible)</p>";
    }
    
    // Verificar si .htaccess existe
    if(file_exists('.htaccess')) {
        echo "<p style='color: green;'>✅ .htaccess existe</p>";
        echo "<details>";
        echo "<summary>Ver contenido .htaccess</summary>";
        echo "<pre>" . htmlspecialchars(file_get_contents('.htaccess')) . "</pre>";
        echo "</details>";
    } else {
        echo "<p style='color: red;'>❌ .htaccess NO existe</p>";
    }
    
    echo "<h2>🏠 Enlaces de Navegación</h2>";
    echo "<ul>";
    echo "<li><a href='/AnimeMovil/pagues/index.php'>🏠 Página Principal</a></li>";
    echo "<li><a href='/AnimeMovil/'>🏠 Página Principal (Root)</a></li>";
    echo "<li><a href='/AnimeMovil/anime'>🔍 Buscador</a></li>";
    echo "<li><a href='/AnimeMovil/diagnostico-completo.php'>🔧 Diagnóstico Completo</a></li>";
    echo "</ul>";
    
    $mysqli->close();
    
} catch(Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>
