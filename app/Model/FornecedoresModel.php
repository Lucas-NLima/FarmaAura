<?php

class FornecedoresModel {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this-> pdo = $pdo;
    }
    public function buscarTodos () {
        $stmt = $this->pdo->query ('SELECT * FROM fornecedores');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

public function cadastrar($nome, $tipo, $quantidade, $preco){

$sql = "INSERT INTO fornecedores (nome, tipo, quantidade, preco) VALUES (:nome,:tipo,:quantidade,:preco)";

$stmt = $this->pdo->prepare($sql);
return $stmt->execute( [
    ':nome' => $nome,
    ':tipo'=> $tipo,
    ':quantidade' => $quantidade,
    ':preco' => $preco
]);


}


public function buscarFornecedores($id){
    $stmt = $this->pdo->query("SELECT * FROM fornecedores WHERE id = $id");
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


public function editar($nome, $tipo, $quantidade, $preco, $id){
    $sql = "UPDATE fornecedores SET nome=?,tipo=?,quantidade=?,preco=? WHERE id = ?";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([$nome, $tipo, $quantidade, $preco, $id]);
}


public function deletar($id){

$sql = "DELETE FROM fornecedores WHERE id = ?";

$stmt = $this->pdo->prepare($sql);
return $stmt->execute( [$id]);
}


}
?>