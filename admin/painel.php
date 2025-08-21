<?php
session_start();

require_once '../assets/includes/connection.php';
// Verifica se o usuário está logado e se tem perfil de 'admin'
if (!isset($_SESSION['tipo_perfil']) || $_SESSION['tipo_perfil'] !== 'admin') {
    // Se não for admin, redireciona para a página principal
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel do Administrador</title>
</head>
<body>
    <header>
        <h1>Painel do Administrador</h1>
        <p>Bem-vindo(a), <?php echo $_SESSION['nome_usuario']; ?>!</p>
        <nav>
            <a href="../index.php">Dashboard</a> | 
            <a href="pedidos.php">Gerenciar Pedidos</a> | 
            <a href="produtos.php">Gerenciar Produtos</a> | 
            <a href="usuarios.php">Gerenciar Usuários</a>
        </nav>
    </header>

    <main>
        <h2>Visão Geral</h2>
        <p>Aqui você poderá ver as estatísticas, pedidos em andamento e outras informações importantes.</p>
        
        <section id="estatisticas">
            <h3>Estatísticas Rápidas</h3>
            <?php
            // Inclui a conexão
            require_once '../includes/connection.php';
            
            // Exemplo: total de vendas
            $sql_total_vendas = "SELECT COUNT(*) AS total FROM pedidos WHERE status != 'cancelado'";
            $result_total_vendas = mysqli_query($conn, $sql_total_vendas);
            $total_vendas = mysqli_fetch_assoc($result_total_vendas)['total'];

            echo "<p>Total de Vendas: <strong>" . $total_vendas . "</strong></p>";

            // Adicione mais consultas aqui para os outros dados...
            mysqli_close($conn);
            ?>
        </section>
    </main>

    <footer>
        <a href="../pages/logout.php">Sair</a>
    </footer>
</body>
</html>