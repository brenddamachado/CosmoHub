<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['adicionar_carrinho'])) {
    $id_produto = $_POST['id_produto'];

    // Verifica se o produto já está no carrinho
    if (isset($_SESSION['carrinho'][$id_produto])) {
        // Aumenta a quantidade
        $_SESSION['carrinho'][$id_produto]['quantidade'] += 1;
    } else {
        // Adiciona o produto ao carrinho (por enquanto, com dados estáticos)
        // Em um projeto real, você buscaria os dados do produto no BD
        $_SESSION['carrinho'][$id_produto] = [
            'quantidade' => 1,
            'preco' => 100.00, // Preço estático
            'nome' => 'Nome do Produto' // Nome estático
        ];
    }
}

$total_compra = 0;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Carrinho de Compras - CosmoHub</title>
</head>
<body>
    <h2>Seu Carrinho</h2>
    <?php if (empty($_SESSION['carrinho'])): ?>
        <p>Seu carrinho está vazio.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Preço Unitário</th>
                    <th>Quantidade</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['carrinho'] as $id => $item): 
                    $subtotal = $item['quantidade'] * $item['preco'];
                    $total_compra += $subtotal;
                ?>
                <tr>
                    <td><?php echo $item['nome']; ?></td>
                    <td>R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></td>
                    <td><?php echo $item['quantidade']; ?></td>
                    <td>R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <h3>Total da Compra: R$ <?php echo number_format($total_compra, 2, ',', '.'); ?></h3>
        
        <a href="finalizar.php"><button>Finalizar Compra</button></a>
    <?php endif; ?>
</body>
</html>