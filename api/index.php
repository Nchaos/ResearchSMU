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
		global $debug;
		if ($debug) echo "Logging in...\n";
		session_start();
		global $mysqli, $debug;
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
				die(json_encode(array('status' => 'Failure', 'ERROR' => 'Could not find user')));
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
						die(json_encode(array('status' => 'Failure', 'ERROR' => 'User could not be validated')));											
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
								die(json_encode(array('status' => 'Failure', 'ERROR' => 'User could not be found outside of Users table')));
						}//^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
						else
							die(json_encode(array('status' => 'Failure', 'ERROR' => 'User is somehow in both Student and Faculty tables')));
					}//^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
					/*====================\\
					||	Invalid Password  ||
					\\====================*/
					else
						die(json_encode(array('status' => 'Failure', 'ERROR' => 'Password invalid')));
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
			die(json_encode(array('status' => 'Failure', 'ERROR' => $e->getMessage())));
		}
		
		echo json_encode(array('status' => 'Success'));
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
		global $mysqli, $debug;
		$check = $_POST['check'];
		$firstName = $_POST['firstName'];
		$lastName = $_POST['lastName'];
		$email = $_POST['email'];
		$password = $_POST['password'];
		$userId = '';
		
		if ($debug) echo "Received parameters\n";
		
		if($firstName === "" || $lastName === "" || $email === "" || $password === "")
			die(json_encode(array('status' => 'Failure', 'ERROR' => 'Received blank parameters from registration')));
		else{
			if ($debug) echo "Checking user doesn't already exist...\n";
			$dupCheck = $mysqli->query("SELECT email FROM Users WHERE email='$email'");
			$checkResults = $dupCheck->fetch_assoc();
			if(!($checkResults === NULL))
				die(json_encode(array('status' => 'Failure', 'ERROR' => 'User already exists')));
			else{
				if ($debug) echo "Creating new user...\n";
				
				//Create encrypted hash from password:
				if ($debug) echo "Hashing password...\n";
				$hashedPassword = password_hash($password, PASSWORD_BCRYPT);
				if($debug) echo "Hashed password, now creating user\n";
				$insertUser = $mysqli->query("INSERT INTO Users (fName, lName, email) VALUES ('$firstName', '$lastName', '$email')");
	
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
					$deptId = $_POST['deptId'];
					$grad = $_POST['grad'];
					$insertStudent = $mysqli->query("INSERT INTO Student (user_ID, inst_ID, dept_ID, graduateStudent) VALUES ('$userId', '$instId', '$deptId', '$grad')");
				}
				elseif($check === "Faculty"){
					if ($debug) echo "faculty\n";
					$instId = $_POST['instId'];
					$deptId = $_POST['deptId'];
					$insertFaculty = $mysqli->query("INSERT INTO Faculty (user_ID, inst_ID, dept_ID) VALUES ('$userId', '$instId', '$deptId')");
				}
				else
					die(json_encode(array('status' => 'Failure', 'ERROR' => 'Is user student or faculty?')));
			}
		}
		echo json_encode(array('status' => 'Success'));
	});
	
	
	
	//==============================================================//
	//                      Filter Institution                      //
	//==============================================================//
	function filterSchool(){//$dept_ID, $inst_ID
		$institution = $_POST['searchString'];
		global $mysqli, $debug;
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
	
	//==============================================================//
	//                      Filter Department                       //
	//==============================================================//
	function filterDepartment(){//$dept_ID, $inst_ID
		$department = $_POST['searchString'];
		global $mysqli, $debug;
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
		
		return $result;
	}
	
	//==============================================================//
	//                      Filter Faculty                          //
	//==============================================================//

	function filterFaculty(){
		session_start();
		global $mysqli, $debug;
		$search = $_POST['search'];
		$firstlast = explode(" ", $search);

		try{
			$sql1 = "SELECT * FROM ResearchOp natural join (select user_ID from faculty natural join users where fName = $firstlast[0] AND lName = $firstlast[1]) as aggr";
			$search = $mysqli -> prepare($sql);
			$search -> execute();
			$searchres = $search -> fetch();
			$stmt -> close();
		}catch(exception $e){
			return "Search failed";
		}
		return $searchres;
	}
	
	
	//==============================================================//
	//                      Position Link                           //
	//==============================================================//	
	function positionLink(){//$dept_ID, $inst_ID
		global $debug;
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
	$app->post('/createResearchOpportunity', function(){
		if ($debug) echo "Creating research opportunity...\n";
		global $mysqli, $debug;
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
	function search($defsearch){
		global $mysqli

		try {
			$sql = "SELECT * FROM ResearchOp 
			WHERE ResearchOp.dept_ID = (select dept_ID from Department where name = $defsearch) as dID";
			$stmt = $mysqli -> prepare($sql);
			$stmt -> execute();
			$search_test = $stmt -> fetch();
			$stmt -> close();
			return $search_test;
		}
		catch(exception $e) {
			return "Search failed";
		}	 
	});

	
	//==============================================================//
	//                   filters (inst. dept.)                      //
	//==============================================================//
	function filterDedman(){//all OPs in Dedman
			global $mysqli, $debug;
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
			
			return $result;
		}
		
	function filterCox(){//all OPs in Cox
			global $mysqli, $debug;
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
			
			return $result;
		}
		
	function filterMeadows(){//all OPs in Meadows
			global $mysqli, $debug;
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
			
			return $result;
		}
		
	function filterSimmons(){//all OPs in Simmons
			global $mysqli, $debug;
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
			
			return $result;
		}
		
	function filterAnthropolgy(){//all OPs in Anthropology
			global $mysqli, $debug;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 1000";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			return $result;
		}
		
	function filterBioScience(){//all OPs in BioScience
			global $mysqli, $debug;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 1001";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			return $result;
		}
		
	function filterChemistry(){//all OPs in Chemistry
			global $mysqli, $debug;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 1002";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			return $result;
		}
		
	function filterEarthScience(){//all OPs in EarthScience
			global $mysqli, $debug;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 1003";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			return $result;
		}
		
	function filterEconomics(){//all OPs in Economics
			global $mysqli, $debug;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 1004";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			return $result;
	}
		
	function filterEnglish(){//all OPs in English
			global $mysqli, $debug;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 1005";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			return $result;
		}
		
	function filterHistory(){//all OPs in History
			global $mysqli, $debug;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 1006";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			return $result;
		}
		
	function filterMath(){//all OPs in Math
			global $mysqli, $debug;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 1007";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			return $result;
		}
		
	function filterPhilosophy(){//all OPs in Philosophy
			global $mysqli, $debug;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 1008";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			return $result;
		}
		
	function filterPhysics(){//all OPs in Physics
			global $mysqli, $debug;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 1009";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			return $result;
		}
		
	function filterPoliScience(){//all OPs in PoliScience
			global $mysqli, $debug;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 1010";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			return $result;
		}
		
	function filterPsychology(){//all OPs in Psychology
			global $mysqli, $debug;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 1011";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			return $result;
		}
		
	function filterReligionScience(){//all OPs in ReligionScience
			global $mysqli, $debug;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 1012";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			return $result;
		}
		
	function filterSociology(){//all OPs in Sociology
			global $mysqli, $debug;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 1013";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			return $result;
		}
		
	function filterStatScience(){//all OPs in StatScience
			global $mysqli, $debug;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 1014";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			return $result;
		}
		
	function filterWorldLang(){//all OPs in WorldLang
			global $mysqli, $debug;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 1015";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			return $result;
		}
		
	function filterCivilEnviroEngin(){//all OPs in CivilEnviroEngin
			global $mysqli, $debug;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 1016";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			return $result;
		}
		
	function filterCSCSE(){//all OPs in CSCSE
			global $mysqli, $debug;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 1017";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			return $result;
		}
		
	function filterEE(){//all OPs in EE
			global $mysqli, $debug;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 1018";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			return $result;
		}
		
	function filterManageScience(){//all OPs in ManageScience
			global $mysqli, $debug;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 1019";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			return $result;
		}

	function filterMechEngin(){//all OPs in MechEngin
			global $mysqli, $debug;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 1020";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			return $result;
		}
		
	function filterAccounting(){//all OPs in Accounting
			global $mysqli, $debug;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 1021";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			return $result;
		}
		
	function filterFinance(){//all OPs in Finance
			global $mysqli, $debug;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 1022";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			return $result;
		}
		
	function filterMarketing(){//all OPs in Marketing
		global $mysqli, $debug;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1023";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
		
	function filterManagement(){//all OPs in Management
		global $mysqli, $debug;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1024";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
		
	function filterRealEstate(){//all OPs in RealEstate
		global $mysqli, $debug;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1025";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
		
	function filterRiskManage(){//all OPs in RiskManage
		global $mysqli, $debug;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1026";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
		
	function filterAdvertising(){//all OPs in Advertising
		global $mysqli, $debug;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1027";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
		
	function filterArt(){//all OPs in Art
		global $mysqli, $debug;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1028";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
		
	function filterArtHistory(){//all OPs in ArtHistory
		global $mysqli, $debug;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1029";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
		
	function filterArtManage(){//all OPs in ArtManage
		global $mysqli, $debug;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1030";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
	function filterCommunication(){//all OPs in Communications
		global $mysqli, $debug;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1031";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
		
	function filterCreativeComp(){//all OPs in CreativeComp
		global $mysqli, $debug;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1032";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
		
	function filterDance(){//all OPs in Dance
		global $mysqli, $debug;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1033";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
		
	function filterFilmMediaArts(){//all OPs in FilmMedia
		global $mysqli, $debug;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1034";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
		
	function filterJournalism(){//all OPs in Journalism
		global $mysqli, $debug;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1035";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
		
	function filterMusic(){//all OPs in Music
		global $mysqli, $debug;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1036";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
		
	function filterTheatre(){//all OPs in Theatre
			global $mysqli, $debug;
			if ($mysqli->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			 
			$s = "SELECT * 
					FROM researchOP
					WHERE dept_ID = 1037";
			if($mysqli->query($s) === TRUE) {
				$result = $mysqli->query($s);
			} else {
				echo "Error creating database: " . $mysqli->error;
			}
			
			return $result;
	}
		
	function filterAppliedPhys(){//all OPs in AppliedPhys
		global $mysqli, $debug;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1038";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
		
	function filterCounseling(){//all OPs in Counseling
		global $mysqli, $debug;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1039";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
		
	function filterDisputeResolution(){//all OPs in DisputeResolution
		global $mysqli, $debug;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1040";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
		
	function filterHigherEd(){//all OPs in HigherEd
		global $mysqli, $debug;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1041";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
		
	function filterSportsManage(){//all OPs in SportsManage
		global $mysqli, $debug;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1042";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
		
	function filterTeacherEd(){//all OPs in TeacherEd
		global $mysqli, $debug;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1043";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
		
	function filterWellness(){//all OPs in Wellness
		global $mysqli, $debug;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1044";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
	//==============================================================//
	//                    button app			                    //
	//==============================================================//
	
	function application_button_press()
	{
		global $mysqli;
		_s
		$deptID = 1231231
		$sql = "SELECT name,count(name) AS c FROM ResearchOp WHERE dept_ID = $deptID GROUP BY name ORDER BY c DESC LIMIT 3";
		if($mysqli ->query($sql) ===true)
		{
			echo "New record created successfully";
		}
		else
		{
			echo "NO INSERT";
		}
		$conn -> close();
	}
	
	
	
	/*&app->get('/', function() use ($app) {
		echo "Index";
	});*/
	
	$app->run();
?>