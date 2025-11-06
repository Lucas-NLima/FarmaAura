<?php
session_start();
require_once "../../db/Database.php";

// Permite apenas admin
if (!isset($_SESSION['usuario_cargo']) || $_SESSION['usuario_cargo'] != 'admin') {
    die("<div style='text-align:center; margin-top:50px; font-family:sans-serif;'>
        <h2 style='color:#b00020;'>🚫 Acesso negado!</h2>
        <p>Somente administradores podem acessar esta página.</p>
        <a href='../../../index.php'>Voltar</a>
    </div>");
}

/* Adicionar Usuário */
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["adicionar"])) {
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);
    $cargo = $_POST["cargo"];
    $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, cargo, verificado) VALUES (?, ?, ?, ?, 1)");
    $stmt->execute([$nome, $email, $senha, $cargo]);
}

/* Editar Usuário */
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["editar"])) {
    $id = $_POST["id"];
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $cargo = $_POST["cargo"];
    $stmt = $pdo->prepare("UPDATE usuarios SET nome=?, email=?, cargo=? WHERE id=?");
    $stmt->execute([$nome, $email, $cargo, $id]);
}

/* Excluir Usuário */
if (isset($_GET["excluir"])) {
    $id = $_GET["excluir"];
    $pdo->prepare("DELETE FROM usuarios WHERE id=?")->execute([$id]);
}

/* Filtro */
$busca = $_GET['busca'] ?? '';
$filtroCargo = $_GET['cargo'] ?? '';

$query = "SELECT id, nome, email, cargo, verificado FROM usuarios WHERE 1=1";
if (!empty($busca)) $query .= " AND (nome LIKE :busca OR email LIKE :busca)";
if (!empty($filtroCargo)) $query .= " AND cargo = :cargo";
$query .= " ORDER BY id DESC";

$stmt = $pdo->prepare($query);
if (!empty($busca)) $stmt->bindValue(":busca", "%$busca%");
if (!empty($filtroCargo)) $stmt->bindValue(":cargo", $filtroCargo);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Usuários - FarmaAura</title>
    <link rel="stylesheet" href="../../../css/editores.css">
</head>
<body>
<div class="admin-container">
    <div class="admin-box" style="width:950px;">
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
        <form method="GET" class="filter-bar">
            <input type="text" name="busca" placeholder="Buscar nome ou e-mail" value="<?= htmlspecialchars($busca) ?>">
            <select name="cargo">
                <option value="">Todos os cargos</option>
                <option value="usuario" <?= $filtroCargo=='usuario'?'selected':'' ?>>Usuário</option>
                <option value="farmaceutico" <?= $filtroCargo=='farmaceutico'?'selected':'' ?>>Farmacêutico</option>
                <option value="admin" <?= $filtroCargo=='admin'?'selected':'' ?>>Admin</option>
            </select>
            <button type="submit">Filtrar</button>
        </form>

        <div class="table-container">
            <table>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Cargo</th>
                    <th>Verificado</th>
                    <th>Ações</th>
                </tr>
                <?php if (empty($usuarios)): ?>
                    <tr><td colspan="6" style="text-align:center;">Nenhum usuário encontrado.</td></tr>
                <?php else: ?>
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
                                <a href="?excluir=<?= $u['id'] ?>" onclick="return confirm('Excluir este usuário?')">🗑️</a>
                            </td>
                        </form>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </table>
        </div>
    </div>
</div>
</body>
</html>
