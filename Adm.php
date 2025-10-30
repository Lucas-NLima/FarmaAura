
<?php
// Se o usuário for admin, mostra o link do painel
if ($_SESSION['usuario_cargo'] == 'admin') {
    echo "<a href='app/view/Admin/usuarios.php'>Painel Admin</a><br>";
}





?>