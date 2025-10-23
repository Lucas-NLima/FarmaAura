<?php

require_once "C:/Turma1/xampp/htdocs/Farmacia/DB/Database.php";
require_once "C:/Turma1/xampp/htdocs/Farmacia/Controller/ProdutoController.php";


$produtoController = new ProdutoController($pdo);

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $produto = $produtoController->deletar($id);
    header('Location: ../../index.php');
} else {
    header('Location: ../../index.php');
}

?>