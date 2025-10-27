<?php
session_start();
require_once "../../db/Database.php";

# Somente admin pode acessar
if (!isset($_SESSION['usuario_cargo']) || $_SESSION['usuario_cargo'] != 'admin') {
    die("Acesso negado! Apenas administradores podem acessar esta página.");
}

# Lista todos os usuários
$stmt = $pdo->query("SELECT id, nome, email, cargo, verificado FROM usuarios");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Painel Administrativo</h2>
<p><a href='../../index.php'>← Voltar</a></p>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Email</th>
        <th>Cargo</th>
        <th>Verificado</th>
        <th>Ações</th>
    </tr>

    <?php foreach ($usuarios as $u): ?>
    <tr>
        <td><?= $u['id'] ?></td>
        <td><?= htmlspecialchars($u['nome']) ?></td>
        <td><?= htmlspecialchars($u['email']) ?></td>
        <td><?= $u['cargo'] ?></td>
        <td><?= $u['verificado'] ? 'Sim' : 'Não' ?></td>
        <td>
            <form method="post" action="../../controller/AdminController.php" style="display:inline;">
                <input type="hidden" name="id" value="<?= $u['id'] ?>">
                <select name="cargo">
                    <option value="usuario" <?= $u['cargo']=='usuario'?'selected':'' ?>>Usuário</option>
                    <option value="farmaceutico" <?= $u['cargo']=='farmaceutico'?'selected':'' ?>>Farmacêutico</option>
                    <option value="admin" <?= $u['cargo']=='admin'?'selected':'' ?>>Admin</option>
                </select>
                <button type="submit" name="alterar">Alterar</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
