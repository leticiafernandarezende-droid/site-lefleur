<?php

session_start();

if (!isset($_SESSION["id"])) {
    header("Location: login.html");
    exit;
}

require("conexão.php");

$id = $_SESSION["id"];

$sql = "DELETE FROM usuarios WHERE id = '$id'";

$resultado = mysqli_query($conn, $sql);

if ($resultado) {

    // Encerra a sessão
    session_unset();
    session_destroy();

    // Volta para a página inicial
    header("Location: index.php");
    exit;

} else {

    echo "Erro ao excluir a conta: " . mysqli_error($conn);
}

?>