```php
<?php

session_start();

if (!isset($_SESSION["id"])) {
    header("Location: ../login.html");
    exit;
}

if ($_SESSION["tipo"] == "admin") {
    echo "Administradores não podem adicionar produtos ao carrinho.";
    exit;
}

require("../conexão.php");

if (!isset($_POST["produto_id"])) {
    echo "Produto não informado.";
    exit;
}

$produto_id = (int) $_POST["produto_id"];

$quantidade = isset($_POST["quantidade"])
    ? (int) $_POST["quantidade"]
    : 1;

if ($quantidade < 1) {
    $quantidade = 1;
}

$usuario_id = (int) $_SESSION["id"];


/*
|--------------------------------------------------------------------------
| 1. Verificar se o produto existe
|--------------------------------------------------------------------------
*/

$sqlProduto = "SELECT id FROM produtos WHERE id = '$produto_id'";

$resultadoProduto = mysqli_query($conn, $sqlProduto);

if (!$resultadoProduto || mysqli_num_rows($resultadoProduto) == 0) {
    echo "Produto não encontrado.";
    exit;
}


/*
|--------------------------------------------------------------------------
| 2. Recuperar personalizações selecionadas
|--------------------------------------------------------------------------
*/

$personalizacoes = [];

if (isset($_POST["personalizacoes"]) && is_array($_POST["personalizacoes"])) {

    foreach ($_POST["personalizacoes"] as $idPersonalizacao) {

        $idPersonalizacao = (int) $idPersonalizacao;

        if ($idPersonalizacao > 0) {
            $personalizacoes[] = $idPersonalizacao;
        }
    }
}


/*
|--------------------------------------------------------------------------
| 3. Ordenar IDs
|--------------------------------------------------------------------------
|
| Isso permite comparar corretamente duas combinações.
|
*/

sort($personalizacoes);


/*
|--------------------------------------------------------------------------
| 4. Procurar carrinho aberto do usuário
|--------------------------------------------------------------------------
*/

$sqlCarrinho = "
    SELECT id
    FROM carrinhos
    WHERE usuario_id = '$usuario_id'
    AND status = 'aberto'
    LIMIT 1
";

$resultadoCarrinho = mysqli_query($conn, $sqlCarrinho);

if (!$resultadoCarrinho) {
    die("Erro ao buscar carrinho: " . mysqli_error($conn));
}


if (mysqli_num_rows($resultadoCarrinho) > 0) {

    $carrinho = mysqli_fetch_assoc($resultadoCarrinho);

    $carrinho_id = (int) $carrinho["id"];

} else {

    /*
    |--------------------------------------------------------------------------
    | 5. Criar novo carrinho
    |--------------------------------------------------------------------------
    */

    $sqlCriarCarrinho = "
        INSERT INTO carrinhos (usuario_id, status)
        VALUES ('$usuario_id', 'aberto')
    ";

    if (!mysqli_query($conn, $sqlCriarCarrinho)) {
        die("Erro ao criar carrinho: " . mysqli_error($conn));
    }

    $carrinho_id = mysqli_insert_id($conn);
}


/*
|--------------------------------------------------------------------------
| 6. Procurar itens do mesmo produto
|--------------------------------------------------------------------------
|
| Não podemos simplesmente procurar pelo produto.
| Precisamos verificar também as personalizações.
|
*/

$sqlItens = "
    SELECT id
    FROM itens_carrinho
    WHERE carrinho_id = '$carrinho_id'
    AND produto_id = '$produto_id'
";

$resultadoItens = mysqli_query($conn, $sqlItens);

if (!$resultadoItens) {
    die("Erro ao buscar itens do carrinho: " . mysqli_error($conn));
}


$itemExistente = null;


/*
|--------------------------------------------------------------------------
| 7. Comparar as personalizações
|--------------------------------------------------------------------------
*/

while ($item = mysqli_fetch_assoc($resultadoItens)) {

    $item_id = (int) $item["id"];

    $sqlPersItem = "
        SELECT personalizacao_id
        FROM item_personalizacao
        WHERE item_carrinho_id = '$item_id'
        ORDER BY personalizacao_id
    ";

    $resultadoPersItem = mysqli_query($conn, $sqlPersItem);

    if (!$resultadoPersItem) {
        die("Erro ao verificar personalizações: " . mysqli_error($conn));
    }

    $persExistentes = [];

    while ($pers = mysqli_fetch_assoc($resultadoPersItem)) {
        $persExistentes[] = (int) $pers["personalizacao_id"];
    }

    sort($persExistentes);


    /*
    |--------------------------------------------------------------------------
    | Comparar arrays
    |--------------------------------------------------------------------------
    */

    if ($persExistentes == $personalizacoes) {

        $itemExistente = $item_id;
        break;
    }
}


/*
|--------------------------------------------------------------------------
| 8. Se encontrou exatamente o mesmo produto + personalização
|--------------------------------------------------------------------------
*/

if ($itemExistente !== null) {

    $sqlAtualizar = "
        UPDATE itens_carrinho
        SET quantidade = quantidade + '$quantidade'
        WHERE id = '$itemExistente'
    ";

    if (!mysqli_query($conn, $sqlAtualizar)) {
        die("Erro ao atualizar quantidade: " . mysqli_error($conn));
    }

} else {

    /*
    |--------------------------------------------------------------------------
    | 9. Criar novo item
    |--------------------------------------------------------------------------
    */

    $sqlNovoItem = "
        INSERT INTO itens_carrinho
        (carrinho_id, produto_id, quantidade)
        VALUES
        ('$carrinho_id', '$produto_id', '$quantidade')
    ";

    if (!mysqli_query($conn, $sqlNovoItem)) {
        die("Erro ao adicionar produto ao carrinho: " . mysqli_error($conn));
    }

    $item_id = mysqli_insert_id($conn);


    foreach ($personalizacoes as $idPersonalizacao) {

        $sqlPersonalizacao = "
            INSERT INTO item_personalizacao
            (item_carrinho_id, personalizacao_id)
            VALUES
            ('$item_id', '$idPersonalizacao')
        ";

        if (!mysqli_query($conn, $sqlPersonalizacao)) {
            die("Erro ao salvar personalização: " . mysqli_error($conn));
        }
    }
}


/*
|--------------------------------------------------------------------------
| 11. Voltar para o carrinho
|--------------------------------------------------------------------------
*/

header("Location: carrinho.php");
exit;

?>
```
