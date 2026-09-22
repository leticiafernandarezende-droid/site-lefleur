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



if (!isset($_GET["id"])) {
    header("Location: pedidosadmin.php");
    exit;
}

$pedido_id = (int) $_GET["id"];

if (
    isset($_POST["atualizar_status"]) &&
    isset($_POST["status"])
) {

    $status = $_POST["status"];

    $statusPermitidos = [
        "Recebido",
        "Em preparação",
        "Enviado",
        "Concluído",
        "Cancelado"
    ];

    if (in_array($status, $statusPermitidos)) {

        $sqlStatus = "
            UPDATE pedidos
            SET status = '$status'
            WHERE id = '$pedido_id'
        ";

        mysqli_query($conn, $sqlStatus);
    }

    header("Location: pedidoadmin.php?id=" . $pedido_id);
    exit;
}


$sqlPedido = "
    SELECT
        p.*,
        u.nome AS cliente,
        u.email,
        u.telefone
    FROM pedidos p
    INNER JOIN usuarios u
        ON p.usuario_id = u.id
    WHERE p.id = '$pedido_id'
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

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Pedido #<?php echo $pedido["id"]; ?>
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

        <a href="produtosadmin.php">
            Produtos
        </a>

        <a href="pedidosadmin.php">
            Pedidos
        </a>

        <span>
            Olá,
            <?php echo htmlspecialchars($_SESSION["nome"]); ?>!
        </span>

        <a href="../logout.php">
            Sair
        </a>

    </nav>

</header>

<main>

    <section class="admin-detalhes-pedido">


        <div class="admin-pedido-topo">

            <div>

                <h2>
                    Pedido #<?php echo $pedido["id"]; ?>
                </h2>

                <p>

                    Realizado em:

                    <?php
                    echo date(
                        "d/m/Y H:i",
                        strtotime(
                            $pedido["data_pedido"]
                        )
                    );
                    ?>

                </p>

            </div>


            <span class="status-admin">

                <?php
                echo htmlspecialchars(
                    $pedido["status"]
                );
                ?>

            </span>

        </div>



        <div class="dados-cliente-pedido">

            <h3>
                Dados do cliente
            </h3>

            <p>

                <strong>
                    Nome:
                </strong>

                <?php
                echo htmlspecialchars(
                    $pedido["cliente"]
                );
                ?>

            </p>


            <p>

                <strong>
                    E-mail:
                </strong>

                <?php
                echo htmlspecialchars(
                    $pedido["email"]
                );
                ?>

            </p>


            <?php if (!empty($pedido["telefone"])): ?>

                <p>

                    <strong>
                        Telefone:
                    </strong>

                    <?php
                    echo htmlspecialchars(
                        $pedido["telefone"]
                    );
                    ?>

                </p>

            <?php endif; ?>

        </div>
                    <div class="informacoes-admin-pedido">

    <div class="endereco-admin-pedido">

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
            <strong>Rua:</strong>
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


    <div class="pagamento-admin-pedido">

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










        <div class="alterar-status">

            <h3>
                Status do pedido
            </h3>


            <form method="POST">

                <select name="status">

                    <option
                        value="Recebido"
                        <?php
                        if ($pedido["status"] == "Recebido") {
                            echo "selected";
                        }
                        ?>
                    >
                        Recebido
                    </option>


                    <option
                        value="Em preparação"
                        <?php
                        if ($pedido["status"] == "Em preparação") {
                            echo "selected";
                        }
                        ?>
                    >
                        Em preparação
                    </option>


                    <option
                        value="Enviado"
                        <?php
                        if ($pedido["status"] == "Enviado") {
                            echo "selected";
                        }
                        ?>
                    >
                        Enviado
                    </option>


                    <option
                        value="Concluído"
                        <?php
                        if ($pedido["status"] == "Concluído") {
                            echo "selected";
                        }
                        ?>
                    >
                        Concluído
                    </option>


                    <option
                        value="Cancelado"
                        <?php
                        if ($pedido["status"] == "Cancelado") {
                            echo "selected";
                        }
                        ?>
                    >
                        Cancelado
                    </option>

                </select>


                <button
                    type="submit"
                    name="atualizar_status"
                    class="btn-produto"
                >
                    Atualizar status
                </button>

            </form>

        </div>



        <h3 class="titulo-itens-admin">
            Produtos do pedido
        </h3>


        <?php if ($resultadoItens && mysqli_num_rows($resultadoItens) > 0): ?>

            <div class="itens-admin-pedido">


                <?php while ($item = mysqli_fetch_assoc($resultadoItens)): ?>


                    <div class="item-admin-pedido">


                        <div class="imagem-admin-pedido">

                            <img
                                src="../<?php echo htmlspecialchars($item["imagem"]); ?>"
                                alt="<?php echo htmlspecialchars($item["nome"]); ?>"
                            >

                        </div>


                        <div class="info-admin-pedido">

                            <h4>

                                <?php
                                echo htmlspecialchars(
                                    $item["nome"]
                                );
                                ?>

                            </h4>


                            <p>

                                Tamanho:

                                <?php
                                echo htmlspecialchars(
                                    $item["tamanho"]
                                );
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
                                mysqli_num_rows(
                                    $resultadoPersonalizacoes
                                ) > 0
                            ): ?>

                                <div class="personalizacoes-admin">

                                    <strong>
                                        Personalizações:
                                    </strong>

                                    <ul>

                                        <?php while (
                                            $personalizacao =
                                            mysqli_fetch_assoc(
                                                $resultadoPersonalizacoes
                                            )
                                        ): ?>

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


                        <div class="valor-admin-pedido">

                            <span>

                                Unitário:

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

                                Subtotal:

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
                Nenhum produto encontrado neste pedido.
            </p>

        <?php endif; ?>

        <div class="total-admin-pedido">

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



        <div class="acoes-admin-pedido">

            <a
                href="pedidosadmin.php"
                class="btn-secundario"
            >
                Voltar para pedidos
            </a>

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
