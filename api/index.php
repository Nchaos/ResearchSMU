<?php
	global $debug = true;
	require 'vendor/autoload.php';
	$app = new \Slim\Slim();
	
	$mysqli = new mysqli("localhost", "root", "toor", "DBGUI");
	if($mysql->connect_errno)
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
			$stmt -> bind_param('i', $email);
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
							$_SESSION]'check'] = 'Faculty';
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
								die(json_encode(array('ERROR' => 'User could not be found outside of Users table');
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
	$app->post('/createAccount', function(){
		if ($debug) echo "Creating account";
		global $mysqli;
		$check = $_POST['studentOrFaculty'];
		$firstName = $_POST['firstName'];
		$lastName = $_POST['lastName'];
		$email = $_POST['email'];
		$password = $_POST['password'];
		$userId = '';
		
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
				$hashedPassword = password_hash($password, PASSWORD_BCRYPT);
				$insertUser = $mysqli->query("INSERT INTO Users (fName, lName, email) VALUES ('$firstName', '$lastName', '$email')");
				$userId = $mysqli->query("SELECT user_ID FROM Users where email='$email'");
				$insertPassword = $mysqli->query("INSERT INTO Password (user_ID, password) VALUES ('$userId', '$hashedPassword')");
				
				if ($debug) ehco "User is a... ";
				if($check === "Student"){
					if ($debug) echo "student\n";
					$instId = $_POST['instId'];
					$major = $_POST['major'];
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
		#echo json_encode(array('SUCCESS' => 'Created user!'));
		if ($debug) echo "Created User!";
	});
	
	
	
	//==============================================================//
	//                      Filter School                           //
	//==============================================================//
	function filterSchool(){//$dept_ID, $inst_ID
		$department = $_GET['searchString'];
		$conn = new mysqli("localhost", "root", "toor", "DBGUI");
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		$sql = "SELECT dept_ID FROM Department WHERE name = $department";
		if($conn->query($sql) === TRUE) {
			$dept_ID = $conn->query($sql);
		} else {
			echo "Error creating database: " . $conn->error;
		} 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = $dept_ID";
		if($conn->query($s) === TRUE) {
			$result = $conn->query($s);
		} else {
			echo "Error creating database: " . $conn->error;
		}
		$conn->close();
		
		return $result;
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
		
		return $result;
	}
	
	
	
	//==============================================================//
	//                      Create ResearchOp                       //
	//==============================================================//
	app->post('/createResearchOpportunity', function(){
		if ($debug) echo "Creating research opportunity...\n"
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
			if ($debug) echo "Checking for duplicate entry...\n"
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
?>