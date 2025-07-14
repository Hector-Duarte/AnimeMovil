<?php
// Configurar conexión
try {
    $pdo = new PDO('mysql:host=localhost;dbname=animemovil', 'root', '');
    
    echo "<h2>Lista de Animes Disponibles</h2>";
    
    // Obtener animes
    $stmt = $pdo->query("SELECT id, title, slug, status FROM animes WHERE status = 1 LIMIT 10");
    $animes = $stmt->fetchAll();
    
    if($animes) {
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Título</th><th>Slug</th><th>Enlaces</th></tr>";
        
        foreach($animes as $anime) {
            echo "<tr>";
            echo "<td>" . $anime['id'] . "</td>";
            echo "<td>" . htmlspecialchars($anime['title']) . "</td>";
            echo "<td>" . htmlspecialchars($anime['slug']) . "</td>";
            echo "<td>";
            echo "<a href='/AnimeMovil/pagues/animeIndex.php?id=" . $anime['id'] . "'>Ver Anime</a>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No hay animes en la base de datos</p>";
    }
    
    echo "<hr>";
    echo "<h2>Lista de Episodios Disponibles</h2>";
    
    // Obtener episodios
    $stmt = $pdo->query("SELECT e.id, e.title, e.numEpi, a.title as anime_title FROM episodios e JOIN animes a ON e.parentId = a.id WHERE e.status = 1 LIMIT 10");
    $episodios = $stmt->fetchAll();
    
    if($episodios) {
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Episodio</th><th>Número</th><th>Anime</th><th>Enlaces</th></tr>";
        
        foreach($episodios as $episodio) {
            echo "<tr>";
            echo "<td>" . $episodio['id'] . "</td>";
            echo "<td>" . htmlspecialchars($episodio['title']) . "</td>";
            echo "<td>" . $episodio['numEpi'] . "</td>";
            echo "<td>" . htmlspecialchars($episodio['anime_title']) . "</td>";
            echo "<td>";
            echo "<a href='/AnimeMovil/pagues/episodio.php?id=" . $episodio['id'] . "'>Ver Episodio</a>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No hay episodios en la base de datos</p>";
    }
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
