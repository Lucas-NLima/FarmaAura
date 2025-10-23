<?php

require_once "C:/Turma1/xampp/htdocs/FarmaAura/app/DB/Database.php";
require_once "C:/Turma1/xampp/htdocs/FarmaAura/app/Controller/FornecedoresController.php";


$fornecedoresController = new FornecedoresController($pdo);

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $fornecedores = $fornecedoresController->deletar($id);
    header('Location: ../../../index.php');
} else {
    header('Location: ../../../index.php');
}

?>