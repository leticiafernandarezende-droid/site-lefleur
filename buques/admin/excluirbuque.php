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


if (!isset($_POST["id"])) {
    echo "Produto não informado.";
    exit;
}

$id = $_POST["id"];

$sql = "DELETE FROM produtos WHERE id = '$id'";

$resultado = mysqli_query($conn, $sql);

if ($resultado) {

    header("Location: produtosadmin.php");
    exit;

} else {

    echo "Erro ao excluir o buquê: " . mysqli_error($conn);
}

?>
```
