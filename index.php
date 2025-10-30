<?php
session_start();



require_once "c:Turma1/xampp/htdocs/FarmaAura/app/db/Database.php";
require_once "c:Turma1/xampp/htdocs/FarmaAura/app/Controller/ProdutoController.php";
require_once "c:Turma1/xampp/htdocs/FarmaAura/app/Controller/FornecedoresController.php";



// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_nome'])) {
    header("Location: app/view/login/login.php");
    exit;
}





?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial</title>

    <link rel="stylesheet" href="css/index.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>

<body>
    <link rel="stylesheet" href="css/index.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <header class="navbar">
        <div class="logo">
            <img src="img/Logo-FarmaAura.png" alt="Farma Aura">

        </div>
        <div class="search-bar">
            <input type="text" placeholder="Buscar na Farma Aura">
        </div>
        <div class="menu-icons">
            <a href="app/view/Admin/Adm.php">Adm</a>
            <a href="logout.php">Sair</a>
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

        </main>
    </section>

       <h2>Nossos Produtos</h2>
    <main class="produtos">
     
       
            <div class="produtos-container">
                <div class="produto-card">
                    <img src="img/creme.png" alt="Creme hidratante">
                    <h3>Creme Hidratante Care</h3>
                    <p class="preco">R$ 89,90</p>
                    <button>Adicionar ao carrinho </button>
                </div>
            </div>
            <div class="produtos-container">
            <div class="produto-card">
                <img src="img/sabonete.png" alt="Sabonete líquido">
                <h3>Sabonete líquido Cetaphil</h3>
                <p class="preco">R$ 79,90</p>
                <button>Adicionar ao carrinho </button>
            </div>
           </div>


            <div class="produto">
                <div class="produto-card">
                    <img src="img/vitamina.png" alt="Vitamina C">
                    <h3>Vitamina C Creamy</h3>
                    <p class="preco">R$ 159,80</p>
                    <button>Adicionar ao carrinho</button>
                </div>
            </div>
    </main>










    <footer class="rodape">
        &copy; 2025 Farma Aura — Cuidando da sua energia e bem-estar 🌼
    </footer>





</html>