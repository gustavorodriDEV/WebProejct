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
        <link rel="stylesheet" href="novaPostagem_StyleSheet.css">
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
                            <select id="categories" name="categories" required>
                                <option value="">Selecione uma categoria</option>
                                <option value="acao">Ação</option>
                                <option value="aventura">Aventura</option>
                                <option value="comedia">Comédia</option>
                                <option value="drama">Drama</option>
                                <option value="terror">Terror</option>
                                <option value="romance">Romance</option>
                                <option value="sci-fi">Sci-Fi</option>
                                <option value="documentario">Documentário</option>
                                <option value="fantasia">Fantasia</option>
                                <option value="suspense">Suspense</option>
                                <option value="animacao">Animação</option>
                                <option value="musical">Musical</option>
                                <option value="historico">Histórico</option>
                                <option value="esportes">Esportes</option>
                                <option value="guerra">Guerra</option>
                                <option value="policial">Policial</option>
                                <option value="biografia">Biografia</option>
                                <option value="western">Western</option>
                                <option value="noir">Noir</option>
                                <option value="ficcao">Ficção</option>
                                <option value="thriller">Thriller</option>
                                <option value="mistério">Mistério</option>
                                <option value="familia">Família</option>
                            </select>
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

                    <div class="form-group">
                        <label for="imagePath"><i class="fas fa-camera"></i> Imagem</label>
                        <input type="file" id="imagePath" name="imagePath" accept="image/*" required>
                    </div>

                    <div class="form-group">
                        <label>Avaliação</label>
                        <div class="rating">
                            <input type="radio" id="rating1" name="rating" value="1" required>
                            <label class="rating-label" for="rating1"><i class="fas fa-star"></i> 1</label>

                            <input type="radio" id="rating2" name="rating" value="2">
                            <label class="rating-label" for="rating2"><i class="fas fa-star"></i> 2</label>

                            <input type="radio" id="rating3" name="rating" value="3">
                            <label class="rating-label" for="rating3"><i class="fas fa-star"></i> 3</label>

                            <input type="radio" id="rating4" name="rating" value="4">
                            <label class="rating-label" for="rating4"><i class="fas fa-star"></i> 4</label>

                            <input type="radio" id="rating5" name="rating" value="5">
                            <label class="rating-label" for="rating5"><i class="fas fa-star"></i> 5</label>
                        </div>
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
