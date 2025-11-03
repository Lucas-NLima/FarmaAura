<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Carrinho</title>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
<h2>Seu Carrinho</h2>
<p><a href="index.php">← Voltar à loja</a></p>

<?php if (!empty($_SESSION['carrinho'])): ?>
<table border="1" cellpadding="8">
    <tr>
        <th>Produto</th>
        <th>Preço</th>
        <th>Quantidade</th>
        <th>Subtotal</th>
        <th>Ação</th>
    </tr>
    <?php foreach ($_SESSION['carrinho'] as $id => $item): ?>
    <tr>
        <td><?= htmlspecialchars($item['nome']) ?></td>
        <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
        <td><?= $item['quantidade'] ?></td>
        <td>R$ <?= number_format($item['preco'] * $item['quantidade'], 2, ',', '.') ?></td>
        <td>
            <a href="carrinho.php?acao=remover&id=<?= $id ?>">Remover</a>
        </td>
    </tr>
    <?php endforeach; ?>
    <tr>
        <td colspan="3"><strong>Total:</strong></td>
        <td colspan="2"><strong>R$ <?= number_format($total, 2, ',', '.') ?></strong></td>
    </tr>
</table>
<?php else: ?>
<p>Seu carrinho está vazio!</p>
<?php endif; ?>
</body>
</html>
