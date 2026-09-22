<?php

session_start();

if (!isset($_SESSION["id"])) {
    header("Location: ../login.html");
    exit;
}

require("../conexão.php");

$usuario_id = (int) $_SESSION["id"];



$sqlCarrinho = "
    SELECT id
    FROM carrinhos
    WHERE usuario_id = '$usuario_id'
    AND status = 'aberto'
    LIMIT 1
";

$resultadoCarrinho = mysqli_query($conn, $sqlCarrinho);

$carrinho_id = null;

if ($resultadoCarrinho && mysqli_num_rows($resultadoCarrinho) > 0) {

    $carrinho = mysqli_fetch_assoc($resultadoCarrinho);

    $carrinho_id = (int) $carrinho["id"];
}


$itens = [];

if ($carrinho_id !== null) {

    $sqlItens = "
        SELECT
            ic.id AS item_id,
            ic.quantidade,
            p.id AS produto_id,
            p.nome,
            p.tamanho,
            p.qtd_rosas,
            p.cor_principal,
            p.cor_secundaria,
            p.tem_glitter,
            p.imagem,
            p.preco
        FROM itens_carrinho ic
        INNER JOIN produtos p
            ON ic.produto_id = p.id
        WHERE ic.carrinho_id = '$carrinho_id'
        ORDER BY ic.id DESC
    ";

    $resultadoItens = mysqli_query($conn, $sqlItens);

    if ($resultadoItens) {

        while ($item = mysqli_fetch_assoc($resultadoItens)) {

            $item_id = (int) $item["item_id"];

            $sqlPersonalizacoes = "
                SELECT
                    pz.id,
                    pz.nome,
                    pz.preco_adicional
                FROM item_personalizacao ip
                INNER JOIN personalizacoes pz
                    ON ip.personalizacao_id = pz.id
                WHERE ip.item_carrinho_id = '$item_id'
                ORDER BY pz.nome
            ";

            $resultadoPersonalizacoes = mysqli_query(
                $conn,
                $sqlPersonalizacoes
            );

            $item["personalizacoes"] = [];

            $valorPersonalizacoes = 0;


            if ($resultadoPersonalizacoes) {

                while ($personalizacao = mysqli_fetch_assoc(
                    $resultadoPersonalizacoes
                )) {

                    $item["personalizacoes"][] = $personalizacao;

                    $valorPersonalizacoes +=
                        (float) $personalizacao["preco_adicional"];
                }
            }

            $precoProduto = (float) $item["preco"];

            $precoUnitario =
                $precoProduto +
                $valorPersonalizacoes;


            $subtotal =
                $precoUnitario *
                (int) $item["quantidade"];


            $item["valor_personalizacoes"] = $valorPersonalizacoes;

            $item["preco_unitario"] = $precoUnitario;

            $item["subtotal"] = $subtotal;


            $itens[] = $item;
        }
    }
}

$total = 0;

