<?php
require '../config.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    redirect('/pages/login.php');
}

// Obtener la información del usuario
$query = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$query->execute([$_SESSION['user_id']]);
$user = $query->fetch();

// Obtener las insignias del usuario
$query = $pdo->prepare("
    SELECT i.nombre, i.imagen 
    FROM usuario_insignias ui
    JOIN insignias i ON ui.insignia_id = i.id
    WHERE ui.usuario_id = ?
");
$query->execute([$_SESSION['user_id']]);
$insignias = $query->fetchAll();

// Decodificar las redes sociales (si existen)
$redes_sociales = !empty($user['redes_sociales']) ? json_decode($user['redes_sociales'], true) : [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - MineHive</title>
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/css/styles.css">
</head>
<body>
    <?php require '../templates/header.php'; ?>

    <main class="profile-container">
        <div class="profile-info">
            <h2>Mi Perfil</h2>
            <div class="profile-picture">
                <img src="<?php echo APP_URL; ?>/pages/mostrar_imagen.php" alt="Foto de perfil">
            </div>
            <div class="profile-details">
                <p><strong>Nombre de Usuario:</strong> <?php echo $user['nombre_usuario']; ?></p>
                <p><strong>Correo Electrónico:</strong> <?php echo $user['email']; ?></p>
                <p><strong>Fecha de Registro:</strong> <?php echo $user['fecha_registro']; ?></p>
                <p><strong>Fecha de Nacimiento:</strong> <?php echo !empty($user['fecha_nacimiento']) ? $user['fecha_nacimiento'] : 'N/A'; ?></p>
                <p><strong>Nombre de Minecraft (Java):</strong> <?php echo !empty($user['minecraft_java']) ? $user['minecraft_java'] : 'N/A'; ?></p>
                <p><strong>Nombre de Minecraft (Bedrock):</strong> <?php echo !empty($user['minecraft_bedrock']) ? $user['minecraft_bedrock'] : 'N/A'; ?></p>
                <p><strong>Nombre de Minecraft (Dungeons):</strong> <?php echo !empty($user['minecraft_dungeons']) ? $user['minecraft_dungeons'] : 'N/A'; ?></p>

                <!-- Mostrar redes sociales -->
                <div class="redes-sociales">
                    <h3>Redes Sociales</h3>
                    <?php if (!empty($redes_sociales)): ?>
                        <?php foreach ($redes_sociales as $red => $url): ?>
                            <div class="red-social">
                                <img src="<?php echo APP_URL; ?>/img/<?php echo $red; ?>.png" alt="<?php echo ucfirst($red); ?>">
                                <a href="<?php echo $url; ?>" target="_blank"><?php echo $url; ?></a>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No hay redes sociales registradas.</p>
                    <?php endif; ?>
                </div>

                <!-- Mostrar insignias -->
                <div class="insignias">
                    <h3>Insignias</h3>
                    <?php if (!empty($insignias)): ?>
                        <?php foreach ($insignias as $insignia): ?>
                            <div class="insignia">
                                <img src="<?php echo APP_URL; ?>/img/<?php echo $insignia['imagen']; ?>" alt="<?php echo $insignia['nombre']; ?>">
                                <span><?php echo $insignia['nombre']; ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No tienes insignias asignadas.</p>
                    <?php endif; ?>
                </div>
            </div>
            <a href="<?php echo APP_URL; ?>/pages/editar_perfil.php" class="btn-primary">Editar Perfil</a>
        </div>
    </main>

    <?php require '../templates/footer.php'; ?>
</body>
</html>