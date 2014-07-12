<?php

function validEmail($email) {
	$isValid = true;
	$atIndex = strrpos($email, "@");
	if (is_bool($atIndex) && !$atIndex) {
		$isValid = false;
		echo '0';
	} else {
		$domain = substr($email, $atIndex + 1);
		$local = substr($email, 0, $atIndex);
		$localLen = strlen($local);
		$domainLen = strlen($domain);
		
		if ($localLen < 1 || $localLen > 64) {
			// local part length exceeded
			$isValid = false;
			echo 1;
		} else if ($domainLen < 1 || $domainLen > 255) {
			// domain part length exceeded
			$isValid = false;
			echo 2;
		} else if ($local[0] == '.' || $local[$localLen - 1] == '.') {
			// local part starts or ends with '.'
			$isValid = false;
			echo 3;
		} else if (preg_match('/\\.\\./', $local)) {
			// local part has two consecutive dots
			$isValid = false;
			echo 4;
		} else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
			// character not valid in domain part
			$isValid = false;
			echo 5;
		} else if (preg_match('/\\.\\./', $domain)) {
			// domain part has two consecutive dots
			$isValid = false;
			echo 6;
		} else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\", "", $local))) {
			// character not valid in local part unless
			// local part is quoted
			if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\", "", $local))) {
				$isValid = false;
				echo 7;
			}
		}
		if ($isValid && !(checkdnsrr($domain, "MX") || checkdnsrr($domain, "A"))) {
			// domain not found in DNS
			$isValid = false;
		}
	}
	return $isValid;
}