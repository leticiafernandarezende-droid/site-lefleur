<?php

include("conexão.php");

$nome = $_POST["nome"];
$email = $_POST["email"];
$senha = $_POST["senha"];
$telefone = $_POST["telefone"];
$tipo = $_POST["tipo"];

$result_func = "INSERT INTO usuarios(nome, email, senha, telefone, tipo)
VALUES ('$nome','$email','$senha','$telefone','$tipo')";

$resultado_func = mysqli_query($conn, $result_func);

if(mysqli_affected_rows($conn) != 0){
echo "Cliente cadastrado com sucesso";
header("Location: index.html"); exit;
} else {
echo "Erro ao cadastrar";
}


?>