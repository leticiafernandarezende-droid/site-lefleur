<?php

session_start();

if (!isset($_SESSION["id"])) {
    header("Location: ../login.html");
    exit;
}

require("../conexão.php");

$usuario_id = $_SESSION["id"];

$sqlCarrinho = "
    SELECT id
    FROM carrinhos
    WHERE usuario_id = '$usuario_id'
    AND status = 'aberto'
    LIMIT 1
";

$resultadoCarrinho = mysqli_query($conn, $sqlCarrinho);

if (!$resultadoCarrinho || mysqli_num_rows($resultadoCarrinho) == 0) {
    header("Location: carrinho.php");
    exit;
}

$carrinho = mysqli_fetch_assoc($resultadoCarrinho);

$carrinho_id = $carrinho["id"];


$sqlItens = "
    SELECT
        ic.id AS item_id,
        ic.quantidade,
        p.id AS produto_id,
        p.nome,
        p.tamanho,
        p.imagem,
        p.preco
    FROM itens_carrinho ic

    INNER JOIN produtos p
        ON ic.produto_id = p.id

    WHERE ic.carrinho_id = '$carrinho_id'
";

$resultadoItens = mysqli_query($conn, $sqlItens);

if (!$resultadoItens || mysqli_num_rows($resultadoItens) == 0) {
    header("Location: carrinho.php");
    exit;
}

$sqlEnderecos = "
    SELECT *
    FROM enderecos
    WHERE usuario_id = '$usuario_id'
    ORDER BY id DESC
";

$resultadoEnderecos = mysqli_query($conn, $sqlEnderecos);



$itens = [];
$total = 0;

