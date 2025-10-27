<?php

require_once "C:/Turma1/xampp/htdocs/FarmaAura/app/Model/FornecedoresModel.php";

class FornecedoresController {
    private $fornecedoresModel;
    public function __construct($pdo) {
        $this->fornecedoresModel = new FornecedoresModel( $pdo);
    }

    public function listar () {
        $fornecedores = $this ->fornecedoresModel ->buscarTodos();
        include_once "C:/Turma1/xampp/htdocs/FarmaAura/app/View/Fornecedores/listar.php";
        return;
    }

public function buscarFornecedores($id) {
    $fornecedores = $this->fornecedoresModel-> buscarFornecedores($id);
    return $fornecedores;
 }


 public function cadastrar($nome, $tipo, $quantidade, $preco) {
    return $this->fornecedoresModel-> cadastrar($nome, $tipo, $quantidade, $preco);
 }


 public function editar($nome, $tipo, $quantidade, $preco, $id) {
    $this->fornecedoresModel-> editar($nome, $tipo, $quantidade,  $preco, $id);
 }


public function deletar($id) {
    $fornecedores = $this->fornecedoresModel-> deletar($id);
    return $fornecedores;
 }

}

?>