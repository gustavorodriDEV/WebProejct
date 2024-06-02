<?php
// Inicia a sessão
session_start();

// Limpa a variável de sessão
$_SESSION = array();

// Destruir a sessão
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

// Redireciona para a página de login ou inicial
header('Location: login.html');
exit;
?>