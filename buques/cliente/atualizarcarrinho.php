```php
<?php

session_start();

if (!isset($_SESSION["id"])) {
    header("Location: ../login.html");
    exit;
}

require("../conexão.php");

if (!isset($_POST["item_id"]) || !isset($_POST["quantidade"])) {
    header("Location: carrinho.php");
    exit;
}

$item_id = $_POST["item_id"];
$quantidade = (int) $_POST["quantidade"];

if ($quantidade < 1) {
    $quantidade = 1;
}


// Garante que o item pertence ao usuário
$usuario_id = $_SESSION["id"];

$sql = "UPDATE itens_carrinho ic

        INNER JOIN carrinhos c
        ON ic.carrinho_id = c.id

        SET ic.quantidade = '$quantidade'

        WHERE ic.id = '$item_id'

        AND c.usuario_id = '$usuario_id'

        AND c.status = 'aberto'";

mysqli_query($conn, $sql);


header("Location: carrinho.php");
exit;

?>
