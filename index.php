<!-- index.php -->
<?php
require 'config.php'; // Aquí ya se inicia la sesión en config.php
require 'templates/header.php';
?>

<main>
    <!-- Sección de Bienvenida -->
    <section class="welcome-section animate__animated animate__fadeIn">
        <div class="container">
            <h1>Bienvenido a <span class="highlight">MineHive</span></h1>
            <p class="subtitle">Tu comunidad definitiva para todo lo relacionado con Minecraft.</p>
            <div class="cta-buttons">
                <a href="<?php echo APP_URL; ?>/pages/registro.php" class="btn-primary">Únete Ahora</a>
                <a href="<?php echo APP_URL; ?>/pages/login.php" class="btn-secondary">Iniciar Sesión</a>
            </div>
        </div>
    </section>

    <!-- Sección de Características -->
    <section class="features-section">
        <div class="container">
            <h2>¿Qué Ofrecemos?</h2>
            <div class="features-grid">
                <!-- Tarjeta 1: Wiki -->
                <div class="feature-card animate__animated animate__fadeInUp">
                    <img src="<?php echo APP_URL; ?>/img/wiki.png" alt="Wiki">
                    <h3>Wiki</h3>
                    <p>Explora nuestra extensa base de conocimientos sobre Minecraft.</p>
                    <a href="<?php echo APP_URL; ?>/pages/wiki.php" class="btn-feature">Ver Wiki</a>
                </div>

                <!-- Tarjeta 2: Mods -->
                <div class="feature-card animate__animated animate__fadeInUp animate__delay-1s">
                    <img src="<?php echo APP_URL; ?>/img/mods.png" alt="Mods">
                    <h3>Mods</h3>
                    <p>Descubre y descarga los mejores mods para personalizar tu experiencia.</p>
                    <a href="<?php echo APP_URL; ?>/pages/mods.php" class="btn-feature">Ver Mods</a>
                </div>

                <!-- Tarjeta 3: Texturas -->
                <div class="feature-card animate__animated animate__fadeInUp animate__delay-2s">
                    <img src="<?php echo APP_URL; ?>/img/texturas.png" alt="Texturas">
                    <h3>Texturas</h3>
                    <p>Mejora los gráficos de Minecraft con nuestras texturas exclusivas.</p>
                    <a href="<?php echo APP_URL; ?>/pages/texturas.php" class="btn-feature">Ver Texturas</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección de Comunidad -->
    <section class="community-section animate__animated animate__fadeIn">
        <div class="container">
            <h2>Únete a Nuestra Comunidad</h2>
            <p>Conéctate con otros jugadores, comparte tus creaciones y participa en eventos exclusivos.</p>
            <div class="community-links">
                <a href="<?php echo APP_URL; ?>/pages/registro.php" class="btn-primary">Regístrate</a>
                <a href="<?php echo APP_URL; ?>/pages/ayuda.php" class="btn-secondary">Más Información</a>
            </div>
        </div>
    </section>
</main>

<?php
require 'templates/footer.php';
?>