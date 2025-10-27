<?php
require_once "app/model/UsuarioModel.php";

class LoginController {
    private $usuarioModel;

    public function __construct($pdo){
        $this->usuarioModel = new UsuarioModel($pdo);
    }

    public function autenticar($email,$senha){
        $usuario = $this->usuarioModel->buscarPorEmail($email);

        if(!$usuario){
            return "Usuário não encontrado!";
        }

        if($usuario['verificado']==0){
            return "Verifique seu e-mail antes de entrar.";
        }

        if(password_verify($senha,$usuario['senha'])){
            session_start();
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_cargo'] = $usuario['cargo'];
            return true;
        }
        return "Senha incorreta!";
    }

    public function logout(){
        session_start();
        session_destroy();
    }
}
?>
