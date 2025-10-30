<?php
session_start();

if (!isset($_SESSION['usuario_cargo'])) {
    die("Acesso negado!");
}

if ($_SESSION['usuario_cargo'] != 'admin' && $_SESSION['usuario_cargo'] != 'farmaceutico') {
    die("Acesso negado! Apenas farmacêuticos ou admin podem acessar.");
}

?>

<?php


require_once "C:/Turma1/xampp/htdocs/FarmaAura/app/DB/Database.php";
require_once "C:/Turma1/xampp/htdocs/FarmaAura/app/Controller/FornecedoresController.php";


$fornecedoresController = new FornecedoresController($pdo);

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $fornecedores = $fornecedoresController->deletar($id);
    header('Location: ../Admin/Adm.php');
} else {
    header('Location: ../Admin/Adm.php');
}

?>