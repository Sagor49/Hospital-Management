<?php
/**
 * Sends the "reset your password" email via PHPMailer + SMTP.
 * Credentials live in config/mail.php.
 */

require_once __DIR__ . '/../libs/PHPMailer/Exception.php';
require_once __DIR__ . '/../libs/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../libs/PHPMailer/SMTP.php';
require_once __DIR__ . '/../config/mail.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

/**
 * @return array{0: bool, 1: string} [success, errorMessage]
 */
function send_password_reset_email(string $toEmail, string $toName, string $resetLink): array {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USERNAME;
        $mail->Password   = SMTP_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = SMTP_PORT;

        $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $mail->addAddress($toEmail, $toName);
        $mail->addReplyTo(SMTP_FROM_EMAIL, SMTP_FROM_NAME);

        $mail->isHTML(true);
        $mail->Subject = 'Reset your City Care Hospital password';
        $mail->Body = '
            <div style="font-family:Poppins,Arial,sans-serif;max-width:480px;margin:0 auto;color:#16211d;">
                <h2 style="color:#1f4b43;margin-bottom:4px;">Password Reset Request</h2>
                <p>Hi ' . htmlspecialchars($toName) . ',</p>
                <p>We received a request to reset your City Care Hospital account password. Click the button below to choose a new one. This link expires in 1 hour.</p>
                <p style="text-align:center;margin:28px 0;">
                    <a href="' . htmlspecialchars($resetLink) . '" style="background:#1f4b43;color:#ffffff;padding:12px 28px;border-radius:30px;text-decoration:none;font-weight:600;display:inline-block;">Reset Password</a>
                </p>
                <p style="color:#61716b;font-size:0.85rem;">If you did not request this, you can safely ignore this email — your password will stay the same.</p>
                <p style="color:#61716b;font-size:0.8rem;margin-top:24px;">City Care Hospital</p>
            </div>';
        $mail->AltBody = "Reset your City Care Hospital password: {$resetLink}\n(This link expires in 1 hour. If you didn't request this, ignore this email.)";

        $mail->send();
        return [true, ''];
    } catch (PHPMailerException $e) {
        return [false, $mail->ErrorInfo];
    }
}
