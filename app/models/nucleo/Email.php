<?php
namespace app\models\nucleo;

require_once('../../../config.php');
require_once '../../../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Email
{
    public static function enviarEmailLog(
        $conteudo,
        $assunto = 'Log-Financeiro'
    ) {
        if (!EMAIL_LOG_ATIVO) {
            return;
        }

        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->Mailer = "smtp";

        $mail->SMTPDebug  = 0;
        $mail->SMTPAuth   = TRUE;
        $mail->SMTPSecure = "tls";
        $mail->Port       = EMAIL_LOG_PORT;
        $mail->Host       = EMAIL_LOG_HOST;
        $mail->Username   = EMAIL_LOG_USER;
        $mail->Password   = EMAIL_LOG_SENHA;

        $mail->IsHTML(true);
        $mail->AddAddress(EMAIL_LOG_PARA);
        $mail->SetFrom(EMAIL_LOG_DE);
        $mail->Subject = $assunto;
        $content = $conteudo;

        $mail->MsgHTML($content);
        $mail->Send();
    }
}
