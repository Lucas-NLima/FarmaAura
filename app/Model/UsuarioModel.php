<?php
class UsuarioModel {
    private $pdo;

    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
    }

    public function cadastrar($nome, $email, $senha, $token){
        $sql = "INSERT INTO usuarios (nome,email,senha,token_verificacao) VALUES (:nome,:email,:senha,:token)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':senha' => $senha,
            ':token' => $token
        ]);
    }

    public function buscarPorEmail($email){
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function verificarToken($token){
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE token_verificacao = ?");
        $stmt->execute([$token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function ativarConta($id){
        $stmt = $this->pdo->prepare("UPDATE usuarios SET verificado=1, token_verificacao=NULL WHERE id=?");
        return $stmt->execute([$id]);
    }

    public function listarUsuarios(){
        $stmt = $this->pdo->query("SELECT id,nome,email,cargo,verificado FROM usuarios");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function atualizarCargo($id, $cargo){
        $stmt = $this->pdo->prepare("UPDATE usuarios SET cargo=? WHERE id=?");
        return $stmt->execute([$cargo,$id]);
    }
}
?>
