<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Defina a variável global para a navbar
$navbar = '
<div class="navbar">
    <a href="#search" class="nav-item"><i class="fas fa-search"></i> Pesquisa</a>
    <a href="perfil.php" class="nav-item"><i class="fas fa-user"></i> Perfil</a>';
    
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $navbar .= '
    <a href="novaPostagem.php" class="nav-item"><i class="fas fa-pencil-alt"></i> Postagem</a>';
}

$navbar .= '
    <a href="index.php" class="nav-item"><i class="fas fa-home"></i> Inicio</a>
</div>';

// Torne a variável $navbar global
$GLOBALS['navbar'] = $navbar;
?>
