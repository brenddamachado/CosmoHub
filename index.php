<?php
// Inclui o arquivo de conexão
require_once './assets/includes/connection.php';

// Inicia a sessão para o carrinho de compras
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CosmoHub - Home</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <h1>CosmoHub</h1>
        <nav>
            <a href="pages/login.php">Login</a>
            <a href="pages/cadastro.php">Cadastro</a>
            <a href="pages/carrinho.php">Carrinho</a>
        </nav>
    </header>

    <main>
        <h2>Nossos Produtos</h2>
        <div class="product-list">
            <?php
            // Seleciona todos os produtos do banco de dados
            $sql = "SELECT * FROM produtos";
            $result = mysqli_query($conn, $sql);

            // Verifica se existem produtos
            if (mysqli_num_rows($result) > 0) {
                // Loop para exibir cada produto
                while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <div class="product-card">
                        <img src="assets/img/<?php echo $row['imagem']; ?>" alt="<?php echo $row['nome']; ?>">
                        <h3><?php echo $row['nome']; ?></h3>
                        <p><?php echo $row['descricao']; ?></p>
                        <span class="price">R$ <?php echo number_format($row['preco'], 2, ',', '.'); ?></span>
                        
                        <form action="pages/carrinho.php" method="post">
                            <input type="hidden" name="id_produto" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="adicionar_carrinho">Adicionar ao Carrinho</button>
                        </form>
                    </div>
                    <?php
                }
            } else {
                echo "<p>Nenhum produto encontrado.</p>";
            }

            // Fecha a conexão com o banco de dados
            mysqli_close($conn);
            ?>
        </div>
    </main>
    
    <footer>
        <p>&copy; 2024 CosmoHub. Todos os direitos reservados.</p>
    </footer>
</body>
</html>