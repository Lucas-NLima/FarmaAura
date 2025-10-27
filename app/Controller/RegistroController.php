<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php'; // carrega o PHPMailer
require_once __DIR__ . '/../db/Database.php';

class RegistroController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function registrar($nome, $email, $senha) {
        // Verifica se o e-mail já está cadastrado
        $stmt = $this->pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            return false;
        }

        // Cria hash da senha
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        $token = bin2hex(random_bytes(16)); // token de verificação

        // Insere o usuário no banco
        $stmt = $this->pdo->prepare("INSERT INTO usuarios (nome, email, senha, token_verificacao) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nome, $email, $senhaHash, $token]);

        // Envia e-mail de verificação
        $this->enviarEmailVerificacao($email, $token);

        return true;
    }

    private function enviarEmailVerificacao($email, $token) {
        $mail = new PHPMailer(true);

        try {
            // Configuração do servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;

            // 🟩 É AQUI QUE VOCÊ SUBSTITUI 🟩
          $mail->Username = 'farma.aura01@gmail.com';
          $mail->Password = 'teqvqoxrgvwplirh'; // senha de app gerada no Gmail

            // 🔹 (não é sua senha normal — explico abaixo)

            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Configurações do e-mail   // Quem envia
            $mail->setFrom('farma.aura01@gmail.com', 'Farmacia Digital');

            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Verifique seu cadastro';
            $link = "http://localhost/FarmaAura/verificar.php?token=$token";
            $mail->Body = "<h3>Bem-vindo!</h3><p>Para ativar sua conta, clique no link abaixo:</p><a href='$link'>$link</a>";

            $mail->send();
        } catch (Exception $e) {
            echo "Erro ao enviar e-mail: {$mail->ErrorInfo}";
        }
    }
}
?>