foreach ($itens as $item) {
    $total += $item["subtotal"];
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

    <title>Meu Carrinho - Atelier le Fleur</title>

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

        <h2>
            Meu Carrinho
        </h2>


        <?php if (count($itens) == 0): ?>

            <div class="carrinho-vazio">

                <p>
                    Seu carrinho está vazio.
                </p>

                <br>

                <a
                    href="produtos.php"
                    class="btn-produto"
                >
                    Ver buquês
                </a>

            </div>


        <?php else: ?>


            <div class="lista-carrinho">


                <?php foreach ($itens as $item): ?>


                    <div class="item-carrinho">


                        <div class="item-carrinho-imagem">

                            <img
                                src="../<?php echo htmlspecialchars($item["imagem"]); ?>"
                                alt="<?php echo htmlspecialchars($item["nome"]); ?>"
                            >

                        </div>


                        <div class="item-carrinho-info">


                            <h3>
                                <?php echo htmlspecialchars($item["nome"]); ?>
                            </h3>


                            <p>
                                <strong>Tamanho:</strong>
                                <?php echo htmlspecialchars($item["tamanho"]); ?>
                            </p>


                            <p>
                                <strong>Rosas:</strong>
                                <?php echo $item["qtd_rosas"]; ?>
                            </p>


                            <p>
                                <strong>Cor principal:</strong>
                                <?php echo htmlspecialchars($item["cor_principal"]); ?>
                            </p>


                            <?php if (!empty($item["cor_secundaria"])): ?>

                                <p>
                                    <strong>Cor secundária:</strong>
                                    <?php echo htmlspecialchars($item["cor_secundaria"]); ?>
                                </p>

                            <?php endif; ?>


                   

                            <div class="personalizacoes-carrinho">

                                <h4>
                                    Personalizações
                                </h4>


                                <?php if (count($item["personalizacoes"]) > 0): ?>


                                    <ul>

                                        <?php foreach ($item["personalizacoes"] as $personalizacao): ?>

                                            <li>

                                                <?php
                                                echo htmlspecialchars(
                                                    $personalizacao["nome"]
                                                );
                                                ?>

                                                -

                                                + R$

                                                <?php
                                                echo number_format(
                                                    $personalizacao["preco_adicional"],
                                                    2,
                                                    ",",
                                                    "."
                                                );
                                                ?>

                                            </li>

                                        <?php endforeach; ?>

                                    </ul>


                                    <p>

                                        <strong>
                                            Adicionais:
                                        </strong>

                                        + R$

                                        <?php
                                        echo number_format(
                                            $item["valor_personalizacoes"],
                                            2,
                                            ",",
                                            "."
                                        );
                                        ?>

                                    </p>


                                <?php else: ?>


                                    <p>
                                        Nenhuma personalização.
                                    </p>


                                <?php endif; ?>

                            </div>



                    

                            <p>

                                <strong>
                                    Preço unitário:
                                </strong>

                                R$

                                <?php
                                echo number_format(
                                    $item["preco_unitario"],
                                    2,
                                    ",",
                                    "."
                                );
                                ?>

                            </p>



                            <!-- QUANTIDADE -->

                            <form
                                action="atualizarcarrinho.php"
                                method="POST"
                                class="form-quantidade">

                                <input
                                    type="hidden"
                                    name="item_id"
                                    value="<?php echo $item["item_id"]; ?>"
                                >


                                <label for="quantidade_<?php echo $item["item_id"]; ?>">

                                    Quantidade:

                                </label>


                                <input
                                    type="number"
                                    id="quantidade_<?php echo $item["item_id"]; ?>"
                                    name="quantidade"
                                    value="<?php echo $item["quantidade"]; ?>"
                                    min="1"
                                >


                                <button
                                    type="submit"
                                    class="btn-produto"
                                >
                                    Atualizar
                                </button>

                            </form>

                            <p class="subtotal">

                                <strong>
                                    Subtotal:
                                </strong>

                                R$

                                <?php
                                echo number_format(
                                    $item["subtotal"],
                                    2,
                                    ",",
                                    "."
                                );
                                ?>

                            </p>


                            <form
                                action="removercarrinho.php"
                                method="POST"
                            >

                                <input
                                    type="hidden"
                                    name="item_id"
                                    value="<?php echo $item["item_id"]; ?>"
                                >


                                <button
                                    type="submit"
                                    class="btn-remover"
                                >
                                    Remover
                                </button>

                            </form>


                        </div>

                    </div>


                <?php endforeach; ?>


            </div>




            <div class="resumo-carrinho">

                <h3>
                    Resumo da compra
                </h3>


                <p>

                    Quantidade de itens:

                    <strong>
                        <?php echo count($itens); ?>
                    </strong>

                </p>


                <p class="total-carrinho">

                    Total:

                    R$

                    <?php
                    echo number_format(
                        $total,
                        2,
                        ",",
                        "."
                    );
                    ?>

                </p>


                <a href="finalizarcompra.php" class="btn-produto">Finalizar compra</a>


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