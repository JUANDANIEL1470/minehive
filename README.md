# minehive



Descripción del Proyecto: MineHive
¿Qué es MineHive?
MineHive es una plataforma web dedicada a la comunidad de Minecraft, diseñada para ser un centro integral donde los jugadores pueden encontrar recursos, compartir sus creaciones y conectarse con otros entusiastas del juego. La plataforma combina elementos de una wiki, un repositorio de mods y texturas, y un sistema de soporte comunitario, todo ello envuelto en una interfaz atractiva y fácil de usar.

Objetivo del Proyecto
El objetivo principal de MineHive es proporcionar a los jugadores de Minecraft un espacio centralizado donde puedan:

Aprender: Acceder a una wiki completa con guías, tutoriales y noticias sobre Minecraft.

Crear y Compartir: Subir y descargar mods, texturas y otras creaciones personalizadas.

Conectar: Participar en una comunidad activa, resolver dudas y colaborar en proyectos.

Características Principales
Wiki de Minecraft:

Información detallada sobre mecánicas del juego, biomas, criaturas, bloques y más.

Noticias y actualizaciones sobre Minecraft.

Repositorio de Mods y Texturas:

Los usuarios pueden subir y descargar mods y paquetes de texturas.

Sistema de valoraciones y comentarios para cada recurso.

Sistema de Ayuda Comunitaria:

Los usuarios pueden publicar preguntas y recibir respuestas de otros miembros de la comunidad.

Roles especiales (administradores, moderadores) para gestionar el contenido y ayudar a los usuarios.

Perfiles de Usuario:

Los usuarios pueden crear perfiles personalizados, subir una foto y mostrar sus contribuciones (mods, texturas, respuestas en el foro de ayuda).

Sistema de insignias y roles (usuario normal, verificado, moderador, administrador).

Diseño Atractivo y Funcional:

Interfaz inspirada en el estilo de Minecraft, con colores y tipografías temáticas.

Animaciones y efectos visuales para mejorar la experiencia del usuario.

Sistema de Registro y Autenticación:

Los usuarios pueden registrarse e iniciar sesión para acceder a todas las funcionalidades.

Los administradores tienen acceso a un panel de control para gestionar usuarios y contenido.

Tecnologías Utilizadas
Frontend:

HTML, CSS y JavaScript para la interfaz de usuario.

Librerías como Animate.css para animaciones.

Diseño responsive para adaptarse a diferentes dispositivos.

Backend:

PHP para la lógica del servidor.

MySQL para la base de datos, donde se almacenan usuarios, mods, texturas, preguntas y respuestas.

Herramientas de Desarrollo:

Visual Studio Code como editor principal.

Wamp64 para el entorno de desarrollo local (Apache, MySQL, PHP).

Estructura del Proyecto
El proyecto está organizado en las siguientes carpetas y archivos principales:

templates/: Contiene los archivos reutilizables como header.php y footer.php.

pages/: Aloja las páginas individuales del sitio (wiki, mods, texturas, ayuda, etc.).

css/: Contiene los archivos de estilos (styles.css).

js/: Almacena los scripts de JavaScript (scripts.js).

img/: Contiene todas las imágenes utilizadas en el sitio (logos, íconos, etc.).

fonts/: Aloja las fuentes personalizadas, como la tipografía de Minecraft.

Funcionalidades Futuras
Integración con APIs de Minecraft: Para mostrar estadísticas en tiempo real o información sobre servidores.

Sistema de Donaciones: Permitir a los usuarios apoyar económicamente el proyecto.

Eventos Comunitarios: Organizar concursos y eventos para fomentar la participación.

Conclusión
MineHive es más que una simple plataforma web; es un espacio creado por y para los amantes de Minecraft. Con su combinación de recursos útiles, herramientas de creación y una comunidad activa, MineHive busca convertirse en el destino definitivo para todos los jugadores de Minecraft, desde principiantes hasta expertos.







BASE DE DATOS:

CREATE DATABASE minehive;
USE minehive;

-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre_usuario` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `foto_perfil` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `rol` enum('admin','moderador','usuario','verificado') DEFAULT 'usuario',
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ultimo_login` timestamp NULL DEFAULT NULL,
  `estado` enum('activo','inactivo','suspendido') DEFAULT 'activo',
  `insignia` varchar(255) DEFAULT NULL,
  `minecraft_java` varchar(50) DEFAULT NULL,
  `minecraft_bedrock` varchar(50) DEFAULT NULL,
  `minecraft_dungeons` varchar(50) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `redes_sociales` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_usuario` (`nombre_usuario`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

-- Tabla mods
CREATE TABLE mods (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT NOT NULL,
    ruta_archivo VARCHAR(255) NOT NULL,
    usuario_id INT NOT NULL,
    fecha_subida TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    descargas INT DEFAULT 0,
    valoracion_promedio FLOAT DEFAULT 0,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabla texturas
CREATE TABLE texturas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT NOT NULL,
    ruta_archivo VARCHAR(255) NOT NULL,
    usuario_id INT NOT NULL,
    fecha_subida TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    descargas INT DEFAULT 0,
    valoracion_promedio FLOAT DEFAULT 0,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabla preguntas
CREATE TABLE preguntas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descripcion TEXT NOT NULL,
    usuario_id INT NOT NULL,
    fecha_publicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('abierta', 'cerrada') DEFAULT 'abierta',
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabla respuestas
CREATE TABLE respuestas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    contenido TEXT NOT NULL,
    usuario_id INT NOT NULL,
    pregunta_id INT NOT NULL,
    fecha_respuesta TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    es_correcta BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (pregunta_id) REFERENCES preguntas(id) ON DELETE CASCADE
);

-- Tabla valoraciones
CREATE TABLE valoraciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    mod_id INT NULL,
    textura_id INT NULL,
    puntuacion INT CHECK (puntuacion BETWEEN 1 AND 5),
    fecha_valoracion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (mod_id) REFERENCES mods(id) ON DELETE CASCADE,
    FOREIGN KEY (textura_id) REFERENCES texturas(id) ON DELETE CASCADE,
    CHECK (mod_id IS NOT NULL OR textura_id IS NOT NULL)
);

-- Tabla insignias
CREATE TABLE insignias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    descripcion TEXT NOT NULL,
    imagen VARCHAR(255) NOT NULL
);

-- Tabla usuario_insignias
CREATE TABLE usuario_insignias (
    usuario_id INT NOT NULL,
    insignia_id INT NOT NULL,
    fecha_obtencion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (usuario_id, insignia_id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (insignia_id) REFERENCES insignias(id) ON DELETE CASCADE
);
