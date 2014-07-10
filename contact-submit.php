<?php

if (!empty($_POST)) {
	$to = 't045tygh05t@gmail.com';
	$subject = "Project inquiry from {$_POST['name']} <{$_POST['email']}>";
	$body = "Name: {$_POST['name']}\r\nEmail: {$_POST['email']}\r\nCompany: {$_POST['company']}\r\nPhone:{$_POST['phone']}\r\n\Budget:{$_POST['budget']}\r\n\r\nProject Description:{$_POST['description']}";
	$headers = "From: {$_POST['email']}\r\nReply-To:{$_POST['email']}\r\nX-Mailer: PHP/{phpversion()}";
	
	if (mail($to, $subject, $body, $headers)) {
		echo 'accepted';
	}
}