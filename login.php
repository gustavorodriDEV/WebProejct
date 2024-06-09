<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
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
                background-color: red;
                text-align: center;
                margin-bottom: 20px;
            }

        </style>
    </head>
    <body>
        <?php
        session_start();
        ?>

        <div class="login-container">
            <h1>Login</h1>
            <p>faça o seu login :)</p>
            <form action="login2.php" method="post" > 
                <input type="text" placeholder="Usuário" name="username" required> 
                <input type="password" placeholder="Senha" name="password" required> 
                <button type="submit">Entra</button>
                <a href="cadastro.php" class="button-like-link">Inscrever-se</a> 
            </form>

            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert">
                    <?php echo $_SESSION['error_message']; ?>
                    <?php unset($_SESSION['error_message']); ?>
                </div>
            <?php endif; ?>

        </div>

        <script src="scripts.js"></script>

    </body>
</html>
