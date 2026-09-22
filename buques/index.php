<?php

session_start();

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Início - Atelier le Fleur</title>

    <link rel="stylesheet" href="style.css">

</head>


<body>

<header class="header">

    <div class="logo">
        Atelier le Fleur
    </div>

    <nav>

        <a href="index.php">
            Início
        </a>

        <a href="cliente/produtos.php">
            Buquês
        </a>


        <?php if (isset($_SESSION["id"])): ?>


            <?php if ($_SESSION["tipo"] == "admin"): ?>

                <span>
                    Olá,
                    <?php echo htmlspecialchars($_SESSION["nome"]); ?>!
                </span>

                <a href="admin/produtosadmin.php">
                    Produtos
                </a>

                <a href="admin/pedidosadmin.php">
                    Pedidos
                </a>

                <a href="logout.php">
                    Sair
                </a>


            <?php else: ?>

                <span>
                    Olá,
                    <?php echo htmlspecialchars($_SESSION["nome"]); ?>!
                </span>

                <a href="cliente/editar.php">
                    Minha conta
                </a>

                <a href="cliente/enderecos.php">
                    Meus endereços
                </a>

                <a href="cliente/pedidos.php">
                    Meus pedidos
                </a>

                <a href="cliente/carrinho.php">
                    Carrinho
                </a>

                <a href="logout.php">
                    Sair
                </a>

            <?php endif; ?>


        <?php else: ?>

            <a href="login.html">
                Entrar
            </a>

            <a href="cadastro.html">
                Criar conta
            </a>

        <?php endif; ?>

    </nav>

</header>

<main>


<section class="home-hero">


    <div class="home-hero-content">


        <span class="home-subtitle">
            ATELIER LE FLEUR
        </span>


        <h1>

            Flores que transformam

            <span>
                momentos em memórias.
            </span>

        </h1>


        <p>

            Encontre o buquê perfeito para cada
            ocasião e torne seus momentos ainda
            mais especiais.

        </p>


        <div class="home-hero-buttons">

            <a
                href="cliente/produtos.php"
                class="btn-produto"
            >
                Conhecer nossos buquês
            </a>


            <?php if (!isset($_SESSION["id"])): ?>

                <a
                    href="cadastro.html"
                    class="btn-secundario"
                >
                    Criar minha conta
                </a>

            <?php else: ?>

                <a
                    href="cliente/pedidos.php"
                    class="btn-secundario"
                >
                    Meus pedidos
                </a>

            <?php endif; ?>

        </div>


    </div>


    <div class="home-hero-decoration">

        <div class="flower-circle">

            <span>✿</span>

        </div>

    </div>


</section>

<section class="sobre-atelier">


    <div class="sobre-conteudo">


        <span class="home-subtitle">
            SOBRE O ATELIER
        </span>


        <h2>
            Um espaço feito para
            celebrar momentos.
        </h2>


        <p>

            No Atelier le Fleur, acreditamos que
            flores são mais do que presentes.
            Elas representam carinho, celebração,
            gratidão e sentimentos que merecem
            ser lembrados.

        </p>


        <p>

            Nossa plataforma foi criada para tornar
            a experiência de escolher e comprar
            seu buquê simples, organizada e especial.

        </p>


        <a
            href="cliente/produtos.php"
            class="btn-produto"
        >
            Ver buquês
        </a>


    </div>


    <div class="sobre-destaque">


        <div class="sobre-card">

            <span>
                ✿
            </span>

            <h3>
                Feito com carinho
            </h3>

            <p>
                Cada composição é pensada
                para tornar seu momento especial.
            </p>

        </div>


    </div>


</section>

<section class="features">


    <span class="home-subtitle">
        NOSSA EXPERIÊNCIA
    </span>


    <h2>
        Tudo para facilitar sua experiência
    </h2>


    <p class="features-description">

        Escolha seu buquê, personalize sua compra
        e acompanhe seus pedidos de forma simples.

    </p>


    <div class="feature-container">



        <div class="feature-card">

            <div class="feature-icon">
                ✿
            </div>

            <h3>
                Buquês
            </h3>

            <p>

                Explore nossa coleção de buquês
                e encontre uma composição para
                cada ocasião.

            </p>

            <a href="cliente/produtos.php">
                Ver buquês →
            </a>

        </div>


        <div class="feature-card">

            <div class="feature-icon">
                ♡
            </div>

            <h3>
                Personalização
            </h3>

            <p>

                Escolha opções especiais para
                deixar seu pedido ainda mais
                personalizado.

            </p>

            <a href="cliente/produtos.php">
                Personalizar →
            </a>

        </div>

        <div class="feature-card">

            <div class="feature-icon">
                ✓
            </div>

            <h3>
                Seus pedidos
            </h3>

            <p>

                Acompanhe seus pedidos e consulte
                todos os detalhes das suas compras.

            </p>


            <?php if (isset($_SESSION["id"])): ?>

                <a href="cliente/pedidos.php">
                    Meus pedidos →
                </a>

            <?php else: ?>

                <a href="login.html">
                    Entrar →
                </a>

            <?php endif; ?>

        </div>


    </div>


</section>

<section class="home-cta">


    <div>

        <span class="home-subtitle">
            SEU MOMENTO MERECE FLORES
        </span>


        <h2>
            Encontre o buquê perfeito
        </h2>


        <p>

            Descubra nossa coleção e escolha
            uma composição para tornar seu
            momento ainda mais especial.

        </p>


        <a
            href="cliente/produtos.php"
            class="btn-produto"
        >
            Explorar buquês
        </a>

    </div>


</section>


</main>

<footer>

    <p>
        Atelier le Fleur — Projeto acadêmico
    </p>

</footer>


</body>

</html>