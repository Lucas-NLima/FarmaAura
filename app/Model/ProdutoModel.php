<?php

class ProdutoModel {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this-> pdo = $pdo;
    }
    public function buscarTodos () {
        $stmt = $this->pdo->query ('SELECT * FROM produtos');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

public function cadastrar($nome, $tipo, $quantidade, $validade, $marca, $preco, $formapagamento){

$sql = "INSERT INTO produtos (nome, tipo, quantidade, validade, marca, preco, formapagamento) VALUES (:nome,:tipo,:quantidade,:validade,:marca,:preco,:formapagamento)";

$stmt = $this->pdo->prepare($sql);
return $stmt->execute( [
    ':nome' => $nome,
    ':tipo'=> $tipo,
    ':quantidade' => $quantidade,
    ':validade' => $validade,
    ':marca' => $marca,
    ':preco' => $preco,
    ':formapagamento' => $formapagamento
]);


}


public function buscarProduto($id){
    $stmt = $this->pdo->query("SELECT * FROM produtos WHERE id = $id");
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


public function editar($nome, $tipo, $quantidade, $validade, $marca, $preco, $formapagamento, $id){
    $sql = "UPDATE produtos SET nome=?,tipo=?,quantidade=?,validade=?,marca=?,preco=?, formapagamento=? WHERE id = ?";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([$nome, $tipo, $quantidade, $validade, $marca, $preco, $formapagamento, $id]);
}


public function deletar($id){

$sql = "DELETE FROM produtos WHERE id = ?";

$stmt = $this->pdo->prepare($sql);
return $stmt->execute( [$id]);
}


}
?>