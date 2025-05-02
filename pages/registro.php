<?php
require '../config.php';

// Verificar si el usuario ya ha iniciado sesión
if (isset($_SESSION['user_id'])) {
    redirect('/index.php');
}

// Procesar el formulario de registro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_usuario = sanitize_input($_POST['nombre_usuario']);
    $email = sanitize_input($_POST['email']);
    $password = sanitize_input($_POST['password']);
    $confirm_password = sanitize_input($_POST['confirm_password']);
    $recaptcha_response = $_POST['g-recaptcha-response'];

    // Verificar reCAPTCHA
    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_data = [
        'secret' => '6LfO9foqAAAAAEGQmFofB94qwmo2vIKLg3RtYs06', // Reemplaza con tu Secret Key
        'response' => $recaptcha_response,
    ];

    $recaptcha_options = [
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'content' => http_build_query($recaptcha_data),
        ],
    ];

    $recaptcha_context = stream_context_create($recaptcha_options);
    $recaptcha_result = file_get_contents($recaptcha_url, false, $recaptcha_context);
    $recaptcha_json = json_decode($recaptcha_result);

    if (!$recaptcha_json->success) {
        $error = "Por favor, verifica que no eres un robot.";
    } elseif ($password !== $confirm_password) {
        $error = "Las contraseñas no coinciden.";
    } else {
        // Insertar el nuevo usuario en la base de datos
        try {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Foto de perfil predeterminada
            $foto_predeterminada = file_get_contents('../img/default_user.png'); // Ruta de la imagen predeterminada

            $query = $pdo->prepare("INSERT INTO usuarios (nombre_usuario, email, contraseña, foto_perfil) VALUES (?, ?, ?, ?)");
            $query->execute([$nombre_usuario, $email, $hashed_password, $foto_predeterminada]);

            // Obtener el ID del usuario recién registrado
            $usuario_id = $pdo->lastInsertId();

            // Asignar la insignia de "Usuario Normal" (ID 4)
            $query = $pdo->prepare("INSERT INTO usuario_insignias (usuario_id, insignia_id) VALUES (?, ?)");
            $query->execute([$usuario_id, 4]);

            // Actualizar la columna `insignia` en la tabla `usuarios`
            $query = $pdo->prepare("UPDATE usuarios SET insignia = ? WHERE id = ?");
            $query->execute(['usuario.png', $usuario_id]); // 'usuario.png' es la imagen de la insignia de usuario normal

            redirect('/pages/login.php');
        } catch (PDOException $e) {
            $error = "Error al registrar el usuario. Inténtalo de nuevo.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - MineHive</title>
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/css/styles-auth.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <?php require '../templates/header.php'; ?>

    <main class="auth-container">
        <div class="auth-form">
            <h2>Regístrate</h2>
            <?php if (isset($error)): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="nombre_usuario">Nombre de Usuario</label>
                    <input type="text" id="nombre_usuario" name="nombre_usuario" required>
                </div>
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <div class="password-input">
                        <input type="password" id="password" name="password" required oninput="checkPasswordStrength(this.value)">
                        <button type="button" class="toggle-password" onclick="togglePasswordVisibility()">👁️</button>
                    </div>
                    <div id="password-strength-bar">
                        <div id="password-strength"></div>
                    </div>
                    <small id="password-strength-text"></small>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Repetir Contraseña</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <div class="form-group">
                    <div class="g-recaptcha" data-sitekey="6LfO9foqAAAAABuCr9tgJ7HCW5joq4rAKGh6Y9cu"></div>
                </div>
                <button type="submit" class="btn-primary">Registrarse</button>
            </form>
            <p>¿Ya tienes una cuenta? <a href="<?php echo APP_URL; ?>/pages/login.php">Inicia sesión aquí</a>.</p>
        </div>
    </main>

    <?php require '../templates/footer.php'; ?>

    <script>
        // Función para mostrar/ocultar la contraseña
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('confirm_password');
            const toggleButton = document.querySelector('.toggle-password');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                confirmPasswordInput.type = 'text';
                toggleButton.textContent = '🙈';
            } else {
                passwordInput.type = 'password';
                confirmPasswordInput.type = 'password';
                toggleButton.textContent = '👁️';
            }
        }

        // Función para verificar la fortaleza de la contraseña
        function checkPasswordStrength(password) {
            const strengthBar = document.getElementById('password-strength');
            const strengthText = document.getElementById('password-strength-text');
            let strength = 0;

            // Verificar longitud
            if (password.length >= 8) strength += 1;
            if (password.length >= 12) strength += 1;

            // Verificar caracteres especiales
            if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) strength += 1;

            // Verificar números
            if (/\d/.test(password)) strength += 1;

            // Verificar mayúsculas y minúsculas
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength += 1;

            // Actualizar la barra y el texto
            switch (strength) {
                case 0:
                case 1:
                    strengthBar.style.width = '20%';
                    strengthBar.style.backgroundColor = '#ff4444';
                    strengthText.textContent = 'Débil';
                    break;
                case 2:
                case 3:
                    strengthBar.style.width = '60%';
                    strengthBar.style.backgroundColor = '#ffcc00';
                    strengthText.textContent = 'Segura';
                    break;
                case 4:
                case 5:
                    strengthBar.style.width = '100%';
                    strengthBar.style.backgroundColor = '#00C851';
                    strengthText.textContent = 'Muy Segura';
                    break;
            }
        }
    </script>
</body>
</html>