<?php
echo "<h1>🔧 Diagnóstico Completo AnimeMovil</h1>";

function test_status($condition, $message) {
    if($condition) {
        echo "<p style='color: green; margin: 5px 0;'>✅ $message</p>";
        return true;
    } else {
        echo "<p style='color: red; margin: 5px 0;'>❌ $message</p>";
        return false;
    }
}

// 1. Configuración básica
echo "<h2>1. 📋 Configuración Básica</h2>";
$config_loaded = false;
try {
    require_once 'config/config.php';
    $config_loaded = true;
    test_status(true, "config.php cargado");
    echo "<p>📊 HOST: " . HOST . "</p>";
    echo "<p>📊 DATABASE: " . DATABASE . "</p>";
} catch(Exception $e) {
    test_status(false, "Error en config.php: " . $e->getMessage());
}

// 2. Conexión a base de datos
echo "<h2>2. 🗄️ Base de Datos</h2>";
$db_connected = false;
if($config_loaded) {
    try {
        $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
        if(!$mysqli->connect_errno) {
            $db_connected = true;
            test_status(true, "Conexión a MySQL exitosa");
            
            // Verificar tablas
            $tables = ['animes', 'episodios', 'usuarios', 'comentarios'];
            foreach($tables as $table) {
                $result = $mysqli->query("SHOW TABLES LIKE '$table'");
                test_status($result->num_rows > 0, "Tabla '$table' existe");
            }
            
            // Contar registros
            $result = $mysqli->query("SELECT COUNT(*) as total FROM animes WHERE status = 1");
            if($result) {
                $row = $result->fetch_assoc();
                test_status($row['total'] > 0, "Animes disponibles: " . $row['total']);
            }
            
            $result = $mysqli->query("SELECT COUNT(*) as total FROM episodios WHERE status = 1");
            if($result) {
                $row = $result->fetch_assoc();
                test_status($row['total'] > 0, "Episodios disponibles: " . $row['total']);
            }
            
        } else {
            test_status(false, "Error de conexión MySQL: " . $mysqli->connect_error);
        }
    } catch(Exception $e) {
        test_status(false, "Error de base de datos: " . $e->getMessage());
    }
}

// 3. Archivos del sistema
echo "<h2>3. 📁 Archivos del Sistema</h2>";
$files_to_check = [
    'pagues/index.php' => 'Página principal',
    'pagues/episodio.php' => 'Página de episodios',
    'pagues/animeIndex.php' => 'Página de animes',
    'api/api.php' => 'API principal',
    'api/nodes/anime/methods/GET.php' => 'API animes',
    'api/nodes/buscador/methods/GET.php' => 'API buscador',
    'assets/webApp/app.js' => 'JavaScript principal',
    'assets/webApp/app.css' => 'CSS principal',
    'assets/webApp/mejoras.css' => 'CSS mejoras',
    '.htaccess' => 'Configuración Apache'
];

foreach($files_to_check as $file => $description) {
    test_status(file_exists($file), "$description ($file)");
}

// 4. Pruebas de API
echo "<h2>4. 🔌 APIs</h2>";
if($db_connected) {
    // Probar API anime
    echo "<h3>API Animes:</h3>";
    $api_urls = [
        '/AnimeMovil/api/api.php?node=anime' => 'Listar animes',
        '/AnimeMovil/api/api.php?node=anime&value=1' => 'Anime por ID',
        '/AnimeMovil/api/api.php?node=buscador&q=test&limit=5' => 'Buscador'
    ];
    
    foreach($api_urls as $url => $desc) {
        echo "<p>🔗 <a href='$url' target='_blank'>$desc</a> ($url)</p>";
    }
}

// 5. Enlaces de navegación
echo "<h2>5. 🧭 Navegación</h2>";
if($db_connected) {
    try {
        $result = $mysqli->query("SELECT id, title FROM animes WHERE status = 1 LIMIT 3");
        if($result && $result->num_rows > 0) {
            echo "<h3>Animes de prueba:</h3>";
            while($row = $result->fetch_assoc()) {
                $anime_url = "/AnimeMovil/pagues/animeIndex.php?id=" . $row['id'];
                echo "<p>🎬 <a href='$anime_url' target='_blank'>" . htmlspecialchars($row['title']) . "</a></p>";
            }
        }
        
        $result = $mysqli->query("SELECT e.id, e.title, a.title as anime_title FROM episodios e JOIN animes a ON e.parentId = a.id WHERE e.status = 1 LIMIT 3");
        if($result && $result->num_rows > 0) {
            echo "<h3>Episodios de prueba:</h3>";
            while($row = $result->fetch_assoc()) {
                $episodio_url = "/AnimeMovil/pagues/episodio.php?id=" . $row['id'];
                echo "<p>📺 <a href='$episodio_url' target='_blank'>" . htmlspecialchars($row['title']) . " (" . htmlspecialchars($row['anime_title']) . ")</a></p>";
            }
        }
        
        $mysqli->close();
    } catch(Exception $e) {
        echo "<p style='color: red;'>Error obteniendo enlaces: " . $e->getMessage() . "</p>";
    }
}

// 6. Enlaces principales
echo "<h2>6. 🏠 Enlaces Principales</h2>";
$main_urls = [
    '/AnimeMovil/pagues/index.php' => '🏠 Página Principal',
    '/AnimeMovil/pagues/animeZone/buscador.php' => '🔍 Buscador',
    '/AnimeMovil/insertar-datos.php' => '➕ Insertar Datos de Prueba'
];

foreach($main_urls as $url => $desc) {
    echo "<p><a href='$url' target='_blank'>$desc</a></p>";
}

// 7. Logs de errores recientes
echo "<h2>7. 📝 Errores Recientes</h2>";
$error_log = 'C:\xampp\apache\logs\error.log';
if(file_exists($error_log)) {
    $errors = file($error_log);
    $recent_errors = array_slice($errors, -10); // Últimos 10 errores
    
    echo "<div style='background: #f5f5f5; padding: 10px; border-radius: 5px; max-height: 200px; overflow-y: auto;'>";
    foreach($recent_errors as $error) {
        if(strpos($error, 'AnimeMovil') !== false) {
            echo "<p style='font-size: 12px; margin: 2px 0;'>" . htmlspecialchars($error) . "</p>";
        }
    }
    echo "</div>";
} else {
    echo "<p>No se puede acceder al log de errores</p>";
}

echo "<hr>";
echo "<h2>🎯 Próximos Pasos Recomendados:</h2>";
echo "<ol>";
echo "<li>Si hay animes pero no episodios, ejecuta 'Insertar Datos de Prueba'</li>";
echo "<li>Verifica que las APIs respondan correctamente</li>";
echo "<li>Prueba la navegación entre páginas</li>";
echo "<li>Revisa los errores recientes si los hay</li>";
echo "</ol>";
?>
