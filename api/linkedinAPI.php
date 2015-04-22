<?php
	header('Content-Type: application/json');
	$debug = true;
	require 'vendor/autoload.php';
	$app = new \Slim\Slim();
	
	$mysqli = new mysqli("localhost", "root", "toor", "DBGUI");
	if($mysqli->connect_errno)
		die("Connection failed: " . $mysqli->connect_errno);	
	//==============================================================//
	//							Make Post							//
	//==============================================================//	
	$app->post('/linkedinPost', function(){
		global $mysqli, $debug;
		$postId = $_POST['researchOpId'];
		
		if($debug) echo "Posting to LinkedIn...\n";

		header('x-li-format: json');
		
		$apiKey = $_POST['api_key'];
		$secretKey = $_POST['secret_key'];
		
		//Check if post is active:
		$sql = "SELECT active FROM ResearchOp WHERE researchOp_ID='$postId'";
		$stmt = $mysqli->prepare($sql);
		$stmt->execute();
		$stmt->bind_result($active);
		$stmt->fetch();
		$stmt->close();
		
		if(!($active)){
			//Prepare authentication
			$linkedIn = new Happyr\LinkedIn\LinkedIn($apiKey, $secretKey);
			
			if($linkedIn->isAuthenticated()){			
				//Get data from database
				$postDept;
				$postName;
				$postDescription;
				
				$sql = "SELECT dept_ID, name, description FROM ResearchOp WHERE researchOp_ID='$postId'";
				$stmt = $mysqli->prepare($sql);
				$stmt->execute();
				$stmt->bind_result($postDept, $postName, $postDescription);
				$stmt->fetch();
				$stmt->close();
				
				//Prepare data for LinkedIn post
				$postComment = "New Research Opportunity in " . $postDept . "!";
				$postTitle = "http://52.11.153.243/research-opporunity/" . $userId;
				$postDescription = (strlen($postDescription) > 256) ? substr($postDescription, 0, 253).'...' : $postDescription;
				
				$postData = json_encode(array('comment' => $postComment,
					'content' => array(
						'title' => $postTitle,
						'description' => $postDescription
					),
					'submitted-url' => $postTitle,
					'submitted-image-url' => 'https://avatars0.githubusercontent.com/u/6441254?v=3&s=400',
					'visibility' => array(
						'code' => 'anyone'
					)
				));
				$urlData = array(
					'Content-Type' => 'application/json',
					'x-li-format' => 'json'
				);
				
				
				//Post data to LinkedIn
				$result = $linkedIn->$api('v1/people/~/shares?format=json', $urlData, 'POST', $postData);
				
				echo $result;
				
			} elseif($linkedIn->hasError()){
				die(json_encode(array('Status' => 'Failure',
					'ERROR' => 'User cancelled the login')));
			} else {
				die(json_encode(array('Status' => 'Failure',
					'Error' => 'Could not be authenticated...')));
			}
		} else {
			//Post is not active, so don't post it
			die(json_encode(array('Status' => 'Failed',
				'ERROR' => 'Post is not active, can\'t ')));
		}
		
	});
	
	
	
	

	$app->run();
?>