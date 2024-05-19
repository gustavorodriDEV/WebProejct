<?php
class autenticacao {
    public static function iniciarSessao() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();  // Só inicia a sessão se não estiver já iniciada
        }
    }

    public static function checkLogin() {
        self::iniciarSessao();  // Garante que a sessão esteja iniciada
        if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
            exit("Logue para acessar");  // Termina a execução e mostra a mensagem se não estiver logado
        }
    }

public static function setUser($username) {
    self::iniciarSessao();
    if (!empty($username)) {
        $_SESSION['username'] = $username;
        error_log("Username set in session: " . $username);  // Log para arquivo de erro padrão do PHP
    } else {
        error_log("Attempted to set empty username in session.");
    }
}

public static function getUsername() {
    self::iniciarSessao();
    if (isset($_SESSION['username'])) {
        error_log("Getting username from session: " . $_SESSION['username']);
        return $_SESSION['username'];
    } else {
        error_log("No username found in session.");
        return null;
    }
}}
?>
