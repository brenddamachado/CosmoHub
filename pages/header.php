<?php
// A lógica para o header dinâmico deve estar aqui.
// Para que funcione, as páginas que o usarem devem ter session_start();
?>

<header>
    <h1>CosmoHub</h1>
    <nav>
        <?php if (isset($_SESSION['tipo_perfil']) && $_SESSION['tipo_perfil'] == 'admin'): ?>
            <a href="painel.php">Dashboard</a>
            <a href="produtos.php">Produtos</a>
            <a href="pedidos.php">Pedidos</a>
            <a href="usuarios.php">Usuários</a>
            <a href="../pages/logout.php">Sair</a>
        <?php elseif (isset($_SESSION['id_usuario'])): ?>
            <a href="../index.php">Home</a>
            <a href="../pages/carrinho.php">Carrinho</a>
            <a href="../pages/perfil.php">Meu Perfil</a>
            <a href="../pages/logout.php">Sair</a>
        <?php else: ?>
            <a href="pages/login.php">Login</a>
            <a href="pages/cadastro.php">Cadastro</a>
            <a href="index.php">Home</a>
        <?php endif; ?>
    </nav>
</header>