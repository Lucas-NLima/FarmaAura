<?php
session_start();

// Verifica se o usuário está logado e é admin
if (!isset($_SESSION['usuario_cargo']) || $_SESSION['usuario_cargo'] != 'admin') {
    die("
        <div style='text-align:center; font-family:sans-serif; margin-top:50px;'>
            <h2 style='color:#b00020;'>🚫 Acesso negado!</h2>
            <p>Somente administradores podem acessar esta página.</p>
            <a href='../../../index.php' style='color:#0066cc;'>Voltar para a página inicial</a>
        </div>
    ");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Administrador - FarmaAura</title>
    <link rel="stylesheet" href="../../../css/adm.css">
</head>
<body>
    <div class="admin-container">
        <div class="admin-box">
            <h1>💊 Painel do Administrador</h1>
            <p>Bem-vindo, <strong><?php echo $_SESSION['usuario_nome']; ?></strong>!</p>

            <div class="admin-options">
                <a href="usuarios.php">👤 Gerenciar Usuários</a>
                <a href="produtos.php">💼 Gerenciar Produtos</a>
                <a href="fornecedores.php">🏭 Gerenciar Fornecedores</a>
                <a href="relatorio.php">📝 relatorio</a>
                <a href="../../../index.php" class="voltar">🏠 Voltar ao Início</a>
            </div>
        </div>
    </div>

    <!-- Fundo animado -->
    <div class="background-animation"></div>
</body>
</html>
