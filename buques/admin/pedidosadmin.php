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



$sql = "
    SELECT
        p.id,
        p.total,
        p.status,
        p.data_pedido,
        u.nome AS cliente,
        u.email
    FROM pedidos p
    INNER JOIN usuarios u
        ON p.usuario_id = u.id
    ORDER BY p.data_pedido DESC
";

$resultado = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Pedidos - Administração</title>

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

    <section class="admin-pedidos">

        <h2>
            Pedidos dos clientes
        </h2>

        <p>
            Consulte os pedidos realizados pelos clientes.
        </p>


        <?php if ($resultado && mysqli_num_rows($resultado) > 0): ?>

            <div class="tabela-pedidos">

                <table>

                    <thead>

                        <tr>

                            <th>
                                Pedido
                            </th>

                            <th>
                                Cliente
                            </th>

                            <th>
                                Data
                            </th>

                            <th>
                                Total
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Ação
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php while ($pedido = mysqli_fetch_assoc($resultado)): ?>

                            <tr>

                                <td>

                                    <strong>
                                        #<?php echo $pedido["id"]; ?>
                                    </strong>

                                </td>


                                <td>

                                    <strong>
                                        <?php
                                        echo htmlspecialchars(
                                            $pedido["cliente"]
                                        );
                                        ?>
                                    </strong>

                                    <br>

                                    <small>

                                        <?php
                                        echo htmlspecialchars(
                                            $pedido["email"]
                                        );
                                        ?>

                                    </small>

                                </td>


                                <td>

                                    <?php
                                    echo date(
                                        "d/m/Y H:i",
                                        strtotime(
                                            $pedido["data_pedido"]
                                        )
                                    );
                                    ?>

                                </td>


                                <td>

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

                                </td>


                                <td>

                                    <span class="status-admin">

                                        <?php
                                        echo htmlspecialchars(
                                            $pedido["status"]
                                        );
                                        ?>

                                    </span>

                                </td>


                                <td>

                                    <a
                                        href="pedidoadmin.php?id=<?php echo $pedido["id"]; ?>"
                                        class="btn-produto"
                                    >
                                        Ver pedido
                                    </a>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    </tbody>

                </table>

            </div>

        <?php else: ?>

            <div class="carrinho-vazio">

                <h3>
                    Nenhum pedido encontrado.
                </h3>

                <p>
                    Ainda não existem pedidos realizados pelos clientes.
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
```
