<?php
require_once("C:\\xampp\\htdocs\\AnimeMovil\\vars_info.php");

echo "<h1>🎬 Navegación AnimeMovil</h1>";
echo "<style>
body { font-family: Arial, sans-serif; margin: 20px; }
.anime-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; margin: 20px 0; }
.anime-card { border: 1px solid #ddd; padding: 15px; border-radius: 8px; background: #f9f9f9; }
.anime-card h3 { margin: 0 0 10px 0; color: #333; }
.anime-card p { margin: 5px 0; color: #666; }
.anime-card a { display: inline-block; padding: 8px 15px; background: #007cba; color: white; text-decoration: none; border-radius: 4px; margin: 5px 5px 0 0; }
.anime-card a:hover { background: #005a87; }
</style>";

try {
    $pdo = new PDO("mysql:host=" . HOST . ";dbname=" . DATABASE, USER, PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Obtener animes
    echo "<h2>📚 Animes Disponibles:</h2>";
    $stmt = $pdo->query("SELECT id, title, slug, sinopsis FROM animes WHERE status = 1 ORDER BY title");
    $animes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($animes) > 0) {
        echo "<div class='anime-grid'>";
        foreach ($animes as $anime) {
            echo "<div class='anime-card'>";
            echo "<h3>" . htmlspecialchars($anime['title']) . "</h3>";
            echo "<p>" . htmlspecialchars(substr($anime['sinopsis'], 0, 100)) . "...</p>";
            
            // Enlace a la página del anime
            echo "<a href='/AnimeMovil/anime/" . $anime['id'] . "-" . $anime['slug'] . "'>Ver Anime</a>";
            
            // Obtener episodios de este anime
            $stmt_eps = $pdo->prepare("SELECT id, title, slug, numEpi FROM episodios WHERE parentId = ? AND status = 1 ORDER BY numEpi LIMIT 5");
            $stmt_eps->execute([$anime['id']]);
            $episodios = $stmt_eps->fetchAll(PDO::FETCH_ASSOC);
            
            if (count($episodios) > 0) {
                echo "<br><strong>Episodios:</strong><br>";
                foreach ($episodios as $ep) {
                    echo "<a href='/AnimeMovil/episodio/" . $ep['id'] . "-" . $ep['slug'] . "'>Ep " . $ep['numEpi'] . "</a>";
                }
                if (count($episodios) == 5) {
                    echo "<a href='/AnimeMovil/anime/" . $anime['id'] . "-" . $anime['slug'] . "'>Ver más...</a>";
                }
            }
            
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "<p>No se encontraron animes.</p>";
    }
    
    // Obtener episodios recientes
    echo "<h2>📺 Episodios Recientes:</h2>";
    $stmt = $pdo->query("SELECT e.id, e.title, e.slug, e.numEpi, a.title as anime_title, a.slug as anime_slug, a.id as anime_id 
                         FROM episodios e 
                         JOIN animes a ON e.parentId = a.id 
                         WHERE e.status = 1 
                         ORDER BY e.id DESC LIMIT 10");
    $episodios_recientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($episodios_recientes) > 0) {
        echo "<div class='anime-grid'>";
        foreach ($episodios_recientes as $ep) {
            echo "<div class='anime-card'>";
            echo "<h3>" . htmlspecialchars($ep['anime_title']) . " - Episodio " . $ep['numEpi'] . "</h3>";
            echo "<p>" . htmlspecialchars($ep['title']) . "</p>";
            echo "<a href='/AnimeMovil/episodio/" . $ep['id'] . "-" . $ep['slug'] . "'>Ver Episodio</a>";
            echo "<a href='/AnimeMovil/anime/" . $ep['anime_id'] . "-" . $ep['anime_slug'] . "'>Ver Anime</a>";
            echo "</div>";
        }
        echo "</div>";
    }
    
} catch(PDOException $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h2>🔧 Enlaces de Prueba:</h2>";
echo "<p><a href='/AnimeMovil/'>← Volver al Inicio</a></p>";
echo "<p><a href='/AnimeMovil/anime'>🔍 Buscar Anime</a></p>";
echo "<p><a href='/AnimeMovil/test-diagnostico.php'>🔧 Diagnóstico</a></p>";

?>
