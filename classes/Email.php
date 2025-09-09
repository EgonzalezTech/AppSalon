<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email
{
    public $email;
    public $nombre;
    public $token;

    public function __construct($nombre, $email, $token)
    {
        $this->nombre = $nombre;
        $this->email = $email;
        $this->token = $token;
    }

    public function enviarConfirmacion()
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'sandbox.smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Username = 'f1f9487fbd2419';
            $mail->Password = '0b7d30faf0c103';
            $mail->Port = 2525;
            $mail->SMTPSecure = 'tls';

            $mail->setFrom('no-reply@appsalon.com', 'AppSalon');
            $mail->addAddress($this->email, $this->nombre);

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Confirma tu cuenta';
            $mail->Body = "
                <html>
                <p><strong>Hola {$this->nombre}</strong>, Has creado tu cuenta en AppSalon, solo debes confirmarla presionando el siguiente enlace:</p>
                <p><a href='http://localhost:3000/confirmar-cuenta?token={$this->token}'>Confirmar Cuenta</a></p>
                <p>Si tu no creaste esta cuenta, puedes ignorar este mensaje</p>
                </html>
            ";

            $mail->send();
            echo 'Correo enviado correctamente';
        } catch (\Exception $e) {
            echo "No se pudo enviar el correo. Error: {$mail->ErrorInfo}";
        }
    }
}
