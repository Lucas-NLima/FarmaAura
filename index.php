<?php

require_once "DB/Database.php";
require_once "Controller/ProdutoController.php";
require_once "Controller/FornecedoresController.php";


$produtoController = new ProdutoController($pdo);
$fornecedoresController = new FornecedoresController($pdo);


$produto = $produtoController->listar();
$fornecedores = $fornecedoresController->listar();


?>