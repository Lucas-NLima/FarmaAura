<?php
session_start();
require_once "c:/Turma1/xampp/htdocs/FarmaAura/app/db/Database.php";
require_once "c:/Turma1/xampp/htdocs/FarmaAura/app/Controller/ProdutoController.php";

// Verifica login
if(!isset($_SESSION['usuario_nome'])){
    header("Location: app/view/login/login.php");
    exit;
}

$produtoController = new ProdutoController($pdo);

if(!isset($_GET['id'])){
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);
$produto = $produtoController->buscarProduto($id);
if(!$produto){
    echo "Produto não encontrado.";
    exit;
}
 // Adicionar ao carrinho
if(isset($_POST['adicionar'])){
    if(!isset($_SESSION['carrinho'][$id])) $_SESSION['carrinho'][$id]=1;
    else $_SESSION['carrinho'][$id]++;

    // Baixa no estoque
    if($produto['quantidade']>0){
        $produtoController->editar(
            $produto['nome'], $produto['tipo'], $produto['quantidade']-1,
            $produto['validade'], $produto['marca'], $produto['preco'],
            $produto['formapagamento'], $produto['id']
        );
    }
    header("Location: index.php"); // Redireciona para a página inicial
    exit;
}


?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($produto['nome']) ?> - Farma Aura</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
/* Fundo animado */
.background-saude {
  position: fixed;
  top:0; left:0;
  width:100%; height:100%;
  z-index:-1;
  background: linear-gradient(-45deg, #e0f7fa, #fff9c4, #c8e6c9, #fff3e0);
  background-size: 400% 400%;
  animation: gradientBG 15s ease infinite;
  overflow: hidden;
}
.background-saude span {
  position: absolute;
  opacity: 0.7;
  font-size: 20px;
  animation: moveParticle 10s linear infinite;
}

/* Animação de gradiente e partículas */
@keyframes gradientBG {
  0% {background-position:0% 50%;}
  50% {background-position:100% 50%;}
  100% {background-position:0% 50%;}
}
@keyframes moveParticle {
  0% {transform: translateY(100vh) translateX(0);}
  100% {transform: translateY(-50px) translateX(100vw);}
}

body {
  font-family: 'Poppins', sans-serif;
  margin:0; padding:0;
  display:flex; justify-content:center; align-items:center;
  min-height:100vh;
}
.container {
  display:flex; flex-wrap:wrap;
  background:#fff; border-radius:20px;
  padding:20px; max-width:900px; width:90%;
  box-shadow:0 4px 15px rgba(0,0,0,0.2);
}
.produto-img {
  flex:1 1 300px;
  text-align:center;
}
.produto-img img {
  max-width:100%; border-radius:15px;
}
.produto-info {
  flex:1 1 300px;
  padding:20px;
}
.produto-info h2 { color:#d3b305; margin-top:0; }
.produto-info p { margin:8px 0; }
.produto-info .preco { color:red; font-weight:600; font-size:1.2rem; }
.botao {
  background-color:#d3b305; color:#fff; border:none;
  padding:12px 25px; border-radius:30px;
  font-weight:600; cursor:pointer; margin-top:15px;
  transition:0.3s;
}
.botao:hover { background-color:#145a40; }
</style>
</head>
<body>

<div class="background-saude" id="bg"></div>
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


<div class="container">
    <div class="produto-img">
        <img src="img/<?= htmlspecialchars($produto['imagem'] ?? 'default.png') ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
    </div>
    <div class="produto-info">
        <h2><?= htmlspecialchars($produto['nome']) ?></h2>
        <p><strong>Marca:</strong> <?= htmlspecialchars($produto['marca']) ?></p>
        <p><strong>Validade:</strong> <?= htmlspecialchars($produto['validade']) ?></p>
        <p><strong>Descrição:</strong> <?= htmlspecialchars($produto['descricao'] ?? 'Sem descrição') ?></p>
        <p class="preco">R$ <?= number_format($produto['preco'],2,',','.') ?></p>
        <form method="post">
            <button type="submit" name="adicionar" class="botao">Adicionar ao Carrinho</button>
        </form>
         <a href="index.php" class="botao" style="display:inline-block; margin-top:10px; text-align:center;">← Voltar</a>
</div>
    </div>
      
</div>


<script>
// Criar partículas flutuantes
const icons = ["❤️","💊","⚕️","🩺","🩹","🩸","🧪","🧬"];
const bg = document.getElementById('bg');
for(let i=0;i<30;i++){
  const span = document.createElement('span');
  span.textContent = icons[Math.floor(Math.random()*icons.length)];
  span.style.left = Math.random()*100+'%';
  span.style.top = Math.random()*100+'%';
  span.style.fontSize = (15+Math.random()*20)+'px';
  span.style.animationDuration = (5+Math.random()*10)+'s';
  bg.appendChild(span);
}
</script>
</body>
</html>
