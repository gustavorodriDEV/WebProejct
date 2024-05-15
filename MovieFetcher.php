<?php

class MovieFetcher {
    private $mysqli;

    public function __construct($host, $username, $password, $database) {
        $this->mysqli = new mysqli($host, $username, $password, $database);
        if ($this->mysqli->connect_error) {
            die("Falha na conexão: " . $this->mysqli->connect_error);
        }
    }

    public function fetchAllMovies() {
        $sql = "SELECT Titulo, Categoria, Diretor, DataDeLancamento, Descricao, Imagem, Avaliacao FROM midias";
        $movies = [];

        if ($result = $this->mysqli->query($sql)) {
            while ($movie = $result->fetch_assoc()) {
                $movies[] = $movie;
            }
            $result->free();
        }
        return $movies;
    }

    public function __destruct() {
        $this->mysqli->close();
    }
}



// Correção dos placeholders
$fetcher = new MovieFetcher('localhost', 'root', '', 'wePro');
$movies = $fetcher->pegaFilmes_Series();

foreach ($movies as $movie) {
    echo '<div class="content">';
    echo '    <div class="movie-image">';
    echo '        <img src="path/to/images/' . htmlspecialchars($movie['Imagem']) . '" alt="Imagem de ' . htmlspecialchars($movie['Titulo']) . '">';
    echo '    </div>';
    echo '    <div class="movie-info">';
    echo '        <div class="movie-rating">★★★☆☆</div>';
    echo '        <div><strong>Título do filme:</strong> ' . htmlspecialchars($movie['Titulo']) . '</div>';
    echo '        <div><strong>Ano:</strong> ' . htmlspecialchars(date('Y', strtotime($movie['DataDeLancamento']))) . '</div>';
    echo '        <div class="movie-text">';
    echo '            <p>' . nl2br(htmlspecialchars($movie['Descricao'])) . '</p>';
    echo '        </div>';
    echo '    </div>';
    echo '</div>';
}


// Fechamento da conexão com o banco de dados após o uso
$fetcher->__destruct(); // ou simplesmente unset($fetcher) se preferir
?>
