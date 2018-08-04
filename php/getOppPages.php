<?php
	
	require 'connect.php';

    $oppIds = [];
	
	$sql = "SELECT * FROM `opportunityinfo`";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		while($row = mysqli_fetch_assoc($result)) {
            array_push($oppIds, $row["oppID"]);
		}
	} else{
		echo ("0 results");
	}
	
	mysqli_close($conn);

    echo json_encode(array($oppIds));
?>