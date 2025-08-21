<?php
session_start();
require_once '../assets/includes/connection.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Busca os dados do cliente
$sql_cliente = "SELECT nome, email, data_criacao FROM usuarios WHERE id = ?";
$stmt_cliente = mysqli_prepare($conn, $sql_cliente);
mysqli_stmt_bind_param($stmt_cliente, "i", $id_usuario);
mysqli_stmt_execute($stmt_cliente);
$result_cliente = mysqli_stmt_get_result($stmt_cliente);
$cliente = mysqli_fetch_assoc($result_cliente);

// Busca o histórico de pedidos do cliente
$sql_pedidos = "SELECT id, status, data_criacao FROM pedidos WHERE id_usuario = ? ORDER BY data_criacao DESC";
$stmt_pedidos = mysqli_prepare($conn, $sql_pedidos);
mysqli_stmt_bind_param($stmt_pedidos, "i", $id_usuario);
mysqli_stmt_execute($stmt_pedidos);
$result_pedidos = mysqli_stmt_get_result($stmt_pedidos);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meu Perfil - CosmoHub</title>
</head>
<body>
    <h2>Meu Perfil</h2>
    <section>
        <h3>Dados Pessoais</h3>
        <p><strong>Nome:</strong> <?php echo $cliente['nome']; ?></p>
        <p><strong>Email:</strong> <?php echo $cliente['email']; ?></p>
        <p><strong>Membro desde:</strong> <?php echo $cliente['data_criacao']; ?></p>
        <a href="editar_perfil.php">Editar Informações</a>
    </section>

    <hr>

    <section>
        <h3>Histórico de Pedidos</h3>
        <?php if (mysqli_num_rows($result_pedidos) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID Pedido</th>
                        <th>Status</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($pedido = mysqli_fetch_assoc($result_pedidos)): ?>
                    <tr>
                        <td><?php echo $pedido['id']; ?></td>
                        <td><?php echo $pedido['status']; ?></td>
                        <td><?php echo $pedido['data_criacao']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Você ainda não fez nenhum pedido.</p>
        <?php endif; ?>
    </section>
</body>
</html>