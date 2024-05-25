<?php
class autenticacao {
    public static function iniciarSessao() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
            error_log("Sessão iniciada.");
        }
    }

    public static function checkLogin() {
        self::iniciarSessao();
        if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
            error_log("Usuário não logado. Redirecionando...");
            header("Location: login.html");
            exit();
        }
        error_log("Usuário logado: " . $_SESSION['username']);
        define('USERNAME', $_SESSION['username']);  // Define a constante após a autenticação
    }

    public static function setUser($username) {
        self::iniciarSessao();
        if (!empty($username)) {
            $_SESSION['username'] = $username;
            $_SESSION['loggedin'] = true;  // Marcar usuário como logado
            error_log("Nome de usuário definido na sessão: " . $username);
        } else {
            error_log("Tentativa de definir nome de usuário vazio na sessão.");
        }
    }

    public static function getUsername() {
        self::iniciarSessao();
        if (isset($_SESSION['username'])) {
            error_log("Obtendo nome de usuário da sessão: " . $_SESSION['username']);
            return $_SESSION['username'];
        } else {
            error_log("Nenhum nome de usuário encontrado na sessão.");
            return null;
        }
    }
}
?>
