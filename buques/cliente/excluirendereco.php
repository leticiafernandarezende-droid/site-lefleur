<?php

session_start();

if (!isset($_SESSION["id"])) {
    header("Location: ../login.html");
    exit;
}

require("../conexão.php");

if (!isset($_POST["id"])) {
    header("Location: enderecos.php");
    exit;
}

$id = (int) $_POST["id"];

$usuario_id = $_SESSION["id"];


$sql = "
    DELETE FROM enderecos
    WHERE id = '$id'
    AND usuario_id = '$usuario_id'
";


$resultado = mysqli_query($conn, $sql);


if ($resultado) {

    header("Location: enderecos.php");
    exit;

}


echo "Erro ao excluir endereço: " . mysqli_error($conn);

?>