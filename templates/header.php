<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MineHive - Tu Wiki de Minecraft</title>
    <!-- Estilos CSS -->
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/css/styles.css">
    <!-- Animaciones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- Fuente Minecraft -->
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/fonts/Minecraft/1_Minecraft-Regular.otf">
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <a href="<?php echo APP_URL; ?>/index.php">
                    <img src="<?php echo APP_URL; ?>/img/logo.png" alt="MineHive Logo">
                </a>
            </div>
            <nav>
                <ul>
                    <li><a href="<?php echo APP_URL; ?>/index.php">Inicio</a></li>
                    <li><a href="<?php echo APP_URL; ?>/pages/wiki.php">Wiki</a></li>
                    <li><a href="<?php echo APP_URL; ?>/pages/mods.php">Mods</a></li>
                    <li><a href="<?php echo APP_URL; ?>/pages/texturas.php">Texturas</a></li>
                    <li><a href="<?php echo APP_URL; ?>/pages/ayuda.php">Ayuda</a></li>
                    <li><a href="<?php echo APP_URL; ?>/pages/version.php">Versión</a></li>
                    <li><a href="<?php echo APP_URL; ?>/pages/usuarios.php">Personas</a></li>
                    <li class="user-menu">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <!-- Usuario ha iniciado sesión -->
                            <div class="user-profile" onclick="toggleDropdown()">
                                <img src="<?php echo APP_URL; ?>/<?php echo $_SESSION['user_photo']; ?>" alt="Foto de perfil" class="profile-pic">
                                <div class="dropdown-content" id="dropdownMenu">
                                    <a href="<?php echo APP_URL; ?>/pages/perfil.php">Mi Perfil</a>
                                    <a href="<?php echo APP_URL; ?>/pages/logout.php">Cerrar Sesión</a>
                                </div>
                            </div>
                        <?php else: ?>
                            <!-- Usuario no ha iniciado sesión -->
                            <div class="user-profile" onclick="toggleDropdown()">
                                <img src="<?php echo APP_URL; ?>/img/default_user.png" alt="Usuario" class="profile-pic">
                                <div class="dropdown-content" id="dropdownMenu">
                                    <a href="<?php echo APP_URL; ?>/pages/login.php">Iniciar Sesión</a>
                                    <a href="<?php echo APP_URL; ?>/pages/registro.php">Registrarse</a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Script para manejar el menú desplegable -->
    <script>
        function toggleDropdown() {
            const dropdownMenu = document.getElementById("dropdownMenu");
            dropdownMenu.style.display = dropdownMenu.style.display === "block" ? "none" : "block";
        }

        // Cerrar el menú desplegable si se hace clic fuera de él
        window.onclick = function(event) {
            if (!event.target.matches('.profile-pic') && !event.target.matches('.user-profile')) {
                const dropdownMenu = document.getElementById("dropdownMenu");
                if (dropdownMenu.style.display === "block") {
                    dropdownMenu.style.display = "none";
                }
            }
        };
    </script>
</body>
</html>