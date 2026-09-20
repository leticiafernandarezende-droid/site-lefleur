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

$sql = "SELECT * FROM produtos ORDER BY id DESC";
$resultado = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Gerenciar Buquês - Atelier le Fleur</title>

    <link rel="stylesheet" href="../style.css">
</head>

<body>

<header>

    <h1>Atelier le Fleur - Administração</h1>

    <nav>
        <a href="../index.php">Início</a>

        <span>
            Olá, <?php echo htmlspecialchars($_SESSION["nome"]); ?>!
        </span>

        <a href="../logout.php">Sair</a>
    </nav>

</header>


<main>

    <section class="catalogo">

        <h2>Gerenciar Buquês</h2>

        <p>
            Aqui você pode cadastrar, editar e excluir os buquês da loja.
        </p>

        <br>
        <a href="cadastrobuque.php" class="btn-produto">
            + Cadastrar novo buquê
        </a>

        <br><br>


        <div class="lista-produtos">

            <?php

            if (mysqli_num_rows($resultado) > 0) {

                while ($produto = mysqli_fetch_assoc($resultado)) {

            ?>

                    <div class="card-produto">

                        <!-- Imagem -->
                        <img
                            src="../<?php echo htmlspecialchars($produto['imagem']); ?>"
                            alt="<?php echo htmlspecialchars($produto['nome']); ?>"
                        >


                        <div class="info-produto">

                            <!-- Nome -->
                            <h3>
                                <?php echo htmlspecialchars($produto['nome']); ?>
                            </h3>


                            <!-- Tamanho -->
                            <p>
                                Tamanho:
                                <?php echo htmlspecialchars($produto['tamanho']); ?>
                            </p>


                            <!-- Rosas -->
                            <p>
                                <?php echo $produto['qtd_rosas']; ?> rosas
                            </p>


                            <!-- Cor principal -->
                            <p>
                                Cor:
                                <?php echo htmlspecialchars($produto['cor_principal']); ?>
                            </p>


                            <!-- Preço -->
                            <p class="preco">

                                R$

                                <?php
                                echo number_format(
                                    $produto['preco'],
                                    2,
                                    ',',
                                    '.'
                                );
                                ?>

                            </p>


                            <!-- Botão editar -->
                            <a
                                href="cadastrobuque.php?id=<?php echo $produto['id']; ?>"
                                class="btn-produto"
                            >
                                Editar
                            </a>


                            <!-- Botão excluir -->
                            <form
                                action="excluirbuque.php"
                                method="POST"
                                style="display:inline;"
                                onsubmit="return confirm('Tem certeza que deseja excluir este buquê?');"
                            >

                                <input
                                    type="hidden"
                                    name="id"
                                    value="<?php echo $produto['id']; ?>"
                                >

                                <button
                                    type="submit"
                                    class="btn-produto"
                                >
                                    Excluir
                                </button>

                            </form>

                        </div>

                    </div>

            <?php

                }

            } else {

                echo "<p>Nenhum buquê cadastrado ainda.</p>";

            }

            ?>

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
