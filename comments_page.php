<?php
require_once 'autenticacao.php';
autenticacao::checkLogin();
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Detalhes do Filme</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 0;
                display: flex;
                flex-direction: column;
                align-items: center;
                min-height: 100vh;
            }
            .content {
                background-color: white;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                width: 60%;
                max-width: 800px;
                margin: 20px 0;
            }
            .movie-image img {
                max-width: 100%;
                height: auto;
                display: block;
                margin-bottom: 20px;
            }
            .movie-info strong {
                display: block;
                margin-top: 10px;
            }
            .movie-description {
                margin-top: 20px;
                margin-bottom: 20px;
            }
            .comments-section {
                margin-top: 20px;
                width: 776.19px; /* Define a largura específica */
                height: 282px; /* Define a altura específica */
                margin-left: auto;
                margin-right: auto;
                padding: 20px;
                border: 1px solid #ddd;
                border-radius: 8px;
                background-color: #fefefe;
                overflow-y: auto; /* Permite rolagem se o conteúdo exceder a altura */
            }

            .comment {
                background-color: #f9f9f9;
                padding: 15px;
                margin-bottom: 15px;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                transition: background-color 0.3s ease;
            }
            .comment:hover {
                background-color: #f1f1f1;
            }
            .comment strong {
                display: block;
                font-size: 1.1em;
                color: #333;
            }
            .add-comment {
                position: fixed;
                left: 0;
                right: 0;
                bottom: 0;
                padding: 10px;
                background: #f9f9f9;
                box-shadow: 0 -2px 5px rgba(0,0,0,0.1);
                display: flex;
                justify-content: center;
                z-index: 1000;
            }
            .add-comment textarea {
                padding: 8px 12px;
                border: 1px solid #ccc;
                border-radius: 4px;
                width: 100%;
                box-sizing: border-box;
                font-family: Arial, sans-serif;
                font-size: 16px;
                height: 40px;
                resize: none;
                outline: none;
                box-shadow: none;
                transition: border-color 0.3s;
            }
            .add-comment textarea:focus {
                border-color: #808080;
            }
            .add-comment input[type="submit"] {
                padding: 10px 20px;
                border: none;
                background-color: #4267B2;
                color: white;
                border-radius: 4px;
                cursor: pointer;
                font-size: 16px;
                transition: background-color 0.3s;
            }
            .add-comment input[type="submit"]:hover {
                background-color: #365899;
            }
        </style>
    </head>
    <body>
        <div class="content">
            <?php
            // Verificar se os dados foram enviados pelo método POST
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $conn = new mysqli('localhost', 'root', '', 'webPro');
                if ($conn->connect_error) {
                    die("Conexão falhou: " . $conn->connect_error);
                }

                $postID = intval($_POST['PostID']);
                $titulo = htmlspecialchars($_POST['Titulo']);
                $diretor = htmlspecialchars($_POST['Diretor']);
                $categoria = htmlspecialchars($_POST['Categoria']);
                $dataDeLancamento = htmlspecialchars($_POST['DataDeLancamento']);
                $descricao = nl2br(htmlspecialchars($_POST['Descricao']));
                $username = $_SESSION['username'];

                echo '<div class="movie-info">';
                echo '<strong>Título do filme:</strong> ' . $titulo . '<br>';
                echo '<strong>Diretor:</strong> ' . $diretor . '<br>';
                echo '<strong>Categoria:</strong> ' . $categoria . '<br>';
                echo '<strong>Data de Lançamento:</strong> ' . $dataDeLancamento . '<br>';
                echo '</div>';
                echo '<div class="movie-description">' . $descricao . '</div>';

                // Verificar se o PostID existe na tabela posts
                $sqlVerificaPostID = "SELECT PostID FROM posts WHERE PostID = $postID";
                $resultVerificaPostID = $conn->query($sqlVerificaPostID);

                if ($resultVerificaPostID->num_rows > 0) {
                    // Inserir um novo comentário se o formulário de comentários foi enviado
                    if (isset($_POST['novoComentario'])) {
                        $conteudo = $conn->real_escape_string(htmlspecialchars($_POST['conteudo']));
                        $dataComentario = date("Y-m-d H:i:s");

                        $sqlInsert = "INSERT INTO comentarios (Conteudo, DataDeComentario, PostID) VALUES ('$conteudo', '$dataComentario', $postID)";
                        if ($conn->query($sqlInsert) !== TRUE) {
                            echo "Erro ao inserir comentário: " . $conn->error;
                        } else {
                            echo "Comentário inserido com sucesso.";
                        }
                    }

                    $sqlComentarios = "SELECT Conteudo, Username, DataDeComentario FROM comentarios WHERE PostID = $postID ORDER BY DataDeComentario DESC";
                    $resultComentarios = $conn->query($sqlComentarios);

                    $commentsHTML = ''; // Inicializa uma string vazia para armazenar os comentários
                    // Exibir os comentários
                    if ($resultComentarios->num_rows > 0) {
                        $commentsHTML .= '<div class="comments-section">';
                        $commentsHTML .= '<h3>Comentários</h3>';
                        while ($row = $resultComentarios->fetch_assoc()) {
                            $conteudo = nl2br(htmlspecialchars($row['Conteudo']));
                            $username = htmlspecialchars($row['Username']);
                            $data = htmlspecialchars($row['DataDeComentario']);

                            $commentsHTML .= '<div class="comment">';
                            $commentsHTML .= '<strong>' . $username . '</strong> <br>';  // Exibir nome de usuário
                            $commentsHTML .= '<div class="comment-text">' . $conteudo . '</div>';
                            $commentsHTML .= '</div>';
                        }
                        $commentsHTML .= '</div>';
                    } else {
                        $commentsHTML .= '<p>Sem comentários.</p>';
                    }

                    if ($resultComentarios === false) {
                        echo "<p>PostID inválido.</p>";
                    }

                    $conn->close();
                } else {
                    echo "<p>Post não encontrado.</p>";
                }
            }
            ?>
        </div> 

        <div>
            <?php echo $commentsHTML; ?>
        </div>

        <div class="add-comment">
            <h3>Adicionar um Comentário</h3>
            <form action="inserecomentario.php" method="POST">
                <!-- Campo oculto para PostID -->
                <input type="hidden" name="PostID" value="<?php echo isset($postID) ? htmlspecialchars($postID) : ''; ?>">

                <input type="hidden" name="username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">

                <label for="conteudo">Comentário:</label>
                <textarea id="conteudo" name="conteudo" required></textarea>
                <input type="submit" value="Enviar">
            </form>
        </div>
    </body>
</html>
