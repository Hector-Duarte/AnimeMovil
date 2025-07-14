<?php
// Versión simplificada de animeIndex.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("config/config.php");

// Validar parámetros
if(!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    http_response_code(404);
    echo "<h1>Error 404</h1><p>Anime no encontrado</p>";
    exit();
}

$anime_id = (int)$_GET["id"];

try {
    // Conectar a base de datos
    $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
    if($mysqli->connect_errno) {
        throw new Exception("Error de conexión: " . $mysqli->connect_error);
    }

    // Obtener información del anime
    $stmt = $mysqli->prepare("SELECT id, title, slug, sinopsis, emision, status FROM animes WHERE id = ? AND status = 1");
    $stmt->bind_param('i', $anime_id);
    $stmt->execute();
    $stmt->store_result();
    
    if($stmt->num_rows === 0) {
        http_response_code(404);
        echo "<h1>Error 404</h1><p>Anime no encontrado</p>";
        exit();
    }
    
    $stmt->bind_result($id, $title, $slug, $sinopsis, $emision, $status);
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
                <a href="/AnimeMovil/anime">Explorar</a>
            </nav>
        </header>
        
        <main>
            <div class="anime-info">
                <div class="anime-header">
                    <div class="anime-poster">
                        <img src="/AnimeMovil/assets/media/anime-<?php echo $id; ?>_grande.jpg" 
                             alt="<?php echo htmlspecialchars($title); ?>" 
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="placeholder-poster" style="display: none;">
                            <span>🎬 ANIME</span>
                        </div>
                    </div>
                    <div class="anime-details">
                        <h1><?php echo htmlspecialchars($title); ?></h1>
                        <p><strong>Fecha de emisión:</strong> <?php echo $emision; ?></p>
                        <p><strong>Estado:</strong> <?php echo $status ? 'Activo' : 'Inactivo'; ?></p>
                        <div class="sinopsis">
                            <h3>📖 Sinopsis:</h3>
                            <p><?php echo htmlspecialchars($sinopsis); ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="episodios-section">
                    <h2>📺 Episodios</h2>
                    <?php
                    // Obtener episodios del anime
                    $stmt = $mysqli->prepare("SELECT id, title, numEpi FROM episodios WHERE parentId = ? AND status = 1 ORDER BY numEpi");
                    $stmt->bind_param('i', $anime_id);
                    $stmt->execute();
                    $stmt->store_result();
                    
                    if($stmt->num_rows > 0) {
                        $stmt->bind_result($ep_id, $ep_title, $ep_num);
                        echo "<div class='episodios-grid'>";
                        while($stmt->fetch()) {
                            echo "<div class='episodio-card'>";
                            echo "<a href='/AnimeMovil/episodio-simple.php?id=$ep_id'>";
                            echo "<div class='episodio-numero'>$ep_num</div>";
                            echo "<div class='episodio-title'>" . htmlspecialchars($ep_title) . "</div>";
                            echo "</a>";
                            echo "</div>";
                        }
                        echo "</div>";
                    } else {
                        echo "<p>No hay episodios disponibles para este anime.</p>";
                    }
                    $stmt->close();
                    ?>
                </div>
                
                <div class="links-section">
                    <h3>🔗 Enlaces útiles:</h3>
                    <ul>
                        <li><a href="/AnimeMovil/episodio-simple.php?id=<?php 
                            // Obtener primer episodio
                            $stmt = $mysqli->prepare("SELECT id FROM episodios WHERE parentId = ? AND status = 1 ORDER BY numEpi LIMIT 1");
                            $stmt->bind_param('i', $anime_id);
                            $stmt->execute();
                            $stmt->bind_result($primer_ep);
                            if($stmt->fetch()) {
                                echo $primer_ep;
                            }
                            $stmt->close();
                        ?>">🎬 Ver primer episodio (Versión simple)</a></li>
                        <li><a href="/AnimeMovil/">🏠 Volver al inicio</a></li>
                    </ul>
                </div>
            </div>
        </main>
    </div>
    
    <style>
    .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
    .anime-info { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .anime-header { display: flex; gap: 20px; margin-bottom: 30px; }
    .anime-poster { width: 200px; height: 280px; flex-shrink: 0; }
    .anime-poster img { width: 100%; height: 100%; object-fit: cover; border-radius: 10px; }
    .placeholder-poster { width: 100%; height: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
                         color: white; display: flex; align-items: center; justify-content: center; 
                         font-weight: bold; border-radius: 10px; }
    .anime-details { flex: 1; }
    .sinopsis { margin-top: 20px; }
    .episodios-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 15px; }
    .episodio-card { background: #f8f9fa; border: 1px solid #ddd; border-radius: 8px; padding: 15px; 
                    transition: all 0.3s ease; }
    .episodio-card:hover { background: #e9ecef; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
    .episodio-card a { text-decoration: none; color: inherit; }
    .episodio-numero { font-size: 18px; font-weight: bold; color: #007bff; margin-bottom: 5px; }
    .episodio-title { font-size: 14px; line-height: 1.4; }
    .links-section { margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; }
    header { background: #333; color: white; padding: 10px; border-radius: 5px; margin-bottom: 20px; 
            display: flex; justify-content: space-between; align-items: center; }
    header nav a { color: white; text-decoration: none; margin: 0 10px; }
    header nav a:hover { text-decoration: underline; }
    </style>
    
    <?php $mysqli->close(); ?>
</body>
</html>
