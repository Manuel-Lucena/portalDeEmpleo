<?php
namespace Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailServices
{
    public static function enviarCorreoSolicitudAceptada($alumno, $oferta, $empresa): bool
    {
        try {
            $mail = new PHPMailer(true);

            // Configuración SMTP para MailHog
            $mail->isSMTP();
            $mail->Host       = 'mailhog';
            $mail->SMTPAuth   = false; 
            $mail->SMTPSecure = false;
            $mail->Port       = 1025;

            // Opciones generales
            $mail->setFrom('no-reply@example.com', 'Portal de Empleo');
            $mail->isHTML(true);

            // Destinatario y contenido
            $mail->addAddress($alumno->getEmail(), $alumno->getNombre());
            $mail->Subject = "Tu solicitud ha sido aprobada";
            $mail->Body = "
                ¡Felicidades {$alumno->getNombre()}!<br><br>
                Tu solicitud para la oferta <strong>{$oferta->getTitulo()}</strong> 
                de la empresa <strong>{$empresa->getNombreEmpresa()}</strong> ha sido aprobada.<br><br>
                ¡Suerte en tu proceso!
            ";

            return $mail->send();
        } catch (\Exception $e) {
            error_log("Error enviando correo: " . $e->getMessage());
            return false;
        }
    }
}
