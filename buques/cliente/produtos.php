<?php

session_start();

require("../conexão.php");

// Busca todos os produtos cadastrados
$sql = "SELECT * FROM produtos ORDER BY id DESC";

$resultado = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Buquês - Atelier le Fleur</title>

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

    <section class="catalogo">

        <h2>Nossos Buquês</h2>

        <p>
            Escolha o buquê perfeito para cada momento.
        </p>


        <div class="lista-produtos">

            <?php

            if (mysqli_num_rows($resultado) > 0) {

                while ($produto = mysqli_fetch_assoc($resultado)) {

            ?>

                    <div class="card-produto">

                        <!-- Imagem do produto -->

                        <img
                            src="../<?php echo htmlspecialchars($produto["imagem"]); ?>"
                            alt="<?php echo htmlspecialchars($produto["nome"]); ?>"
                        >


                        <div class="info-produto">

                            <!-- Nome -->

                            <h3>
                                <?php
                                echo htmlspecialchars($produto["nome"]);
                                ?>
                            </h3>


                            <!-- Tamanho -->

                            <p>
                                <strong>Tamanho:</strong>

                                <?php
                                echo htmlspecialchars($produto["tamanho"]);
                                ?>
                            </p>


                            <!-- Quantidade de rosas -->

                            <p>
                                <strong>Rosas:</strong>

                                <?php
                                echo $produto["qtd_rosas"];
                                ?>
                            </p>


                            <!-- Cor principal -->

                            <p>
                                <strong>Cor:</strong>

                                <?php
                                echo htmlspecialchars(
                                    $produto["cor_principal"]
                                );
                                ?>
                            </p>


                            <!-- Cor secundária -->

                            <?php if (!empty($produto["cor_secundaria"])): ?>

                                <p>
                                    <strong>Cor secundária:</strong>

                                    <?php
                                    echo htmlspecialchars(
                                        $produto["cor_secundaria"]
                                    );
                                    ?>
                                </p>

                            <?php endif; ?>


                            <!-- Glitter -->

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


                            <!-- Preço -->

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

                            <form action="adicionarcarrinho.php" method="POST"> 
                                <input type="hidden" name="produto_id" value="<?php echo $produto["id"]; ?>" > 
                                <label for="quantidade"> Quantidade: </label> <input type="number" name="quantidade" id="quantidade" value="1" min="1" > 
                                <button type="submit" class="btn-produto"> Adicionar ao carrinho </button> 
                            </form>






                            <!-- Ver produto -->

                            <a
                                href="produto.php?id=<?php echo $produto["id"]; ?>"
                                class="btn-produto"
                            >
                                Ver produto
                            </a>

                        </div>

                    </div>

            <?php

                }

            } else {

            ?>

                <p>
                    Nenhum buquê cadastrado ainda.
                </p>

            <?php

            }

            ?>

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
