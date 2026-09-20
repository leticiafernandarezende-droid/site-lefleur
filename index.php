<?php

    session_start();
?>
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Atelier le Fleur</title>
    <link rel="stylesheet" href="style.css">
    </head>

    <body>

    <header class="header">
    <div class="logo">Atelier le Fleur</div>
    
  <nav> <a href="index.php">Início</a> <a href="cliente/produtos.php">Buquês</a> <?php if (isset($_SESSION["id"])): ?> 
   <?php if ($_SESSION["tipo"] == "admin"): ?><span> Olá, <?php echo htmlspecialchars($_SESSION["nome"]); ?>! </span> <a href="admin/produtosadmin.php"> Administração </a> <a href="logout.php"> Sair </a> <?php else: ?> 
  <span> Olá, <?php echo htmlspecialchars($_SESSION["nome"]); ?>! </span> <a href="cliente/editar.php"> Minha conta </a> <a href="cliente/carrinho.php"> Carrinho </a> <a href="logout.php"> Sair </a> <?php endif; ?> <?php else: ?> 
     <a href="login.html"> Entrar </a> <a href="cadastro.html"> Criar conta </a> <?php endif; ?> </nav>
    </header>

    <main>

    <section class="hero">
        <div class="hero-content">
            <span class="subtitle">BEM-VINDO À Atelier le Fleur</span>

            <h1>
                Flores que transformam
                <span>momentos em memórias.</span>
            </h1>

            <p>
                Gerencie flores, buquês, plantas e arranjos
                de forma simples e organizada.
            </p>

        </div>

        <div class="hero-flower"></div>
    </section>

    <section class="features">

        <h2>Por que usar nosso sistema?</h2>
        <p>Nosso sistema facilita o gerenciamento da floricultura, organizando produtos, preços e estoque de forma rápida e prática.</p>

        <div class="feature-container">

            <div class="feature-card">
                <div class="feature-icon"></div>
                <h3>Produtos</h3>
                <p>
                    Cadastre e organize todas as flores,
                    plantas e arranjos da floricultura.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon"></div>
                <h3>Pesquisa</h3>
                <p>
                    Encontre rapidamente os produtos
                    utilizando nome ou categoria.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon"></div>
                <h3>Estoque</h3>
                <p>
                    Acompanhe a quantidade disponível
                    de cada produto.
                </p>
            </div>

        </div>

    </section>

    </main>

    <footer>
    <p>Atelier le Fleur — Projeto acadêmico</p>
    </footer>

    </body>
    </html>