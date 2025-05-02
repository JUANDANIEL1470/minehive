<?php
require '../config.php';

// Cerrar la sesión
session_destroy();
redirect('/index.php');
?>