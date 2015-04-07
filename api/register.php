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
		}
	}
	
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
});