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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina Inicial</title>
</head>
<body>
    <h1>Bem vindo !!</h1>
    <h2>Farma Aura</h2>
    <h3>“Aura é o brilho invisível que envolve cada um de nós.
Na FarmaAura, queremos manter esse brilho aceso <br> com cuidado, atenção e amor pela sua saúde.”</h3>
<h4>Clique abaixo para se cadastrar ou efetuar seu login</h4>
<input type="button" value="Cadastro" onclick="location.href='app/view/login.php'"/>
<input type="button" value="Login" onclick="location.href='app/view/login.php'"/>
<h4>E para mais informações</h4>
<input type="button" value="Mais Informações" onclick="location.href='maisinfo.php'"/>

</body>
</html>