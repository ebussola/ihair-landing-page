<?php

if ( !empty($_POST['subscriber']) ) {

	$rawSubscriber = $_POST['subscriber'];

	if ( filter_var($rawSubscriber, FILTER_VALIDATE_EMAIL) ) {

		$validSubscriber = $rawSubscriber;

		//this is where the creating of the csv takes place
		$cvsData = $validSubscriber . "\n";

		// $fp is now the file pointer to file $filename
		$fp = fopen("subscribers.csv","a"); 

		if($fp){
			fwrite($fp,$cvsData); 
		}

		// Close the file
		fclose($fp); 

		// Replace your email address with the following
		$recipient = "newsletter@smartmap.com";

		// Subject of recieving email
		$subject = "Subscription Confirmation";

		// Send a confirmation email
		mail($validSubscriber, $subject, "Thanks for subscribing to our newsletter.", "From: $recipient \r\n") or die("There is an error in sending email, please try later.");

		// success message
		echo "success";

	} else {
		// error message
		echo "error";
	}

} else {

	// error message
	echo "error";

}