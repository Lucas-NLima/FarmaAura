<?php
session_start();
require_once "C:/Turma1/xampp/htdocs/FarmaAura/app/db/Database.php";
require_once "C:/Turma1/xampp/htdocs/FarmaAura/app/Controller/ProdutoController.php";

$produtoController = new ProdutoController($pdo);

// Inicializa o carrinho se não existir
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// Adicionar produto ao carrinho
if (isset($_GET['acao']) && $_GET['acao'] == 'adicionar' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $produto = $produtoController->buscarProduto($id);

    if ($produto) {
        // Se já existe no carrinho, aumenta a quantidade
        if (isset($_SESSION['carrinho'][$id])) {
            $_SESSION['carrinho'][$id]['quantidade']++;
        } else {
            $_SESSION['carrinho'][$id] = [
                'nome' => $produto['nome'],
                'preco' => $produto['preco'],
                'quantidade' => 1
            ];
        }

        // Reduz quantidade no estoque
        if ($produto['quantidade'] > 0) {
            $produtoController->editar(
                $produto['nome'],
                $produto['tipo'],
                $produto['quantidade'] - 1,
                $produto['validade'],
                $produto['marca'],
                $produto['preco'],
                $produto['formapagamento'],
                $produto['id']
            );
        }
    }
    header("Location: carrinho.php");
    exit;
}

// Remover item do carrinho
if (isset($_GET['acao']) && $_GET['acao'] == 'remover' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if (isset($_SESSION['carrinho'][$id])) {
        $quantidadeCarrinho = $_SESSION['carrinho'][$id]['quantidade'];

        // Reestorna a quantidade para o estoque
        $produto = $produtoController->buscarProduto($id);
        $produtoController->editar(
            $produto['nome'],
            $produto['tipo'],
            $produto['quantidade'] + $quantidadeCarrinho,
            $produto['validade'],
            $produto['marca'],
            $produto['preco'],
            $produto['formapagamento'],
            $produto['id']
        );

        unset($_SESSION['carrinho'][$id]);
    }
    header("Location: carrinho.php");
    exit;
}

// Calcular total
$total = 0;
foreach ($_SESSION['carrinho'] as $item) {
    $total += $item['preco'] * $item['quantidade'];
}
?>
