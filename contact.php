<?php

if (!empty($_POST)) {
	$to = 'toastyghost@live.com';
	$subject = "Project inquiry from {$_POST['name']} <{$_POST['email']}>";
	$body = "Name: {$_POST['name']}\r\nEmail: {$_POST['email']}\r\nCompany: {$_POST['company']}\r\nPhone: {$_POST['phone']}\r\nBudget: {$_POST['budget']}\r\n\r\nProject Description: {$_POST['description']}";
	$headers = "From: info@khameleon.org\r\nReply-To:{$_POST['email']}\r\nX-Mailer: PHP/{phpversion()}";
	
	if (mail($to, $subject, $body, $headers)) {
		echo 1;
	} else {
		echo 0;
	}
}