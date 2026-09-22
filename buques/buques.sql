create database buques;
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL UNIQUE,
    senha VARCHAR(25) NOT NULL, -- Armazena a senha criptografada (hash)
    tipo ENUM('cliente', 'admin') DEFAULT 'cliente', -- Define o nível de acesso
    telefone VARCHAR(20),
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS produtos ( id INT AUTO_INCREMENT PRIMARY KEY, nome VARCHAR(50) NOT NULL, tamanho VARCHAR(10) NOT NULL, qtd_rosas INT NOT NULL, cor_principal VARCHAR(20) NOT NULL, cor_secundaria VARCHAR(20), tem_glitter BOOLEAN DEFAULT TRUE, imagem VARCHAR(65) NOT NULL, preco DECIMAL(10,2) NOT NULL );
CREATE TABLE IF NOT EXISTS personalizacoes ( id INT AUTO_INCREMENT PRIMARY KEY, nome VARCHAR(50) NOT NULL, preco_adicional DECIMAL(10,2) DEFAULT 0.00 );
CREATE TABLE IF NOT EXISTS carrinhos ( id INT AUTO_INCREMENT PRIMARY KEY, usuario_id INT NOT NULL, status VARCHAR(20) DEFAULT 'aberto', data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE );
CREATE TABLE IF NOT EXISTS itens_carrinho ( id INT AUTO_INCREMENT PRIMARY KEY, carrinho_id INT NOT NULL, produto_id INT NOT NULL, quantidade INT NOT NULL DEFAULT 1, FOREIGN KEY (carrinho_id) REFERENCES carrinhos(id) ON DELETE CASCADE, FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE );
CREATE TABLE IF NOT EXISTS item_personalizacao ( id INT AUTO_INCREMENT PRIMARY KEY, item_carrinho_id INT NOT NULL, personalizacao_id INT NOT NULL, FOREIGN KEY (item_carrinho_id) REFERENCES itens_carrinho(id) ON DELETE CASCADE, FOREIGN KEY (personalizacao_id) REFERENCES personalizacoes(id) ON DELETE CASCADE );
CREATE TABLE enderecos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    nome_destinatario VARCHAR(100) NOT NULL,
    cep VARCHAR(10) NOT NULL,
    rua VARCHAR(150) NOT NULL,
    numero VARCHAR(20) NOT NULL,
    complemento VARCHAR(100),
    bairro VARCHAR(100) NOT NULL,
    cidade VARCHAR(100) NOT NULL,
    estado VARCHAR(2) NOT NULL,
    FOREIGN KEY (usuario_id)
        REFERENCES usuarios(id)
        ON DELETE CASCADE
);
CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'Recebido',
    data_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (usuario_id)
        REFERENCES usuarios(id)
        ON DELETE CASCADE
);
CREATE TABLE itens_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    produto_id INT NOT NULL,
    quantidade INT NOT NULL,
    preco_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,

    FOREIGN KEY (pedido_id)
        REFERENCES pedidos(id)
        ON DELETE CASCADE,

    FOREIGN KEY (produto_id)
        REFERENCES produtos(id)
        ON DELETE CASCADE
);
CREATE TABLE item_pedido_personalizacao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_pedido_id INT NOT NULL,
    nome VARCHAR(100) NOT NULL,
    preco_adicional DECIMAL(10,2) NOT NULL,

    FOREIGN KEY (item_pedido_id)
        REFERENCES itens_pedido(id)
        ON DELETE CASCADE
);
ALTER TABLE pedidos
ADD COLUMN forma_pagamento VARCHAR(30) NOT NULL;
