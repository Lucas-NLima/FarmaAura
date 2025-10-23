<?php

require_once "C:/Turma1/xampp/htdocs/Farmacia/DB/Database.php";
require_once "C:/Turma1/xampp/htdocs/Farmacia/Controller/FornecedoresController.php";


$fornecedoresController = new FornecedoresController($pdo);

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $fornecedores = $fornecedoresController->deletar($id);
    header('Location: ../../index.php');
} else {
    header('Location: ../../index.php');
}

?>