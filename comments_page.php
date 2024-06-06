<?php
require_once 'autenticacao.php';
require_once 'visitarUsuario.php';  // Inclui a classe VisitarUsuario

autenticacao::checkLogin();
?>


<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Detalhes do Filme</title>
        <link rel="stylesheet" href="modal.css">
                <link rel="stylesheet" href="FeedStyleSheet.css">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">

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
                border-radius: 16px; /* Borda mais arredondada */
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                width: 70%; /* Largura aumentada */
                max-width: 1000px; /* Largura máxima aumentada */
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
                box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
                display: flex;
                justify-content: center;
                z-index: 1000;
            }

            .add-comment form {
                display: flex;
                align-items: center;
                width: 100%;
                max-width: 800px;
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
                margin-right: 10px;
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
            }}

    $postID = intval($_POST['PostID']);

    // Consulta SQL para obter os detalhes do post juntamente com a foto de perfil do usuário
    $sqlPost = "SELECT p.PostID, p.username, p.Titulo, p.Diretor, p.Categoria, p.DataDeLancamento, p.Descricao, p.Caminho_Imagem, u.FotoPerfil
            FROM posts p
            LEFT JOIN Perfilusuario u ON p.username = u.nomeUsuario
            WHERE p.PostID = ?
            ORDER BY p.DataDeLancamento DESC";

    $stmt = $conn->prepare($sqlPost);
    if ($stmt) {
        $stmt->bind_param("i", $postID); // Vincula o PostID à consulta
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Aqui você pode usar os dados obtidos, por exemplo:
            $titulo = htmlspecialchars($row['Titulo']);
            $diretor = htmlspecialchars($row['Diretor']);
            $categoria = htmlspecialchars($row['Categoria']);
            $dataDeLancamento = htmlspecialchars($row['DataDeLancamento']);
            $descricao = nl2br(htmlspecialchars($row['Descricao']));
            $caminhoImagem = htmlspecialchars($row['Caminho_Imagem']);
            $fotoPerfil = htmlspecialchars($row['FotoPerfil']);
                //$username = $_SESSION['username']; // Descomente e ajuste conforme seu sistema de login
                // Query para buscar detalhes da postagem incluindo o caminho da imagem
                $sql = "SELECT p.PostID, p.username, p.Titulo, p.Diretor, p.Categoria, p.DataDeLancamento, p.Descricao, p.Caminho_Imagem, u.FotoPerfil
                        FROM posts p
                        LEFT JOIN Perfilusuario u ON p.username = u.nomeUsuario
                        WHERE PostID = $postID
                        ORDER BY p.DataDeLancamento DESC";

        }
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    echo '<div class="movie-info">';
                    echo '<strong>Título do filme:</strong> ' . $titulo . '<br>';
                    echo '<strong>Diretor:</strong> ' . $diretor . '<br>';
                    echo '<strong>Categoria:</strong> ' . $categoria . '<br>';
                    echo '<strong>Data de Lançamento:</strong> ' . $dataDeLancamento . '<br>';
                    echo '<div class="movie-description">' . $descricao . '</div>';
                    // Adicionando a imagem
                    echo '<img src="' . htmlspecialchars($row['Caminho_Imagem']) . '" alt="Imagem do filme" style="max-width: 100%; height: auto;">';
                    echo '</div>';
                    echo '    <div class="user-info">';  // Removido o estilo inline, adicionado via CSS
                if (empty($row['FotoPerfil'])) {
                    echo '<i class="fas fa-user-circle avatar-icon default-avatar" style="font-size: 55px; color: #777;"></i>';  // Adicionada classe para ícone padrão
                } else {
                    echo '<img src="' . htmlspecialchars($row['FotoPerfil']) . '" alt="Perfil do usuário" class="profile-image">';
                }
                echo '        <span class="user-name">' . htmlspecialchars($row['username']) . '</span>';
                echo '    </div>';
                    // Lógica de comentários
                    $sqlComentarios = "SELECT Conteudo, Username, DataDeComentario FROM comentarios WHERE PostID = $postID ORDER BY DataDeComentario DESC";
                    $resultComentarios = $conn->query($sqlComentarios);
                    $commentsHTML = '';

                    if ($resultComentarios->num_rows > 0) {
                        $commentsHTML .= '<div class="comments-section">';
                        $commentsHTML .= '<h3>Comentários</h3>';

                        while ($row = $resultComentarios->fetch_assoc()) {
                            $conteudo = nl2br(htmlspecialchars($row['Conteudo']));
                            $username = htmlspecialchars($row['Username']);
                            $data = htmlspecialchars($row['DataDeComentario']);
                            $dataFormatada = date('d/m/Y H:i:s', strtotime($data));

                            $commentsHTML .= "<div class='comment'>";
                            $commentsHTML .= "
                            <form action='PerfilComment.php' method='post'>
                                <strong>
                                    <button type='submit' style='border: none; background: none; padding: 0; font: inherit; cursor: pointer; color: blue; text-decoration: underline;'>
                                        " . htmlspecialchars($username) . "
                                    </button>
                                </strong>
                                <input type='hidden' name='username' value='" . htmlspecialchars($username) . "'>
                                <span>" . $dataFormatada . "</span>
                            </form><br>";

                            $commentsHTML .= '<div class="comment-text">' . $conteudo . '</div>';
                            $commentsHTML .= '</div>';
                        }

                        $commentsHTML .= '</div>';
                    } else {
                        $commentsHTML .= '<p>Sem comentários.</p>';
                    }
                } else {
                    echo "<p>Post não encontrado.</p>";
                }
                $conn->close();
            }
            ?>

        </div> 


        <div>
            <?php
            echo $commentsHTML;
            ?>
        </div>

        <div class="add-comment">
            <h3>Faça um Comentário</h3>
            <form action="inserecomentario.php" method="POST">
                <!-- Campo oculto para PostID -->
                <input type="hidden" name="PostID" value="<?php echo isset($postID) ? htmlspecialchars($postID) : ''; ?>">
                <input type="hidden" name="username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
                <textarea id="conteudo" name="conteudo" required></textarea>
                <input type="submit" value="Enviar">
            </form>
        </div>

        <div id="userProfileModal" class="modal" style="display: none;">
            <div class="modal-content">
                <span class="close" onclick="closeUserProfileModal()">&times;</span>
                <?php if ($perfilUsuario): ?>
                    <div class="avatar">
                        <!-- Adicionar lógica para mostrar imagem, se disponível -->
                    </div>
                    <div id="modalUsername"><?php echo htmlspecialchars($perfilUsuario['nomeUsuario']); ?></div>
                    <p id="modalBio"><?php echo htmlspecialchars($perfilUsuario['biografia']); ?></p>
                    <p>Entrou em: <span id="modalJoinedDate"><?php echo (new DateTime($perfilUsuario['dataDeCriacao']))->format('d/m/Y'); ?></span></p>
                    <p>Conta criada há: <span id="modalAccountAge"><?php echo $mensagemDiasConta; ?></span></p>
                <?php else: ?>
                    <p>Usuário não encontrado.</p>
                <?php endif; ?>
                <script src="modal.js"></script>
            </div>

    </body>

</html>
