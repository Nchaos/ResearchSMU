<?php
global $debug = true;

//==============================================================//
//						Password Stuff							//
//==============================================================//
define("PBKDF2_HASH_ALGORITHM", "sha256");
define("PBKDF2_ITERATIONS", 1500);
define("PBKDF2_SALT_BYTE_SIZE", 10);
define("PBKDF2_HASH_BYTE_SIZE", 60);
define("HASH_SECTIONS", 4);
define("HASH_ALGORITHM_INDEX", 0);
define("HASH_ITERATION_INDEX", 1);
define("HASH_SALT_INDEX", 2);
define("HASH_PBKDF2_INDEX", 3);

function create_hash($password, $salt)
{
	if ($debug)
		echo "Hashing password";
	
	global $mysqli;
	return PBKDF2_HASH_ALGORITHM . ":" . PBKDF2_ITERATIONS . ":" . $salt . ":" .
	base64_encode(pbkdf2(
		PBKDF2_HASH_ALGORITHM,
		$password,
		$salt,
		PBKDF2_ITERATIONS,
		PBKDF2_HASH_BYTE_SIZE,
		true
	));
}

function validate_password($password, $correct_hash){
	if($debug)
		echo "Validating Password";
	$params = explode(":", $correct_hash);
	if(count($params) < HASH_SECTIONS)
		return false;
	$pbkdf2 = base64_decode($params[HASH_PBKDF2_INDEX]);
	return slow_equals(
		$pbkdf2,
		pbkdf2(
			$params[HASH_ALGORITHM_INDEX],
			$password,
			$params[HASH_SALT_INDEX],
			(int)$params[HASH_ITERATION_INDEX],
			strlen($pbkdf2),
			true
		)
	);}

function slow_equals($a, $b){
	$diff = strlen($a) ^ strlen($b);
	for($i = 0; $i < strlen($a) && $i < strlen($b); $i++){
	$diff |= ord($a[$i]) ^ ord($b[$i]);}
	return $diff === 0;
}

function pbkdf2($algorithm, $password, $salt, $count, $key_length, $raw_output = false){
	$algorithm = strtolower($algorithm);
	
	if(!in_array($algorithm, hash_algos(), true)){
		trigger_error('PBKDF2 ERROR: Invalid hash algorithm.', E_USER_ERROR);}
		
	if($count <= 0 || $key_length <= 0){
		trigger_error('PBKDF2 ERROR: Invalid parameters.', E_USER_ERROR);}
		
	if (function_exists("hash_pbkdf2")) {
		// The output length is in NIBBLES (4-bits) if $raw_output is false!
		if (!$raw_output) {
			$key_length = $key_length * 2;
		}
		
	return hash_pbkdf2($algorithm, $password, $salt, $count, $key_length, $raw_output);
	}
	
	$hash_length = strlen(hash($algorithm, "", true));
	$block_count = ceil($key_length / $hash_length);
	$output = "";
	for($i = 1; $i <= $block_count; $i++) {
		// $i encoded as 4 bytes, big endian.
		$last = $salt . pack("N", $i);
		// first iteration
		$last = $xorsum = hash_hmac($algorithm, $last, $password, true);
		// perform the other $count - 1 iterations
		for ($j = 1; $j < $count; $j++) {
		$xorsum ^= ($last = hash_hmac($algorithm, $last, $password, true));
		}
		$output .= $xorsum;
	}
	if($raw_output){
		return substr($output, 0, $key_length);}
	else{
		return bin2hex(substr($output, 0, $key_length));}
}



//==============================================================//
//							Login								//
//==============================================================//
$app->post('/loginUser', function(){
	session_start();
	global $mysqli;
	$email = $_POST['email'];
	$password = $_POST['password'];

	try {
		$sql = "SELECT user_ID FROM Users WHERE email=(?)";
		$stmt = $mysqli -> prepare($sql);
		$stmt -> bind_param('s', $email);
		$stmt -> execute();
		$username_test = $stmt -> fetch();
		
		//vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
		if(($username_test === NULL)) {
			die(json_encode(array('ERROR' => 'Could not find user')));
		}^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
		else{
			$stmt->close();
			$sql = "SELECT saltValue FROM Users WHERE email=(?)";
			$stmt1 = $mysqli -> prepare($sql);
			$stmt1 -> bind_param('s', $email);
			$stmt1 -> execute();
			$passwordVal = '';
			$stmt1->bind_result($passwordVal);
			$stmt1 -> fetch();
			//vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
			if($passwordVal === NULL) {																						
				die(json_encode(array('ERROR' => 'User could not be validated')));											
			}//^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
			/*=================\\
			||	Get User Data  ||
			\\=================*/
			//vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
			else if(validate_password($password,$passwordVal)) {
				$stmt1->close();
				$components = "SELECT * FROM Users WHERE email='$email'";
				$returnValue = $mysqli -> query($components);
				$iteration = $returnValue -> fetch_assoc();
				
				$userId = $_SESSION['userId'] = $iteration['user_ID'];
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
					$checkAdmin = $mysqli->query("SELECT TOP 1 user_ID FROM Admin WHERE user_ID='$userId'");
					$result = $checkAdmin->fetch_assoc();
					
					/*==================\\
					||	Get Admin Data  ||
					\\==================*/
					//vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
					if($checkAdmin !== NULL){
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
		}//^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
		$mysqli = null;
	}catch(exception $e){
		//echo '{"error":{"text":'. $e->getMessage() .'}}';
		die(json_encode(array('ERROR' => $e->getMessage())));
	}
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
	global $mysqli;
	$check = $_POST['studentOrFaculty'];
	$firstName = $_POST['firstName'];
	$lastName = $_POST['lastName'];
	$email = $_POST['email'];
	$userId = '';
	
	if($firstName === "" || $lastName === "" || $email === "" || $password === "")
		die(json_encode(array('ERROR' => 'Received blank parameters from registration')));
	else{
		$dupCheck = $mysqli->query("SELECT email FROM Users WHERE email='$email'");
		$checkResults = $dupCheck->fetch_assoc();
		if(!($checkResults === NULL))
			die(json_encode(array('ERROR' => 'User already exists')));
		else{
			$saltValue = base64_encode(mcrypt_create_iv(PBKDF2_SALT_BYTE_SIZE, MCRYPT_DEV_URANDOM));
			$hashedPassword = create_hash($password, $saltValue);

			$insertUser = $mysqli->query("INSERT INTO Users (fName, lName, email, saltValue) VALUES ('$firstName', '$lastName', '$email', '$saltValue')");
			$userId = $mysqli->query("SELECT user_ID FROM Users where email='$email'");
			$insertPassword = $mysqli->query("INSERT INTO Password (user_ID, password) VALUES ('$userId', '$hashedPassword')");
			
			if($check === "Student"){
				$instId = $_POST['instId'];
				$major = $_POST['major'];
				$grad = $_POST['grad'];
				$insertStudent = $mysqli->query("INSERT INTO Student (user_ID, inst_ID, dept_ID, graduateStudent) VALUES ('$userId', '$instId', '$major', '$grad')");
			}
			elseif($check === "Faculty"){
				$instId = $_POST['instId'];
				$deptId = $_POST['deptId'];
				$insertFaculty = $mysqli->query("INSERT INTO Faculty (user_ID, inst_ID, dept_ID) VALUES ('$userId', '$instId', '$deptId')");
			}
	}
	

	}
});
?>