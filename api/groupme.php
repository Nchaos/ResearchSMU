<?php
	/*==========================================================================================================================================================\\
	||		Create New Group																																	||
	||==========================================================================================================================================================||
	||																																							||
	||	curl -X POST -H "Content-Type: application/json" -d '{"name": "<Group Name>", "share": "false"}' https://api.groupme.com/v3/groups?token=<Access Token>	||
	||																																							||
	||	+name																																					||
	||	-description																																			||
	||	*share: false																																			||
	\\==========================================================================================================================================================*/
	
	
	/*======================================================================================================================================================================================================\\
	||		Create New Bot For Group																																										||
	||======================================================================================================================================================================================================||
	||																																																		||
	||	curl -X POST -H "Content-Type: application/json" -d '{"bot": {"name": "<User's Name>", "group_id": "<Group ID>", "callback_url": "<URL>"}}' https://api.groupme.com/v3/bots?token=<Access Token>	||	
	||																																																		||
	||	+bot[name]																																															||
	||	+bot[group_id]	13629414																																											||
	||	-bot[callback_url]																																													||
	\\======================================================================================================================================================================================================*/
	
	
	/*==========================================================================================================================================\\
	||		Post A Message																														||
	||==========================================================================================================================================||
	||																																			||
	||	curl -X POST -H "Content-Type: application/json" -d '{"bot_id": "<Bot Id>", "text": "<Message>"}' https://api.groupme.com/v3/bots/post	||
	||																																			||
	||	+bot_id		181f76df6d707dfcf8fcfaef90		6fda211a563a38374c3f4c2327																	||
	||	+text																																	||
	\\==========================================================================================================================================*/



	$debug = true;
	require 'vendor/autoload.php';
	$app = new \Slim\Slim();
	
	$mysqli = new mysqli("localhost", "root", "toor", "DBGUI");
	if($mysqli->connect_errno)
		die("Connection failed: " . $mysqli->connect_errno);
	
	
	
	/*==================================================\\
	||					Authenticate					||
	\\==================================================*/
	function authenticate(){
		global $mysqli, $debug;
		
	}
	
	
	
	/*==================================================\\
	||					Create Group					||
	\\==================================================*/
	function createGroup($name){
		global $mysqli, $debug;
		$accessToken = '';
		
		$sql = "SELECT accessToken FROM Token WHERE token_ID='2'";
		
		
		if($result = $mysqli->query($sql)){
			$row = $result->fetch_now();
			$accessToken = $row['accessToken'];
		} else {
			die(json_encode(array('success' => false,
				'ERROR' => 'shits fucked... idk'
			)));
		}
		
		$curl = curl_init();
		curl_setopt($curl, array(
			CURLOPT_URL => 'https://api.groupme.com/v3/groups?token=' . $accessToken,
			CURLOPT_POST => 1,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_HTTPHEADER => array('Content-Type' => 'application/json'),
			CURLOPT_POSTFIELDS => json_encode(array(
				"name" => $name, 
				"share" => false
			)),
			//CURLOPT_VERBOSE => 1
		));
		
		$response = json_decode(curl_exec($curl));
		$httpStatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);
		
		if($httpStatus == '201'){
			$groupId = $response['id'];
			
			echo date('g:i') . ' Created new group: ' . $groupId . '<br>';
		} else {
			die(json_encode(array(
				'success' => false,
				'ERROR' => $httpStatus
			)));
		}
	}
	
	
	
	/*==================================================\\
	||					Create Bot						||
	\\==================================================*/
	function createBot($groupId, $name){
		global $mysqli, $debug;
		
	}
	
	
	
	/*==================================================\\
	||					Post Message					||
	\\==================================================*/
	function postMessage($botId, $message){
		global $mysqli, $debug;
		
	}
	
	
	
	/*==================================================\\
	||				Main Chat Function					||
	\\==================================================*/
	$app->post('/chat', function() {
		global $mysqli, $debug;
		
		$facultyId = $_POST['faculty_ID'];
		$studentId = $_POST['student_ID'];
		$timestamp = date("Y-m-d H:i:s");
		
		$groupId = '';
		$botS = '';
		$botF = '';
		
		//Check if a chat session already exists
		$sql = "SELECT * FROM Chat WHERE student_ID=? AND faculty_ID=?";
		$check = $mysqli->prepare($sql);
		$check->bind_param('ii', $studentId, $facultyId);
		$check->exec();
		
		if($result = $check->fetch_assoc()){
			$rowN = $result->num_rows;
			
			if($rowN > 1){
				//There shouldn't be more than one row returned
				die(json_encode(array('Status' => 'Failure',
					'ERROR' => 'Returned multiple rows from Chat table'
				)));
			} else if($rowN < 1) {
				//Create a new chat session
				$result->free();
				$check->close();
				
				
				
			} else {
				//Fetch the chat information
				$sql = "";
			}
			
			
					
		} else {
			die(json_encode(array(
				'success' => false,
				'ERROR' => 'SHITS FUCKED'
			)));
		}

		
	});
	
	
	
	/*==============================================\\
	||					Callback					||
	\\==============================================*/
	$app->post('/callback', function() {
		global $mysqli, $debug;
		
		
	});
	
	$app->run();
	
?>