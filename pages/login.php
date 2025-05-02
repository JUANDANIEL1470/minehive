<?php
require '../config.php';

// Verificar si el usuario ya ha iniciado sesión
if (isset($_SESSION['user_id'])) {
    redirect('/index.php');
}

// Procesar el formulario de inicio de sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize_input($_POST['email']);
    $password = sanitize_input($_POST['password']);
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
    } else {
        // Buscar el usuario en la base de datos
        $query = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $query->execute([$email]);
        $user = $query->fetch();

        // Verificar la contraseña
        if ($user && password_verify($password, $user['contraseña'])) {
            // Iniciar sesión
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_photo'] = !empty($user['foto_perfil']) ? 'mostrar_imagen.php' : 'img/default_user.png';
            redirect('/index.php');
        } else {
            $error = "Correo electrónico o contraseña incorrectos.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - MineHive</title>
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/css/styles-auth.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <?php require '../templates/header.php'; ?>

    <main class="auth-container">
        <div class="auth-form">
            <h2>Iniciar Sesión</h2>
            <?php if (isset($error)): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <div class="password-input">
                        <input type="password" id="password" name="password" required>
                        <button type="button" class="toggle-password" onclick="togglePasswordVisibility()">👁️</button>
                    </div>
                </div>
                <div class="form-group">
                    <div class="g-recaptcha" data-sitekey="6LfO9foqAAAAABuCr9tgJ7HCW5joq4rAKGh6Y9cu"></div>
                </div>
                <button type="submit" class="btn-primary">Iniciar Sesión</button>
            </form>
            <p>¿No tienes una cuenta? <a href="<?php echo APP_URL; ?>/pages/registro.php">Regístrate aquí</a>.</p>
        </div>
    </main>

    <?php require '../templates/footer.php'; ?>

    <script>
        // Función para mostrar/ocultar la contraseña
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const toggleButton = document.querySelector('.toggle-password');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleButton.textContent = '🙈';
            } else {
                passwordInput.type = 'password';
                toggleButton.textContent = '👁️';
            }
        }
    </script>
</body>
</html>