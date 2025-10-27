<?php
session_start();
require_once "app/db/Database.php";
require_once "app/controller/ProdutoController.php";

$produtoController = new ProdutoController($pdo);
$produtos = $produtoController->listar();

echo "<h1>Bem-vindo, {$_SESSION['usuario_nome']}</h1>";

if ($_SESSION['usuario_cargo'] == 'admin') {
    echo "<a href='app/view/Admin/usuarios.php'>Painel Admin</a><br>";
}

echo "<a href='logout.php'>Sair</a><br>";
