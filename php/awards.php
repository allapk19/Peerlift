<?php
  //this is to test to see if the page is updating.

  class summerP {
    function summerP($nameP, $descriptionP, $linkP, $selectivityP, $locationP, $conferenceP, $descriptionDateP, $metricDateP, $tagsP, $gradesP, $networkP, $filterDate) {
      $this->name = $nameP;
      $this->description = $descriptionP;
      $this->link = $linkP;
      $this->selectivity = $selectivityP;
      $this->location = $locationP;
      $this->conference = $conferenceP;
      $this->descriptionDate = $descriptionDateP;
      $this->metricDate = $metricDateP;
      $this->tags = $tagsP;
      $this->grades = $gradesP;
	  $this->network = $networkP;
	  $this->filterDate = $filterDate;
    }
    public function __toString() {
      $a2 = stripos($this->metricDate,"/");
      $a3 = substr($this->metricDate, 0, $a2);
	  $temp = substr($this->metricDate, $a2);
	  $a4 = stripos($temp,"/");
	  $a5 = substr($temp, 0, $a4);
		
	  $month = date('m');
	  $day = date('d');
	  $difference = $a3 - $month;
	
	  if ($difference == 0) {
		  if ($day > $a5) {
			  $difference = 93;
		  }
	  }
	  if ($difference < 0) {
		  $difference += 12;
	  }
	  if ($difference == 10) {
		  $difference = 91;
	  } else if ($difference == 11) {
		  $difference = 92;
	  }
		
	  $network = 1;
	  $conference = 2;
	  if (strcmp($this->network, 'yes') == 0) {
		  $network = 0;
	  }
	  if (strcmp($this->conference, 'yes') == 0) {
		  $conference = 0;
	  }
		
      return "{$difference}\t{$network}\t{$this->selectivity}\t{$conference}\tname{$this->name}endName\t";
    }
  }
  // create an object
  //$herbie = new summerP('test','pen','hello','hello','hello','hello','hello','hello','hello','hello');

  // show object properties
  //echo $herbie;
  //echo (string)$herbie;

  //connect to the server, if connection is succesful it prints conn, if not then it prints out noconn.
  require 'connect.php';

  //echo "<br><br>";

  $sql = "select *, (DAYOFYEAR(FilterDate) - DAYOFYEAR(CURDATE())) as days from `awards` where MOD(DAYOFYEAR(FilterDate) - DAYOFYEAR(CURDATE()) + 365, 365) order by CASE WHEN days < 0 THEN 1 ELSE 0 END, days";
  $result = $conn->query($sql);

  $oppCount = 0;
  function printData($link, $name, $description, $descriptionDate, $grades, $tags, $selectivity, $metricDate, $timestamp){
		global $oppCount;
	  	$oppCount++;

	    if (strcmp($selectivity, '1') == 0){
          $selectivity = "Slightly Selective";
        }
        else if(strcmp($selectivity, '2') == 0){
          $selectivity = "Somewhat Selective";
        }
        else if(strcmp($selectivity, '3') == 0){
          $selectivity = "Selective";
        }
	    else if(strcmp($selectivity, '4') == 0){
          $selectivity = "Highly Selective";
        }
      
        echo "<div class=\"oppWrapper\">";
        echo "<div class=\"addOpp\">";
            echo "<i class=\"fa fa-list-ul\"></i>";
        echo "</div>";
	  	if ($oppCount % 2 == 0) {
	  		echo "<div class=\"opportunity-alt\">";
		} else {
			echo "<div class=\"opportunity\">";
		}
	  	  echo "<div class=\"container\">";
	  	    echo "<h2 class=\"oppName\">". $name ."</h2>";
            $name = preg_replace('/\s+/', '', $name);
            $name = preg_replace('/\(|\)/','',$name);
            $name = str_replace("'", '', $name);
            $name = preg_replace('/[^a-z\d]+/i', '', $name);
            echo "<i class=\"fa fa-plus-circle addDesktop\" id=\"$name\"></i><br><br>";
	  	    echo "<div class=\"flex-container\">";
	  	      echo "<div class=\"grade\"><h3>". $grades ."</h3></div>";
	  	      echo "<div class=\"selectivity\"><h3>". $selectivity ."</h3></div>";
	  	      echo "<div class=\"deadline\"><h3>". $descriptionDate ."</h3></div>";
              echo "<input type=\"hidden\" class=\"metricDate\" value=\"$metricDate\" />";
		    echo "</div>";
	        echo "<div class=\"info\"><p>". $description ."</p><h3>See More</h3><h3>See Less</h3></div><br>";
            echo "<h2><a class=\"applyLink\" id=\"$name\" href=\"". $link ."\" target=\"_blank\">Apply Now<i class=\"fa fa-chevron-circle-right\"></i></h2></a>";
	      echo "</div>";
	    echo "</div>";
	    echo "</div>";

  }

  function metricFilter($metric, $metricPost){

    if (isset($metricPost)) {
      if ((stripos($metric, $metricPost)!==false)){
        return true;
      }
      else{
        return false;
      }

    }
    else {
      return true;
    }

  }

  if ($result->num_rows > 0) {
    $summerProgramOpArray = array();
    $arr = array("key" => "value");
    $i = 0;
      // output data of each row
      while($row = $result->fetch_assoc()) {
        $timestamp = $row["Timestamp"];
        $name = $row["Name"];
        $description = $row["Description"];
        $link = $row["Link"];
        $location = $row["Location"];
        $descriptionDate = $row["DescriptionDate"];
        $grades = $row["Grade"];
        $tags = $row["Tags"];
        $selectivity = $row["Selectivity"];
        $metricDate = $row["MetricDate"];
        $filterDate = $row["FilterDate"];
		$conference = $row["Conference"];
		$network = $row["Network"];
        $qwerty = new summerP($name, $description, $link, $selectivity, $location, $conference, $descriptionDate, $metricDate, $tags, $grades, $network, $filterDate);
        $qname = $qwerty->name;
        $arr[$qname] = $qwerty;
        array_push($summerProgramOpArray, $qwerty);
        //echo $summerProgramOpArray[$i];
        //echo $arr[$qname]->link;

        $i = $i + 1;

      }

      $summerProgramOpArrayString = array();

      for($a = 0; $a<count($summerProgramOpArray); $a++){
        $proxy = (string)$summerProgramOpArray[$a];
        array_push($summerProgramOpArrayString, $proxy);
        //echo $proxy;
        //echo "<br>";
      }

      for($a = 0; $a<count($summerProgramOpArrayString); $a++){

        $aTestString = (string)$summerProgramOpArrayString[$a];
        $a1 = stripos($aTestString,"name");
        $a2 = stripos($aTestString,"endname");
        $a3 = substr($aTestString, ($a1+4), ($a2-($a1+4)));
        //echo $a3;
        $name = $arr[$a3]->name;
        $timestamp = $arr[$a3]->timestamp;
        $description = $arr[$a3]->description;
        $link = $arr[$a3]->link;
        //$location = $arr[$a3]->location;
        $descriptionDate = $arr[$a3]->descriptionDate;
        $grades = $arr[$a3]->grades;
        $tags = $arr[$a3]->tags;
        $selectivity = $arr[$a3]->selectivity;
        $conference = $arr[$a3]->conference;
        $metricDate = $arr[$a3]->metricDate;
        $filterDate = $arr[$a3]->filterDate;
		$network = $arr[$a3]->network;

          if(isset($_POST['SubmitButton'])){
            //echo (stripos($_POST['grade'], $grades));
            /*if ((stripos($grades,$_POST['grade'])!==false)){
              printData($link, $name, $description, $descriptionDate, $grades, $tags);

            }

            else{
              echo "";
            }*/
            $tT = 0;
            $gT = 0;
            $tT = isset($_POST['tags']);
            $gT = isset($_POST['grade']);

            if($tT == 1 && $gT== 1){

              if (metricFilter($grades,$_POST['grade'])){
                $arraryNew = $_POST['tags'];
                foreach ($arraryNew as $value) {

                  if (metricFilter($tags,$value)){
                    printData($link, $name, $description, $descriptionDate, $grades, $tags, $selectivity, $filterDate, $timestamp);
					break;
                  }
                  else{
                    echo "";
                  }
                }
              }
              else{
                echo "";
              }

            }
            else if($gT== 1 && ($tT != 1)){

              if (metricFilter($grades,$_POST['grade'])){
                printData($link, $name, $description, $descriptionDate, $grades, $tags, $selectivity, $filterDate, $timestamp);
              }
              else{
                echo "";
              }
            }
            else if($gT != 1 && ($tT== 1)){

              $arraryNew = $_POST['tags'];
              foreach ($arraryNew as $value) {

                if (metricFilter($tags,$value)){
                  printData($link, $name, $description, $descriptionDate, $grades, $tags, $selectivity, $filterDate, $timestamp);
			      break;
                }
                else{
                  echo "";
                }
              }
            }
            else{


              printData($link, $name, $description, $descriptionDate, $grades, $tags, $selectivity, $filterDate, $timestamp);
            }

          }
          else{
            printData($link, $name, $description, $descriptionDate, $grades, $tags, $selectivity, $filterDate, $timestamp);
          }


      }

    }
    else {
      echo "0 results";
    }

 ?>