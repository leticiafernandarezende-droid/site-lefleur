<?php

session_start();

if (!isset($_SESSION["id"])) {
    header("Location: login.html");
    exit;
}

require("conexão.php");

$id = $_SESSION["id"];

$nome = $_POST["nome"];
$email = $_POST["email"];
$telefone = $_POST["telefone"];
$senha = $_POST["senha"];

if (!empty($senha)) {

    $sql = "UPDATE usuarios 
            SET nome = '$nome',
                email = '$email',
                telefone = '$telefone',
                senha = '$senha'
            WHERE id = '$id'";

} else {

    $sql = "UPDATE usuarios 
            SET nome = '$nome',
                email = '$email',
                telefone = '$telefone'
            WHERE id = '$id'";
}

$resultado = mysqli_query($conn, $sql);

if ($resultado) {

    // Atualiza os dados da sessão
    $_SESSION["nome"] = $nome;
    $_SESSION["email"] = $email;

    header("Location: index.php");
    exit;

} else {

    echo "Erro ao atualizar a conta: " . mysqli_error($conn);
}

?>