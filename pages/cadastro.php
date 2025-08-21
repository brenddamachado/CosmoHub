<?php
// Inclui o arquivo de conexão
require_once '../assets/includes/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Criptografa a senha para segurança
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // Insere o novo usuário no banco de dados
    $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
    
    // Usa prepared statements para prevenir injeção de SQL
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $nome, $email, $senha_hash);

    if (mysqli_stmt_execute($stmt)) {
        echo "Cadastro realizado com sucesso!";
        // Redireciona para a página de login após o cadastro
        header("Location: login.php");
    } else {
        echo "Erro ao cadastrar: " . mysqli_error($conn);
    }
    
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro - CosmoHub</title>
</head>
<body>
    <h2>Cadastro de Novo Usuário</h2>
    <form action="cadastro.php" method="post">
        <label for="nome">Nome:</label><br>
        <input type="text" id="nome" name="nome" required><br><br>
        
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>
        
        <label for="senha">Senha:</label><br>
        <input type="password" id="senha" name="senha" required><br><br>
        
        <button type="submit">Cadastrar</button>
    </form>
    <p>Já tem uma conta? <a href="login.php">Faça login aqui</a>.</p>
</body>
</html>