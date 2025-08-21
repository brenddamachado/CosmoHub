<?php
session_start();
require_once '../assets/includes/connection.php';

if (!isset($_SESSION['tipo_perfil']) || $_SESSION['tipo_perfil'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Lógica para atualizar o status do pedido, se o formulário for submetido
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['atualizar_status'])) {
    $id_pedido = $_POST['id_pedido'];
    $novo_status = $_POST['status'];
    
    $sql = "UPDATE pedidos SET status = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $novo_status, $id_pedido);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// Seleciona todos os pedidos do banco de dados
$sql_pedidos = "SELECT p.id, u.nome AS cliente_nome, p.status, p.data_criacao FROM pedidos p JOIN usuarios u ON p.id_usuario = u.id ORDER BY p.data_criacao DESC";
$result_pedidos = mysqli_query($conn, $sql_pedidos);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Pedidos - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <h2>Gerenciar Pedidos</h2>
    </header>

    <main>
        <table>
            <thead>
                <tr>
                    <th>ID Pedido</th>
                    <th>Cliente</th>
                    <th>Status</th>
                    <th>Data</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($pedido = mysqli_fetch_assoc($result_pedidos)): ?>
                <tr>
                    <td><?php echo $pedido['id']; ?></td>
                    <td><?php echo $pedido['cliente_nome']; ?></td>
                    <td><?php echo $pedido['status']; ?></td>
                    <td><?php echo $pedido['data_criacao']; ?></td>
                    <td>
                        <form action="pedidos.php" method="post">
                            <input type="hidden" name="id_pedido" value="<?php echo $pedido['id']; ?>">
                            <select name="status">
                                <option value="aguardando embalagem" <?php echo ($pedido['status'] == 'aguardando embalagem') ? 'selected' : ''; ?>>Aguardando embalagem</option>
                                <option value="enviado" <?php echo ($pedido['status'] == 'enviado') ? 'selected' : ''; ?>>Enviado</option>
                                <option value="cancelado" <?php echo ($pedido['status'] == 'cancelado') ? 'selected' : ''; ?>>Cancelado</option>
                            </select>
                            <button type="submit" name="atualizar_status">Atualizar</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>
</body>
</html>