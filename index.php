<?php

require_once "app/DB/Database.php";
require_once "app/Controller/ProdutoController.php";
require_once "app/Controller/FornecedoresController.php";


$produtoController = new ProdutoController($pdo);
$fornecedoresController = new FornecedoresController($pdo);


$produto = $produtoController->listar();
$fornecedores = $fornecedoresController->listar();

?>