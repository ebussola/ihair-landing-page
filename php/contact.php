<?php

require __DIR__ . '/../vendor/autoload.php';

if ( !empty($_POST['email']) && !empty($_POST['name']) && !empty($_POST['message']) ) {

	// Recieved data from form
	$rawEmail   = trim($_POST['email']);
	$rawName    = trim($_POST['name']);
	$rawMessage = trim($_POST['message']);

	// Validate form data
	if ( filter_var($rawEmail, FILTER_VALIDATE_EMAIL) ) {
        $config = include 'config.php';

        $transport = new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl');
        $transport->setUsername($config['username']);
        $transport->setPassword($config['password']);
        $mailer = new Swift_Mailer($transport);

		$validEmail   = $rawEmail;
		$validName    = filter_var($rawName, FILTER_SANITIZE_STRING);
		$validMessage = filter_var($rawMessage, FILTER_SANITIZE_STRING);

		// Message format
		$html  = "Nome: $validName, \r\n";
		$html .= "Email: $validEmail \r\n";
		$html .= "Mensagem: $validMessage \r\n";

		// Replace your email address with the following
		$recipient = $config['recipient'];

		// Subject of recieving email
		$subject = "iHair Feedback";

		// Send email
		$mailheader = "From: $validEmail \r\n";

        $message = new Swift_Message($subject, $html);
        $message->setTo($recipient['email'], $recipient['name']);
        $message->setFrom($validEmail, $validName);
        $mailer->send($message) || die("There is an error in sending email, please try later.");

		// Send a confirmation email
        $message = new Swift_Message("iHair | feedback recebido", "Recebemos o seu feedback. Muito obrigado por nos ajudar!");
        $message->setTo($validEmail, $validName);
        $message->setFrom($config['from']['email'], $config['from']['name']);
        $mailer->send($message)|| die("There is an error in sending email, please try later.");

        // Success message
		echo json_encode(array(
	  	'status'  => "success", 
	  	'message' => "Obrigado, uma mensagem de confirmação foi enviada para seu email."
	  ));

	} else {
		// Error message
		echo json_encode(array(
	    'status'  => "error", 
	    'message' => "Email inválido."
	  ));
	}
	
} else {
	// Error message
	echo json_encode(array(
    'status'  => "error", 
    'message' => "Por favor, preencha todos os campos."
  ));
}