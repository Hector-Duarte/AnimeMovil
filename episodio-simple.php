<?php
// Versión simplificada de episodio.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("config/config.php");

// Validar parámetros
if(!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    http_response_code(404);
    echo "<h1>Error 404</h1><p>Episodio no encontrado</p>";
    exit();
}

$episodio_id = (int)$_GET["id"];

try {
    // Conectar a base de datos
    $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
    if($mysqli->connect_errno) {
        throw new Exception("Error de conexión: " . $mysqli->connect_error);
    }

    // Obtener información del episodio
    $stmt = $mysqli->prepare("SELECT e.id, e.title, e.slug, e.numEpi, e.parentId, a.title as anime_title, a.slug as anime_slug FROM episodios e JOIN animes a ON e.parentId = a.id WHERE e.id = ? AND e.status = 1");
    $stmt->bind_param('i', $episodio_id);
    $stmt->execute();
    $stmt->store_result();
    
    if($stmt->num_rows === 0) {
        http_response_code(404);
        echo "<h1>Error 404</h1><p>Episodio no encontrado</p>";
        exit();
    }
    
    $stmt->bind_result($id, $title, $slug, $numEpi, $parentId, $anime_title, $anime_slug);
    $stmt->fetch();
    $stmt->close();
    
} catch(Exception $e) {
    echo "<h1>Error</h1><p>" . htmlspecialchars($e->getMessage()) . "</p>";
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($title); ?> - AnimeMovil</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/AnimeMovil/assets/webApp/app.css"/>
    <link rel="stylesheet" type="text/css" href="/AnimeMovil/assets/webApp/mejoras.css"/>
</head>
<body>
    <div class="container">
        <header>
            <div class="logo">
                <a href="/AnimeMovil/" title="Página principal">
                    <img src="/AnimeMovil/assets/webApp/logo.png" alt="AnimeMovil"/>
                </a>
            </div>
            <nav>
                <a href="/AnimeMovil/">Inicio</a>
                <a href="/AnimeMovil/anime-simple.php?id=<?php echo $parentId; ?>">Volver al Anime</a>
            </nav>
        </header>
        
        <main>
            <div class="episodio-info">
                <h1><?php echo htmlspecialchars($title); ?></h1>
                <p><strong>Episodio:</strong> <?php echo $numEpi; ?></p>
                <p><strong>Anime:</strong> 
                    <a href="/AnimeMovil/anime-simple.php?id=<?php echo $parentId; ?>">
                        <?php echo htmlspecialchars($anime_title); ?>
                    </a>
                </p>
                
                <div class="video-container">
                    <div style="background: #333; color: white; padding: 50px; text-align: center; border-radius: 10px;">
                        <h3>🎬 Reproductor de Video</h3>
                        <p>Aquí iría el reproductor del episodio</p>
                        <p>ID del episodio: <?php echo $id; ?></p>
                    </div>
                </div>
                
                <div class="navegacion-episodios">
                    <h3>📺 Otros episodios:</h3>
                    <?php
                    // Obtener otros episodios del mismo anime
                    $stmt = $mysqli->prepare("SELECT id, title, numEpi FROM episodios WHERE parentId = ? AND status = 1 ORDER BY numEpi");
                    $stmt->bind_param('i', $parentId);
                    $stmt->execute();
                    $stmt->store_result();
                    
                    if($stmt->num_rows > 0) {
                        $stmt->bind_result($ep_id, $ep_title, $ep_num);
                        echo "<ul class='episodios-lista'>";
                        while($stmt->fetch()) {
                            $clase = ($ep_id == $id) ? 'actual' : '';
                            echo "<li class='$clase'>";
                            echo "<a href='/AnimeMovil/episodio-simple.php?id=$ep_id'>";
                            echo "Ep. $ep_num: " . htmlspecialchars($ep_title);
                            echo "</a>";
                            echo "</li>";
                        }
                        echo "</ul>";
                    }
                    $stmt->close();
                    $mysqli->close();
                    ?>
                </div>
            </div>
        </main>
    </div>
    
    <style>
    .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
    .episodio-info { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .video-container { margin: 20px 0; }
    .episodios-lista { list-style: none; padding: 0; }
    .episodios-lista li { margin: 5px 0; padding: 10px; background: #f5f5f5; border-radius: 5px; }
    .episodios-lista li.actual { background: #e8f5e8; border: 2px solid green; }
    .episodios-lista a { text-decoration: none; color: #333; }
    .episodios-lista a:hover { color: #007bff; }
    header { background: #333; color: white; padding: 10px; border-radius: 5px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
    header nav a { color: white; text-decoration: none; margin: 0 10px; }
    header nav a:hover { text-decoration: underline; }
    </style>
</body>
</html>