while ($item = mysqli_fetch_assoc($resultadoItens)) {

    $personalizacoes = [];

    $sqlPersonalizacoes = "
        SELECT
            p.nome,
            p.preco_adicional
        FROM item_personalizacao ip
        INNER JOIN personalizacoes p
            ON ip.personalizacao_id = p.id
        WHERE ip.item_carrinho_id = '{$item["item_id"]}'
        ORDER BY p.nome
    ";

    $resultadoPersonalizacoes = mysqli_query(
        $conn,
        $sqlPersonalizacoes
    );

    $totalPersonalizacoes = 0;

    if ($resultadoPersonalizacoes) {

        while ($personalizacao = mysqli_fetch_assoc($resultadoPersonalizacoes)) {

            $personalizacoes[] = $personalizacao;

            $totalPersonalizacoes += (float) $personalizacao["preco_adicional"];
        }
    }


    $precoUnitario =
        (float) $item["preco"] +
        $totalPersonalizacoes;

    $subtotal =
        $precoUnitario *
        (int) $item["quantidade"];

    $total += $subtotal;


    $item["personalizacoes"] = $personalizacoes;
    $item["preco_unitario"] = $precoUnitario;
    $item["subtotal"] = $subtotal;

    $itens[] = $item;
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Finalizar compra - Atelier le Fleur</title>

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

    <section class="finalizar-compra">

        <h2>Finalizar compra</h2>

        <div class="etapa-compra">

            <h3>1. Endereço de entrega</h3>

            <?php if (
                $resultadoEnderecos &&
                mysqli_num_rows($resultadoEnderecos) > 0
            ): ?>

                <div class="lista-enderecos-compra">

                    <?php
                    $primeiroEndereco = true;
                    ?>

                    <?php while ($endereco = mysqli_fetch_assoc($resultadoEnderecos)): ?>

                        <label class="endereco-opcao">

                            <input
                                type="radio"
                                name="endereco_id"
                                value="<?php echo $endereco["id"]; ?>"
                                form="form-pedido"
                                <?php
                                if ($primeiroEndereco) {
                                    echo "checked";
                                    $primeiroEndereco = false;
                                }
                                ?>
                            >

                            <div>

                                <strong>
                                    <?php
                                    echo htmlspecialchars(
                                        $endereco["nome_destinatario"]
                                    );
                                    ?>
                                </strong>

                                <p>
                                    <?php
                                    echo htmlspecialchars(
                                        $endereco["rua"]
                                    );
                                    ?>,
                                    <?php
                                    echo htmlspecialchars(
                                        $endereco["numero"]
                                    );
                                    ?>
                                </p>

                                <?php if (!empty($endereco["complemento"])): ?>

                                    <p>
                                        <?php
                                        echo htmlspecialchars(
                                            $endereco["complemento"]
                                        );
                                        ?>
                                    </p>

                                <?php endif; ?>

                                <p>
                                    <?php
                                    echo htmlspecialchars(
                                        $endereco["bairro"]
                                    );
                                    ?>
                                </p>

                                <p>
                                    <?php
                                    echo htmlspecialchars(
                                        $endereco["cidade"]
                                    );
                                    ?>
                                    -
                                    <?php
                                    echo htmlspecialchars(
                                        $endereco["estado"]
                                    );
                                    ?>
                                </p>

                                <p>
                                    CEP:
                                    <?php
                                    echo htmlspecialchars(
                                        $endereco["cep"]
                                    );
                                    ?>
                                </p>

                            </div>

                        </label>

                    <?php endwhile; ?>

                </div>

                <a
                    href="enderecos.php"
                    class="btn-secundario"
                >
                    Gerenciar endereços
                </a>

            <?php else: ?>

                <div class="aviso-endereco">

                    <p>
                        Você ainda não possui um endereço cadastrado.
                    </p>

                    <a
                        href="enderecos.php"
                        class="btn-produto"
                    >
                        Cadastrar endereço
                    </a>

                </div>

            <?php endif; ?>

        </div>


        <div class="etapa-compra">

            <h3>2. Forma de pagamento</h3>

            <div class="formas-pagamento">

                <label class="pagamento-opcao">

                    <input
                        type="radio"
                        name="forma_pagamento"
                        value="Pix"
                        form="form-pedido"
                        checked
                    >

                    <span>
                        Pix
                    </span>

                </label>


                <label class="pagamento-opcao">

                    <input
                        type="radio"
                        name="forma_pagamento"
                        value="Cartão de crédito"
                        form="form-pedido"
                    >

                    <span>
                        Cartão de crédito
                    </span>

                </label>


                <label class="pagamento-opcao">

                    <input
                        type="radio"
                        name="forma_pagamento"
                        value="Cartão de débito"
                        form="form-pedido"
                    >

                    <span>
                        Cartão de débito
                    </span>

                </label>


                <label class="pagamento-opcao">

                    <input
                        type="radio"
                        name="forma_pagamento"
                        value="Dinheiro"
                        form="form-pedido"
                    >

                    <span>
                        Dinheiro
                    </span>

                </label>

            </div>

        </div>


        <div class="etapa-compra">

            <h3>3. Resumo do pedido</h3>


            <div class="resumo-finalizacao">

                <?php foreach ($itens as $item): ?>

                    <div class="item-finalizacao">

                        <div>

                            <strong>
                                <?php
                                echo htmlspecialchars(
                                    $item["nome"]
                                );
                                ?>
                            </strong>

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


                            <?php if (!empty($item["personalizacoes"])): ?>

                                <div class="personalizacoes-finalizacao">

                                    <strong>
                                        Personalizações:
                                    </strong>

                                    <?php foreach (
                                        $item["personalizacoes"]
                                        as $personalizacao
                                    ): ?>

                                        <p>

                                            <?php
                                            echo htmlspecialchars(
                                                $personalizacao["nome"]
                                            );
                                            ?>

                                            + R$
                                            <?php
                                            echo number_format(
                                                $personalizacao["preco_adicional"],
                                                2,
                                                ",",
                                                "."
                                            );
                                            ?>

                                        </p>

                                    <?php endforeach; ?>

                                </div>

                            <?php endif; ?>

                        </div>


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

                <?php endforeach; ?>


                <div class="total-finalizacao">

                    <span>
                        Total
                    </span>

                    <strong>

                        R$
                        <?php
                        echo number_format(
                            $total,
                            2,
                            ",",
                            "."
                        );
                        ?>

                    </strong>

                </div>

            </div>

        </div>

        <form
            id="form-pedido"
            action="processarpedido.php"
            method="POST"
        >

            <input
                type="hidden"
                name="confirmar"
                value="1"
            >

            <button
                type="submit"
                class="btn-produto"
            >
                Confirmar pedido
            </button>

        </form>


        <br>

        <a
            href="carrinho.php"
            class="btn-secundario"
        >
            Voltar para o carrinho
        </a>

    </section>

</main>


<footer>

    <p>
        &copy; <?php echo date("Y"); ?> Atelier le Fleur
    </p>

</footer>

</body>

</html>