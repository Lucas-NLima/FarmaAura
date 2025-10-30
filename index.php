<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina Inicial</title>
</head>
<body>
   <link rel="stylesheet" href="css/index.css">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
   <header class="navbar">
    <div class="logo">
      <img src="img/Logo FarmaAura.png" alt="Farma Aura">
      
    </div>
    <div class="search-bar">
      <input type="text" placeholder="Buscar na Farma Aura">
    </div>
    <h4>Clique para se cadastrar ou efetuar seu login</h4>
    <div class="menu-icons">
      <a href="app/view/login/login.php">Entrar ou cadastrar</a>
      <a href="#">Meus pedidos</a>
      <a href="#">Cesta (R$0,00)</a>
    </div>
  </header>
   
  </nav>

  <section class="banner">
    <div class="banner-texto">
      <h3>Abasteça sua farmácia💊</h3>
      <p>Com até <strong>25% de desconto</strong> nos seus produtos favoritos.</p>
      <a href="#" class="botao">Confira</a>
    </div>
    <img src="img/carmed.png" alt="Remédios em promoção">
      <main class="conteudo">
  
    <p>Cuide da sua saúde com brilho, amor e bem-estar.</p>
    <a href="logout.php" class="sair">Sair</a>
  </main>
</section>
  <footer class="rodape">
    &copy; 2025 Farma Aura — Cuidando da sua energia e bem-estar 🌼
  </footer>

  



<?php
session_start();

require_once "app/DB/Database.php";
require_once "app/Controller/ProdutoController.php";
require_once "app/Controller/FornecedoresController.php";


$produtoController = new ProdutoController($pdo);
$produtos = $produtoController->listar();

echo "<h1>Bem-vindo, {$_SESSION['usuario_nome']}</h1>";

if ($_SESSION['usuario_cargo'] == 'admin') {
    echo "<a href='app/view/Admin/usuarios.php'>Painel Admin</a><br>";
}

echo "<a href='logout.php'>Sair</a><br>";
?>
</html>