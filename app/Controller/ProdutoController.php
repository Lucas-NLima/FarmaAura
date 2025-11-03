<?php
require_once "C:/Turma1/xampp/htdocs/FarmaAura/app/Model/ProdutoModel.php";

class ProdutoController {
    private $produtoModel;

    public function __construct($pdo) {
        $this->produtoModel = new ProdutoModel($pdo);
    }

    // Lista todos os produtos
    public function listar() {
        return $this->produtoModel->buscarTodos();
    }

    // Busca produto por ID
    public function buscarProduto($id) {
        return $this->produtoModel->buscarProduto($id);
    }

    // Cadastrar novo produto
    public function cadastrar($tipo, $nome, $quantidade, $validade, $marca, $preco, $imagem = 'default.png', $formapagamento = null) {
        return $this->produtoModel->cadastrar($tipo, $nome, $quantidade, $validade, $marca, $preco, $imagem, $formapagamento);
    }

    // Editar produto existente
    public function editar($nome, $tipo, $quantidade, $validade, $marca, $preco, $formapagamento, $imagem, $id) {
    $this->produtoModel->editar($nome, $tipo, $quantidade, $validade, $marca, $preco, $formapagamento, $imagem, $id);
}


    // Deletar produto
    public function deletar($id) {
        return $this->produtoModel->deletar($id);
    }

    // Buscar produtos por nome
    public function buscar($termo = '') {
        return $this->produtoModel->buscar($termo);
    }

    // Reduz quantidade do produto no estoque
    public function reduzirQuantidade($idProduto, $quantidade = 1) {
        return $this->produtoModel->reduzirQuantidade($idProduto, $quantidade);
    }
}
?>
