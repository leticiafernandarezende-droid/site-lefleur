<?php

session_start();

if (!isset($_SESSION["id"])) {
    header("Location: ../login.html");
    exit;
}

require("../conexão.php");

if (!isset($_POST["item_id"])) {
    header("Location: carrinho.php");
    exit;
}

$item_id = $_POST["item_id"];

$usuario_id = $_SESSION["id"];

$sql = "DELETE ic

        FROM itens_carrinho ic

        INNER JOIN carrinhos c
        ON ic.carrinho_id = c.id

        WHERE ic.id = '$item_id'

        AND c.usuario_id = '$usuario_id'

        AND c.status = 'aberto'";

mysqli_query($conn, $sql);


header("Location: carrinho.php");
exit;

?>

