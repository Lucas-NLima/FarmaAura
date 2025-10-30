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

// Instancia os controladores
$produtoController = new ProdutoController($pdo);
$produtos = $produtoController->listar();


// Se o usuário for admin, mostra o link do painel
if ($_SESSION['usuario_cargo'] == 'admin') {
    echo "<a href='app/view/Admin/usuarios.php'>Painel Admin</a><br>";
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
    <header class="navbar">
        <div class="logo">
            <img src="img/Logo FarmaAura.png" alt="Farma Aura">
        </div>

        <div class="search-bar">
            <input type="text" placeholder="Buscar na Farma Aura">
        </div>

        <div class="menu-icons">
            <a href="app/view/registro/registro.php">Entrar ou cadastrar</a>
            <a href="logout.php">Sair</a>
            <a href="#">Meus pedidos</a>
            <a href="#">Cesta (R$0,00)</a>
        </div>
    </header>
    
    <nav class="categorias">
        <a href="#">Mais Buscados</a>
        <a href="#">Serviços e Vacinas</a>
        <a href="#">Manipulação</a>
        <a href="#">Queridinhos</a>
        <a href="#">Cupons</a>
        <a href="#">Perfumes</a>
        <a href="#">Dose Certa</a>
    </nav>

    <section class="banner">
        <div class="banner-texto">
            <h2>Abasteça sua farmacinha 💊</h2>
            <p>Com até <strong>25% de desconto</strong> nos seus produtos favoritos.</p>
            <a href="#" class="botao">Confira</a>
        </div>
        <img src="img/remedios.png" alt="Remédios em promoção">
    </section>

    <main class="conteudo">
        <p>Cuide da sua saúde com brilho, amor e bem-estar.</p>
    </main>

    <footer class="rodape">
        &copy; 2025 Farma Aura — Cuidando da sua energia e bem-estar 🌼
    </footer>
</body>
</html>
