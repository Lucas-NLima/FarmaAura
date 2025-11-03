<?php
session_start();

require_once "c:/Turma1/xampp/htdocs/FarmaAura/app/db/Database.php";
require_once "c:/Turma1/xampp/htdocs/FarmaAura/app/Controller/ProdutoController.php";

// Verifica login
if (!isset($_SESSION['usuario_nome'])) {
    header("Location: app/view/login/login.php");
    exit;
}

$produtoController = new ProdutoController($pdo);

// Busca produtos
$termo = $_GET['busca'] ?? '';
$produtos = $produtoController->buscar($termo);

// Inicializa carrinho
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// Adicionar produto ao carrinho
if (isset($_GET['acao']) && $_GET['acao'] == 'adicionar' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if (!isset($_SESSION['carrinho'][$id])) $_SESSION['carrinho'][$id] = 1;
    else $_SESSION['carrinho'][$id]++;

    // Atualiza estoque
    $produto = $produtoController->buscarProduto($id);
    if ($produto && $produto['quantidade'] > 0) {
        $nova_quantidade = $produto['quantidade'] - 1;

        // Função editar() agora com 9 parâmetros (nome, tipo, quantidade, validade, marca, preco, imagem, id, formapagamento)
        $produtoController->editar(
            $produto['nome'],
            $produto['tipo'],
            $nova_quantidade,
            $produto['validade'],
            $produto['marca'],
            $produto['preco'],
            $produto['imagem'] ?? 'default.png',
            $produto['id'],
            $produto['formapagamento'] ?? null
        );
    }

    $_SESSION['mensagem_sucesso'] = "Produto adicionado ao carrinho com sucesso!";
    header("Location: index.php");
    exit;
}

// Remover produto do carrinho
if (isset($_GET['acao']) && $_GET['acao'] == 'remover' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if (isset($_SESSION['carrinho'][$id])) {
        $_SESSION['carrinho'][$id]--;
        if ($_SESSION['carrinho'][$id] <= 0) unset($_SESSION['carrinho'][$id]);
    }
    header("Location: index.php");
    exit;
}

// Total do carrinho
$totalCarrinho = 0;
foreach ($_SESSION['carrinho'] as $id => $qtd) {
    $produto = $produtoController->buscarProduto($id);
    if ($produto) $totalCarrinho += $produto['preco'] * $qtd;
}

// Mensagem de sucesso
$mensagem_sucesso = $_SESSION['mensagem_sucesso'] ?? '';
unset($_SESSION['mensagem_sucesso']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Farma Aura - Loja</title>
<link rel="stylesheet" href="css/index.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

<style>
/* Carrinho flutuante */
.carrinho {
    position: fixed;
    top: 80px;
    right: 20px;
    background-color: #fff;
    border: 2px solid #eee3a8;
    border-radius: 15px;
    padding: 15px;
    width: 250px;
    max-height: 400px;
    overflow-y: auto;
    z-index: 998;
}
.carrinho h4 { text-align: center; color: #d3b305; margin: 0 0 10px 0; }
.carrinho-item { display: flex; justify-content: space-between; margin-bottom: 5px; align-items: center; }
.carrinho-item button { background-color:#d3b305; color:#fff; border:none; padding:4px 8px; border-radius:8px; cursor:pointer; }
.carrinho-item button:hover { background-color:#145a40; }

/* Botões dos cards */
.botoes-produto button {
    margin: 5px; padding: 8px 12px;
    border-radius: 12px; border:none; cursor:pointer;
    background-color: #d3b305; color:#fff; font-weight:600; transition:0.3s;
}
.botoes-produto button:hover { background-color:#145a40; }

/* Mensagem de sucesso */
.mensagem-sucesso {
    background-color:#d4edda;
    color:#155724;
    border:1px solid #c3e6cb;
    padding:15px;
    border-radius:12px;
    margin:20px auto;
    max-width:500px;
    text-align:center;
    font-weight:600;
}
</style>
</head>
<body>

<?php if ($mensagem_sucesso): ?>
    <div class="mensagem-sucesso" id="mensagem-sucesso">
        <?= htmlspecialchars($mensagem_sucesso) ?>
    </div>
<?php endif; ?>

<header class="navbar">
    <div class="logo">
        <img src="img/Logo-FarmaAura.png" alt="Farma Aura">
    </div>
    <div class="search-bar">
        <form method="get" action="index.php">
            <input type="text" name="busca" placeholder="Buscar produtos..." value="<?= htmlspecialchars($termo) ?>">
            <button type="submit">🔍</button>
        </form>
    </div>
    <div class="menu-icons">
        <?php if ($_SESSION['usuario_cargo'] == 'admin'): ?>
            <a href="app/view/Admin/Adm.php">Adm</a>
        <?php endif; ?>
        <a href="logout.php">Sair</a>
        <a href="finalizar_compra.php">Finalizar compra</a>
        <a href="#">Cesta (R$<?= number_format($totalCarrinho,2,',','.') ?>)</a>
    </div>
</header>

<section class="banner">
    <div class="banner-texto">
        <h3>Abasteça sua farmácia 💊</h3>
        <p>Com até <strong>25% de desconto</strong> nos seus produtos favoritos.</p>
    </div>
    <img src="img/carmed.png" alt="Remédios em promoção">
</section>

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


<!-- Carrinho Flutuante -->
<div class="carrinho">
    <h4>Seu Carrinho</h4>
    <?php if(empty($_SESSION['carrinho'])): ?>
        <p>Carrinho vazio.</p>
    <?php else: ?>
        <?php foreach($_SESSION['carrinho'] as $id => $qtd):
            $produto = $produtoController->buscarProduto($id);
            if(!$produto) continue;
        ?>
        <div class="carrinho-item">
            <span><?= htmlspecialchars($produto['nome']) ?> x <?= $qtd ?></span>
            <button onclick="window.location.href='index.php?acao=remover&id=<?= $id ?>'">❌</button>
        </div>
        <?php endforeach; ?>
        <p><strong>Total: R$ <?= number_format($totalCarrinho,2,',','.') ?></strong></p>
    <?php endif; ?>
</div>

<h2 class="nossosprodutos">Nossos Produtos</h2>

<main class="produtos">
<?php foreach($produtos as $produto): ?>
    <div class="produto-card">
        <img src="img/<?= htmlspecialchars($produto['imagem'] ?? 'default.png') ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
        <h3><?= htmlspecialchars($produto['nome']) ?></h3>
        <p class="preco">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>
        <div class="botoes-produto">
            <button onclick="window.location.href='produtos_detalhes.php?id=<?= $produto['id'] ?>'">Mais Informações</button>
            <button onclick="window.location.href='index.php?acao=adicionar&id=<?= $produto['id'] ?>'">Adicionar ao Carrinho</button>
        </div>
    </div>
<?php endforeach; ?>

</main>

<script>
// Efeito para desaparecer a mensagem de sucesso
window.addEventListener('DOMContentLoaded', () => {
    const msg = document.getElementById('mensagem-sucesso');
    if (msg) {
        setTimeout(() => {
            msg.style.transition = 'opacity 0.5s';
            msg.style.opacity = 0;
            setTimeout(() => msg.remove(), 500);
        }, 4000);
    }
});
</script>

</body>
</html>
