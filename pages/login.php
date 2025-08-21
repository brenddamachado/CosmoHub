<?php
// Inicia a sessão
session_start();
// Inclui o arquivo de conexão
require_once '../assets/includes/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    $sql = "SELECT id, nome, senha, tipo_perfil FROM usuarios WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) == 1) {
        mysqli_stmt_bind_result($stmt, $id, $nome, $senha_hash, $tipo_perfil);
        mysqli_stmt_fetch($stmt);

        if (password_verify($senha, $senha_hash)) {
            $_SESSION['id_usuario'] = $id;
            $_SESSION['nome_usuario'] = $nome;
            $_SESSION['tipo_perfil'] = $tipo_perfil;

            if ($tipo_perfil == 'admin') {
                header("Location: ../admin/painel.php");
                exit();
            } else {
                header("Location: ../index.php");
                exit();
            }
        } else {
            echo "Senha incorreta.";
        }
    } else {
        echo "Usuário não encontrado.";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - CosmoHub</title>
</head>
<body>
    <h2>Login</h2>
    <form action="login.php" method="post">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>
        
        <label for="senha">Senha:</label><br>
        <input type="password" id="senha" name="senha" required><br><br>
        
        <button type="submit">Entrar</button>
    </form>
    <p>Não tem uma conta? <a href="cadastro.php">Cadastre-se aqui</a>.</p>
</body>
</html>