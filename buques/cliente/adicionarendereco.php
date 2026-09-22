<?php

session_start();

if (!isset($_SESSION["id"])) {
    header("Location: ../login.html");
    exit;
}

require("../conexão.php");

$usuario_id = $_SESSION["id"];

if (
    !isset($_POST["nome_destinatario"]) ||
    !isset($_POST["cep"]) ||
    !isset($_POST["rua"]) ||
    !isset($_POST["numero"]) ||
    !isset($_POST["bairro"]) ||
    !isset($_POST["cidade"]) ||
    !isset($_POST["estado"])
) {
    header("Location: enderecos.php");
    exit;
}


$nome_destinatario = $_POST["nome_destinatario"];
$cep = $_POST["cep"];
$rua = $_POST["rua"];
$numero = $_POST["numero"];
$complemento = $_POST["complemento"] ?? "";
$bairro = $_POST["bairro"];
$cidade = $_POST["cidade"];
$estado = strtoupper($_POST["estado"]);


$sql = "
    INSERT INTO enderecos
    (
        usuario_id,
        nome_destinatario,
        cep,
        rua,
        numero,
        complemento,
        bairro,
        cidade,
        estado
    )
    VALUES
    (
        '$usuario_id',
        '$nome_destinatario',
        '$cep',
        '$rua',
        '$numero',
        '$complemento',
        '$bairro',
        '$cidade',
        '$estado'
    )
";


$resultado = mysqli_query($conn, $sql);


if ($resultado) {

    header("Location: enderecos.php");
    exit;

}


echo "Erro ao cadastrar endereço: " . mysqli_error($conn);

?>