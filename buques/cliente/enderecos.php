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
    FROM enderecos
    WHERE usuario_id = '$usuario_id'
    ORDER BY id DESC
";

$resultado = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Meus endereços - Atelier le Fleur</title>

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

    <section class="enderecos-container">

        <h2>Meus endereços</h2>

        <p class="descricao-enderecos">
            Cadastre os endereços onde deseja receber seus pedidos.
        </p>


        <div class="novo-endereco">

            <h3>Adicionar novo endereço</h3>

            <form action="adicionarendereco.php" method="POST">

                <div class="campo-endereco">

                    <label for="nome_destinatario">
                        Nome do destinatário
                    </label>

                    <input
                        type="text"
                        id="nome_destinatario"
                        name="nome_destinatario"
                        required
                    >

                </div>


                <div class="linha-endereco">

                    <div class="campo-endereco">

                        <label for="cep">
                            CEP
                        </label>

                        <input
                            type="text"
                            id="cep"
                            name="cep"
                            maxlength="10"
                            placeholder="00000-000"
                            required
                        >

                    </div>


                    <div class="campo-endereco">

                        <label for="estado">
                            Estado
                        </label>

                        <input
                            type="text"
                            id="estado"
                            name="estado"
                            maxlength="2"
                            placeholder="SP"
                            required
                        >

                    </div>

                </div>


                <div class="campo-endereco">

                    <label for="rua">
                        Rua
                    </label>

                    <input
                        type="text"
                        id="rua"
                        name="rua"
                        required
                    >

                </div>


                <div class="linha-endereco">

                    <div class="campo-endereco">

                        <label for="numero">
                            Número
                        </label>

                        <input
                            type="text"
                            id="numero"
                            name="numero"
                            required
                        >

                    </div>


                    <div class="campo-endereco">

                        <label for="complemento">
                            Complemento
                        </label>

                        <input
                            type="text"
                            id="complemento"
                            name="complemento"
                            placeholder="Apartamento, bloco..."
                        >

                    </div>

                </div>


                <div class="campo-endereco">

                    <label for="bairro">
                        Bairro
                    </label>

                    <input
                        type="text"
                        id="bairro"
                        name="bairro"
                        required
                    >

                </div>


                <div class="campo-endereco">

                    <label for="cidade">
                        Cidade
                    </label>

                    <input
                        type="text"
                        id="cidade"
                        name="cidade"
                        required
                    >

                </div>


                <button type="submit" class="btn-produto">
                    Adicionar endereço
                </button>

            </form>

        </div>


        <h3 class="titulo-enderecos">
            Endereços cadastrados
        </h3>


        <?php if ($resultado && mysqli_num_rows($resultado) > 0): ?>

            <div class="lista-enderecos">

                <?php while ($endereco = mysqli_fetch_assoc($resultado)): ?>

                    <div class="card-endereco">

                        <h3>
                            <?php echo htmlspecialchars($endereco["nome_destinatario"]); ?>
                        </h3>

                        <p>
                            <?php echo htmlspecialchars($endereco["rua"]); ?>,
                            <?php echo htmlspecialchars($endereco["numero"]); ?>
                        </p>

                        <?php if (!empty($endereco["complemento"])): ?>

                            <p>
                                <?php echo htmlspecialchars($endereco["complemento"]); ?>
                            </p>

                        <?php endif; ?>

                        <p>
                            <?php echo htmlspecialchars($endereco["bairro"]); ?>
                        </p>

                        <p>
                            <?php echo htmlspecialchars($endereco["cidade"]); ?>
                            -
                            <?php echo htmlspecialchars($endereco["estado"]); ?>
                        </p>

                        <p>
                            CEP:
                            <?php echo htmlspecialchars($endereco["cep"]); ?>
                        </p>


                        <form
                            action="excluir_endereco.php"
                            method="POST"
                            onsubmit="return confirm('Deseja realmente excluir este endereço?');"
                        >

                            <input
                                type="hidden"
                                name="id"
                                value="<?php echo $endereco["id"]; ?>"
                            >

                            <button
                                type="submit"
                                class="btn-cancelar"
                            >
                                Excluir endereço
                            </button>

                        </form>

                    </div>

                <?php endwhile; ?>

            </div>

        <?php else: ?>

            <div class="carrinho-vazio">

                <p>
                    Você ainda não possui nenhum endereço cadastrado.
                </p>

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