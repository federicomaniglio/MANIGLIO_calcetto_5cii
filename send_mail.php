<?php
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

function sendLoginMail($toAddress, $toName) {

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'sandbox.smtp.mailtrap.io'; // o live.smtp.mailtrap.io per stream reali
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['USERNAME']; // username fornito da Mailtrap
        $mail->Password   = $_ENV['PASSWORD']; // password fornita da Mailtrap
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // o 'ssl' su porta 465
        $mail->Port       = 587; // 25, 465, 587 o 2525 sono possibili

        // Mittente e destinatario
        $mail->setFrom('calcetto@noreply.com', 'Calcetto Website');
        $mail->addAddress($toAddress, $toName);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'New access to Calcetto';
        $mail->Body    = "<h1>Hello $toName</h1><p>New access to yuor account.</p>";
        $mail->AltBody = 'New access to yuor account.';

        $mail->send();
    } catch (Exception $e) {
        echo "Mailer Error: {$mail->ErrorInfo}";
    }
}