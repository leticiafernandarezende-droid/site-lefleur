<?php

session_start();

if (!isset($_SESSION["id"])) {
    header("Location: login.html");
    exit;
}

require("../conexão.php");

$id = $_SESSION["id"];

$sql = "SELECT * FROM usuarios WHERE id = '$id'";

$resultado = mysqli_query($conn, $sql);

if (mysqli_num_rows($resultado) == 0) {
    echo "Usuário não encontrado.";
    exit;
}

$usuario = mysqli_fetch_assoc($resultado);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar Conta - Atelier le Fleur</title>

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

    <h1>Editar minha conta</h1>

    <form action="salvar.php" method="POST">

        <label>Nome:</label>
        <input
            type="text"
            name="nome"
            value="<?php echo htmlspecialchars($usuario['nome']); ?>"
            required
        >

        <br><br>

        <label>E-mail:</label>
        <input
            type="email"
            name="email"
            value="<?php echo htmlspecialchars($usuario['email']); ?>"
            required
        >

        <br><br>

        <label>Telefone:</label>
        <input
            type="text"
            name="telefone"
            value="<?php echo htmlspecialchars($usuario['telefone']); ?>"
            required
        >

        <br><br>

        <label>Nova senha:</label>
        <input
            type="password"
            name="senha"
            placeholder="Digite uma nova senha"
        >

        <br><br>

        <button type="submit">
            Salvar alterações
        </button>

    </form>

    <form action="deletar.php" method="POST"
        onsubmit="return confirm('Tem certeza que deseja excluir sua conta? Essa ação não pode ser desfeita.');">

        <button type="submit">
        Excluir minha conta
        </button>

        </form>

    <br>

    <a href="../index.php">Voltar</a>

</body>

</html>