<?php

session_start();

if (!isset($_SESSION["id"])) {
    header("Location: ../login.html");
    exit;
}

require("../conexão.php");

// Verifica se estamos editando
$editando = false;
$produto = null;

if (isset($_GET["id"])) {

    $id = $_GET["id"];

    $sql = "SELECT * FROM produtos WHERE id = '$id'";

    $resultado = mysqli_query($conn, $sql);

    if (mysqli_num_rows($resultado) > 0) {

        $produto = mysqli_fetch_assoc($resultado);

        $editando = true;

    } else {

        echo "Produto não encontrado.";
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?php echo $editando ? "Editar Buquê" : "Cadastrar Buquê"; ?>
        - Atelier le Fleur
    </title>

    <link rel="stylesheet" href="../style.css">

</head>

<body>

    <main>

        <h1>
            <?php echo $editando ? "Editar Buquê" : "Cadastrar Novo Buquê"; ?>
        </h1>


        <form action="salvarbuque.php" method="POST">


            <?php if ($editando): ?>

                <input
                    type="hidden"
                    name="id"
                    value="<?php echo $produto["id"]; ?>"
                >

            <?php endif; ?>


            <label for="nome">
                Nome do buquê:
            </label>

            <input
                type="text"
                id="nome"
                name="nome"
                value="<?php echo $editando ? htmlspecialchars($produto["nome"]) : ""; ?>"
                required
            >

            <br><br>

            <label for="tamanho">
                Tamanho:
            </label>

            <select
                id="tamanho"
                name="tamanho"
                required
            >

                <option value="">
                    Selecione
                </option>

                <option
                    value="P"
                    <?php
                    if ($editando && $produto["tamanho"] == "P") {
                        echo "selected";
                    }
                    ?>
                >
                    P
                </option>

                <option
                    value="M"
                    <?php
                    if ($editando && $produto["tamanho"] == "M") {
                        echo "selected";
                    }
                    ?>
                >
                    M
                </option>

                <option
                    value="G"
                    <?php
                    if ($editando && $produto["tamanho"] == "G") {
                        echo "selected";
                    }
                    ?>
                >
                    G
                </option>

            </select>

            <br><br>


            <label for="qtd_rosas">
                Quantidade de rosas:
            </label>

            <input
                type="number"
                id="qtd_rosas"
                name="qtd_rosas"
                min="1"
                value="<?php echo $editando ? $produto["qtd_rosas"] : ""; ?>"
                required
            >

            <br><br>


            <label for="cor_principal">
                Cor principal:
            </label>

            <input
                type="text"
                id="cor_principal"
                name="cor_principal"
                value="<?php echo $editando ? htmlspecialchars($produto["cor_principal"]) : ""; ?>"
                required
            >

            <br><br>


            <label for="cor_secundaria">
                Cor secundária:
            </label>

            <input
                type="text"
                id="cor_secundaria"
                name="cor_secundaria"
                value="<?php echo $editando ? htmlspecialchars($produto["cor_secundaria"]) : ""; ?>"
            >

            <br><br>



            <label for="tem_glitter">
                Possui glitter?
            </label>

            <select
                id="tem_glitter"
                name="tem_glitter"
                required
            >

                <option
                    value="1"
                    <?php
                    if ($editando && $produto["tem_glitter"] == 1) {
                        echo "selected";
                    }
                    ?>
                >
                    Sim
                </option>

                <option
                    value="0"
                    <?php
                    if ($editando && $produto["tem_glitter"] == 0) {
                        echo "selected";
                    }
                    ?>
                >
                    Não
                </option>

            </select>

            <br><br>


            <label for="imagem">
                Caminho da imagem:
            </label>

            <input
                type="text"
                id="imagem"
                name="imagem"
                placeholder="Ex: img/buque-vermelho.jpg"
                value="<?php echo $editando ? htmlspecialchars($produto["imagem"]) : ""; ?>"
                required
            >

            <br><br>


            <!-- PREÇO -->

            <label for="preco">
                Preço:
            </label>

            <input
                type="number"
                id="preco"
                name="preco"
                step="0.01"
                min="0"
                value="<?php echo $editando ? $produto["preco"] : ""; ?>"
                required
            >

            <br><br>

            <button type="submit">

                <?php
                echo $editando
                    ? "Salvar alterações"
                    : "Cadastrar buquê";
                ?>

            </button>

        </form>

                <form action="excluirbuque.php" method="POST" style="display: inline;" 
                onsubmit="return confirm('Deseja realmente excluir este buquê?');" > 
                <input type="hidden" name="id" value="<?php echo $produto["id"]; ?>" > 
                <button type="submit"> Excluir </button> </form>


        <br>

        <a href="produtosadmin.php">
            Voltar para produtos
        </a>

    </main>

</body>

</html>
```
