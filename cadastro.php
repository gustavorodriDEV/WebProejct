<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="estilos.css">

    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-image: linear-gradient(to right, #6e45e2, #88d3ce, #ffcc2f);
            display: flex;
            justify-content: center; /* Centraliza horizontalmente */
            align-items: center;     /* Centraliza verticalmente */
            height: 100vh;           /* Garante que o contêiner pai ocupe a altura total da janela */
            margin: 0;
        }

        .alert {
            padding: 10px;
            color: white;
            background-color: red; /* Cor de fundo para erro, mude conforme o contexto */
            margin-bottom: 20px;
            text-align: center;
            display: none; /* Esconder inicialmente, mostrado via PHP se necessário */
        }
        .success {
            background-color: green; /* Cor de fundo para sucesso */
        }
    </style>
</head>
<body>
    <?php
    session_start();
    ?>

    <div class="login-container">
        <h1>Cadastro</h1>

        <form action="registro.php" method="post">
            <input type="text" placeholder="Nome de usuário" name="username" required>
            <input type="email" placeholder="Email" name="email" required>
            <input type="password" placeholder="Senha" name="password" required minlength="8" maxlength="20">
            <input type="password" placeholder="Confirme sua senha" name="confirmPassword" required minlength="8" maxlength="20">
            <button type="submit">Registrar</button>
            <a href="login.php" class="button-like-link">Já tem conta? Entre aqui</a>
        </form>
  <?php if (isset($_SESSION['error_message'])): ?>
        <div id="alertMessage" class="alert" style="display: block;">
            <?php echo $_SESSION['error_message'];
            unset($_SESSION['error_message']); ?>
        </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success_message'])): ?>
        <div id="alertMessage" class="alert success" style="display: block;">
            <?php echo $_SESSION['success_message'];
            unset($_SESSION['success_message']); ?>
        </div>
        <?php endif; ?>
    </div>

            <script src="scripts.js"></script>


</body>
</html>
