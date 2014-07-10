<?php require 'header.php' ?>

<div id="main">

	<?php
	
	if (!empty($_POST)) {
		require 'validation.php';
		
		?><p><?php
		
		if (validEmail($_POST['email'])) {
			$to = 'toastyghost@live.com';
			$subject = "Project inquiry from {$_POST['name']} <{$_POST['email']}>";
			$body = "Name: {$_POST['name']}\r\nEmail: {$_POST['email']}\r\nCompany: {$_POST['company']}\r\nPhone:{$_POST['phone']}\r\nBudget:{$_POST['budget']}\r\n\r\nProject Description:{$_POST['description']}";
			$headers = "From: {$_POST['email']}\r\nReply-To:{$_POST['email']}\r\nX-Mailer: PHP/{phpversion()}";
			
			if (mail($to, $subject, $body, $headers)) {
				?>
					Your project inquiry was successfully sent.
				<?php
			}
		} else {
			?>
				You did not enter a valid email address.
			<?php
		}
	}
	?>
	
	Redirecting&hellip;</p>
	
	<script type="text/javascript">
		setTimeout(function() {
			window.location.href = 'http://khameleon.org/work';
		}, 5000);
	</script>

</div>

<?php

require 'footer.php';