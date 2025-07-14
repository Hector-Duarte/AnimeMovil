<?php
require_once 'config/config.php';

echo "<h1>Insertar Datos de Prueba</h1>";

try {
    $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
    
    if($mysqli->connect_errno){
        echo "<p style='color: red;'>Error de conexión: " . $mysqli->connect_error . "</p>";
        exit;
    }
    
    // Verificar si ya hay datos
    $result = $mysqli->query("SELECT COUNT(*) as total FROM animes");
    $row = $result->fetch_assoc();
    
    echo "<p>Animes actuales en la base de datos: " . $row['total'] . "</p>";
    
    if($row['total'] == 0) {
        echo "<h2>Insertando datos de prueba...</h2>";
        
        // Insertar algunos animes de ejemplo
        $animes = [
            [1, 1, 'Naruto', 'naruto', 1, 'Un ninja joven busca convertirse en Hokage', '2002-10-03', 0, 1, ''],
            [2, 1, 'One Piece', 'one-piece', 1, 'Piratas buscan el tesoro legendario', '1999-10-20', 0, 1, ''],
            [3, 1, 'Attack on Titan', 'attack-on-titan', 0, 'Humanidad lucha contra titanes', '2013-04-07', 0, 1, ''],
            [4, 1, 'Death Note', 'death-note', 0, 'Un estudiante encuentra un cuaderno sobrenatural', '2006-10-04', 0, 1, ''],
            [5, 1, 'Dragon Ball Z', 'dragon-ball-z', 0, 'Goku y sus amigos defienden la Tierra', '1989-04-26', 0, 1, '']
        ];
        
        $stmt = $mysqli->prepare("INSERT INTO animes (id, status, title, slug, simulcasts, sinopsis, emision, nextEpi, collection, message) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        foreach($animes as $anime) {
            $stmt->bind_param('iissiisiss', ...$anime);
            if($stmt->execute()) {
                echo "<p style='color: green;'>✓ Insertado: " . $anime[2] . "</p>";
            } else {
                echo "<p style='color: red;'>✗ Error insertando: " . $anime[2] . " - " . $mysqli->error . "</p>";
            }
        }
        
        // Insertar algunos episodios de ejemplo
        echo "<h2>Insertando episodios de prueba...</h2>";
        
        $episodios = [
            [1, 1, 'Naruto Episodio 1', 'naruto-episodio-1', 1, '', '', 1],
            [2, 1, 'Naruto Episodio 2', 'naruto-episodio-2', 2, '', '', 1],
            [3, 1, 'One Piece Episodio 1', 'one-piece-episodio-1', 1, '', '', 2],
            [4, 1, 'Attack on Titan Episodio 1', 'attack-on-titan-episodio-1', 1, '', '', 3]
        ];
        
        $stmt = $mysqli->prepare("INSERT INTO episodios (id, status, title, slug, numEpi, imgCustom, message, parentId) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        foreach($episodios as $episodio) {
            $stmt->bind_param('iisssssi', ...$episodio);
            if($stmt->execute()) {
                echo "<p style='color: green;'>✓ Insertado episodio: " . $episodio[2] . "</p>";
            } else {
                echo "<p style='color: red;'>✗ Error insertando episodio: " . $episodio[2] . " - " . $mysqli->error . "</p>";
            }
        }
        
        echo "<h2>✅ Datos de prueba insertados correctamente!</h2>";
    } else {
        echo "<p>Ya hay datos en la base de datos. No se insertarán datos de prueba.</p>";
    }
    
    // Mostrar datos actuales
    echo "<h2>Datos actuales:</h2>";
    
    $result = $mysqli->query("SELECT id, title, status FROM animes LIMIT 10");
    if($result->num_rows > 0) {
        echo "<h3>Animes:</h3><ul>";
        while($row = $result->fetch_assoc()) {
            echo "<li>ID: " . $row['id'] . " - " . htmlspecialchars($row['title']) . " (Status: " . $row['status'] . ")</li>";
        }
        echo "</ul>";
    }
    
    $result = $mysqli->query("SELECT e.id, e.title, a.title as anime_title FROM episodios e JOIN animes a ON e.parentId = a.id LIMIT 10");
    if($result->num_rows > 0) {
        echo "<h3>Episodios:</h3><ul>";
        while($row = $result->fetch_assoc()) {
            echo "<li>ID: " . $row['id'] . " - " . htmlspecialchars($row['title']) . " (" . htmlspecialchars($row['anime_title']) . ")</li>";
        }
        echo "</ul>";
    }
    
    $mysqli->close();
    
    echo "<hr>";
    echo "<h3>Probar APIs:</h3>";
    echo "<ul>";
    echo "<li><a href='/AnimeMovil/api/api.php?node=anime'>API Animes</a></li>";
    echo "<li><a href='/AnimeMovil/api/api.php?node=buscador&q=naruto'>API Buscador</a></li>";
    echo "<li><a href='/AnimeMovil/pagues/index.php'>Página Principal</a></li>";
    echo "</ul>";
    
} catch(Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>
