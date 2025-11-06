<?php
require_once "c:/Turma1/xampp/htdocs/FarmaAura/app/DB/Database.php";
require_once "c:/Turma1/xampp/htdocs/FarmaAura/app/controller/RegistroController.php";

$registro = new RegistroController($pdo);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    if ($registro->registrar($nome, $email, $senha)) {
        echo "<p style='color: green; text-align: center; margin-top: 20px;'>Conta criada! Verifique seu email.</p>";

    } else {
        echo "<p style='color:red;  text-align: center;  margin-top: 20px;''>Erro ao registrar. Email já cadastrado?</p>";
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
        <div style="position:fixed; top:20px; left:20px; z-index:9999;">
    <button id="acessibilidadeBtn" class="botao">♿ Acessibilidade</button>
</div>

<div style="position:fixed; top:20px; left:1790px; z-index:9999;">
   <a href="../login/login.php"><button class="botao">Voltar</button></a>
</div>



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
