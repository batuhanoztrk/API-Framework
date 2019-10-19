<?php

include __DIR__ . '/PhpMailer/class.phpmailer.php';

class Mailer
{

    private $email;
    private $password;
    private $name;
    private $host = "smtp.gmail.com";
    private $port = 587;
    private $SMTPSecure = "tls";

    public function setMailServer($host, $post, $SMTPSecure)
    {
        $this->host = $host;
        $this->port = $post;
        $this->SMTPSecure = $SMTPSecure;
    }

    public function setMail($email, $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public function sendMail($email, $name, $subject, $content)
    {
        $mail = new PHPMailer();

        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = $this->host;
        $mail->Port = intval($this->port);
        $mail->SMTPSecure = $this->SMTPSecure;
        $mail->Username = $this->email;
        $mail->Password = $this->password;
        $mail->SetFrom($mail->Username, $this->name);
        $mail->AddAddress($email, $name);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $subject;
        $mail->MsgHTML($content);

        if ($mail->Send()) return [1, null];

        return [0, $mail->ErrorInfo];

    }

}