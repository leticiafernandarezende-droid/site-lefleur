<?php

session_start();

if (!isset($_SESSION["id"])) {
    header("Location: ../login.html");
    exit;
}

// Conexão com o banco
require("../conexão.php");

// Recebe os dados enviados pelo formulário
$nome = $_POST["nome"];
$tamanho = $_POST["tamanho"];
$qtd_rosas = $_POST["qtd_rosas"];
$cor_principal = $_POST["cor_principal"];
$cor_secundaria = $_POST["cor_secundaria"];
$tem_glitter = $_POST["tem_glitter"];
$imagem = $_POST["imagem"];
$preco = $_POST["preco"];


if (isset($_POST["id"]) && !empty($_POST["id"])) {


    $id = $_POST["id"];

    $sql = "UPDATE produtos SET
                nome = '$nome',
                tamanho = '$tamanho',
                qtd_rosas = '$qtd_rosas',
                cor_principal = '$cor_principal',
                cor_secundaria = '$cor_secundaria',
                tem_glitter = '$tem_glitter',
                imagem = '$imagem',
                preco = '$preco'
            WHERE id = '$id'";

    $resultado = mysqli_query($conn, $sql);

    if ($resultado) {

        header("Location: produtosadmin.php");
        exit;

    } else {

        echo "Erro ao editar o buquê: " . mysqli_error($conn);
    }

} else {
    $sql = "INSERT INTO produtos
            (
                nome,
                tamanho,
                qtd_rosas,
                cor_principal,
                cor_secundaria,
                tem_glitter,
                imagem,
                preco
            )
            VALUES
            (
                '$nome',
                '$tamanho',
                '$qtd_rosas',
                '$cor_principal',
                '$cor_secundaria',
                '$tem_glitter',
                '$imagem',
                '$preco'
            )";

    $resultado = mysqli_query($conn, $sql);

    if ($resultado) {

        header("Location: produtosadmin.php");
        exit;

    } else {

        echo "Erro ao cadastrar o buquê: " . mysqli_error($conn);
    }
}

?>
```
