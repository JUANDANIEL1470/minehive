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
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 20-03-2025 a las 21:26:27
-- Versión del servidor: 9.1.0
-- Versión de PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `minehive`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `insignias`
--

DROP TABLE IF EXISTS `insignias`;
CREATE TABLE IF NOT EXISTS `insignias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text NOT NULL,
  `imagen` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mods`
--

DROP TABLE IF EXISTS `mods`;
CREATE TABLE IF NOT EXISTS `mods` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `ruta_archivo` varchar(255) NOT NULL,
  `usuario_id` int NOT NULL,
  `fecha_subida` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `descargas` int DEFAULT '0',
  `valoracion_promedio` float DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas`
--

DROP TABLE IF EXISTS `preguntas`;
CREATE TABLE IF NOT EXISTS `preguntas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `usuario_id` int NOT NULL,
  `fecha_publicacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `estado` enum('abierta','cerrada') DEFAULT 'abierta',
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuestas`
--

DROP TABLE IF EXISTS `respuestas`;
CREATE TABLE IF NOT EXISTS `respuestas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `contenido` text NOT NULL,
  `usuario_id` int NOT NULL,
  `pregunta_id` int NOT NULL,
  `fecha_respuesta` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `es_correcta` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `pregunta_id` (`pregunta_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `texturas`
--

DROP TABLE IF EXISTS `texturas`;
CREATE TABLE IF NOT EXISTS `texturas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `ruta_archivo` varchar(255) NOT NULL,
  `usuario_id` int NOT NULL,
  `fecha_subida` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `descargas` int DEFAULT '0',
  `valoracion_promedio` float DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre_usuario` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `foto_perfil` longblob,
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_insignias`
--

DROP TABLE IF EXISTS `usuario_insignias`;
CREATE TABLE IF NOT EXISTS `usuario_insignias` (
  `usuario_id` int NOT NULL,
  `insignia_id` int NOT NULL,
  `fecha_obtencion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`usuario_id`,`insignia_id`),
  KEY `insignia_id` (`insignia_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `valoraciones`
--

DROP TABLE IF EXISTS `valoraciones`;
CREATE TABLE IF NOT EXISTS `valoraciones` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `mod_id` int DEFAULT NULL,
  `textura_id` int DEFAULT NULL,
  `puntuacion` int DEFAULT NULL,
  `fecha_valoracion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `mod_id` (`mod_id`),
  KEY `textura_id` (`textura_id`)
) ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
