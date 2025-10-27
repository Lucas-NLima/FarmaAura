<?php
session_start();
require_once "../db/Database.php";
require_once "../model/UsuarioModel.php";

$usuarioModel = new UsuarioModel($pdo);
$msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $usuario = $usuarioModel->buscarPorEmail($email);

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        if (!$usuario['verificado']) {
            $msg = "Conta não verificada! Verifique seu e-mail.";
        } else {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_cargo'] = $usuario['cargo'];
            header("Location: ../../index.php");
            exit;
        }
    } else {
        $msg = "E-mail ou senha incorretos!";
    }
}
?>

<h2>Login</h2>
<?php if($msg) echo "<p>$msg</p>"; ?>
<form method="post">
    <label>Email:</label><br>
    <input type="email" name="email" required><br>
    <label>Senha:</label><br>
    <input type="password" name="senha" required><br><br>
    <button type="submit">Entrar</button>
</form>
<p><a href="registro.php">Cadastrar</a></p>
