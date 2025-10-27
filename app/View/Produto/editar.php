
<?php
session_start();

if (!isset($_SESSION['usuario_cargo'])) {
    die("Acesso negado!");
}

if ($_SESSION['usuario_cargo'] != 'admin' && $_SESSION['usuario_cargo'] != 'farmaceutico') {
    die("Acesso negado! Apenas farmacêuticos ou admin podem acessar.");
}

?>


<?php


require_once "C:/Turma1/xampp/htdocs/FarmaAura/app/DB/Database.php";
require_once "C:/Turma1/xampp/htdocs/FarmaAura/app/Controller/ProdutoController.php";


$produtoController = new ProdutoController($pdo);

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $produto = $produtoController->buscarProduto($id);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
</head>
<body>
    
<form method="post">
    <label for="nome">Nome:</label>
    <input type="text" name="nome" value="<?=$produto['nome'];?>" required><br>

    <label for="tipo">Tipo:</label>
    <input type="text" name="tipo" value="<?=$produto['tipo'];?>" required><br>

    <label for="quantidade">Quantidade:</label>
    <input type="number" name="quantidade" value="<?=$produto['quantidade'];?>" required><br>

    <label for="validade">Validade:</label>
    <input type="date" name="validade" value="<?=$produto['validade'];?>" required><br>

    <label for="marca">Marca:</label>
    <input type="text" name="marca" value="<?=$produto['marca'];?>" required><br>

    <label for="preco">Preço:</label>
    <input type="number" name="preco" value="<?=$produto['preco'];?>" required><br>

    <label for="formapagamento">Forma de pagamento:</label>
    <input type="text" name="formapagamento" value="<?=$produto['formapagamento'];?>" required><br>

    <input type="submit">

</form>

</body>
</html>

<?php

} else {
    header('Location: listar.php');
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $nome = $_POST['nome'];
    $tipo = $_POST['tipo'];
    $quantidade = $_POST['quantidade'];
    $validade = $_POST['validade'];
    $marca = $_POST['marca'];
    $preco = $_POST['preco'];
    $formapagamento = $_POST['formapagamento'];

    $produtoController->editar($nome, $tipo, $quantidade, $validade, $marca, $preco, $formapagamento, $id);

    header('Location: ../../../index.php');
}


?>