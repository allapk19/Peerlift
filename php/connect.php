<?php
    $host = 'peerlift.cwp44tiqr9lo.us-east-2.rds.amazonaws.com:3306';
    $user = 'peerl_root';
    $password = 'peerliftDB2017!';
    $db = 'peerlift_db2';

	$conn = mysqli_connect($host, $user, $password, $db);

	/* check connection */
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	} else {
        //echo("Success");
    }
?>
