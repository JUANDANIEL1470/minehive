<?php
require '../config.php';

// Verificar si se ha proporcionado un ID de wiki
if (!isset($_GET['id'])) {
    redirect('/pages/wiki.php');
}

$wiki_id = intval($_GET['id']);

// Obtener la wiki específica
$query = $pdo->prepare("
    SELECT w.*, u.nombre_usuario, u.foto_perfil 
    FROM wiki w
    JOIN usuarios u ON w.usuario_id = u.id
    WHERE w.id = ?
");
$query->execute([$wiki_id]);
$wiki = $query->fetch();

if (!$wiki) {
    redirect('/pages/wiki.php');
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $wiki['titulo']; ?> - MineHive</title>
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/css/styles.css">
</head>
<body>
    <?php require '../templates/header.php'; ?>

    <main class="wiki-detalle-container">
        <h1><?php echo $wiki['titulo']; ?></h1>
        <div class="wiki-autor">
            <img src="<?php echo APP_URL; ?>/pages/mostrar_imagen.php?usuario_id=<?php echo $wiki['usuario_id']; ?>" alt="Foto de perfil" class="profile-pic">
            <p>Subido por: <?php echo $wiki['nombre_usuario']; ?></p>
            <p>Fecha: <?php echo $wiki['fecha_subida']; ?></p>
        </div>
        <div class="wiki-contenido">
            <?php echo $wiki['descripcion']; ?>
        </div>
    </main>

    <?php require '../templates/footer.php'; ?>
</body>
</html>