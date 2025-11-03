<?php
session_start();
require_once "c:/Turma1/xampp/htdocs/FarmaAura/app/db/Database.php";

// 🚫 Apenas administradores podem acessar
if (!isset($_SESSION['usuario_cargo']) || $_SESSION['usuario_cargo'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Filtro de período
$filtro = $_GET['periodo'] ?? 'todos';
switch ($filtro) {
    case 'hoje':
        $condicao = "DATE(v.data_venda) = CURDATE()";
        break;
    case 'semana':
        $condicao = "YEARWEEK(v.data_venda, 1) = YEARWEEK(CURDATE(), 1)";
        break;
    case 'mes':
        $condicao = "MONTH(v.data_venda) = MONTH(CURDATE()) AND YEAR(v.data_venda) = YEAR(CURDATE())";
        break;
    default:
        $condicao = "1";
        break;
}

// Consulta de vendas agrupadas
$sql = "SELECT p.nome, p.marca, SUM(v.quantidade) AS vendido, p.preco, SUM(v.quantidade * p.preco) AS total
        FROM vendas v
        JOIN produtos p ON v.produto_id = p.id
        WHERE $condicao
        GROUP BY v.produto_id
        ORDER BY vendido DESC";

$stmt = $pdo->query($sql);
$relatorio = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Relatório de Vendas - FarmaAura</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #ffffff, #eaf7f0);
    margin: 0; padding: 0;
}
header {
    background-color: #145a40;
    color: #fff;
    text-align: center;
    padding: 20px;
    font-size: 1.4em;
}
.container {
    max-width: 1000px;
    margin: 40px auto;
    background-color: #fff;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    animation: fadeIn 0.8s ease;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(15px); }
    to { opacity: 1; transform: translateY(0); }
}
h2 { color: #145a40; text-align: center; }
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}
th, td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
    text-align: center;
}
th { background-color: #d3b305; color: white; }
tr:hover { background-color: #f7f7f7; }
.total {
    margin-top: 20px;
    text-align: right;
    font-weight: bold;
    color: #145a40;
}
select {
    padding: 8px 12px;
    border-radius: 8px;
    border: 1px solid #ccc;
    margin-bottom: 20px;
}
.voltar {
    display: inline-block;
    margin-top: 20px;
    background-color: #d3b305;
    color: #fff;
    padding: 10px 20px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: bold;
}
.voltar:hover { background-color: #145a40; }
canvas {
    max-width: 800px;
    display: block;
    margin: 40px auto;
}
</style>
</head>
<body>
<header>📊 Relatório de Vendas - FarmaAura</header>

<div class="container">
    <form method="get" style="text-align:center;">
        <label for="periodo">Período:</label>
        <select name="periodo" id="periodo" onchange="this.form.submit()">
            <option value="todos" <?= $filtro=='todos'?'selected':'' ?>>Todos</option>
            <option value="hoje" <?= $filtro=='hoje'?'selected':'' ?>>Hoje</option>
            <option value="semana" <?= $filtro=='semana'?'selected':'' ?>>Esta Semana</option>
            <option value="mes" <?= $filtro=='mes'?'selected':'' ?>>Este Mês</option>
        </select>
    </form>

    <?php if (empty($relatorio)): ?>
        <p style="text-align:center;">Nenhuma venda registrada neste período.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Produto</th>
                <th>Marca</th>
                <th>Preço</th>
                <th>Quantidade Vendida</th>
                <th>Total</th>
            </tr>
            <?php 
            $totalGeral = 0;
            foreach ($relatorio as $item): 
                $totalGeral += $item['total'];
            ?>
            <tr>
                <td><?= htmlspecialchars($item['nome']) ?></td>
                <td><?= htmlspecialchars($item['marca']) ?></td>
                <td>R$ <?= number_format($item['preco'],2,',','.') ?></td>
                <td><?= $item['vendido'] ?></td>
                <td>R$ <?= number_format($item['total'],2,',','.') ?></td>
            </tr>
            <?php endforeach; ?>
        </table>

        <canvas id="grafico"></canvas>

        <p class="total">💰 Total Geral: R$ <?= number_format($totalGeral,2,',','.') ?></p>
    <?php endif; ?>

    <a href="Adm.php" class="voltar">⬅ Voltar</a>
</div>

<script>
const ctx = document.getElementById('grafico');
const grafico = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_column($relatorio, 'nome')) ?>,
        datasets: [{
            label: 'Quantidade Vendida',
            data: <?= json_encode(array_column($relatorio, 'vendido')) ?>,
            backgroundColor: '#d3b305aa',
            borderColor: '#145a40',
            borderWidth: 2
        }]
    },
    options: {
        plugins: {
            title: {
                display: true,
                text: 'Produtos Mais Vendidos',
                color: '#145a40',
                font: { size: 18, weight: 'bold' }
            }
        },
        scales: {
            y: { beginAtZero: true }
        },
        animation: { duration: 1000, easing: 'easeOutBounce' }
    }
});
</script>
</body>
</html>
