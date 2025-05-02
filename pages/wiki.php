<?php
require '../config.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    redirect('/pages/login.php');
}

// Obtener todas las wikis
$query = $pdo->prepare("
    SELECT w.*, u.nombre_usuario, u.foto_perfil 
    FROM wiki w
    JOIN usuarios u ON w.usuario_id = u.id
    ORDER BY w.fecha_subida DESC
");
$query->execute();
$wikis = $query->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wiki - MineHive</title>
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/css/styles.css">
    <!-- TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#descripcion',
            plugins: 'advlist link image lists',
            toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright | bullist numlist | link image',
            height: 300,
        });
    </script>
</head>
<body>
    <?php require '../templates/header.php'; ?>

    <main class="wiki-container">
        <h1>Wiki de Minecraft</h1>

        <!-- Botón para abrir el modal de nueva wiki -->
        <button class="btn-primary" onclick="abrirModal()">Crear Nueva Wiki</button>

        <!-- Modal para subir una nueva wiki -->
        <div id="modal-nueva-wiki" class="modal">
            <div class="modal-contenido">
                <span class="cerrar-modal" onclick="cerrarModal()">&times;</span>
                <h2>Subir una Nueva Wiki</h2>
                <form method="POST" action="guardar_wiki.php" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="titulo">Título</label>
                        <input type="text" id="titulo" name="titulo" required>
                    </div>
                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <textarea id="descripcion" name="descripcion"></textarea>
                    </div>
                    <button type="submit" class="btn-primary">Subir Wiki</button>
                </form>
            </div>
        </div>

        <!-- Lista de todas las wikis en cards -->
        <div class="lista-wikis">
            <h2>Wikis Recientes</h2>
            <?php if (empty($wikis)): ?>
                <p>No hay wikis disponibles.</p>
            <?php else: ?>
                <div class="wiki-cards">
                    <?php foreach ($wikis as $wiki): ?>
                        <div class="wiki-card" onclick="window.location.href='wiki_detalle.php?id=<?php echo $wiki['id']; ?>'">
                            <div class="wiki-header">
                                <img src="<?php echo APP_URL; ?>/pages/mostrar_imagen.php?usuario_id=<?php echo $wiki['usuario_id']; ?>" alt="Foto de perfil" class="profile-pic">
                                <div class="wiki-info">
                                    <h3><?php echo $wiki['titulo']; ?></h3>
                                    <p>Subido por: <?php echo $wiki['nombre_usuario']; ?></p>
                                    <p>Fecha: <?php echo $wiki['fecha_subida']; ?></p>
                                </div>
                            </div>
                            <div class="wiki-resumen">
                                <?php echo substr(strip_tags($wiki['descripcion']), 0, 150); ?>...
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php require '../templates/footer.php'; ?>

    <script>
        // Funciones para abrir y cerrar el modal
        function abrirModal() {
            document.getElementById('modal-nueva-wiki').style.display = 'block';
        }

        function cerrarModal() {
            document.getElementById('modal-nueva-wiki').style.display = 'none';
        }
    </script>
</body>
</html>