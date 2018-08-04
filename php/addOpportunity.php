<?php
	
	require 'connect.php';
	
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $details = mysqli_real_escape_string($conn, $_POST['details']);
	$email = mysqli_real_escape_string($conn, $_POST['email']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
	
	// Attempt insert query execution
	$sql = "INSERT INTO suggestedopportunities (name, email, details, type) VALUES ('$name', '$email', '$details', '$type')";
	if(mysqli_query($conn, $sql)){
		echo(mysqli_insert_id($conn));
	} else{
		echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
	}
	
	mysqli_close($conn);


  /*$details = wordwrap($details, 70, "\r\n");
  $to = 'opportunities@peerlift.org';
  $subject = "New Opportunity";
  $body = "From: " . $name . "\r\n" .
  "Email: " . $email . "\r\n" .
  "Message: \r\n" . $details;
  echo($body);
  mail($to, $subject, $body);*/
?>
