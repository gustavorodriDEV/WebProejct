<?php
require_once 'autenticacao.php';
autenticacao::checkLogin();
$username = autenticacao::getUsername();
include 'navBar.php';
echo $GLOBALS['navbar'];
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Nova Postagem</title>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
        <style>
            .navbar {
                width: 100%;
                background: linear-gradient(to right, #6e45e2, #88d3ce);
                padding: 10px 0;
                display: flex;
                justify-content: space-evenly;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                position: fixed;
                top: 0;
                z-index: 1000;
                font-size: 25px;
            }

            .nav-item i {
                margin-right: 8px;
            }

            .nav-item {
                color: white;
                text-decoration: none;
                font-size: 16px;
                padding: 8px 16px;
                border-radius: 20px;
                transition: background-color 0.3s;
            }

            .nav-item:hover {
                background-color: rgba(255, 255, 255, 0.2);
            }

            body, h1, form {
                margin: 0;
                padding: 0;
            }

            body {
                font-family: Arial, sans-serif;
                margin: 20px;
                padding-top: 60px;
            }

            .container {
                max-width: 800px;
                margin: auto;
            }

            input[type="text"], input[type="number"], textarea, select {
                width: calc(100% - 20px);
                padding: 10px;
                margin-top: 8px;
                border: 1px solid #ccc;
                border-radius: 4px;
                box-sizing: border-box;
                transition: border 0.3s;
            }

            input[type="text"]:focus, input[type="number"]:focus, textarea:focus, select:focus {
                border: 1px solid #007bff;
            }

            button[type="submit"] {
                width: 100%;
                padding: 10px;
                margin-top: 8px;
                background-color: #5cb85c;
                color: white;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                transition: background-color 0.3s;
            }

            button[type="submit"]:hover {
                background-color: #4cae4c;
            }

            label {
                display: block;
                margin-top: 20px;
            }

            h1 {
                text-align: center;
                color: #333;
                margin-top: 30px;
                margin-bottom: 30px;
            }

            .form-group {
                margin-bottom: 20px;
                flex: 1;
            }

            .form-group:not(:last-child) {
                margin-right: 20px;
            }

            .form-row {
                display: flex;
                justify-content: space-between;
            }

            .buttons button {
                padding: 10px 15px;
                margin-right: 10px;
                cursor: pointer;
            }

            .buttons {
                margin-top: 20px;
            }

            textarea {
                height: 150px;
                resize: none;
            }
            .rating {
                display: flex;
                justify-content: space-between;
                max-width: 250px; /* Controla a largura total dos botões */
            }

            .rating input[type="radio"] {
                display: none; /* Esconde os botões de rádio padrão */
            }

            .rating-label {
                background-color: #f0f0f0;
                color: #333;
                padding: 10px 20px;
                font-size: 16px;
                border: 2px solid transparent;
                transition: background-color 0.3s, transform 0.3s;
                cursor: pointer;
                flex-grow: 1;
                text-align: center;
            }

            .rating input[type="radio"]:checked + .rating-label {
                background-color: #4CAF50; /* Cor de fundo quando selecionado */
                color: white;
                border-color: #4CAF50;
            }

            .rating-label:hover {
                background-color: #ddd; /* Cor de fundo ao passar o mouse */
                transform: scale(1.05); /* Efeito de crescimento ao passar o mouse */
            }

        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <h1>Nova Postagem</h1>
                <form action="processaPostagem.php" method="post" enctype="multipart/form-data">

                    <div class="form-group">
                        <label for="title">Título</label>
                        <input type="text" id="title" name="title" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="categories">Categorias</label>
                            <input type="text" id="categories" name="categories" required>
                        </div>

                        <div class="form-group">
                            <label for="director">Diretor</label>
                            <input type="text" id="director" name="director" placeholder="Nome do diretor" required>
                        </div>

                        <div class="form-group">
                            <label for="releaseYear">Ano de Lançamento</label>
                            <input type="number" id="releaseYear" name="releaseYear" placeholder="Ano" min="1960" max="2024" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Descrição</label>
                        <textarea id="description" name="description" required></textarea>
                    </div>

                    <!-- Adicionando campo de avaliação -->
                    <div class="form-group">
                        <label>Avaliação</label>
                        <div class="rating">
                            <input type="radio" id="rating1" name="rating" value="1" required><label class="rating-label" for="rating1">1</label>
                            <input type="radio" id="rating2" name="rating" value="2"><label class="rating-label" for="rating2">2</label>
                            <input type="radio" id="rating3" name="rating" value="3"><label class="rating-label" for="rating3">3</label>
                            <input type="radio" id="rating4" name="rating" value="4"><label class="rating-label" for="rating4">4</label>
                            <input type="radio" id="rating5" name="rating" value="5"><label class="rating-label" for="rating5">5</label>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="imagePath">Imagem</label>
                        <input type="file" id="imagePath" name="imagePath" accept="image/*" required>
                    </div>

                    <div class="buttons">
                        <button type="submit">Publicar</button>
                        <button type="button" onclick="window.history.back();">Cancelar</button>
                        <input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>">
                    </div>
                </form>
            </div>
        </div>

    </body>
</html>