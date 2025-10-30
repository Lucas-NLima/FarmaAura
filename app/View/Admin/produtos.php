<link rel="stylesheet" href="../../../css/adm.css">
<?php
session_start();
require_once "../../db/Database.php";
require_once "../../controller/ProdutoController.php";

# Apenas admin pode acessar
if (!isset($_SESSION['usuario_cargo']) || $_SESSION['usuario_cargo'] != 'admin') {
    die("Acesso negado! Apenas administradores podem acessar esta página.");
}

$produtoController = new ProdutoController($pdo);
$produtos = $produtoController->listar();

# Se o formulário foi enviado (adicionar)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adicionar'])) {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $quantidade = $_POST['quantidade'];
    $produtoController->criar($nome, $descricao, $preco, $quantidade);
    header("Location: produtos.php");
    exit;
}

# Se for para excluir
if (isset($_GET['excluir'])) {
    $produtoController->excluir($_GET['excluir']);
    header("Location: produtos.php");
    exit;
}
?>

<h2>Gerenciar Produtos</h2>
<p><a href="Adm.php">← Voltar</a></p>


<canvas id="background"></canvas>
<script>
const canvas = document.getElementById('background');
const ctx = canvas.getContext('2d');
canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

const particles = [];
const numParticles = 40;

for (let i = 0; i < numParticles; i++) {
  particles.push({
    x: Math.random() * canvas.width,
    y: Math.random() * canvas.height,
    r: Math.random() * 6 + 2,
    dx: (Math.random() - 0.5) * 1.2,
    dy: (Math.random() - 0.5) * 1.2,
  });
}

function draw() {
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  for (let p of particles) {
    ctx.beginPath();
    const gradient = ctx.createRadialGradient(p.x, p.y, 0, p.x, p.y, p.r);
    gradient.addColorStop(0, 'rgba(43,182,115,0.8)');
    gradient.addColorStop(1, 'rgba(43,182,115,0)');
    ctx.fillStyle = gradient;
    ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
    ctx.fill();

    p.x += p.dx;
    p.y += p.dy;

    if (p.x < 0 || p.x > canvas.width) p.dx *= -1;
    if (p.y < 0 || p.y > canvas.height) p.dy *= -1;
  }
  requestAnimationFrame(draw);
}

draw();
</script>


