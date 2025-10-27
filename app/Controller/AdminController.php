<?php
require_once "app/model/UsuarioModel.php";
require_once __DIR__ . '/../db/Database.php';

class AdminController {
    private $usuarioModel;

    public function __construct($pdo){
        $this->usuarioModel = new UsuarioModel($pdo);
    }

    public function listarUsuarios(){
        return $this->usuarioModel->listarUsuarios();
    }

    public function atualizarCargo($id, $cargo){
        return $this->usuarioModel->atualizarCargo($id, $cargo);
    }

    public function processarPost(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['alterar'])) {
            $id = $_POST['id'];
            $cargo = $_POST['cargo'];
            $this->atualizarCargo($id, $cargo);
            header("Location: ../view/Admin/usuarios.php");
            exit;
        }
    }
}

// Chamar o método processarPost fora da classe
$adminController = new AdminController($pdo);
$adminController->processarPost();
