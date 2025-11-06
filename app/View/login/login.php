<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../../css/login.css">
</head>
<body>
    
<div style="position:fixed; top:20px; left:20px; z-index:9999;">
    <button id="acessibilidadeBtn" class="botao">♿ Acessibilidade</button>
</div>


<?php
session_start();
require_once "c:/Turma1/xampp/htdocs/FarmaAura/app/DB/Database.php";
require_once "c:/Turma1/xampp/htdocs/FarmaAura/app/model/UsuarioModel.php";

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
            header("Location: ../../../index.php");
            exit;
        }
    } else {
        $msg = "E-mail ou senha incorretos!";
    }
    
}
?>
<div class="container">
    <img src="../../../img/Logo-FarmaAura.png" alt="logo" width="150px">

<?php if($msg) echo "<p>$msg</p>"; ?>


<form method="post">
    <label>Email:</label><br>
    <input type="email" name="email" required><br>
    <label>Senha:</label><br>
    <input type="password" name="senha" required><br><br>
    <button type="submit">Entrar</button>
</form>
<p><a href="../registro/registro.php">Cadastrar</a></p>
</div>

</body>
<script>
const btn = document.getElementById('acessibilidadeBtn');
let modo = 0; // 0=normal, 1=daltonico, 2=cegos

btn.addEventListener('click', () => {
    modo++;
    if(modo > 2) modo = 0;

    document.body.classList.remove('acessibilidade-daltonico', 'acessibilidade-cegos');

    if(modo === 1){
        document.body.classList.add('acessibilidade-daltonico');
        btn.textContent = "Modo Daltonico ♿";
    } else if(modo === 2){
        document.body.classList.add('acessibilidade-cegos');
        btn.textContent = "Modo Leitura ♿";

        // Leitura automática de página
        let textoPagina = document.body.innerText;
        const speech = new SpeechSynthesisUtterance(textoPagina);
        window.speechSynthesis.cancel(); // cancela leitura anterior
        window.speechSynthesis.speak(speech);
    } else {
        btn.textContent = "♿ Acessibilidade";
    }
});
</script>
</html>
