<?php

session_start();


if (!isset($_SESSION["id"])) {
    header("Location: ../login.html");
    exit;
}


require("../conexão.php");


if (!isset($_POST["pedido_id"])) {
    header("Location: pedidos.php");
    exit;
}


$pedido_id = (int) $_POST["pedido_id"];

$usuario_id = $_SESSION["id"];

$sql = "
    UPDATE pedidos
    SET status = 'Cancelado'
    WHERE id = '$pedido_id'
    AND usuario_id = '$usuario_id'
    AND (
        status = 'Recebido'
        OR status = 'Em preparação'
    )
";


$resultado = mysqli_query($conn, $sql);

if ($resultado) {

    header(
        "Location: pedido.php?id=" . $pedido_id
    );

    exit;
}


echo "Erro ao cancelar o pedido.";

?>
```
