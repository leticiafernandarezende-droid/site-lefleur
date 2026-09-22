<?php

session_start();

if (!isset($_SESSION["id"])) {
    header("Location: ../login.html");
    exit;
}

require("../conexão.php");

$usuario_id = (int) $_SESSION["id"];


if (!isset($_GET["id"])) {
    header("Location: produtos.php");
    exit;
}

$pedido_id = (int) $_GET["id"];

$sql = "
    SELECT
        id,
        total,
        status,
        data_pedido
    FROM pedidos
    WHERE id = '$pedido_id'
    AND usuario_id = '$usuario_id'
    LIMIT 1
";

$resultado = mysqli_query($conn, $sql);

if (!$resultado || mysqli_num_rows($resultado) == 0) {
    echo "Pedido não encontrado.";
    exit;
}

$pedido = mysqli_fetch_assoc($resultado);

?>

<!DOCTYPE html>

<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Pedido Confirmado - Atelier le Fleur</title>

    <link
        rel="stylesheet"
        href="../style.css"
    >

</head>


<body>


<header class="header">

    <div class="logo">
        Atelier le Fleur
    </div>

    <nav>

        <a href="../index.php">
            Início
        </a>

        <a href="produtos.php">
            Buquês
        </a>


        <?php if (isset($_SESSION["id"])): ?>


            <?php if ($_SESSION["tipo"] == "admin"): ?>

                <span>
                    Olá,
                    <?php echo htmlspecialchars($_SESSION["nome"]); ?>!
                </span>

                <a href="../admin/produtosadmin.php">
                    Produtos
                </a>

                <a href="../admin/pedidosadmin.php">
                    Pedidos
                </a>

                <a href="../logout.php">
                    Sair
                </a>


            <?php else: ?>

                <span>
                    Olá,
                    <?php echo htmlspecialchars($_SESSION["nome"]); ?>!
                </span>

                <a href="editar.php">
                    Minha conta
                </a>

                <a href="enderecos.php">
                    Meus endereços
                </a>

                <a href="pedidos.php">
                    Meus pedidos
                </a>

                <a href="carrinho.php">
                    Carrinho
                </a>

                <a href="../logout.php">
                    Sair
                </a>

            <?php endif; ?>


        <?php else: ?>

            <a href="../login.html">
                Entrar
            </a>

            <a href="../cadastro.html">
                Criar conta
            </a>

        <?php endif; ?>

    </nav>

</header>


<main>

    <section class="secao">

        <div class="pedido-confirmado">

            <div class="pedido-icone">
                ✓
            </div>


            <h2>
                Pedido realizado com sucesso!
            </h2>


            <p class="mensagem-pedido">

                Obrigado pela sua compra no
                <strong>Atelier le Fleur</strong>.

            </p>


            <div class="dados-pedido">

                <p>

                    <strong>
                        Número do pedido:
                    </strong>

                    #<?php echo $pedido["id"]; ?>

                </p>


                <p>

                    <strong>
                        Data:
                    </strong>

                    <?php
                    echo date(
                        "d/m/Y H:i",
                        strtotime($pedido["data_pedido"])
                    );
                    ?>

                </p>


                <p>

                    <strong>
                        Status:
                    </strong>

                    <?php echo htmlspecialchars($pedido["status"]); ?>

                </p>


                <p class="valor-pedido">

                    <strong>
                        Total:
                    </strong>

                    R$

                    <?php
                    echo number_format(
                        $pedido["total"],
                        2,
                        ",",
                        "."
                    );
                    ?>

                </p>

            </div>


            <div class="acoes-pedido">

                <a
                    href="pedidos.php"
                    class="btn-produto"
                >
                    Meus pedidos
                </a>


                <a
                    href="produtos.php"
                    class="btn-secundario"
                >
                    Continuar comprando
                </a>

            </div>

        </div>

    </section>

</main>


<footer>

    <p>
        &copy; <?php echo date("Y"); ?> Atelier le Fleur
    </p>

</footer>


</body>

</html>
```
