<?php
session_start();
require_once "../../db/Database.php";
require_once "C:/Turma1/xampp/htdocs/FarmaAura/app/Controller/ProdutoController.php";

// Verifica se é admin
if (!isset($_SESSION['usuario_cargo']) || $_SESSION['usuario_cargo'] != 'admin') {
    die("<div style='text-align:center; margin-top:50px; font-family:sans-serif;'>
        <h2 style='color:#b00020;'>🚫 Acesso negado!</h2>
        <p>Somente administradores podem acessar esta página.</p>
        <a href='../../../Adm.php'>Voltar</a>
    </div>");
}

$produtoController = new ProdutoController($pdo);

/* ==========================
   ADICIONAR NOVO PRODUTO
========================== */
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["adicionar"])) {
    $tipo = $_POST["tipo"];
    $nome = $_POST["nome"];
    $validade = $_POST["validade"];
    $quantidade = $_POST["quantidade"];
    $marca = $_POST["marca"];
    $preco = $_POST["preco"];

    // Upload da imagem
    $imagem = 'default.png';
    if (!empty($_FILES['imagem']['name'])) {
        $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $imagem = uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['imagem']['tmp_name'], "../../../img/" . $imagem);
    }

    $stmt = $pdo->prepare("INSERT INTO produtos (tipo, nome, validade, quantidade, marca, preco, imagem) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$tipo, $nome, $validade, $quantidade, $marca, $preco, $imagem]);
}

/* ==========================
   EDITAR PRODUTO
========================== */
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["editar"])) {
    $id = $_POST["id"];
    $tipo = $_POST["tipo"];
    $nome = $_POST["nome"];
    $validade = $_POST["validade"];
    $quantidade = $_POST["quantidade"];
    $marca = $_POST["marca"];
    $preco = $_POST["preco"];

    $produtoAtual = $produtoController->buscarProduto($id);
    $imagem = $produtoAtual['imagem'];

    // Verifica se enviou nova imagem
    if (!empty($_FILES['imagem']['name'])) {
        $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $imagem = uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['imagem']['tmp_name'], "../../../img/" . $imagem);
    }

    $stmt = $pdo->prepare("UPDATE produtos SET tipo=?, nome=?, validade=?, quantidade=?, marca=?, preco=?, imagem=? WHERE id=?");
    $stmt->execute([$tipo, $nome, $validade, $quantidade, $marca, $preco, $imagem, $id]);
}

/* ==========================
   EXCLUIR PRODUTO
========================== */
if (isset($_GET["excluir"])) {
    $id = $_GET["excluir"];
    $produto = $produtoController->buscarProduto($id);
    if ($produto && $produto['imagem'] != 'default.png') {
        @unlink("../../../img/" . $produto['imagem']); // remove imagem antiga
    }
    $pdo->prepare("DELETE FROM produtos WHERE id=?")->execute([$id]);
}

/* ==========================
   LISTAR PRODUTOS
========================== */
$stmt = $pdo->query("SELECT * FROM produtos ORDER BY id DESC");
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Produtos - FarmaAura</title>
    <link rel="stylesheet" href="../../../css/editores.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .table-container {
            max-height: 400px;
            overflow-y: auto;
            border-radius: 10px;
            border: 1px solid #ccc;
            margin-top: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            text-align: center;
        }

        th {
            background: #00796b;
            color: white;
            position: sticky;
            top: 0;
        }

        button {
            background-color: #00796b;
            color: #fff;
            border: none;
            padding: 8px 14px;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background-color: #004d40;
        }

        .grafico-container {
            margin-top: 40px;
            background: #fff;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
<div class="admin-container">
    <div class="admin-box" style="width:1000px;">
        <h1>💊 Gerenciar Produtos</h1>
        <p><a href='Adm.php'>← Voltar</a></p>

        <h3>Adicionar Novo Produto</h3>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="tipo" placeholder="Tipo" required><br>
            <input type="text" name="nome" placeholder="Nome do produto" required><br>
            <input type="date" name="validade" required><br>
            <input type="number" name="quantidade" placeholder="Quantidade" required><br>
            <input type="text" name="marca" placeholder="Marca" required><br>
            <input type="number" step="0.01" name="preco" placeholder="Preço" required><br>
            <input type="file" name="imagem" accept="image/*"><br>
            <button type="submit" name="adicionar">Adicionar Produto</button>
        </form>

        <h3>Produtos Cadastrados</h3>
        <div class="table-container">
            <table border="1" cellpadding="4">
                <tr>
                    <th>ID</th>
                    <th>Tipo</th>
                    <th>Nome</th>
                    <th>Validade</th>
                    <th>Qtd</th>
                    <th>Marca</th>
                    <th>Preço (R$)</th>
                    <th>Imagem</th>
                    <th>Ações</th>
                </tr>
                <?php foreach ($produtos as $p): ?>
                <tr>
                    <form method="POST" enctype="multipart/form-data">
                        <td><?= $p['id'] ?></td>
                        <td><input type="text" name="tipo" value="<?= htmlspecialchars($p['tipo']) ?>"></td>
                        <td><input type="text" name="nome" value="<?= htmlspecialchars($p['nome']) ?>"></td>
                        <td><input type="date" name="validade" value="<?= $p['validade'] ?>"></td>
                        <td><input type="number" name="quantidade" value="<?= $p['quantidade'] ?>"></td>
                        <td><input type="text" name="marca" value="<?= htmlspecialchars($p['marca']) ?>"></td>
                        <td><input type="number" step="0.01" name="preco" value="<?= $p['preco'] ?>"></td>
                        <td>
                            <img src="../../../img/<?= htmlspecialchars($p['imagem'] ?? 'default.png') ?>" width="50"><br>
                            <input type="file" name="imagem" accept="image/*">
                        </td>
                        <td>
                            <input type="hidden" name="id" value="<?= $p['id'] ?>">
                            <button type="submit" name="editar">💾</button>
                            <a href="?excluir=<?= $p['id'] ?>" onclick="return confirm('Deseja excluir este produto?')">🗑️</a>
                        </td>
                    </form>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <!-- GRÁFICO -->
        <div class="grafico-container">
            <h3>📊 Gráfico de Produtos por Tipo</h3>
            <canvas id="graficoProdutos"></canvas>
        </div>
    </div>
</div>

<script>
const ctx = document.getElementById('graficoProdutos');
const dados = <?= json_encode($produtos) ?>;

// Contar produtos por tipo
const contagem = {};
dados.forEach(p => {
  contagem[p.tipo] = (contagem[p.tipo] || 0) + 1;
});

new Chart(ctx, {
  type: 'bar',
  data: {
    labels: Object.keys(contagem),
    datasets: [{
      label: 'Quantidade de Produtos',
      data: Object.values(contagem),
      borderWidth: 1,
      backgroundColor: '#00796b'
    }]
  },
  options: {
    responsive: true,
    scales: {
      y: { beginAtZero: true }
    }
  }
});
</script>

</body>
</html>
