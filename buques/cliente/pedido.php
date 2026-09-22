<?php

session_start();

if (!isset($_SESSION["id"])) {
    header("Location: ../login.html");
    exit;
}

require("../conexão.php");

$usuario_id = $_SESSION["id"];


if (!isset($_GET["id"])) {
    header("Location: pedidos.php");
    exit;
}

$pedido_id = (int) $_GET["id"];



$sqlPedido = "
    SELECT *
    FROM pedidos
    WHERE id = '$pedido_id'
    AND usuario_id = '$usuario_id'
";

$resultadoPedido = mysqli_query($conn, $sqlPedido);

if (!$resultadoPedido || mysqli_num_rows($resultadoPedido) == 0) {
    echo "Pedido não encontrado.";
    exit;
}

$pedido = mysqli_fetch_assoc($resultadoPedido);

$sqlItens = "
    SELECT
        ip.*,
        p.nome,
        p.tamanho,
        p.imagem
    FROM itens_pedido ip
    INNER JOIN produtos p
        ON ip.produto_id = p.id
    WHERE ip.pedido_id = '$pedido_id'
    ORDER BY ip.id ASC
";

$resultadoItens = mysqli_query($conn, $sqlItens);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Pedido #<?php echo $pedido["id"]; ?> - Atelier le Fleur
    </title>

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

    <section class="detalhes-pedido">

        <div class="topo-pedido">

            <div>

                <h2>
                    Pedido #<?php echo $pedido["id"]; ?>
                </h2>

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
             
            <div class="informacoes-pedido">

    <div class="endereco-pedido">

        <h3>Endereço de entrega</h3>

        <p>
            <strong>Destinatário:</strong>
            <?php
            echo htmlspecialchars(
                $pedido["nome_destinatario"]
            );
            ?>
        </p>

        <p>
            <strong>Endereço:</strong>
            <?php
            echo htmlspecialchars(
                $pedido["rua"]
            );
            ?>,
            <?php
            echo htmlspecialchars(
                $pedido["numero"]
            );
            ?>
        </p>

        <?php if (!empty($pedido["complemento"])): ?>

            <p>
                <strong>Complemento:</strong>
                <?php
                echo htmlspecialchars(
                    $pedido["complemento"]
                );
                ?>
            </p>

        <?php endif; ?>

        <p>
            <strong>Bairro:</strong>
            <?php
            echo htmlspecialchars(
                $pedido["bairro"]
            );
            ?>
        </p>

        <p>
            <strong>Cidade:</strong>
            <?php
            echo htmlspecialchars(
                $pedido["cidade"]
            );
            ?>
            -
            <?php
            echo htmlspecialchars(
                $pedido["estado"]
            );
            ?>
        </p>

        <p>
            <strong>CEP:</strong>
            <?php
            echo htmlspecialchars(
                $pedido["cep"]
            );
            ?>
        </p>

    </div>


    <div class="pagamento-pedido">

        <h3>Forma de pagamento</h3>

        <p>
            <?php
            echo htmlspecialchars(
                $pedido["forma_pagamento"]
            );
            ?>
        </p>

        </div>

    </div>




        <h3 class="titulo-itens">
            Itens do pedido
        </h3>


        <?php if ($resultadoItens && mysqli_num_rows($resultadoItens) > 0): ?>

            <div class="itens-pedido">

                <?php while ($item = mysqli_fetch_assoc($resultadoItens)): ?>

                    <div class="item-pedido">

                        <div class="imagem-item-pedido">

                            <img
                                src="../<?php echo htmlspecialchars($item["imagem"]); ?>"
                                alt="<?php echo htmlspecialchars($item["nome"]); ?>"
                            >

                        </div>


                        <div class="info-item-pedido">

                            <h4>
                                <?php
                                echo htmlspecialchars($item["nome"]);
                                ?>
                            </h4>

                            <p>
                                Tamanho:
                                <?php
                                echo htmlspecialchars($item["tamanho"]);
                                ?>
                            </p>

                            <p>
                                Quantidade:
                                <?php
                                echo $item["quantidade"];
                                ?>
                            </p>


                            <?php
                            $item_id = $item["id"];

                            $sqlPersonalizacoes = "
                                SELECT *
                                FROM item_pedido_personalizacao
                                WHERE item_pedido_id = '$item_id'
                            ";

                            $resultadoPersonalizacoes =
                                mysqli_query(
                                    $conn,
                                    $sqlPersonalizacoes
                                );
                            ?>


                            <?php if (
                                $resultadoPersonalizacoes &&
                                mysqli_num_rows($resultadoPersonalizacoes) > 0
                            ): ?>

                                <div class="personalizacoes-pedido">

                                    <strong>
                                        Personalizações:
                                    </strong>

                                    <ul>

                                        <?php
                                        while (
                                            $personalizacao =
                                            mysqli_fetch_assoc(
                                                $resultadoPersonalizacoes
                                            )
                                        ):
                                        ?>

                                            <li>

                                                <?php
                                                echo htmlspecialchars(
                                                    $personalizacao["nome"]
                                                );
                                                ?>

                                                +

                                                R$
                                                <?php
                                                echo number_format(
                                                    $personalizacao[
                                                        "preco_adicional"
                                                    ],
                                                    2,
                                                    ",",
                                                    "."
                                                );
                                                ?>

                                            </li>

                                        <?php endwhile; ?>

                                    </ul>

                                </div>

                            <?php endif; ?>

                        </div>


                        <div class="valor-item-pedido">

                            <span>
                                R$
                                <?php
                                echo number_format(
                                    $item["preco_unitario"],
                                    2,
                                    ",",
                                    "."
                                );
                                ?>
                            </span>

                            <strong>
                                R$
                                <?php
                                echo number_format(
                                    $item["subtotal"],
                                    2,
                                    ",",
                                    "."
                                );
                                ?>
                            </strong>

                        </div>

                    </div>

                <?php endwhile; ?>

            </div>

        <?php else: ?>

            <p>
                Nenhum item encontrado neste pedido.
            </p>

        <?php endif; ?>


        <div class="total-pedido">

            <span>
                Total do pedido
            </span>

            <strong>

                R$
                <?php
                echo number_format(
                    $pedido["total"],
                    2,
                    ",",
                    "."
                );
                ?>

            </strong>

        </div>


        <div class="acoes-pedido">

        <a
        href="pedidos.php"
        class="btn-secundario"
        >
        Voltar para meus pedidos
        </a>

        <a
        href="produtos.php"
        class="btn-produto"
        >
        Continuar comprando
        </a>


        <?php if (
        $pedido["status"] == "Recebido" ||
        $pedido["status"] == "Em preparação"):
        ?>

        <form
            action="cancelarpedido.php"
            method="POST"
            onsubmit="return confirm('Tem certeza que deseja cancelar este pedido?');">


            <input
                type="hidden"
                name="pedido_id"
                value="<?php echo $pedido["id"]; ?>"
            >

            <button
                type="submit"
                class="btn-cancelar"
            >
                Cancelar pedido
            </button>

        </form>

        <?php endif; ?>

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
