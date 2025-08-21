<?php
session_start();
require_once '../assets/includes/connection.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['tipo_perfil']) || $_SESSION['tipo_perfil'] !== 'admin') {
    redirecionar('../index.php');
}

// Lógica de adicionar produto
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['adicionar_produto'])) {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $estoque = $_POST['estoque'];
    $imagem = $_POST['imagem'];

    $sql = "INSERT INTO produtos (nome, descricao, preco, estoque, imagem) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssdis", $nome, $descricao, $preco, $estoque, $imagem);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}
// Lógica de excluir produto
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['excluir_produto'])) {
    $id_produto = $_POST['id_produto'];
    $sql = "DELETE FROM produtos WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_produto);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

$sql_produtos = "SELECT * FROM produtos";
$result_produtos = mysqli_query($conn, $sql_produtos);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Produtos - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <header>
        <h2>Gerenciar Produtos</h2>
    </header>
    <main>
        <button id="abrirModal">Adicionar Novo Produto</button>

        <hr>

        <h3>Produtos Cadastrados</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Preço</th>
                    <th>Estoque</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($produto = mysqli_fetch_assoc($result_produtos)): ?>
                <tr>
                    <td><?php echo $produto['id']; ?></td>
                    <td><?php echo $produto['nome']; ?></td>
                    <td><?php echo formatar_moeda($produto['preco']); ?></td>
                    <td><?php echo $produto['estoque']; ?></td>
                    <td>
                        <form action="produtos.php" method="post" style="display:inline;">
                            <input type="hidden" name="id_produto" value="<?php echo $produto['id']; ?>">
                            <button type="submit" name="excluir_produto">Excluir</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>
    
    <div id="meuModal" class="modal">
        <div class="modal-content">
            <span class="fechar">&times;</span>
            <h3>Adicionar Novo Produto</h3>
            <form action="produtos.php" method="post">
                <label>Nome:</label><input type="text" name="nome" required>
                <label>Descrição:</label><textarea name="descricao" required></textarea>
                <label>Preço:</label><input type="number" step="0.01" name="preco" required>
                <label>Estoque:</label><input type="number" name="estoque" required>
                <label>Imagem (nome do arquivo):</label><input type="text" name="imagem" required>
                <button type="submit" name="adicionar_produto">Salvar Produto</button>
            </form>
        </div>
    </div>

    <script>
    // Lógica JavaScript para abrir e fechar o modal
    var modal = document.getElementById("meuModal");
    var btn = document.getElementById("abrirModal");
    var span = document.getElementsByClassName("fechar")[0];
    
    btn.onclick = function() {
      modal.style.display = "block";
    }
    
    span.onclick = function() {
      modal.style.display = "none";
    }
    
    window.onclick = function(event) {
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }
    </script>
</body>
</html>