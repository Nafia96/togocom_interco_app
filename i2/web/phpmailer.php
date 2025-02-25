<?php

require '/usr/share/php/libphp-phpmailer/class.phpmailer.php';
require '/usr/share/php/libphp-phpmailer/class.smtp.php';
$mail = new PHPMailer;
$mail->setFrom('kouevistyl@gmail.com');
$mail->addAddress('kouevistyl@gmail.com');
$mail->Subject = 'Message sent by PHPMailer';
$mail->Body('Hello! Use PHPMailer to send email using PHP');
$mail->IsSMTP();
$mail->SMTPSecure = 'tls';
$mail->Host = 'tls://smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->port = 587;

//Set your existing gmail address as user name
$mail->Username = 'kouevistyl@gmail.com';

//Set the password of your gmail address here
$mail->Password = 'HouessouCecile9';
if(!$mail->send()){
	echo 'Email is not sent.';
	echo 'Email error: '.$mail->ErrorInfo; 
}else{
	echo 'Email has been sent.';
}

?>
