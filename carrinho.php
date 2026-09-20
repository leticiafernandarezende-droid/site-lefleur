<?php

session_start();

if (!isset($_SESSION["id"])) {
    header("Location: ../login.html");
    exit;
}

require("../conexão.php");

$usuario_id = $_SESSION["id"];


// Procura o carrinho aberto
$sql = "SELECT id
        FROM carrinhos
        WHERE usuario_id = '$usuario_id'
        AND status = 'aberto'
        LIMIT 1";

$resultado = mysqli_query($conn, $sql);

$carrinho = null;

if (mysqli_num_rows($resultado) > 0) {
    $carrinho = mysqli_fetch_assoc($resultado);
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Meu Carrinho - Atelier le Fleur</title>

    <link rel="stylesheet" href="../style.css">

</head>

<body>

<header>

    <h1>Atelier le Fleur</h1>

    <nav>

        <a href="../index.php">
            Início
        </a>

        <a href="produtos.php">
            Buquês
        </a>

        <a href="carrinho.php">
            Carrinho
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

    <section class="catalogo">

        <h2>Meu Carrinho</h2>


        <?php

        if ($carrinho == null) {

            echo "<p>Seu carrinho está vazio.</p>";

        } else {

            $carrinho_id = $carrinho["id"];


            // Busca os produtos do carrinho
            $sql = "SELECT
                        ic.id AS item_id,
                        ic.quantidade,
                        p.id AS produto_id,
                        p.nome,
                        p.imagem,
                        p.preco,
                        p.tamanho,
                        p.qtd_rosas

                    FROM itens_carrinho ic

                    INNER JOIN produtos p
                    ON ic.produto_id = p.id

                    WHERE ic.carrinho_id = '$carrinho_id'

                    ORDER BY ic.id DESC";


            $resultado = mysqli_query($conn, $sql);


            if (mysqli_num_rows($resultado) == 0) {

                echo "<p>Seu carrinho está vazio.</p>";

            } else {

                $total = 0;

        ?>

                <div class="lista-produtos">

                <?php

                while ($item = mysqli_fetch_assoc($resultado)) {

                    $subtotal =
                        $item["preco"] * $item["quantidade"];

                    $total += $subtotal;

                ?>

                    <div class="card-produto">

                        <img
                            src="../<?php echo htmlspecialchars($item["imagem"]); ?>"
                            alt="<?php echo htmlspecialchars($item["nome"]); ?>"
                        >


                        <div class="info-produto">

                            <h3>
                                <?php
                                echo htmlspecialchars($item["nome"]);
                                ?>
                            </h3>


                            <p>
                                Tamanho:
                                <?php
                                echo htmlspecialchars($item["tamanho"]);
                                ?>
                            </p>


                            <p>
                                <?php
                                echo $item["qtd_rosas"];
                                ?>
                                rosas
                            </p>


                            <p>
                                Preço unitário:
                                R$

                                <?php
                                echo number_format(
                                    $item["preco"],
                                    2,
                                    ",",
                                    "."
                                );
                                ?>
                            </p>


                            <!-- Alterar quantidade -->

                            <form
                                action="atualizarcarrinho.php"
                                method="POST"
                            >

                                <input
                                    type="hidden"
                                    name="item_id"
                                    value="<?php echo $item["item_id"]; ?>"
                                >

                                <label>
                                    Quantidade:
                                </label>

                                <input
                                    type="number"
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


                            <!-- Subtotal -->

                            <p class="preco">

                                Subtotal:

                                R$

                                <?php
                                echo number_format(
                                    $subtotal,
                                    2,
                                    ",",
                                    "."
                                );
                                ?>

                            </p>


                            <!-- Remover -->

                            <form
                                action="removercarrinho.php"
                                method="POST"
                                onsubmit="return confirm('Deseja remover este produto do carrinho?');"
                            >

                                <input
                                    type="hidden"
                                    name="item_id"
                                    value="<?php echo $item["item_id"]; ?>"
                                >

                                <button
                                    type="submit"
                                    class="btn-produto"
                                >
                                    Remover
                                </button>

                            </form>

                        </div>

                    </div>

                <?php

                }

                ?>

                </div>


                <!-- TOTAL -->

                <div class="total-carrinho">

                    <h2>

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

                    </h2>


                    <button class="btn-produto">
                        Finalizar compra
                    </button>

                </div>

        <?php

            }

        }

        ?>

    </section>

</main>


<footer>

    <p>
        &copy;
        <?php echo date("Y"); ?>
        Atelier le Fleur
    </p>

</footer>

</body>

</html>
```
