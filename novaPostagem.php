<?php
require_once 'autenticacao.php';  // Inclui a classe de autenticação
autenticacao::checkLogin();  // Verifica se o usuário está logado
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
        </style>
  </head>
    <body>
        <div class="navbar">
            <a href="#search" class="nav-item"><i class="fas fa-search"></i> Pesquisa</a>
            <a href="perfil.html" class="nav-item"><i class="fas fa-user"></i> Perfil</a>
            <a href="novaPostagem.html" class="nav-item"><i class="fas fa-pencil-alt"></i> Postagem</a>
            <a href="index.html" class="nav-item"><i class="fas fa-home"></i> Início</a>
        </div>

        <div class="container">
            <h1>Nova Postagem</h1>
            <form action="processaPostagem.php" method="post">
                <div class="form-group">
                    <label for="title">Título</label>
                    <input type="text" id="title" name="title">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="categories">Categorias</label>
                    <input type="text" id="categories" name="categories">
                        

                    </div>

                    <div class="form-group">
                        <label for="director">Diretor</label>
                        <input type="text" id="director" name="director" placeholder="Nome do diretor">
                    </div>

                    <div class="form-group">
                        <label for="releaseYear">Ano de Lançamento</label>
                        <input type="number" id="releaseYear" name="releaseYear" placeholder="Ano" min="1960" max="2024">
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Descrição</label>
                    <textarea id="description" name="description"></textarea>
                </div>

                <div class="buttons">
                    <button type="submit">Publicar</button>
                    <button type="button">Cancelar</button>
                </div>
            </form>
        </div>
    </body>
</html>
