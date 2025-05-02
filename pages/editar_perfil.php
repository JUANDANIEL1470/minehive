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

// Decodificar las redes sociales (si existen)
$redes_sociales = json_decode($user['redes_sociales'], true) ?? [];

// Procesar el formulario de edición
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_usuario = sanitize_input($_POST['nombre_usuario']);
    $email = sanitize_input($_POST['email']);
    $fecha_nacimiento = sanitize_input($_POST['fecha_nacimiento']);
    $minecraft_java = sanitize_input($_POST['minecraft_java']);
    $minecraft_bedrock = sanitize_input($_POST['minecraft_bedrock']);
    $minecraft_dungeons = sanitize_input($_POST['minecraft_dungeons']);
    $redes_sociales = json_encode($_POST['redes_sociales']); // Convertir a JSON

    // Procesar la imagen de perfil
    if (!empty($_POST['foto_perfil_base64'])) {
        $foto_perfil_base64 = $_POST['foto_perfil_base64'];
        $foto_perfil_base64 = str_replace('data:image/jpeg;base64,', '', $foto_perfil_base64);
        $foto_perfil_base64 = str_replace(' ', '+', $foto_perfil_base64);
        $foto_perfil_data = base64_decode($foto_perfil_base64);

        // Guardar la nueva foto de perfil en la base de datos
        $foto_perfil = $foto_perfil_data;
    } else {
        $foto_perfil = $user['foto_perfil'];
    }

    // Actualizar la información del usuario
    try {
        $query = $pdo->prepare("UPDATE usuarios SET nombre_usuario = ?, email = ?, fecha_nacimiento = ?, minecraft_java = ?, minecraft_bedrock = ?, minecraft_dungeons = ?, redes_sociales = ?, foto_perfil = ? WHERE id = ?");
        $query->execute([$nombre_usuario, $email, $fecha_nacimiento, $minecraft_java, $minecraft_bedrock, $minecraft_dungeons, $redes_sociales, $foto_perfil, $_SESSION['user_id']]);

        // Actualizar la foto de perfil en la sesión
        $_SESSION['user_photo'] = 'mostrar_imagen.php';

        redirect('/pages/perfil.php');
    } catch (PDOException $e) {
        $error = "Error al actualizar el perfil. Inténtalo de nuevo.";
    }
}
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil - MineHive</title>
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/css/styles.css">
    <!-- Librería para recortar imágenes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
</head>
<body>
    <?php require '../templates/header.php'; ?>

    <main class="edit-profile-container">
        <div class="edit-profile-form">
            <h2>Editar Perfil</h2>
            <?php if (isset($error)): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="POST" action="" enctype="multipart/form-data">
                <!-- Campo oculto para la imagen recortada -->
                <input type="hidden" id="foto_perfil_base64" name="foto_perfil_base64">

                <!-- Foto de perfil -->
                <div class="form-group">
                    <label for="foto_perfil">Foto de Perfil</label>
                    <div class="profile-picture-edit">
                        <img id="profile-picture-preview" src="<?php echo APP_URL; ?>/pages/mostrar_imagen.php" alt="Foto de perfil">
                        <input type="file" id="foto_perfil" name="foto_perfil" accept="image/*">
                        <div id="image-cropper" style="display: none;">
                            <img id="cropper-image" src="" alt="">
                        </div>
                        <button type="button" id="crop-button" style="display: none;">Recortar</button>
                    </div>
                </div>

                <!-- Nombre de usuario -->
                <div class="form-group">
                    <label for="nombre_usuario">Nombre de Usuario</label>
                    <input type="text" id="nombre_usuario" name="nombre_usuario" value="<?php echo $user['nombre_usuario']; ?>" required>
                </div>

                <!-- Correo electrónico -->
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>
                </div>

                <!-- Fecha de nacimiento -->
                <div class="form-group">
                    <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo $user['fecha_nacimiento']; ?>">
                </div>

                <!-- Nombres de Minecraft -->
                <div class="form-group">
                    <label for="minecraft_java">Nombre de Minecraft (Java)</label>
                    <input type="text" id="minecraft_java" name="minecraft_java" value="<?php echo $user['minecraft_java']; ?>">
                </div>
                <div class="form-group">
                    <label for="minecraft_bedrock">Nombre de Minecraft (Bedrock)</label>
                    <input type="text" id="minecraft_bedrock" name="minecraft_bedrock" value="<?php echo $user['minecraft_bedrock']; ?>">
                </div>
                <div class="form-group">
                    <label for="minecraft_dungeons">Nombre de Minecraft (Dungeons)</label>
                    <input type="text" id="minecraft_dungeons" name="minecraft_dungeons" value="<?php echo $user['minecraft_dungeons']; ?>">
                </div>

                <!-- Redes sociales -->
                <div class="form-group">
                    <label>Redes Sociales</label>
                    <?php
                    $redes_disponibles = ['facebook', 'X-twitter', 'instagram', 'youtube', 'twitch'];
                    foreach ($redes_disponibles as $red): ?>
                        <div class="social-input">
                            <img src="<?php echo APP_URL; ?>/img/<?php echo $red; ?>.png" alt="<?php echo ucfirst($red); ?>">
                            <input type="text" name="redes_sociales[<?php echo $red; ?>]" placeholder="URL de <?php echo ucfirst($red); ?>" value="<?php echo $redes_sociales[$red] ?? ''; ?>">
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Cambiar contraseña -->
                <div class="form-group">
                    <label for="nueva_contraseña">Cambiar Contraseña</label>
                    <input type="password" id="nueva_contraseña" name="nueva_contraseña" placeholder="Nueva contraseña">
                </div>

                <!-- Cambiar correo electrónico -->
                <div class="form-group">
                    <label for="nuevo_email">Cambiar Correo Electrónico</label>
                    <input type="email" id="nuevo_email" name="nuevo_email" placeholder="Nuevo correo electrónico">
                </div>

                <button type="submit" class="btn-primary">Guardar Cambios</button>
            </form>
        </div>
    </main>

    <?php require '../templates/footer.php'; ?>

    <script>
        // Script para el recorte de imágenes
        const imageInput = document.getElementById('foto_perfil');
        const cropperImage = document.getElementById('cropper-image');
        const cropperContainer = document.getElementById('image-cropper');
        const cropButton = document.getElementById('crop-button');
        let cropper;

        imageInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    cropperImage.src = e.target.result;
                    cropperContainer.style.display = 'block';
                    cropButton.style.display = 'block';

                    if (cropper) {
                        cropper.destroy();
                    }
                    cropper = new Cropper(cropperImage, {
                        aspectRatio: 1, // Relación de aspecto 1:1 (cuadrado)
                        viewMode: 1,
                    });
                };
                reader.readAsDataURL(file);
            }
        });

        cropButton.addEventListener('click', function () {
            const croppedCanvas = cropper.getCroppedCanvas();
            const croppedImage = croppedCanvas.toDataURL('image/jpeg');

            // Actualizar la vista previa de la foto de perfil
            const profilePicturePreview = document.getElementById('profile-picture-preview');
            profilePicturePreview.src = croppedImage;

            // Guardar la imagen recortada en el campo oculto
            document.getElementById('foto_perfil_base64').value = croppedImage;

            // Ocultar el recortador
            cropperContainer.style.display = 'none';
            cropButton.style.display = 'none';
        });
    </script>
</body>
</html>