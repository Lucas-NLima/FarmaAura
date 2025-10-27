<?php
require_once "app/db/Database.php";

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $pdo->prepare("SELECT id, verificado FROM usuarios WHERE token_verificacao = ?");
    $stmt->execute([$token]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        if ($usuario['verificado']) {
            echo "<p>Conta já verificada!</p>";
        } else {
            $stmt = $pdo->prepare("UPDATE usuarios SET verificado = 1, token_verificacao = NULL WHERE id = ?");
            $stmt->execute([$usuario['id']]);
            echo "<p>Conta verificada com sucesso! Você já pode fazer login.</p>";
        }
    } else {
        echo "<p>Token inválido.</p>";
    }
} else {
    echo "<p>Token não encontrado.</p>";
}
?>
<p><a href="index.php">Ir para o login</a></p>
