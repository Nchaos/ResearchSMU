//==============================================================//
//							Login								//
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

function create_hash($password)
{
	global $mysqli;
	$salt = base64_encode(mcrypt_create_iv(PBKDF2_SALT_BYTE_SIZE, MCRYPT_DEV_URANDOM));
	//$mysqli -> query("UPDATE saltValue SET saltValue='$salt' WHERE password='$password'");
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

function validate_password($password, $correct_hash)
{
	$params = explode(":", $correct_hash);
	if(count($params) < HASH_SECTIONS){
		return false;
	}
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
	);
}

function slow_equals($a, $b)
{
	$diff = strlen($a) ^ strlen($b);
	for($i = 0; $i < strlen($a) && $i < strlen($b); $i++){
	$diff |= ord($a[$i]) ^ ord($b[$i]);}
	return $diff === 0;
}

function pbkdf2($algorithm, $password, $salt, $count, $key_length, $raw_output = false)
{
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
		if(($username_test === NULL)) {
			$JSONarray = array(
				'status'=>'Failure',
				'user_ID'=>NULL,
				'fName'=>NULL,
				'lName'=>NULL,
				'email'=>NULL);
			echo json_encode($JSONarray);
			return;
		}
		else{
			$stmt->close();
			$sql = "SELECT saltValue FROM Users WHERE email=(?)";
			$stmt1 = $mysqli -> prepare($sql);
			$stmt1 -> bind_param('s', $email);
			$stmt1 -> execute();
			$passwordVal = '';
			$stmt1->bind_result($passwordVal);
			$stmt1 -> fetch();
			if($passwordVal === NULL) {
				$JSONarray = array(
				'status'=>'Failure',
				'user_ID'=>NULL,
				'fName'=>NULL,
				'lName'=>NULL,
				'email'=>NULL);
				echo json_encode($JSONarray);
				return;
			}
			else if(validate_password($password,$passwordVal)) {
				$stmt1->close();
				$_SESSION['loggedin'] = true;
				$query = "SELECT user_ID FROM Users WHERE email=(?)";
				$stmt2 = $mysqli -> prepare($query);
				$stmt2 -> bind_param('s', $email);
				$stmt2 -> execute();
				$stmt2->bind_result($temp);
				$stmt2 -> fetch();
				$_SESSION['user_ID'] = $temp;
				$_SESSION['email'] = $email;
				$statusFlg = 'Succeed';
				$stmt2->close();
				$components = "SELECT * FROM Users WHERE email='$email'";
				$returnValue = $mysqli -> query($components);
				$iteration = $returnValue -> fetch_assoc();
				$JSONarray = array(
				'status'=>$statusFlg,
				'user_ID'=>$iteration['user_ID'],
				'firstName'=>$iteration['firstName'],
				'lastName'=>$iteration['lastName'],
				'email'=>$iteration['email']);
				echo json_encode($JSONarray);
				return;
			}
			//verifies password
			else {
				$JSONarray = array(
				'status'=>'Failure',
				'user_ID'=>NULL,
				'fName'=>NULL,
				'lName'=>NULL,
				'email'=>NULL);
				echo json_encode($JSONarray);
				return;
			}
		}
		//returns null when password is wrong
		$mysqli = null;
	} catch(exception $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
	echo "Finish5";
});


//==============================================================//
//						Register								//
//==============================================================//
$app->post('/createUserAccount', function(){
	global $mysqli;
	$fName = $_POST['fName'];
	$lName = $_POST['lName'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	if($fName === "" || $lName === "" || $email === "" || $password === "")
	$outputJSON = array ('u_id'=>-2);
	else{
	$dupCheck = $mysqli->query("SELECT email FROM Users WHERE email = '$email' LIMIT 1");
	$checkResults = $dupCheck->fetch_assoc();
	$hashedPassword = create_hash($password);
		if(!($checkResults === NULL))
			$outputJSON = array ('u_id'=>-1);
			else{
			$prevUser = $mysqli->query("SELECT user_ID FROM Users ORDER BY user_ID DESC LIMIT 1");
			$row = $prevUser->fetch_assoc();
			if($row === NULL){
				$outputJSON = array ('u_id'=>1);
				//$insertion = $mysqli->query("INSERT INTO Users (user_ID, fName, lName, email, saltValue) VALUES (1, '$fName', '$lName', '$email', '$hashedPassword')");
				$insertion1 = $mysqli->("INSERT INTO Users (user_ID, fName, lName, email) VALUES (1, '$fName', '$lName', '$email')");
				$insertion2 = $mysqli->("INSERT INTO Password (user_ID, password) VALUES ((SELECT user_ID FROM Users WHERE email='$email'), '$password')");
			}
			else{
				$newID = $row['user_ID']+1;
				$outputJSON = array ('u_id'=>$newID);
				//$insertion = $mysqli->query("INSERT INTO Users (user_ID, fName, lName, email, password, saltValue) VALUES ($newID, '$fName', '$lName', '$email', '$password', '$hashedPassword')");
			}
		}
	}
	echo json_encode($outputJSON);
});