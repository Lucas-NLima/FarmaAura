<?php
session_start();
require_once "../../db/Database.php";

// 🚫 Permite acesso apenas a admins
if (!isset($_SESSION['usuario_cargo']) || $_SESSION['usuario_cargo'] != 'admin') {
    die("<div style='text-align:center; margin-top:50px; font-family:sans-serif;'>
        <h2 style='color:#b00020;'>🚫 Acesso negado!</h2>
        <p>Somente administradores podem acessar esta página.</p>
        <a href='../../../index.php'>Voltar</a>
    </div>");
}

/* ==========================
   ADICIONAR NOVO USUÁRIO
========================== */
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["adicionar"])) {
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);
    $cargo = $_POST["cargo"];

    $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, cargo, verificado) VALUES (?, ?, ?, ?, 1)");
    $stmt->execute([$nome, $email, $senha, $cargo]);
}

/* ==========================
   EDITAR USUÁRIO
========================== */
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["editar"])) {
    $id = $_POST["id"];
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $cargo = $_POST["cargo"];

    $stmt = $pdo->prepare("UPDATE usuarios SET nome=?, email=?, cargo=? WHERE id=?");
    $stmt->execute([$nome, $email, $cargo, $id]);
}

/* ==========================
   EXCLUIR USUÁRIO
========================== */
if (isset($_GET["excluir"])) {
    $id = $_GET["excluir"];
    $pdo->prepare("DELETE FROM usuarios WHERE id=?")->execute([$id]);
}

/* ==========================
   LISTAR USUÁRIOS
========================== */
$stmt = $pdo->query("SELECT id, nome, email, cargo, verificado FROM usuarios ORDER BY id DESC");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Usuários - FarmaAura</title>
    <link rel="stylesheet" href="../../../css/adm.css">
</head>
<body>
<div class="admin-container">
    <div class="admin-box" style="width:900px;">
        <h1>👥 Gerenciar Usuários</h1>
        <p><a href='Adm.php'>← Voltar</a></p>

        <h3>Adicionar Novo Usuário</h3>
        <form method="POST">
            <input type="text" name="nome" placeholder="Nome completo" required><br>
            <input type="email" name="email" placeholder="E-mail" required><br>
            <input type="password" name="senha" placeholder="Senha" required><br>
            <select name="cargo" required>
                <option value="usuario">Usuário</option>
                <option value="farmaceutico">Farmacêutico</option>
                <option value="admin">Admin</option>
            </select><br>
            <button type="submit" name="adicionar">Adicionar Usuário</button>
        </form>

        <h3>Usuários Cadastrados</h3>
        <table border="1" cellpadding="8" style="width:100%; border-collapse:collapse; font-size:14px;">
            <tr style="background:#00796b; color:white;">
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Cargo</th>
                <th>Verificado</th>
                <th>Ações</th>
            </tr>
            <?php foreach ($usuarios as $u): ?>
            <tr>
                <form method="POST">
                    <td><?= $u['id'] ?></td>
                    <td><input type="text" name="nome" value="<?= htmlspecialchars($u['nome']) ?>"></td>
                    <td><input type="email" name="email" value="<?= htmlspecialchars($u['email']) ?>"></td>
                    <td>
                        <select name="cargo">
                            <option value="usuario" <?= $u['cargo']=='usuario'?'selected':'' ?>>Usuário</option>
                            <option value="farmaceutico" <?= $u['cargo']=='farmaceutico'?'selected':'' ?>>Farmacêutico</option>
                            <option value="admin" <?= $u['cargo']=='admin'?'selected':'' ?>>Admin</option>
                        </select>
                    </td>
                    <td><?= $u['verificado'] ? '✅' : '❌' ?></td>
                    <td>
                        <input type="hidden" name="id" value="<?= $u['id'] ?>">
                        <button type="submit" name="editar">💾</button>
                        <a href="?excluir=<?= $u['id'] ?>" onclick="return confirm('Deseja excluir este usuário?')">🗑️</a>
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
