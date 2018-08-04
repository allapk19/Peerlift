<?php
	
	require 'connect.php';
	
    $oppId = mysqli_real_escape_string($conn, $_POST['oppId']);
	
	// Attempt insert query execution
	$sql = "SELECT * FROM `opportunityinfo` WHERE oppID='$oppId'";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		while($row = mysqli_fetch_assoc($result)) {
            $oppId = $row["oppID"];
            $grade = $row["grade"];
            $location = $row["location"];
            $date = $row["dates"];
            $metricDate = $row["metricDate"];
            $shortDesc = $row["shortDescription"];
            $longDesc = $row["longDescription"];
            $timeEstimate = $row["timeEstimate"];
            $programFaq = $row["programFaq"];
            $peerFaq = $row["peerFaq"];
            $peerName = $row["peerName"];
            $financialAid = $row["financialAid"];
            $cost = $row["programCost"];
            $link = $row["link"];
            $eligibility = $row["eligibility"];

            $components = [];
            $componentsAdvice = [];
            $componentsNumber = [];

            $sql2 = "SELECT * FROM `sectioninfo` WHERE oppID='$oppId'";
            $result2 = $conn->query($sql2);

            if ($result2->num_rows > 0) {
                while($row2 = mysqli_fetch_assoc($result2)) {
                    if (strcasecmp($row2["sectionType"], "Other") == 0) {
                        array_push($components, $row2["otherName"]);
                    } else {
                        array_push($components, $row2["sectionType"]);
                    }
                    array_push($componentsAdvice, $row2["sectionAdvice"]);
                    array_push($componentsNumber, $row2["sectionNumber"]);
                }
            }

            $sql3 = "SELECT * FROM `summerprograms` WHERE oppID='$oppId'";
            $result3 = $conn->query($sql3);

            if ($result3->num_rows > 0) {
                while($row3 = mysqli_fetch_assoc($result3)) {
                    $opportunityName = $row3["Name"];
                }
            }
		}
	} else{
		echo ("0 results");
	}
	
	mysqli_close($conn);

    echo json_encode(array($opportunityName, $grade, $location, $date, $shortDesc, $longDesc, $programFaq, $peerFaq, $peerName, $financialAid, $cost, $link, $timeEstimate, $components, $componentsAdvice, $componentsNumber, $eligibility, $metricDate));
?>