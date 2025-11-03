<?php
class ProdutoModel {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Buscar todos os produtos
    public function buscarTodos() {
        $stmt = $this->pdo->query('SELECT * FROM produtos');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar produto por ID
    public function buscarProduto($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM produtos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Cadastrar produto
    public function cadastrar($tipo, $nome, $quantidade, $validade, $marca, $preco, $imagem = 'default.png', $formapagamento = null) {
        $sql = "INSERT INTO produtos (tipo, nome, quantidade, validade, marca, preco, imagem, formapagamento) 
                VALUES (:tipo, :nome, :quantidade, :validade, :marca, :preco, :imagem, :formapagamento)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':tipo' => $tipo,
            ':nome' => $nome,
            ':quantidade' => $quantidade,
            ':validade' => $validade,
            ':marca' => $marca,
            ':preco' => $preco,
            ':imagem' => $imagem,
            ':formapagamento' => $formapagamento
        ]);
    }

    // Editar produto
  public function editar($nome, $tipo, $quantidade, $validade, $marca, $preco, $formapagamento, $imagem, $id) {
    $sql = "UPDATE produtos 
            SET nome=?, tipo=?, quantidade=?, validade=?, marca=?, preco=?, formapagamento=?, imagem=? 
            WHERE id=?";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([$nome, $tipo, $quantidade, $validade, $marca, $preco, $formapagamento, $imagem, $id]);
}

    

    // Deletar produto
    public function deletar($id) {
        $produto = $this->buscarProduto($id);
        if ($produto && $produto['imagem'] != 'default.png') {
            @unlink("C:/Turma1/xampp/htdocs/FarmaAura/img/" . $produto['imagem']); // remove imagem
        }
        $stmt = $this->pdo->prepare("DELETE FROM produtos WHERE id=?");
        return $stmt->execute([$id]);
    }

    // Buscar produtos por nome
    public function buscar($termo = '') {
        if ($termo) {
            $stmt = $this->pdo->prepare("SELECT * FROM produtos WHERE nome LIKE ?");
            $stmt->execute(["%$termo%"]);
        } else {
            $stmt = $this->pdo->query("SELECT * FROM produtos");
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Reduz quantidade do produto ao comprar
    public function reduzirQuantidade($idProduto, $quantidade = 1) {
        $produto = $this->buscarProduto($idProduto);
        if (!$produto) return false;
        $novaQuantidade = max(0, $produto['quantidade'] - $quantidade);
        $stmt = $this->pdo->prepare("UPDATE produtos SET quantidade=? WHERE id=?");
        return $stmt->execute([$novaQuantidade, $idProduto]);
    }
}
?>
