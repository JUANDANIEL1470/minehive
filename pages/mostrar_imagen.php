<?php
require '../config.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    die("Acceso no autorizado.");
}

// Obtener la imagen del usuario
$query = $pdo->prepare("SELECT foto_perfil FROM usuarios WHERE id = ?");
$query->execute([$_SESSION['user_id']]);
$imagen = $query->fetchColumn();

// Devolver la imagen en formato JPEG
header("Content-Type: image/jpeg");
echo $imagen;
?>