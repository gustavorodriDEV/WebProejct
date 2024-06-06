<?php
require_once 'db_conexao.php'; 

$query = "SELECT * FROM usuarios";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "Nome: " . $row['nome'] . "<br>";
    }
} else {
    echo "0 resultados encontrados";
}

$conn->close();
?>
