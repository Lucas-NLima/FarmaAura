<?php
require_once "c:/Turma1/xampp/htdocs/FarmaAura/app/DB/Database.php";
require_once "c:/Turma1/xampp/htdocs/FarmaAura/app/controller/RegistroController.php";

$registro = new RegistroController($pdo);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    if ($registro->registrar($nome, $email, $senha)) {
        echo "<p>Conta criada! Verifique seu email para ativá-la.</p>";
    } else {
        echo "<p style='color:red;'>Erro ao registrar. Email já cadastrado?</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
        <link rel="stylesheet" href="../../../css/registro.css">

<form method="post">
    <h2>Crie sua conta:</h2>
    <label>Nome:</label><br>
    <input type="text" name="nome" required><br><br>
    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>
    <label>Senha:</label><br>
    <input type="password" name="senha" required><br><br>

    <button type="submit">Registrar</button>

</form>

</body>
</html>
