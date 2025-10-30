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

<h2>Registro</h2>
<form method="post">
    <label>Nome:</label><br>
    <input type="text" name="nome" required><br><br>
    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>
    <label>Senha:</label><br>
    <input type="password" name="senha" required><br><br>
    <button type="submit">Registrar</button>
</form>

<p>Já tem conta? <a href='../login/login.php'>Entre aqui</a></p>
