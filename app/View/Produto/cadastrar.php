<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Produtos</title>
</head>
<body>
    <form method="post">
       
        <label for="nome">Nome:</label>
        <input type="text" name="nome" require>

        <label for="tipo">Tipo:</label>
        <input type="text" name="tipo" require>

        <label for="quantidade">Quantidade:</label>
        <input type="number" name="quantidade" require>

        <label for="validade">Validade:</label>
        <input type="date" name="validade" require>

        <label for="marca">Marca:</label>
        <input type="text" name="marca" require>

        <label for="preco">Preço:</label>
        <input type="number" name="preco" require>

        <label for="formapagamento">Forma de pagamento:</label>
        <input type="text" name="formapagamento" require>

        <input type="submit">
    </form>
</body>
</html>


<?php

require_once "C:/Turma1/xampp/htdocs/FarmaAura/app/DB/Database.php";
require_once "C:/Turma1/xampp/htdocs/FarmaAura/app/Controller/ProdutoController.php";

$produtoController = new ProdutoController($pdo);

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    $nome = $_POST ['nome'];
    $tipo = $_POST ['tipo'];
    $quantidade = $_POST ['quantidade'];
    $validade = $_POST ['validade'];
    $marca = $_POST ['marca'];
    $preco = $_POST ['preco'];
    $formapagamento = $_POST ['formapagamento'];
  

    $produtoController->cadastrar($nome, $tipo, $quantidade, $validade, $marca, $preco, $formapagamento);

    header('Location: ../../../index.php');
}


?>