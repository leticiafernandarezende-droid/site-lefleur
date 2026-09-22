<?php

session_start();

if (!isset($_SESSION["id"])) {
    header("Location: ../login.html");
    exit;
}

require("../conexão.php");

$usuario_id = $_SESSION["id"];


$sql = "
    SELECT *
    FROM pedidos
    WHERE usuario_id = '$usuario_id'
    ORDER BY data_pedido DESC
";

$resultado = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Meus Pedidos - Atelier le Fleur</title>

    <link rel="stylesheet" href="../style.css">

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

        <h2>Meus pedidos</h2>

        <p>
            Aqui você pode consultar todos os pedidos realizados.
        </p>


        <?php if ($resultado && mysqli_num_rows($resultado) > 0): ?>

            <div class="lista-pedidos">

                <?php while ($pedido = mysqli_fetch_assoc($resultado)): ?>

                    <div class="card-pedido">

                        <div class="pedido-cabecalho">

                            <div>

                                <h3>
                                    Pedido #<?php echo $pedido["id"]; ?>
                                </h3>

                                <p>
                                    Realizado em:
                                    <?php
                                    echo date(
                                        "d/m/Y H:i",
                                        strtotime($pedido["data_pedido"])
                                    );
                                    ?>
                                </p>

                            </div>

                            <span class="status-pedido">

                                <?php
                                echo htmlspecialchars($pedido["status"]);
                                ?>

                            </span>

                        </div>


                        <div class="pedido-informacoes">

                            <div>

                                <strong>Total</strong>

                                <p class="pedido-total">

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


                            <a
                                href="pedido.php?id=<?php echo $pedido["id"]; ?>"
                                class="btn-produto"
                            >
                                Ver pedido
                            </a>

                        </div>

                    </div>

                <?php endwhile; ?>

            </div>

        <?php else: ?>

            <div class="carrinho-vazio">

                <h3>Você ainda não realizou nenhum pedido.</h3>

                <p>
                    Explore nossos buquês e faça seu primeiro pedido.
                </p>

                <a href="produtos.php" class="btn-produto">
                    Ver buquês
                </a>

            </div>

        <?php endif; ?>

    </section>

</main>


<footer>

    <p>
        &copy; <?php echo date("Y"); ?> Atelier le Fleur
    </p>

</footer>

</body>

</html>
