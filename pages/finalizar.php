<?php
session_start();
require_once '../assets/includes/connection.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

// Processa a finalização da compra
if (!empty($_SESSION['carrinho'])) {
    $id_usuario = $_SESSION['id_usuario'];
    $status = 'aguardando embalagem';

    // Inicia uma transação para garantir que tudo seja inserido ou nada
    mysqli_begin_transaction($conn);

    try {
        // Insere o pedido na tabela 'pedidos'
        $sql_pedido = "INSERT INTO pedidos (id_usuario, status) VALUES (?, ?)";
        $stmt_pedido = mysqli_prepare($conn, $sql_pedido);
        mysqli_stmt_bind_param($stmt_pedido, "is", $id_usuario, $status);
        mysqli_stmt_execute($stmt_pedido);
        
        // Obtém o ID do pedido que acabou de ser inserido
        $id_pedido = mysqli_insert_id($conn);
        
        // Insere os itens do carrinho na tabela 'itens_pedido'
        foreach ($_SESSION['carrinho'] as $id_produto => $item) {
            $sql_item = "INSERT INTO itens_pedido (id_pedido, id_produto, quantidade, preco_unitario) VALUES (?, ?, ?, ?)";
            $stmt_item = mysqli_prepare($conn, $sql_item);
            mysqli_stmt_bind_param($stmt_item, "iiid", $id_pedido, $id_produto, $item['quantidade'], $item['preco']);
            mysqli_stmt_execute($stmt_item);
        }

        // Se tudo deu certo, confirma a transação
        mysqli_commit($conn);
        
        // Limpa o carrinho
        unset($_SESSION['carrinho']);
        
        echo "<h1>Compra finalizada com sucesso!</h1>";
        echo "<p>Seu pedido foi gerado e está com o status 'Aguardando embalagem'.</p>";
        echo "<a href='../index.php'>Voltar para a Home</a>";

    } catch (Exception $e) {
        // Se algo der errado, desfaz a transação
        mysqli_rollback($conn);
        echo "Erro ao finalizar a compra: " . $e->getMessage();
    }
    
    mysqli_close($conn);
} else {
    echo "<h1>Seu carrinho está vazio.</h1>";
    echo "<a href='../index.php'>Voltar para a Home</a>";
}
?>