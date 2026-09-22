<?php

session_start();

require("../conexão.php");


if (!isset($_GET["id"])) {
    echo "Produto não informado.";
    exit;
}

$id = $_GET["id"];


$sql = "SELECT * FROM produtos WHERE id = '$id'";

$resultado = mysqli_query($conn, $sql);



if (mysqli_num_rows($resultado) == 0) {
    echo "Produto não encontrado.";
    exit;
}

$produto = mysqli_fetch_assoc($resultado);


$sqlPersonalizacoes = "
    SELECT *
    FROM personalizacoes
    ORDER BY nome
";

$resultadoPersonalizacoes =
    mysqli_query($conn, $sqlPersonalizacoes);

?>


<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?php echo htmlspecialchars($produto["nome"]); ?>
        - Atelier le Fleur
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

    <section class="produto-detalhes">

        <div class="produto-imagem">

            <img
                src="../<?php echo htmlspecialchars($produto["imagem"]); ?>"
                alt="<?php echo htmlspecialchars($produto["nome"]); ?>"
            >

        </div>


        <div class="produto-info">

            <h2>
                <?php echo htmlspecialchars($produto["nome"]); ?>
            </h2>


            <p>
                <strong>Tamanho:</strong>

                <?php echo htmlspecialchars($produto["tamanho"]); ?>
            </p>


            <p>
                <strong>Quantidade de rosas:</strong>

                <?php echo $produto["qtd_rosas"]; ?>
            </p>


            <p>
                <strong>Cor principal:</strong>

                <?php echo htmlspecialchars($produto["cor_principal"]); ?>
            </p>


            <?php if (!empty($produto["cor_secundaria"])): ?>

                <p>
                    <strong>Cor secundária:</strong>

                    <?php echo htmlspecialchars($produto["cor_secundaria"]); ?>
                </p>

            <?php endif; ?>


            <p>
                <strong>Glitter:</strong>

                <?php

                if ($produto["tem_glitter"]) {
                    echo "Sim";
                } else {
                    echo "Não";
                }

                ?>

            </p>


            <p class="preco">

                R$

                <?php

                echo number_format(
                    $produto["preco"],
                    2,
                    ",",
                    "."
                );

                ?>

            </p>


            <?php if (isset($_SESSION["id"]) && $_SESSION["tipo"] != "admin"): ?>

                <!-- ADICIONAR AO CARRINHO -->

                <form
                    action="adicionarcarrinho.php"
                    method="POST"
                >

                    <input
                        type="hidden"
                        name="produto_id"
                        value="<?php echo $produto["id"]; ?>"
                    >


                    <label for="quantidade">
                        Quantidade:
                    </label>


                    <input
                        type="number"
                        id="quantidade"
                        name="quantidade"
                        value="1"
                        min="1"
                    >


                    <button
                        type="submit"
                        class="btn-produto"
                    >
                        Adicionar ao carrinho
                    </button>

                </form>


                <form action="adicionarcarrinho.php" method="POST"> <input type="hidden" name="produto_id" value="<?php echo $produto["id"]; ?>" > 
                <label for="quantidade"> Quantidade: </label> 
                <input type="number" name="quantidade" id="quantidade" value="1" min="1" > <h3>Personalize seu buquê</h3> 
                <?php if (mysqli_num_rows($resultadoPersonalizacoes) > 0) { while ($personalizacao = mysqli_fetch_assoc($resultadoPersonalizacoes)) { ?> <label> <input type="checkbox" name="personalizacoes[]" value="<?php echo $personalizacao["id"]; ?>" >
                 <?php echo htmlspecialchars( $personalizacao["nome"] ); ?> + R$ <?php echo number_format( $personalizacao["preco_adicional"], 2, ",", "." ); ?> </label> <br> <?php } } ?> <br> 
                <button type="submit" class="btn-produto" > Adicionar ao carrinho </button> 
            </form>


            <?php elseif (!isset($_SESSION["id"])): ?>

                <p>
                    Faça login para adicionar produtos ao carrinho.
                </p>

                <a
                    href="../login.html"
                    class="btn-produto"
                >
                    Entrar
                </a>

            <?php endif; ?>


            <br>


            <a
                href="produtos.php"
                class="btn-produto"
            >
                Voltar para os buquês
            </a>

        </div>

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
