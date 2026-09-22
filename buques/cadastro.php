<?php

include("conexão.php");


// =====================================================
// DADOS DO USUÁRIO
// =====================================================

$nome = $_POST["nome"];
$email = $_POST["email"];
$senha = $_POST["senha"];
$telefone = $_POST["telefone"];


// Como o cliente não deve escolher o tipo,
// deixamos como cliente automaticamente.
$tipo = "cliente";


// =====================================================
// DADOS DO ENDEREÇO
// =====================================================

$cep = $_POST["cep"];
$estado = $_POST["estado"];
$cidade = $_POST["cidade"];
$bairro = $_POST["bairro"];
$rua = $_POST["rua"];
$numero = $_POST["numero"];
$complemento = $_POST["complemento"];


// =====================================================
// CADASTRA O USUÁRIO
// =====================================================

$sql_usuario = "
    INSERT INTO usuarios
    (
        nome,
        email,
        senha,
        telefone,
        tipo
    )
    VALUES
    (
        '$nome',
        '$email',
        '$senha',
        '$telefone',
        '$tipo'
    )
";


$resultado_usuario = mysqli_query($conn, $sql_usuario);


// Verifica se o usuário foi cadastrado

if (!$resultado_usuario) {

    echo "Erro ao cadastrar usuário: "
        . mysqli_error($conn);

    exit;
}


// =====================================================
// PEGA O ID DO USUÁRIO CRIADO
// =====================================================

$usuario_id = mysqli_insert_id($conn);


// =====================================================
// CADASTRA O ENDEREÇO
// =====================================================

$sql_endereco = "
    INSERT INTO enderecos
    (
        usuario_id,
        cep,
        estado,
        cidade,
        bairro,
        rua,
        numero,
        complemento
    )
    VALUES
    (
        '$usuario_id',
        '$cep',
        '$estado',
        '$cidade',
        '$bairro',
        '$rua',
        '$numero',
        '$complemento'
    )
";


$resultado_endereco = mysqli_query($conn, $sql_endereco);


// =====================================================
// VERIFICA O ENDEREÇO
// =====================================================

if (!$resultado_endereco) {

    echo "Usuário criado, mas ocorreu um erro ao cadastrar o endereço: "
        . mysqli_error($conn);

    exit;
}


// =====================================================
// SUCESSO
// =====================================================

header("Location: login.html");

exit;

?>