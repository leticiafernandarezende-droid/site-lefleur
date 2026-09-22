```php
<?php

session_start();

if (!isset($_SESSION["id"])) {
    header("Location: ../login.html");
    exit;
}

if ($_SESSION["tipo"] != "admin") {
    echo "Acesso negado.";
    exit;
}

require("../conexão.php");

$nome = $_POST["nome"];
$tamanho = $_POST["tamanho"];
$qtd_rosas = $_POST["qtd_rosas"];
$cor_principal = $_POST["cor_principal"];
$cor_secundaria = $_POST["cor_secundaria"] ?? "";
$tem_glitter = isset($_POST["tem_glitter"]) ? 1 : 0;
$preco = $_POST["preco"];


/* =====================================================
   VERIFICA SE É EDIÇÃO OU CADASTRO
===================================================== */

if (isset($_POST["id"]) && !empty($_POST["id"])) {

    $id = $_POST["id"];

    /* Buscar imagem atual */
    $sqlImagem = "SELECT imagem FROM produtos WHERE id = '$id'";
    $resultadoImagem = mysqli_query($conn, $sqlImagem);

    $produtoAtual = mysqli_fetch_assoc($resultadoImagem);

    $imagem = $produtoAtual["imagem"];


    /* =================================================
       SE O ADMIN ESCOLHEU UMA NOVA IMAGEM
    ================================================= */

    if (isset($_FILES["imagem"]) && $_FILES["imagem"]["error"] == 0) {

        $nomeOriginal = $_FILES["imagem"]["name"];

        $extensao = strtolower(
            pathinfo($nomeOriginal, PATHINFO_EXTENSION)
        );

        $extensoesPermitidas = ["jpg", "jpeg", "png", "webp"];

        if (!in_array($extensao, $extensoesPermitidas)) {
            die("Formato de imagem não permitido.");
        }

        /* Nome único */
        $novoNome = uniqid() . "." . $extensao;

        /* Caminho físico */
        $caminho = "../img/" . $novoNome;

        /* Salvar imagem */
        if (!move_uploaded_file($_FILES["imagem"]["tmp_name"], $caminho)) {
            die("Erro ao salvar a nova imagem.");
        }

        /* Caminho que será salvo no banco */
        $imagem = "img/" . $novoNome;
    }


    /* =================================================
       ATUALIZAR PRODUTO
    ================================================= */

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


/* =====================================================
   NOVO CADASTRO
===================================================== */

} else {

    if (!isset($_FILES["imagem"]) || $_FILES["imagem"]["error"] != 0) {
        die("Você precisa selecionar uma imagem.");
    }

    $nomeOriginal = $_FILES["imagem"]["name"];

    $extensao = strtolower(
        pathinfo($nomeOriginal, PATHINFO_EXTENSION)
    );

    $extensoesPermitidas = ["jpg", "jpeg", "png", "webp"];

    if (!in_array($extensao, $extensoesPermitidas)) {
        die("Formato de imagem não permitido.");
    }


    /* Nome único para a imagem */
    $novoNome = uniqid() . "." . $extensao;


    /* Caminho físico */
    $caminho = "../img/" . $novoNome;


    /* Salvar imagem na pasta img */
    if (!move_uploaded_file($_FILES["imagem"]["tmp_name"], $caminho)) {
        die("Erro ao salvar a imagem.");
    }


    /* Caminho salvo no banco */
    $imagem = "img/" . $novoNome;


    /* =================================================
       INSERT
    ================================================= */

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
