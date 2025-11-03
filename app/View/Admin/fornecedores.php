<?php
session_start();
require_once "../../db/Database.php";

// Verifica permissão
if (!isset($_SESSION['usuario_cargo']) || $_SESSION['usuario_cargo'] != 'admin') {
    die("<div style='text-align:center; margin-top:50px; font-family:sans-serif;'>
        <h2 style='color:#b00020;'>🚫 Acesso negado!</h2>
        <p>Somente administradores podem acessar esta página.</p>
        <a href='../../../Adm.php'>Voltar</a>
    </div>");
}

// Adicionar fornecedor
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["adicionar"])) {
    $nome = $_POST["nome"];
    $contato = $_POST["contato"];
    $email = $_POST["email"];

    $stmt = $pdo->prepare("INSERT INTO fornecedores (nome, contato, email) VALUES (?, ?, ?)");
    $stmt->execute([$nome, $contato, $email]);
}

// Editar fornecedor
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["editar"])) {
    $id = $_POST["id"];
    $nome = $_POST["nome"];
    $contato = $_POST["contato"];
    $email = $_POST["email"];

    $stmt = $pdo->prepare("UPDATE fornecedores SET nome=?, contato=?, email=? WHERE id=?");
    $stmt->execute([$nome, $contato, $email, $id]);
}

// Excluir fornecedor
if (isset($_GET["excluir"])) {
    $id = $_GET["excluir"];
    $pdo->prepare("DELETE FROM fornecedores WHERE id=?")->execute([$id]);
}

// Listar fornecedores
$stmt = $pdo->query("SELECT * FROM fornecedores ORDER BY id DESC");
$fornecedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Fornecedores - FarmaAura</title>
    <link rel="stylesheet" href="../../../css/adm.css">
</head>
<body>
<div class="admin-container">
    <div class="admin-box" style="width:800px;">
        <h1>🏭 Gerenciar Fornecedores</h1>
        <p><a href='Adm.php'>← Voltar</a></p>

        <h3>Adicionar Novo Fornecedor</h3>
        <form method="POST">
            <input type="text" name="nome" placeholder="Nome do fornecedor" required><br>
            <input type="text" name="contato" placeholder="Telefone ou WhatsApp" required><br>
            <input type="email" name="email" placeholder="E-mail" required><br>
            <button type="submit" name="adicionar">Adicionar</button>
        </form>

        <h3>Fornecedores Cadastrados</h3>
        <table border="1" cellpadding="8" style="width:100%; border-collapse:collapse;">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Contato</th>
                <th>Email</th>
                <th>Ações</th>
            </tr>
            <?php foreach ($fornecedores as $f): ?>
            <tr>
                <form method="POST">
                    <td><?= $f['id'] ?></td>
                    <td><input type="text" name="nome" value="<?= htmlspecialchars($f['nome']) ?>"></td>
                    <td><input type="text" name="contato" value="<?= htmlspecialchars($f['contato']) ?>"></td>
                    <td><input type="email" name="email" value="<?= htmlspecialchars($f['email']) ?>"></td>
                    <td>
                        <input type="hidden" name="id" value="<?= $f['id'] ?>">
                        <button type="submit" name="editar">💾</button>
                        <a href="?excluir=<?= $f['id'] ?>" onclick="return confirm('Excluir este fornecedor?')">🗑️</a>
                    </td>
                </form>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
<div class="background-animation"></div>
</body>
</html>
