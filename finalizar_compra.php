<?php
session_start();
require_once "c:/Turma1/xampp/htdocs/FarmaAura/app/db/Database.php";
require_once "c:/Turma1/xampp/htdocs/FarmaAura/app/Controller/ProdutoController.php";

if (!isset($_SESSION['usuario_nome'])) {
    header("Location: app/view/login/login.php");
    exit;
}

$produtoController = new ProdutoController($pdo);

// Se o carrinho estiver vazio
if (empty($_SESSION['carrinho'])) {
    $_SESSION['mensagem_sucesso'] = "Seu carrinho está vazio!";
    header("Location: index.php");
    exit;
}

// Carrega produtos e calcula total
$totalCarrinho = 0;
$produtosCarrinho = [];
foreach ($_SESSION['carrinho'] as $id => $qtd) {
    $produto = $produtoController->buscarProduto($id);
    if ($produto) {
        $produto['qtd'] = (int)$qtd;
        $produtosCarrinho[] = $produto;
        $totalCarrinho += ((float)$produto['preco']) * $produto['qtd'];
    }
}

// Quando clicar em “Finalizar Compra”
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['finalizar'])) {
    $formaPagamento = $_POST['pagamento'] ?? 'Não informada';

    foreach ($produtosCarrinho as $p) {
        $novoEstoque = max(0, (int)$p['quantidade'] - (int)$p['qtd']);

        // Atualiza o estoque
        $produtoController->editar(
            $p['nome'], 
            $p['tipo'], 
            $novoEstoque, 
            $p['validade'], 
            $p['marca'], 
            $p['preco'],
            $formaPagamento, 
            $p['imagem'] ?? '',
            $p['id']
            
        );

        // Registra a venda no banco
        $stmt = $pdo->prepare("INSERT INTO vendas (produto_id, nome_produto, quantidade, total) VALUES (?, ?, ?, ?)");
        $stmt->execute([$p['id'], $p['nome'], $p['qtd'], ($p['preco'] * $p['qtd'])]);
    }

    // Limpa o carrinho
    $_SESSION['carrinho'] = [];
    $_SESSION['mensagem_sucesso'] = "🎉 Compra finalizada com sucesso! Obrigado pela preferência ❤️";
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Finalizar Compra - FarmaAura</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
body, html { margin:0; padding:0; font-family: 'Poppins', sans-serif; }
.background-animada {
    position: fixed; top:0; left:0; width:100%; height:100%;
    background: linear-gradient(-45deg, #fef9e7, #d3b305, #e8f5e9, #fff);
    background-size: 400% 400%;
    animation: gradienteBG 15s ease infinite;
    z-index:-1;
}
@keyframes gradienteBG {
  0%{background-position:0% 50%;}
  50%{background-position:100% 50%;}
  100%{background-position:0% 50%;}
}
.card-compra {
    max-width: 700px; width:90%;
    background:#fff; border-radius:20px;
    padding:30px; box-shadow:0 8px 20px rgba(0,0,0,0.2);
    margin:50px auto; animation: aparecer 0.6s ease;
}
@keyframes aparecer { 
  0%{opacity:0; transform:translateY(-20px);} 
  100%{opacity:1; transform:translateY(0);} 
}
h2 { color:#d3b305; text-align:center; margin-top:0; }
.produto-item { display:flex; justify-content:space-between; margin:10px 0; border-bottom:1px solid #eee; padding-bottom:5px; }
.produto-item span { font-weight:600; }
.total { text-align:right; font-size:1.2rem; color:#2e7d32; font-weight:700; margin-top:10px; }
form { margin-top:20px; }
form label { display:block; margin:10px 0 5px; font-weight:600; }
form select, form input {
    width:100%; padding:10px; border-radius:12px; border:1px solid #ccc;
    font-size:1rem; margin-bottom:15px;
}
.botao-finalizar {
    background-color:#d3b305; color:#fff; border:none;
    padding:12px 25px; border-radius:30px; font-weight:600; cursor:pointer; width:100%;
    transition:0.3s; font-size:1.1rem;
}
.botao-finalizar:hover { background-color:#145a40; transform: scale(1.03); }
.botao-voltar {
    background:#ccc; color:#333; border:none;
    padding:10px 20px; border-radius:20px; font-weight:600; cursor:pointer;
    margin-top:10px; display:block; text-align:center; text-decoration:none;
}
.botao-voltar:hover { background:#999; }
</style>
</head>
<body>
<div class="background-animada"></div>
<!-- Botão de acessibilidade -->
<div style="position:fixed; top:20px; left:20px; z-index:9999;">
    <button id="acessibilidadeBtn" class="botao">♿ Acessibilidade</button>
</div>

<style>
/* Estilo do modo acessibilidade */
body.acessibilidade-daltonico {
    filter: grayscale(60%) contrast(150%);
}

body.acessibilidade-cegos {
    background-color: #000 !important;
    color: #fff !important;
}

body.acessibilidade-cegos a, 
body.acessibilidade-cegos button {
    background-color: #d3b305 !important;
    color: #000 !important;
    border: 2px solid #fff;
}
</style>

<script>
const btn = document.getElementById('acessibilidadeBtn');
let modo = 0; // 0=normal, 1=daltonico, 2=cegos

btn.addEventListener('click', () => {
    modo++;
    if(modo > 2) modo = 0;

    document.body.classList.remove('acessibilidade-daltonico', 'acessibilidade-cegos');

    if(modo === 1){
        document.body.classList.add('acessibilidade-daltonico');
        btn.textContent = "Modo Daltonico ♿";
    } else if(modo === 2){
        document.body.classList.add('acessibilidade-cegos');
        btn.textContent = "Modo Leitura ♿";

        // Leitura automática de página
        let textoPagina = document.body.innerText;
        const speech = new SpeechSynthesisUtterance(textoPagina);
        window.speechSynthesis.cancel(); // cancela leitura anterior
        window.speechSynthesis.speak(speech);
    } else {
        btn.textContent = "♿ Acessibilidade";
    }
});
</script>


<div class="card-compra">
    <h2>Finalizar Compra</h2>

    <div>
        <?php foreach ($produtosCarrinho as $p): ?>
            <div class="produto-item">
                <span><?= htmlspecialchars($p['nome']) ?> x <?= $p['qtd'] ?></span>
                <span>R$ <?= number_format($p['preco'] * $p['qtd'],2,',','.') ?></span>
            </div>
        <?php endforeach; ?>
        <div class="total">Total: R$ <?= number_format($totalCarrinho,2,',','.') ?></div>
    </div>

    <form method="post">
        <label for="pagamento">Forma de Pagamento:</label>
        <select name="pagamento" id="pagamento" required>
            <option value="">Selecione...</option>
            <option value="PIX">PIX</option>
            <option value="Cartão de Crédito">Cartão de Crédito</option>
            <option value="Boleto">Boleto</option>
        </select>

        <button type="submit" name="finalizar" class="botao-finalizar">💳 Finalizar Compra</button>
    </form>

    <a href="index.php" class="botao-voltar">← Voltar para a Loja</a>
</div>
</body>
</html>
