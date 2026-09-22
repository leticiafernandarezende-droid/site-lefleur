<?php

session_start();

if (!isset($_SESSION["id"])) {
    header("Location: ../login.html");
    exit;
}

require("../conexão.php");

$usuario_id = $_SESSION["id"];


if (!isset($_POST["confirmar"])) {
    header("Location: finalizar_compra.php");
    exit;
}


if (
    !isset($_POST["endereco_id"]) ||
    !isset($_POST["forma_pagamento"])
) {
    echo "Endereço ou forma de pagamento não informado.";
    exit;
}

$endereco_id = (int) $_POST["endereco_id"];

$forma_pagamento = $_POST["forma_pagamento"];


$formasPermitidas = [
    "Pix",
    "Cartão de crédito",
    "Cartão de débito",
    "Dinheiro"
];

if (!in_array($forma_pagamento, $formasPermitidas)) {
    echo "Forma de pagamento inválida.";
    exit;
}


$sqlEndereco = "
    SELECT *
    FROM enderecos
    WHERE id = '$endereco_id'
    AND usuario_id = '$usuario_id'
    LIMIT 1
";

$resultadoEndereco = mysqli_query(
    $conn,
    $sqlEndereco
);

if (
    !$resultadoEndereco ||
    mysqli_num_rows($resultadoEndereco) == 0
) {
    echo "Endereço não encontrado.";
    exit;
}

$endereco = mysqli_fetch_assoc(
    $resultadoEndereco
);


$sqlCarrinho = "
    SELECT id
    FROM carrinhos
    WHERE usuario_id = '$usuario_id'
    AND status = 'aberto'
    LIMIT 1
";

$resultadoCarrinho = mysqli_query(
    $conn,
    $sqlCarrinho
);

if (
    !$resultadoCarrinho ||
    mysqli_num_rows($resultadoCarrinho) == 0
) {
    echo "Carrinho não encontrado.";
    exit;
}

$carrinho = mysqli_fetch_assoc(
    $resultadoCarrinho
);

$carrinho_id = $carrinho["id"];


$sqlItens = "
    SELECT
        ic.id AS item_id,
        ic.quantidade,
        p.id AS produto_id,
        p.preco
    FROM itens_carrinho ic

    INNER JOIN produtos p
        ON ic.produto_id = p.id

    WHERE ic.carrinho_id = '$carrinho_id'
";

$resultadoItens = mysqli_query(
    $conn,
    $sqlItens
);

if (
    !$resultadoItens ||
    mysqli_num_rows($resultadoItens) == 0
) {
    echo "O carrinho está vazio.";
    exit;
}


mysqli_begin_transaction($conn);

try {

    $itens = [];

    $total = 0;


    while ($item = mysqli_fetch_assoc($resultadoItens)) {

        $totalPersonalizacoes = 0;

        $sqlPersonalizacoes = "
            SELECT
                p.id,
                p.nome,
                p.preco_adicional
            FROM item_personalizacao ip

            INNER JOIN personalizacoes p
                ON ip.personalizacao_id = p.id

            WHERE ip.item_carrinho_id = '{$item["item_id"]}'
        ";

        $resultadoPersonalizacoes = mysqli_query(
            $conn,
            $sqlPersonalizacoes
        );


        $personalizacoes = [];


        if ($resultadoPersonalizacoes) {

            while (
                $personalizacao =
                mysqli_fetch_assoc(
                    $resultadoPersonalizacoes
                )
            ) {

                $personalizacoes[] =
                    $personalizacao;

                $totalPersonalizacoes +=
                    (float) $personalizacao["preco_adicional"];
            }
        }


        $precoUnitario =
            (float) $item["preco"] +
            $totalPersonalizacoes;


        $subtotal =
            $precoUnitario *
            (int) $item["quantidade"];


        $total += $subtotal;


        $item["personalizacoes"] =
            $personalizacoes;

        $item["preco_unitario"] =
            $precoUnitario;

        $item["subtotal"] =
            $subtotal;


        $itens[] = $item;
    }

    $nome_destinatario =
        $endereco["nome_destinatario"];

    $cep =
        $endereco["cep"];

    $rua =
        $endereco["rua"];

    $numero =
        $endereco["numero"];

    $complemento =
        $endereco["complemento"];

    $bairro =
        $endereco["bairro"];

    $cidade =
        $endereco["cidade"];

    $estado =
        $endereco["estado"];


    $sqlPedido = "
        INSERT INTO pedidos
        (
            usuario_id,
            total,
            status,
            nome_destinatario,
            cep,
            rua,
            numero,
            complemento,
            bairro,
            cidade,
            estado,
            forma_pagamento
        )
        VALUES
        (
            '$usuario_id',
            '$total',
            'Recebido',
            '$nome_destinatario',
            '$cep',
            '$rua',
            '$numero',
            '$complemento',
            '$bairro',
            '$cidade',
            '$estado',
            '$forma_pagamento'
        )
    ";


    $resultadoPedido = mysqli_query(
        $conn,
        $sqlPedido
    );


    if (!$resultadoPedido) {
        throw new Exception(
            "Erro ao criar pedido: " .
            mysqli_error($conn)
        );
    }


    $pedido_id =
        mysqli_insert_id($conn);



    foreach ($itens as $item) {

        $produto_id =
            $item["produto_id"];

        $quantidade =
            $item["quantidade"];

        $preco_unitario =
            $item["preco_unitario"];

        $subtotal =
            $item["subtotal"];


        $sqlItem = "
            INSERT INTO itens_pedido
            (
                pedido_id,
                produto_id,
                quantidade,
                preco_unitario,
                subtotal
            )
            VALUES
            (
                '$pedido_id',
                '$produto_id',
                '$quantidade',
                '$preco_unitario',
                '$subtotal'
            )
        ";


        $resultadoItem = mysqli_query(
            $conn,
            $sqlItem
        );


        if (!$resultadoItem) {
            throw new Exception(
                "Erro ao inserir item do pedido: " .
                mysqli_error($conn)
            );
        }


        $item_pedido_id =
            mysqli_insert_id($conn);


      
        foreach (
            $item["personalizacoes"]
            as $personalizacao
        ) {

            $nome =
                $personalizacao["nome"];

            $preco_adicional =
                $personalizacao["preco_adicional"];


            $sqlPersonalizacao = "
                INSERT INTO item_pedido_personalizacao
                (
                    item_pedido_id,
                    nome,
                    preco_adicional
                )
                VALUES
                (
                    '$item_pedido_id',
                    '$nome',
                    '$preco_adicional'
                )
            ";


            $resultadoPersonalizacao =
                mysqli_query(
                    $conn,
                    $sqlPersonalizacao
                );


            if (!$resultadoPersonalizacao) {
                throw new Exception(
                    "Erro ao salvar personalização: " .
                    mysqli_error($conn)
                );
            }
        }
    }

    $sqlFinalizarCarrinho = "
        UPDATE carrinhos
        SET status = 'finalizado'
        WHERE id = '$carrinho_id'
    ";


    $resultadoFinalizarCarrinho =
        mysqli_query(
            $conn,
            $sqlFinalizarCarrinho
        );


    if (!$resultadoFinalizarCarrinho) {
        throw new Exception(
            "Erro ao finalizar carrinho: " .
            mysqli_error($conn)
        );
    }

    mysqli_commit($conn);


    header(
        "Location: pedidoconfirmado.php?id=" .
        $pedido_id
    );

    exit;


} catch (Exception $e) {

    mysqli_rollback($conn);

    echo "Erro ao processar pedido: " .
         $e->getMessage();
}

?>