function filterSchool(){//$dept_ID, $inst_ID
		$institution = $_POST['searchString'];
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		$sql = "SELECT inst_ID FROM institution WHERE name = $institution";
		if($mysqli->query($sql) === TRUE) {
			$inst_ID = $mysqli->query($sql);
		} else {
			echo "Error creating database: " . $mysqli->error;
		} 
		$s = "SELECT * 
				FROM researchOP
				WHERE inst_ID = $inst_ID";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}