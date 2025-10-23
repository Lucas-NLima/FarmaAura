<?php

require_once "C:/Turma1/xampp/htdocs/Farmacia/DB/Database.php";
require_once "C:/Turma1/xampp/htdocs/Farmacia/Controller/FornecedoresController.php";


$fornecedoresController = new FornecedoresController($pdo);

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $fornecedores = $fornecedoresController->buscarFornecedores($id);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Fornecedor</title>
</head>
<body>
    
<form method="post">
    <label for="nome">Nome:</label>
    <input type="text" name="nome" value="<?=$fornecedores['nome'];?>" required><br>

    <label for="tipo">Tipo:</label>
    <input type="text" name="tipo" value="<?=$fornecedores['tipo'];?>" required><br>

    <label for="quantidade">Quantidade:</label>
    <input type="number" name="quantidade" value="<?=$fornecedores['quantidade'];?>" required><br>

    <label for="preco">Preço:</label>
    <input type="number" name="preco" value="<?=$fornecedores['preco'];?>" required><br>

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
    $preco = $_POST['preco'];

    $fornecedoresController->editar($nome, $tipo, $quantidade, $preco, $id);

    header('Location: ../../index.php');
}


?>