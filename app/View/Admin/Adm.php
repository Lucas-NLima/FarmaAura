<?php
session_start();

if (!isset($_SESSION['usuario_cargo']) || $_SESSION['usuario_cargo'] != 'admin') {
    die("<h2 style='color:red; text-align:center;'>Acesso negado. Apenas administradores podem acessar esta página.</h2>");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Painel Administrativo - FarmaAura</title>
  <link rel="stylesheet" href="../../../css/adm.css">
</head>
<body>
  <div class="container">
    <h1>💊 Painel Administrativo - FarmaAura</h1>
    <h3>Bem-vindo, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>!</h3>

    <p>Gerencie as seções do sistema:</p>

    <a href="produtos.php">📦 Produtos</a>
    <a href="fornecedores.php">🚚 Fornecedores</a>
    <a href="usuarios.php">👤 Usuários</a>
    <a href="../../../index.php">🏠 Voltar ao site</a>
  </div>

  <canvas id="background"></canvas>
  <script>
    const canvas = document.getElementById('background');
    const ctx = canvas.getContext('2d');
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
    const particles = [];
    const numParticles = 40;
    for (let i = 0; i < numParticles; i++) {
      particles.push({
        x: Math.random() * canvas.width,
        y: Math.random() * canvas.height,
        r: Math.random() * 6 + 2,
        dx: (Math.random() - 0.5) * 1.2,
        dy: (Math.random() - 0.5) * 1.2,
      });
    }
    function draw() {
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      for (let p of particles) {
        ctx.beginPath();
        const gradient = ctx.createRadialGradient(p.x, p.y, 0, p.x, p.y, p.r);
        gradient.addColorStop(0, 'rgba(43,182,115,0.8)');
        gradient.addColorStop(1, 'rgba(43,182,115,0)');
        ctx.fillStyle = gradient;
        ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
        ctx.fill();

        p.x += p.dx;
        p.y += p.dy;

        if (p.x < 0 || p.x > canvas.width) p.dx *= -1;
        if (p.y < 0 || p.y > canvas.height) p.dy *= -1;
      }
      requestAnimationFrame(draw);
    }
    draw();
  </script>
</body>
</html>
