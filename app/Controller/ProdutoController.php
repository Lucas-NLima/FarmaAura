<?php

require_once "C:/Turma1/xampp/htdocs/FarmaAura/Model/ProdutoModel.php";

class ProdutoController {
    private $produtoModel;
    public function __construct($pdo) {
        $this->produtoModel = new ProdutoModel( $pdo);
    }

    public function listar () {
        $produtos = $this ->produtoModel ->buscarTodos();
        include_once "app/view/Produto/listar.php";
        return;
    }

public function buscarProduto($id) {
    $produto = $this->produtoModel-> buscarProduto($id);
    return $produto;
 }


 public function cadastrar($nome, $tipo, $quantidade, $validade, $marca, $preco, $formapagamento) {
    return $this->produtoModel-> cadastrar($nome, $tipo, $quantidade, $validade,$marca, $preco, $formapagamento);
 }


 public function editar($nome, $tipo, $quantidade, $validade, $marca, $preco, $formapagamento, $id) {
    $this->produtoModel-> editar($nome, $tipo, $quantidade, $validade, $marca, $preco, $formapagamento, $id);
 }


public function deletar($id) {
    $produto = $this->produtoModel-> deletar($id);
    return $produto;
 }

}

?>