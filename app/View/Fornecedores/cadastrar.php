<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Fornecedores</title>
</head>
<body>
    <form method="post">
       
        <label for="nome">Nome:</label>
        <input type="text" name="nome" require>

        <label for="tipo">Tipo:</label>
        <input type="text" name="tipo" require>

        <label for="quantidade">Quantidade:</label>
        <input type="text" name="quantidade" require>

        <label for="preco">Preço:</label>
        <input type="text" name="preco" require>

        <input type="submit">
    </form>
</body>
</html>


<?php

require_once "C:/Turma1/xampp/htdocs/FarmaAura/app/DB/Database.php";
require_once "C:/Turma1/xampp/htdocs/FarmaAura/app/Controller/FornecedoresController.php";

$fornecedoresController = new FornecedoresController($pdo);

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    $nome = $_POST ['nome'];
    $tipo = $_POST ['tipo'];
    $quantidade = $_POST ['quantidade'];
    $preco = $_POST ['preco'];
  

    $fornecedoresController->cadastrar($nome, $tipo, $quantidade, $preco);

    header('Location: ../../../index.php');
}


?>