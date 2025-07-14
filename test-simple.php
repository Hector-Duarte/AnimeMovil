<?php
echo "<h1>AnimeMovil Test</h1>";
echo "<p>PHP funciona correctamente!</p>";
echo "<p>Fecha actual: " . date('Y-m-d H:i:s') . "</p>";

// Probar conexión a base de datos
try {
    $pdo = new PDO('mysql:host=localhost;dbname=animemovil', 'root', '');
    echo "<p style='color: green;'>✓ Conexión a base de datos exitosa!</p>";
    
    // Contar animes
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM animes");
    $result = $stmt->fetch();
    echo "<p>Total de animes en la base de datos: " . $result['total'] . "</p>";
    
} catch(PDOException $e) {
    echo "<p style='color: red;'>✗ Error de base de datos: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h3>Enlaces de prueba:</h3>";
echo "<ul>";
echo "<li><a href='/AnimeMovil/pagues/index.php'>Página principal</a></li>";
echo "<li><a href='/AnimeMovil/pagues/animeIndex.php'>Lista de animes</a></li>";
echo "<li><a href='/AnimeMovil/pagues/episodio.php?id=1'>Episodio de prueba</a></li>";
echo "</ul>";
?>
