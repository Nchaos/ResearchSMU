<?php
	header('Content-Type: application/json');
	$debug = true;
	require_once 'vendor/autoload.php';
	$app = new Slim\Slim();
	
	
	$mysqli = new mysqli("localhost", "root", "toor", "DBGUI");
	if($mysqli->connect_errno)
		die("Connection failed: " . $mysqli->connect_errno);
	
	
	
	//==============================================================//
	//							Make Post							//
	//==============================================================//	
	$app->post('/linkedinPost', function(){
		if($debug) echo "Posting to LinkedIn...\n";
		global $mysqli;
		header('x-li-format: json');
		
		$linkedIn = new Happyr\LinkedIn\LinkedIn('app_id', 'app_secret');
		
		
	});
	
	
	
	

	$app->run();
?>