<?php
	app->post('/createResearchOpportunity', function(){
		global $mysqli;
		$userId = $_SESSION['userId'];
		$instId = $_SESSION['instId'];
		$deptId = $_SESSION['deptId'];
		$check = $_POST['check'];
		$name = $_POST['name'];
		$dateStart = $_POST['dateStart'];
		$dateEnd = $_POST['dateEnd'];
		$numPositions = $_POST['numPositions'];
		$paid = $_POST['paid'];
		$workStudy = $_POST['workStudy'];
		$graduate = $_POST['graduate'];
		$undergraduate = $_POST['undergraduate'];
		
		if($name === "" || $dateStart === "" || $dateEnd === "" || $numPositions === "")
			die(json_encode(array('ERROR' => 'Received blank parameters from creation page')));
		else{
			$dupCheck = $mysqli->query("SELECT TOP 1 researchOp_ID FROM ResearchOp WHERE user_ID='$userId' AND name='$name' AND dateStart='$dateStart' AND dateEnd='$dateEnd' AND num_Positions='$numPositions'");
			$checkResults = $dupCheck->fetch_assoc();
			
			if(!($checkResults === NULL))
				die(json_encode(array('ERROR' => 'Research Opportunity already exists')));
			else{
				$insertROP = $mysqli->query("INSERT INTO ResearchOp (user_ID, inst_ID, dept_ID, name, dateStart, dateEnd, num_Positions, paid, work_study, graduate, undergraduate)
					VALUES ('$userId', '$instId', '$deptId', '$name', '$dateStart', '$dateEnd', '$numPositions', '$paid', '$workStudy', '$graduate', '$undergraduate')");
				die(json_encode(array('Status' => 'Success')));
			}
		}
	});
?>