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
		
		$postUrl = "https://api.linkedin.com/v1/people/~/shares?oauth2_access_token=AQW9dItCSzDAJ2AXC9q0t-tE1W0bIk7hNdNyYdAVccv-jiDMlYxaFQ4-1pxOSYji8UKd8oUB459apIJ8pZhf0G7Xc_65wWIMoIaF8hJ4fy_si5qfLJzrI6sAEtTn67gF0GhBvllKk-avH8wqjcin1_fIp_rF8rzuHWUI1Gk86a0-6nwIQY8&format=json";
		
		//Check if post is active:
		$sql = "SELECT active FROM ResearchOp WHERE researchOp_ID='$postId'";
		$stmt = $mysqli->prepare($sql);
		$stmt->execute();
		$stmt->bind_result($active);
		$stmt->fetch();
		$stmt->close();
		
		if(!($active)){
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
			
			//Post data to LinkedIn
			
			
		} else {
			//Post is not active, so don't post it
			die(json_encode(array('Status' => 'Failed',
				'ERROR' => 'Post is not active, can\'t ')));
		}
		
	});
	
	
	
	

	$app->run();
?>