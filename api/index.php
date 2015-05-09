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
				$hashedPassword = password_hash($password, PASSWORD_DEFAULT, array('salt'=>'22abgspq1257odb397zndo'));
				if($debug) echo "Hashed password, now creating user\n";
				$insertUser = $mysqli->query("INSERT INTO Users (fName, lName, email, dateCreated, userType) VALUES ('$firstName', '$lastName', '$email', '$date', '$check')");
	
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
		$inst_ID = $_POST['institution'];
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		// $sql = "SELECT inst_ID FROM Institution WHERE name = $institution";
		// if($mysqli->query($sql) === TRUE) {
		// 	$inst_ID = $mysqli->query($sql);
		// } else {
		// 	echo "Error creating database: " . $mysqli->error;
		// } 
		$s = "SELECT * 
				FROM ResearchOp
				WHERE inst_ID = '$inst_ID'";
		if($mysqli->query($s) == TRUE) {
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
		$dept_ID = $_POST['department'];
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $mysqli->connect_error);
		}
		// $sql = "SELECT dept_ID FROM Department WHERE name = $department";
		// if($mysqli->query($sql) === TRUE) {
		// 	$dept_ID = $mysqli->query($sql);
		// } else {
		// 	echo "Error creating database: " . $mysqli->error;
		// } 
		$s = "SELECT * 
				FROM ResearchOp
				WHERE dept_ID = '$dept_ID'";
		if($mysqli->query($s) == TRUE) {
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
		//if ($debug) echo "Creating research opportunity...\n";
		global $mysqli;
		$userId = $_SESSION['userId'];
		//$instId = $_SESSION['instId'];
		$deptId = $_POST['deptId'];
		//$check = $_POST['check'];
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
		
		//get instId
		$sql5 = "SELECT inst_ID from Department WHERE dept_ID = '$deptId'";
		$stmt = $mysqli->prepare($sql5);
		$stmt->execute();
		$stmt->bind_result($instId);
		$stmt->fetch();
		$stmt->close();
		
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
					VALUES ('$userId', '$instId', '$deptId', '$dateCreated', '$name', '$desc','$dateStart', '$dateEnd', 
					'$numPositions', '$paid', '$workStudy', '$undergraduate', '$graduate')");
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
	$app->post('/login', function(){
		global $mysqli;
		session_start();
		//-----------Getting User ID--------------//
		$email_User_Entered = $_POST['email'];
		$password_User_Entered = $_POST['password'];
		$query = "SELECT user_ID FROM Users WHERE email= '$email_User_Entered'";
		$res = $mysqli->query($query);
		$actual_result = $res->fetch_assoc();
		if(($actual_result === NULL))
		{
				//-------Email not found-------//
				echo json_encode(array("success"=> false ,'message' => 'Could not find user'));
		}
		else
		{
			$user = $actual_result['user_ID'];
			//----------Obtained User ID--------------//
			//----------Getting Password paired with User ID--------------//
			$second_query = "SELECT password FROM Password WHERE user_ID = '$user'";
			$second_res = $mysqli->query($second_query);
			$second_actual_result = $second_res->fetch_assoc();
			if($actual_result === NULL)
			{
					//-------Password not found---------//
					echo json_encode(array("success"=> false ,'message' => 'Password could not be found'));
			}
			else
			{
					$password_result = $second_actual_result['password'];
					$hash_password = password_hash($password_User_Entered, PASSWORD_DEFAULT, array('salt'=>'22abgspq1257odb397zndo'));
					//----------Obtained Password paired with USer ID--------------//
					//----------Verify Password with hash--------------------------//
					if($hash_password == $password_result)
					{
						
						
						//--------Getting User data--------------//
						$components = "Select * FROM Users WHERE user_ID = '$user'";
						$returnValue = $mysqli -> query($components);
						$iteration = $returnValue -> fetch_assoc();
						
						$_SESSION['userId'] = $user;
						$_SESSION['firstName'] = $iteration['fName'];
						$_SESSION['lastName'] = $iteration['lName'];
						$_SESSION['email'] = $iteration['email'];
						$_SESSION['userType'] = $iteration['userType'];
						//---------Obtained User Data-------------//
						echo json_encode(array("success"=> true ,'message' => 'User validated'));
						
					}
					else
					{
							//--------Wrong password Entered---------//
							echo json_encode(array("success"=> false ,'message' => $hash_password));
					}
			}
		}
	});

	
	
	//==============================================================//
	//                		   logout  		   	                    //
	//==============================================================//	
	$app->post('/logout', function(){
		// Initialize the session.
		// If you are using session_name("something"), don't forget it now!
		session_start();

		// Unset all of the session variables.
		$_SESSION = array();	
		

		// If it's desired to kill the session, also delete the session cookie.
		// Note: This will destroy the session, and not just the session data!
		if (ini_get('session.use_cookies')) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000,
			$params['path'], $params['domain'],
			$params['secure'], $params['httponly']);
		}

		// Finally, destroy the session.
		session_destroy();
	});
	
	
	//==============================================================//
	//                		   Userinfo		   	                    //
	//==============================================================//	
	$app->post('/userinfo', function()
	{
		global $mysqli, $debug;
		session_start();
		$userId = $_SESSION['userId'];
		$firstName = $_SESSION['firstName'];
		$lastName = $_SESSION['lastName'];
		$email = $_SESSION['email'];
		$userType = $_SESSION['userType'];
		$query = "SELECT password FROM Password WHERE user_ID = '$userId'";
		$res = $mysqli->query($query);
		
		$userId = $_SESSION['userId'];
		$check = $_SESSION['userType'];
		
		if($check == 'Student') {
			$sql1 = "SELECT (CASE WHEN Student.graduateStudent = 1 THEN 'Graduate' ELSE 'Undergraduate' END) as paidval
					FROM Student WHERE user_ID='$userId'";
			$stmt = $mysqli->prepare($sql1);
			$stmt->execute();
			$stmt->bind_result($studentType);
			$stmt->fetch();
			$stmt->close();
	
			$sql = "SELECT name FROM Department WHERE dept_ID = (SELECT dept_ID FROM Student WHERE user_ID = '$userId')";
			$stmt = $mysqli->prepare($sql);
			$stmt->execute();
			$stmt->bind_result($active);
			$stmt->fetch();
			$stmt->close();
		
			$department = $active;
			
		} elseif($check == 'Faculty') {
			$studentType = 'Faculty';
			$department = 'Computer Science and Engineering';

			
			$sql = "SELECT name FROM Department WHERE dept_ID = (SELECT dept_ID FROM Faculty WHERE user_ID = '$userId')";
			$stmt = $mysqli->prepare($sql);
			$stmt->execute();
			$stmt->bind_result($active);
			$stmt->fetch();
			$stmt->close();
		
			$department = $active;
		}else
		{
			$studentType = 'Neither';
			$department = 'May God have mercy on your soul';
		}	
		// echo $userId;
		// echo $firstName;
		// echo $lastName;
		// echo $emaill
		// echo $studentType;
		// echo $department;
		$password = $res->fetch_assoc();
		$info = array('userId'=> $userId, 'firstName' => $firstName, 'lastName'=>$lastName,'email'=>$email,
				'studentType'=>$studentType,'department'=>$department);
		echo json_encode($info);
		return  json_encode($info);

	});

	
	
	//==============================================================//
	//                		   Change Info 	   	                    //
	//==============================================================//	
	$app->post('/changeinfo', function(){
		global $mysqli;
		session_start();
		$userid = $_SESSION['userId'];
		//change pwd, dept, major, institution
		if(isset($_POST['department']))
		{
			$dept = $_POST['department'];
			
			$sql = "UPDATE Student SET dept_ID = (Select dept_ID from Department where name = '$dept')";
			$stmt = $mysqli -> query($sql);
		}
		if(isset($_POST['institution']))
		{
			$inst = $_POST['institution'];
			
			$sql = "UPDATE Student SET inst_ID = (Select inst_ID from Institution where name = '$inst')";
			$stmt = $mysqli -> query($sql);

		}
		if(isset($_POST['gradstatus']))
		{
			$grad = $_POST['gradstatus'];
			
			$sql = "UPDATE Student SET graduateStudent = '$grad')";
			$stmt = $mysqli -> query($sql);
		}
		if(isset($_POST['lname']))
		{
			$lname = $_POST['lname'];
			
			$sql = "Update Users SET lName = '$lname' WHERE user_ID = '$userid'";
			$stmt = $mysqli -> query($sql);

		}
	});
	
	
	
	//==============================================================//
	//                		   Check Session  	                    //
	//==============================================================//	
	$app->get('/sessionStatus', function() {
		
		//session_start();
		session_start();
		$answer = false;
		
		if(isset($_SESSION['userId']))
		{
			$answer = true;
		}
		else
		{
			$answer = false;
			session_destroy();
		}
		echo $answer;
		return json_encode($answer);
	});
	
	
	//==============================================================//
	//                		   Apply		  	                    //
	//==============================================================//	
	$app->post('/apply', function() {
		global $mysqli;
		session_start();
		$user = $_SESSION['userId'];
		echo $user;
		$op = $_POST['opID'];
		echo $op;
		$date = date("Y-m-d");
		echo $date;
		$sql = "Insert into Applicants(researchOp_ID, user_ID, status, dateSubmitted) values ('$op', '$user', 'Pending', '$date')";
		//echo $sql;
		$success = $mysqli -> query($sql);		
	});
	
	//==============================================================//
	//                	Update Password		  	                    //
	//==============================================================//	
	
	$app->post('/updatePassword', function(){
		global $mysqli;
		session_start();
		//-----------Getting User ID--------------//
		$userID = $_SESSION['userId'];
		//-----------Getting User Entered Old Password
		$old_password = $_POST['oldpassword'];

			//----------Getting Password paired with User ID--------------//
			$password_query = "SELECT password FROM Password WHERE user_ID = '$userID'";
			$query_res = $mysqli->query($password_query);
			$database_password = $query_res->fetch_assoc();
			if($database_password === NULL)
			{
					//-------Password not found---------//
					echo json_encode(array("success"=> false ,'message' => 'Password could not be found'));
			}
			else
			{
					$password = $database_password['password'];
					$hash_password = password_hash($old_password, PASSWORD_DEFAULT, array('salt'=>'22abgspq1257odb397zndo'));
					//----------Obtained Password paired with USer ID--------------//
					//----------Verify Password with hash--------------------------//
					if($hash_password == $password)
					{
						$new_password = $_POST['password'];
						$hash_new_password = password_hash($new_password, PASSWORD_DEFAULT, array('salt'=>'22abgspq1257odb397zndo'));
						$sql = "UPDATE Password SET password = '$hash_new_password' WHERE user_ID = '$userID'";
						$stmt = $mysqli -> query($sql);
						echo "password updated";
					}
					else
					{
							//--------Wrong password Entered---------//
							echo json_encode(array("success"=> false ,'message' => $hash_password));
					}
			}
	
	});

	
	//==============================================================//
	//                	deactivate user		  	                    //
	//==============================================================//
	
	$app->post('/apply', function() {
		global $mysqli;
		session_start();
		$date = date("Y-m-d");
		$userId = $_SESSION['UserId'];
		$sql = "UPDATE Users SET active = '0' WHERE user_ID = '$userId')";
		$stmt = $mysqli -> query($sql);
		$sql = "UPDATE Users SET dateDeactivated = '$date' WHERE user_ID = '$userId')";
		$stmt2 = $mysqli -> query($sql);
		
		echo 'Account disabled';
	});



	//==============================================================//
	//                	Change Lname		  	                    //
	//==============================================================//	
	$app->post('/changeLname', function(){
		global $mysqli;
		session_start();
		
		$lname = $_POST['lname'];
		$userId = $_SESSION['userId'];
		
		$sql = "Update Users SET lName = '$lname' WHERE user_ID = '$userId'";
		$stmt = $mysqli -> query($sql);
	});
	
	//==============================================================//
	//                	applied find		  	                    //
	//==============================================================//	
	$app->post('/changeLname', function(){
		global $mysqli;
		session_start();
		
		$userId = $_SESSION['userId'];
		$userType = $_SESSION['userType'];
		
		if($userType == 'Faculty')
		{
			$sql = "SELECT * FROM ResearchOp WHERE user_ID = '$userId'";
		}
		else
		{
			$sql = "SELECT * FROM ResearchOp WHERE researchOp_ID = (SELECT researchOp_ID FROM Applicants WHERE user_ID = '$userId')";
		}
		
		$stmt = $mysqli -> query($sql);
		
		echo json_encode($stmt);
	});

	$app->run();
?>
