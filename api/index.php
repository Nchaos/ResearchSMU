<?php
	$debug = true;
	require 'vendor/autoload.php';
	$app = new \Slim\Slim();
	
	$mysqli = new mysqli("localhost", "root", "toor", "DBGUI");
	if($mysqli->connect_errno)
		die("Connection failed: " . $mysqli->connect_error);
	//==============================================================//
	//							Login								//
	//==============================================================//
	$app->post('/loginUser', function(){
		if ($debug) echo "Logging in...\n";
		session_start();
		global $mysqli;
		$email = $_POST['email'];
		$password = $_POST['password'];
		try {
			//Try to find the email in 'Users' table:
			if ($debug) echo "Looking for email in User's table\n";
			$sql = "SELECT user_ID FROM Users WHERE email=(?)";
			$stmt = $mysqli -> prepare($sql);
			$userId = '';
			$stmt -> bind_param('s', $email);
			$stmt -> execute();
			$stmt -> bind_result($userId);
			$username_test = $stmt -> fetch();
			
			//vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
			if(($username_test === NULL)) {
				//email was not found in the 'Users' table
				die(json_encode(array('ERROR' => 'Could not find user')));
			}//^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
			else{
				if ($debug) echo "Found user, checking if active...\n";
				//email was successfully found in the 'Users' table
				$stmt->close();
				
				//Fetch the status of activation for a user:
				$sql = "SELECT active FROM Users WHERE user_ID='$userId'";
				$stmt1 = $mysqli->prepare($sql);
				$stmt1->execute();
				$active = '';
				$stmt1->bind_result($active);
				$stmt1-fetch();
				$stmt1->close();
				//Check if user's account is deactivated:
				if($active){	
					if ($debug) echo "User is not deactivated, validating password...\n";
					//Fetch the associated password hash for that user from the 'Password' table
					$sql = "SELECT password FROM Password WHERE user_ID='$userId'";
					$stmt1 = $mysqli->prepare($sql);
					$stmt1->execute();
					$passwordVal = '';
					$stmt1->bind_result($passwordVal);
					$stmt1->fetch();
					$stmt1->close();			
					//vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
					if($passwordVal === NULL) {																						
						die(json_encode(array('ERROR' => 'User could not be validated')));											
					}//^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
					/*=================\\
					||	Get User Data  ||
					\\=================*/
					//vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
					else if(password_verify($password,$passwordVal)){
						if ($debug) echo "Password correct, fetching info...\n";
						$components = "SELECT * FROM Users WHERE user_ID='$userId'";
						$returnValue = $mysqli -> query($components);
						$iteration = $returnValue -> fetch_assoc();
						
						$_SESSION['userId'] = $userId;
						$_SESSION['firstName'] = $iteration['fName'];
						$_SESSION['lastName'] = $iteration['lName'];
						$_SESSION['email'] = $iteration['email'];
						
						$checkStudent = $mysqli->query("SELECT TOP 1 user_ID FROM Student WHERE user_ID='$userId'");
						$checkFaculty = $mysqli->query("SELECT TOP 1 user_ID FROM Faculty WHERE user_ID='$userId'");
						$resultStudent = $checkStudent->fetch_assoc();
						$resultFaculty = $checkFaculty->fetch_assoc();
						
						/*====================\\
						||	Get Student Data  ||
						\\====================*/
						//vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
						if($resultStudent !== NULL && $resultFaculty === NULL){
							if ($debug) echo "User is student\n";
							$components = "SELECT * FROM Student WHERE user_ID='$userId'";
							$returnValue = $mysqli->query($components);
							$iteration = $returnValue->fetch_assoc();
							
							$_SESSION['instId'] = $iteration['inst_ID'];
							$_SESSION['deptId'] = $iteration['dept_ID'];
							$_SESSION['grad'] = $iteration['graduateStudent'];
							$_SESSION['check'] = 'Student';
						}//^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
						/*====================\\
						||	Get Faculty Data  ||
						\\====================*/
						//vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
						elseif($resultStudent === NULL && $resultFaculty !== NULL){
							if ($debug) echo "User is faculty\n";
							$components = "SELECT * FROM Faculty WHERE user_ID='$userId'";
							$returnValue = $mysqli->query($components);
							$iteration = $returnValue->fetch_assoc();
							
							$_SESSION['instId'] = $iteration['inst_ID'];
							$_SESSION['deptId'] = $iteration['dept_ID'];
							$_SESSION['check'] = 'Faculty';
						}//^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
						/*===============================================\\
						||	If the user isn't in either the Student or	 ||
						||	  Faculty table, then check the Admin table  ||
						\\===============================================*/
						//vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
						elseif($resultStudent === NULL && $resultFaculty === NULL){
							if ($debug) echo "User is not in student or faculty\n";
							$checkAdmin = $mysqli->query("SELECT TOP 1 user_ID FROM Admin WHERE user_ID='$userId'");
							$result = $checkAdmin->fetch_assoc();
							
							/*==================\\
							||	Get Admin Data  ||
							\\==================*/
							//vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
							if($checkAdmin !== NULL){
								if ($debug) echo "User is an admin\n";
								$components = "SELECT * FROM Student WHERE user_ID='$userId'";
								$returnValue = $mysqli->query($components);
								$iteration = $returnValue->fetch_assoc();
							
								$_SESSION['check'] = 'Admin';
							}//^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
							else
								die(json_encode(array('ERROR' => 'User could not be found outside of Users table')));
						}//^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
						else
							die(json_encode(array('ERROR' => 'User is somehow in both Student and Faculty tables')));
					}//^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
					/*====================\\
					||	Invalid Password  ||
					\\====================*/
					else
						die(json_encode(array('ERROR' => 'Password invalid')));
				}
			}//^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
			
			if ($debug) echo "Incrementing log count\n";
			$logCount = $mysqli->query("UPDATE Users SET loginCount=
				(SELECT loginCount FROM Users WHERE user_ID='$userId')+1 
				WHERE user_ID='$userId'");
			
			if ($debug){
				if($logCount)
					echo "Successfully updated login count!";
				else
					echo "ERROR: could not update login count";
			}
			
			$mysqli = null;
		}catch(exception $e){
			//echo '{"error":{"text":'. $e->getMessage() .'}}';
			die(json_encode(array('ERROR' => $e->getMessage())));
		}
		
		echo json_encode(array('SUCCESS' => 'User logged in.'));
	});
	
	
	
	//==============================================================//
	//							Logout								//
	//==============================================================//
	$app->post('/logout', function() {
		session_start();
		$_SESSION = array();
		if (ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000,
			$params["path"], $params["domain"],
			$params["secure"], $params["httponly"]
			);
		}
		session_destroy();
	});
	
	
	
	//==============================================================//
	//							Register							//
	//==============================================================//
	
	//Still need to add date created:
	//  Get the current time, assign it to a variable,
	//  and insert it with all the user's information.
	
	$app->post('/createAccount', function(){
		global $mysqli, $debug;
		if ($debug) echo "Creating account";
		$check = $_POST['check'];
		$firstName = $_POST['firstName'];
		$lastName = $_POST['lastName'];
		$email = $_POST['email'];
		$password = $_POST['password'];
		$userId = '';
		$date = date("Y-m-d");
		
		if ($debug) echo "Received parameters";
		
		if($firstName === "" || $lastName === "" || $email === "" || $password === "")
			die(json_encode(array('ERROR' => 'Received blank parameters from registration')));
		else{
			if ($debug) echo "Checking user doesn't already exist...\n";
			$dupCheck = $mysqli->query("SELECT email FROM Users WHERE email='$email'");
			$checkResults = $dupCheck->fetch_assoc();
			if(!($checkResults === NULL))
				die(json_encode(array('ERROR' => 'User already exists')));
			else{
				if ($debug) echo "Creating new user...\n";
				
				//Create encrypted hash from password:
				if ($debug) echo "Hashing password...\n";
				$hashedPassword = password_hash($password, PASSWORD_BCRYPT);
				if($debug) echo "Hashed password, now creating user\n";
				$insertUser = $mysqli->query("INSERT INTO Users (fName, lName, email, dateCreated) VALUES ('$firstName', '$lastName', '$email', '$date')");
	
				if ($debug) echo "User created, now fetching id\n";
				$selectUserId = $mysqli->query("SELECT user_ID FROM Users where email='$email'");
				$res = $selectUserId->fetch_assoc();
				$userId = $res['user_ID'];
				echo "$userId\n";
				if ($debug) echo "Got id, now inserting password\n";
				$insertPassword = $mysqli->query("INSERT INTO Password (user_ID, password) VALUES ('$userId', '$hashedPassword')");
				
				if ($debug) echo "User is a... ";
				if($check === "Student"){
					if ($debug) echo "student\n";
					$instId = $_POST['instId'];
					$major = $_POST['deptId'];
					$grad = $_POST['grad'];
					$insertStudent = $mysqli->query("INSERT INTO Student (user_ID, inst_ID, dept_ID, graduateStudent) VALUES ('$userId', '$instId', '$major', '$grad')");
				}
				elseif($check === "Faculty"){
					if ($debug) echo "faculty\n";
					$instId = $_POST['instId'];
					$deptId = $_POST['deptId'];
					$insertFaculty = $mysqli->query("INSERT INTO Faculty (user_ID, inst_ID, dept_ID) VALUES ('$userId', '$instId', '$deptId')");
				}
				else
					die(json_encode(array('ERROR' => 'Is user student or faculty?')));
				
			}
			}
		echo json_encode(array('SUCCESS' => 'Created user!'));
		//if ($debug) echo "Created User!";
	});
	
	
	
	//==============================================================//
	//                      Filter Institution                      //
	//==============================================================//
	$app->post('/filterSchool', function(){//$dept_ID, $inst_ID
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
		
		echo json_encode($result);
	});
	
	//==============================================================//
	//                      Filter Department                       //
	//==============================================================//
	$app->post('/filterDepartment', function(){//$dept_ID, $inst_ID
		$department = $_POST['searchString'];
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $mysqli->connect_error);
		}
		$sql = "SELECT dept_ID FROM department WHERE name = $department";
		if($mysqli->query($sql) === TRUE) {
			$dept_ID = $mysqli->query($sql);
		} else {
			echo "Error creating database: " . $mysqli->error;
		} 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = $dept_ID";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		echo json_encode($result);
	});
	
	//==============================================================//
	//                      Filter Faculty                          //
	//==============================================================//

	function filterFaculty(){
		session_start();
		global $mysqli;
		$search = $_POST['search'];
		$firstlast = explode(" ", $search);

		try{
			$sql1 = "SELECT * FROM ResearchOp natural join (select user_ID from faculty natural join users where fName = $firstlast[0] AND lName = $firstlast[1]) as aggr";
			$search = $mysqli -> prepare($sql);
			$search -> execute();
			$searchres = $search -> fetch();
			$stmt -> close();
		}catch(exception $e){
			echo "Search failed: ";
		}
		echo json_encode($searchres);
	}
	
	
	//==============================================================//
	//                      Position Link                           //
	//==============================================================//	
	function positionLink(){//$dept_ID, $inst_ID
		$buttonName = $_GET['buttonClick'];
		$conn = new mysqli("localhost", "root", "toor", "DBGUI");
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		$sql = "SELECT dept_ID FROM Department WHERE name = $buttonName";
		if($conn->query($sql) === TRUE) {
			$dept_ID = $conn->query($sql);
		} else {
			echo "Error creating database: " . $conn->error;
		} 
		$s = "SELECT name, dateCreated, dateFinished, num_Positions 
				FROM researchOP
				WHERE dept_ID = $dept_ID";
		if($conn->query($s) === TRUE) {
			$result = $conn->query($s);
		} else {
			echo "Error creating database: " . $conn->error;
		}
		$conn->close();
		
		echo json_encode($result);
	}
	
	
	
	//==============================================================//
	//                      Create ResearchOp                       //
	//==============================================================//
	$app->post('/createResearchOpportunity', function(){
		if ($debug) echo "Creating research opportunity...\n";
		global $mysqli;
		$userId = $_SESSION['userId'];
		$instId = $_SESSION['instId'];
		$deptId = $_SESSION['deptId'];
		$check = $_POST['check'];
		$name = $_POST['name'];
		$description = $_POST['desc'];
		$dateStart = $_POST['dateStart'];
		$dateEnd = $_POST['dateEnd'];
		$numPositions = $_POST['numPositions'];
		$paid = $_POST['paid'];
		$workStudy = $_POST['workStudy'];
		$graduate = $_POST['graduate'];
		$undergraduate = $_POST['undergraduate'];
		$todayDate = date("Y-m-d");
		
		if($name === "" || $dateStart === "" || $dateEnd === "" || $numPositions === "")
			die(json_encode(array('ERROR' => 'Received blank parameters from creation page')));
		else{
			if ($debug) echo "Checking for duplicate entry...\n";
			$dupCheck = $mysqli->query("SELECT TOP 1 researchOp_ID FROM ResearchOp WHERE user_ID='$userId' AND name='$name' AND dateStart='$dateStart' AND num_Positions='$numPositions'");
			$checkResults = $dupCheck->fetch_assoc();
			
			if(!($checkResults === NULL))
				die(json_encode(array('ERROR' => 'Research Opportunity already exists')));
			else{
				if ($debug) echo "Creating unique entry\n";
				$insertROP = $mysqli->query("INSERT INTO ResearchOp (user_ID, inst_ID, dept_ID, dateCreated, 
					name, description, startDate, endDate, numPositions, paid, workStudy, acceptsUndergrad, 
					acceptsGrad) 
					VALUES ('$userId', '$instId', '$deptId', '$dateCreated', '$name', '$dateStart', '$dateEnd', 
					'$numPositions', '$paid', '$workStudy', '$graduate', '$undergraduate')");
				die(json_encode(array('Status' => 'Success')));
			}
		}
	});
	
	//==============================================================//
	//                      Search			                        //
	//==============================================================//
	$app->post('/search', function(){
		session_start();
		global $mysqli;
		$search = $_POST['search'];

		try {
			$sql = "SELECT * FROM ResearchOp WHERE dept_ID like ?";
			$stmt = $mysqli -> prepare($sql);
			$stmt -> bind_param('s', $search);
			$stmt -> execute();
			$search_test = $stmt -> fetch();
			$count = 0;
			
			echo sqltojsonarray($array);
			$stmt -> close();
		}catch(exception $e){
			echo "Search failed";
		}	 
	});
	
	//==============================================================//
	//                   AutoComplete		                        //
	//==============================================================//
	//==============================================================//
	//                      Autocomplete  		                    //
	//==============================================================//
	$app->post('/autocomplete', function(){
		global $mysqli;
		
		$term = trim(strip_tags($_GET['term']));//retrieve the search term that autocomplete sends

		$qstring = "SELECT fName FROM Users WHERE fName LIKE '%".$term."%' and userType = 'faculty'";
		$result = mysql_query($qstring);//query the database for entries containing the term

		while ($row = mysql_fetch_array($result,MYSQL_ASSOC))//loop through the retrieved values
		{
				$row['fName']=htmlentities(stripslashes($row['fName']));
				$row_set[] = $row;//build an array
		}

		$qstring = "SELECT lName FROM Users WHERE lName LIKE '%".$term."%' and userType = 'faculty'";
		$result = mysql_query($qstring);//query the database for entries containing the term
		while ($row = mysql_fetch_array($result,MYSQL_ASSOC))//loop through the retrieved values
		{
				$row['lName']=htmlentities(stripslashes($row['fName']));
				$row_set[] = $row;//build an array
		}

		$qstring = "SELECT name FROM Institution WHERE name LIKE '%".$term."%'";
		$result = mysql_query($qstring);//query the database for entries containing the term
		while ($row = mysql_fetch_array($result,MYSQL_ASSOC))//loop through the retrieved values
		{
				$row['name']=htmlentities(stripslashes($row['name']));
				$row_set[] = $row;//build an array
		}

		$qstring = "SELECT name FROM Department WHERE name LIKE '%".$term."%'";
		$result = mysql_query($qstring);//query the database for entries containing the term
		while ($row = mysql_fetch_array($result,MYSQL_ASSOC))//loop through the retrieved values
		{
				$row['name']=htmlentities(stripslashes($row['name']));
				$row_set[] = $row;//build an array
		}
		$output = array_slice($row_set, 0, 10);
		
		echo json_encode($output);//format the array into json data
	});
	//==============================================================//
	//			Collect Information for Application Page			//
	//==============================================================//
	
	$app->get('/appInfo', function(){
			
		$info = array('title' => 'Some Title', 'positionCount' => '3', 'startDate' => 'Yesterday', 'professor' => 'Mr. FuckNo', 'desc' => 'Yada Yada!!!');
		echo json_encode($info);
		 
	});
	
	//==============================================================//
	//                   filters (inst. dept.)                      //
	//==============================================================//
	function filterLyle(){//all OPs in Lyle
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE inst_ID = 05";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
	}

	function filterDedman(){//all OPs in Dedman
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE inst_ID = 01";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
		}
		
	function filterCox(){//all OPs in Cox
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE inst_ID = 02";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
		}
		
	function filterMeadows(){//all OPs in Meadows
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE inst_ID = 03";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
		}
		
	function filterSimmons(){//all OPs in Simmons
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE inst_ID = 04";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
		}
		
	function filterAnthropolgy(){//all OPs in Anthropology
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 03";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
		}
		
	function filterBioScience(){//all OPs in BioScience
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 08";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
		}
		
	function filterChemistry(){//all OPs in Chemistry
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 10";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
		}
		
	function filterEarthScience(){//all OPs in EarthScience
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 18";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
		}
		
	function filterEconomics(){//all OPs in Economics
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 19";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
	}
		
	function filterEnglish(){//all OPs in English
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 21";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);;
		}
		
	function filterHistory(){//all OPs in History
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 25";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
		}
		
	function filterMath(){//all OPs in Math
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 30";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
		}
		
	function filterPhilosophy(){//all OPs in Philosophy
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 33";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
		}
		
	function filterPhysics(){//all OPs in Physics
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 34";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
		}
		
	function filterPoliScience(){//all OPs in PoliScience
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 35";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
		}
		
	function filterPsychology(){//all OPs in Psychology
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 36";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
		}
		
	function filterReligionScience(){//all OPs in ReligionScience
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 38";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
		}
		
	function filterSociology(){//all OPs in Sociology
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 41";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
		}
		
	function filterStatScience(){//all OPs in StatScience
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 42";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
		}
		
	function filterWorldLang(){//all OPs in WorldLang
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 46";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
		}
		
	function filterCivilEnviroEngin(){//all OPs in CivilEnviroEngin
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 11";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
		}
		
	function filterCSCSE(){//all OPs in CSCSE
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 13";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
		}
		
	function filterEE(){//all OPs in EE
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 20";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
		}
		
	function filterManageScience(){//all OPs in ManageScience
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 28";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
		}

	function filterMechEngin(){//all OPs in MechEngin
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 31";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
		}
		
	function filterAccounting(){//all OPs in Accounting
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 01";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
		}
		
	function filterFinance(){//all OPs in Finance
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 23";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
		}
		
	function filterMarketing(){//all OPs in Marketing
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 29";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
		}
		
	function filterManagement(){//all OPs in Management
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 27";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
		}
		
	function filterRealEstate(){//all OPs in RealEstate
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 37";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
		}
		
	function filterRiskManage(){//all OPs in RiskManage
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 39";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
		}
		
	function filterAdvertising(){//all OPs in Advertising
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 02";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
		}
		
	function filterArt(){//all OPs in Art
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 05";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
		}
		
	function filterArtHistory(){//all OPs in ArtHistory
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 06";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
		}
		
	function filterArtManage(){//all OPs in ArtManage
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 07";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
		}
		
	function filterCommunication(){//all OPs in Communications
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 12";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
		}
		
	function filterCreativeComp(){//all OPs in CreativeComp
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 15";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
		}
		
	function filterDance(){//all OPs in Dance
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 16";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
		}
		
	function filterFilmMediaArts(){//all OPs in FilmMedia
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 22";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
		}
		
	function filterJournalism(){//all OPs in Journalism
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 26";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
		}
		
	function filterMusic(){//all OPs in Music
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 32";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
		}
		
	function filterTheatre(){//all OPs in Theatre
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 44";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
	}
		
	function filterAppliedPhys(){//all OPs in AppliedPhys
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 04";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
		}
		
	function filterCounseling(){//all OPs in Counseling
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 14";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
		}
		
	function filterDisputeResolution(){//all OPs in DisputeResolution
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 17";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
		}
		
	function filterHigherEd(){//all OPs in HigherEd
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 23";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
		}
		
	function filterSportsManage(){//all OPs in SportsManage
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 40";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
		}
		
	function filterTeacherEd(){//all OPs in TeacherEd
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 43";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
		}
		
	function filterWellness(){//all OPs in Wellness
			global $mysqli;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 45";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			echo sqltojsonarray($result);
	}
	
	function sqltojsonarray($result){
		global $mysqli;
		$count = 0;
		while ($rows = mysqli_fetch_row($result))
		{
			if($count < 5)
			{
				$array[] = $rows;
			}
			else
			{
				break;
			}
			$count = $count + 1;
		}
		return json_encode($array);
	}
	
	//==============================================================//
	//                   add resume     	                        //
	//==============================================================//
	
	function resumeAdd($form_data){
		global $mysqli;
		$data = addslashes(fread(fopen($form_data, "r"), filesize($form_data)));  
		$result=MYSQL_QUERY("INSERT INTO uploads (description, data,filename,filesize,filetype) ". 
		"VALUES('$form_description','$data','$form_data_name','$form_data_size','$form_data_type')");  
		$id= mysql_insert_id();  
		echo "<p>File ID: <b>$id</b><br>";  
		echo "<p>File Name: <b>$form_data_name</b><br>";  
		echo "<p>File Size: <b>$form_data_size</b><br>";  
		echo "<p>File Type: <b>$form_data_type</b><p>";  
	}

	//==============================================================//
	//                   access resume   	                        //
	//==============================================================//	
		
	function accessResume($ID){
		$query = "SELECT data, filetype FROM uploads where id = $id"; 
		if($mysqli->query($query) === TRUE) {
				$result = $mysqli->query($query);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}		  
		$data = MYSQL_RESULT($result,0,"data");  
		$type = MYSQL_RESULT($result,0,"filetype");  
		Header( "Content-type: $type");  
		echo $data;
	}
	
	//==============================================================//
	//                   delete resume   	                        //
	//==============================================================//
	
	function deleteResume(){
		$query = "DELETE FROM uploads where id=$id";  
		if($mysqli->query($query) === TRUE) {
				$delete = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
		print "File ID $id has been removed from the database"; 
	}
	
	
	//==============================================================//
	//                		   login  		   	                    //
	//==============================================================//
	//trevor messed up the syntax and wasted 40 mintues of my time
	//its k tho. i still love you bud
	
	$app->post('/login', function(){
		global $mysqli;
		//-----------Getting User ID--------------//
		$email_User_Entered = $_POST['email'];
		$password_User_Entered = $_POST['password'];
		$query = "SELECT user_ID FROM Users WHERE email= '$email_User_Entered'";
		$res = $mysqli->query($query);
		$actual_result = $res->fetch_assoc();
		if(($actual_result === NULL))
		{
				//-------Email not found-------//
				die(json_encode(array('ERROR' => 'Could not find user')));
		}
		else
		{
			$user = $actual_result["user_ID"];
			//----------Obtained User ID--------------//
			//----------Getting Password paired with User ID--------------//
			$second_query = "SELECT password FROM Password WHERE user_ID = '$user'";
			$second_res = $mysqli->query($second_query);
			$second_actual_result = $second_res->fetch_assoc();
			if($actual_result === NULL)
			{
					//-------Password not found---------//
					die(json_encode(array('ERROR' => 'Password could not be found')));
			}
			else
			{
					$password_result = $second_actual_result["password"];
					//----------Obtained Password paired with USer ID--------------//
					//----------Verify Password with hash--------------------------//
					if(password_verify($password_User_Entered,$password_result))
					{
						//--------Getting User data--------------//
						$components = "Select * FROM Users WHERE user_ID = 'user'";
						$returnValue = $mysqli -> query($components);
						$iteration = $returnValue -> fetch_assoc();
						
						$_SESSION['userId'] = $userId;
						$_SESSION['firstName'] = $iteration['fName'];
						$_SESSION['lastName'] = $iteration['lName'];
						$_SESSION['email'] = $iteration['email'];
						$_SESSION['userType'] = $iteration['userType'];
						//---------Obtained User Data-------------//
					}
					else
					{
							//--------Wrong password Entered---------//
							die(json_encode(array('ERROR' => 'User could not be validated')));
					}
			}
		}
	});
		
	$app->run();
?>
