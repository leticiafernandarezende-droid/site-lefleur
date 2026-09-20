<?php

session_start();

if (!isset($_SESSION["id"])) {
    header("Location: ../login.html");
    exit;
}

if ($_SESSION["tipo"] == "admin") {
    echo "Administradores não podem realizar compras.";
    exit;
}

require("../conexão.php");


if (!isset($_POST["produto_id"])) {
    echo "Produto não informado.";
    exit;
}

$produto_id = $_POST["produto_id"];

if (isset($_POST["quantidade"])) {
    $quantidade = (int) $_POST["quantidade"];
} else {
    $quantidade = 1;
}

if ($quantidade < 1) {
    $quantidade = 1;
}


$usuario_id = $_SESSION["id"];

$sql = "SELECT id FROM carrinhos
        WHERE usuario_id = '$usuario_id'
        AND status = 'aberto'
        LIMIT 1";

$resultado = mysqli_query($conn, $sql);


if (mysqli_num_rows($resultado) > 0) {

    $carrinho = mysqli_fetch_assoc($resultado);

    $carrinho_id = $carrinho["id"];

} else {

    // Cria um novo carrinho
    $sql = "INSERT INTO carrinhos (usuario_id, status)
            VALUES ('$usuario_id', 'aberto')";

    mysqli_query($conn, $sql);

    $carrinho_id = mysqli_insert_id($conn);
}

$sql = "SELECT id, quantidade
        FROM itens_carrinho
        WHERE carrinho_id = '$carrinho_id'
        AND produto_id = '$produto_id'
        LIMIT 1";

$resultado = mysqli_query($conn, $sql);


if (mysqli_num_rows($resultado) > 0) {

    $item = mysqli_fetch_assoc($resultado);

    $nova_quantidade = $item["quantidade"] + $quantidade;

    $sql = "UPDATE itens_carrinho
            SET quantidade = '$nova_quantidade'
            WHERE id = '" . $item["id"] . "'";

    mysqli_query($conn, $sql);

} else {

    $sql = "INSERT INTO itens_carrinho
            (carrinho_id, produto_id, quantidade)
            VALUES
            ('$carrinho_id', '$produto_id', '$quantidade')";

    mysqli_query($conn, $sql);
}


header("Location: carrinho.php");
exit;

?>