<?php
session_start();
require_once '../assets/includes/connection.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['tipo_perfil']) || $_SESSION['tipo_perfil'] !== 'admin') {
    redirecionar('../index.php');
}

// Lógica para adicionar um novo usuário/funcionário
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['adicionar_usuario'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $tipo_perfil = $_POST['tipo_perfil'];

    // Criptografa a senha para segurança
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (nome, email, senha, tipo_perfil) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssss", $nome, $email, $senha_hash, $tipo_perfil);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "<p class='success'>Usuário adicionado com sucesso!</p>";
    } else {
        echo "<p class='error'>Erro ao adicionar usuário: " . mysqli_error($conn) . "</p>";
    }
    mysqli_stmt_close($stmt);
}

// Seleciona todos os usuários para exibição
$sql_usuarios = "SELECT id, nome, email, tipo_perfil, ultimo_login, data_criacao FROM usuarios ORDER BY data_criacao DESC";
$result_usuarios = mysqli_query($conn, $sql_usuarios);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Usuários - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <header>
        <h2>Gerenciar Usuários</h2>
    </header>
    <main>
        <h3>Adicionar Novo Funcionário</h3>
        <form action="usuarios.php" method="post" class="admin-form">
            <label>Nome:</label><input type="text" name="nome" required>
            <label>Email:</label><input type="email" name="email" required>
            <label>Senha:</label><input type="password" name="senha" required>
            <label>Tipo de Perfil:</label>
            <select name="tipo_perfil">
                <option value="cliente">Cliente</option>
                <option value="admin">Admin</option>
            </select>
            <button type="submit" name="adicionar_usuario">Adicionar Usuário</button>
        </form>
        <hr>
        <h3>Todos os Usuários</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Tipo</th>
                    <th>Último Login</th>
                    <th>Data de Criação</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($usuario = mysqli_fetch_assoc($result_usuarios)): ?>
                <tr>
                    <td><?php echo $usuario['id']; ?></td>
                    <td><?php echo $usuario['nome']; ?></td>
                    <td><?php echo $usuario['email']; ?></td>
                    <td><?php echo $usuario['tipo_perfil']; ?></td>
                    <td><?php echo $usuario['ultimo_login']; ?></td>
                    <td><?php echo $usuario['data_criacao']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>
</body>
</html>