<?php
	header('Content-Type: application/json');
	$debug = false;
	require 'vendor/autoload.php';
	$app = new \Slim\Slim();


	$app->post('/datatable',function(){
		$mysqli = new mysqli("localhost", "root", "toor", "DBGUI");

		//Arguments
		if(isset($_POST['institution']))
		{
			$institution = $_POST['institution'];
		} else {
		 	//if($debug) echo "Institution = %";
			$institution = "%";
		}
		if(isset($_POST['department']))
		{
			$department = $_POST['department'];
		} else {
		 	//if($debug) echo "Department = %";
			$department = "%";
		}

		//$institution = $_POST['institution'];
		//$department = $_POST['department'];

		// $institution = "Lyle";
		// $department = "CSE";
		
		// TABLE MAGIC TIME, Create temp table, Drop if it already exists
		$sqldrop = "DROP TABLE IF EXISTS `DBGUI`.`TEMP` ";
		$stmt0 = $mysqli -> prepare($sqldrop);
		$stmt0 -> execute();
		$stmt0 -> close();
		
		$sql = "CREATE TABLE TEMP(ResearchOp_ID int primary key, rName VARCHAR(48), fName VARCHAR(45), lName VARCHAR(45), startDate DATE, endDate DATE, numPositions INT, dName VARCHAR(45), iName varchar(45), paid BOOL, workStudy BOOL, acceptsUndergrad BOOL, acceptsGrad BOOL)";
		$stmt = $mysqli -> prepare($sql);
		$stmt -> execute();
		$stmt -> close();
		
		// Insert required info into temporary table
		$sql1 = "INSERT into TEMP SELECT ResearchOp.ResearchOp_ID, ResearchOp.name, Users.fName, Users.lName, ResearchOp.startDate, ResearchOp.endDate, ResearchOp.numPositions, Department.name, Institution.name, ResearchOp.paid, ResearchOp.workStudy, ResearchOp.acceptsUndergrad, ResearchOp.acceptsGrad from ResearchOp, Department, Users, Institution WHERE Department.dept_ID = ResearchOp.dept_ID AND ResearchOp.user_ID = Users.user_ID AND ResearchOp.inst_ID = Institution.inst_ID AND Institution.inst_ID LIKE ? AND Department.dept_ID LIKE ?";

		$stmt1 = $mysqli -> prepare($sql1);
		$stmt1 -> bind_param('ss', $institution, $department);
		$stmt1 -> execute();
		$stmt1 -> close();
		
		$table = 'TEMP';
		 
		// Table's primary key
		$primaryKey = 'ResearchOp_ID';
		 
		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes


		$columns = array(
			//array( 'db' => 'ResearchOp_ID',		'dt' => ResearchOp_ID ),
			array( 'db' => 'rName',    				'dt' => "rName" ),
			array( 'db' => 'fName',    				'dt' => "fName" ),
			// array( 'db' => 'lName',    			'dt' => lName ),
			//array( 'db' => 'startDate',			'dt' => startDate ), 
			//array( 'db' => 'endDate',  			'dt' => endDate ),
			//array( 'db' => 'numPositions',    	'dt' => numPositions ),
			array( 'db' => 'dName',    				'dt' => "dName" ),
			array( 'db' => 'iName',    				'dt' => "iName" )
			//array( 'db' => 'paid',				'dt' => paid ), 
			//array( 'db' => 'workStudy',  			'dt' => workStudy ),
			//array( 'db' => 'acceptsUndergrad',	'dt' => acceptsUndergrad ), 
			//array( 'db' => 'acceptsGrad',  		'dt' => acceptsGrad ),
		);
		 
		// SQL server connection information
		$sql_details = array(
			'user' => 'root',
			'pass' => 'toor',
			'db'   => 'DBGUI',
			'host' => 'localhost'
		);
		 
		 
		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP
		 * server-side, there is no need to edit below this line.
		 */
		 
		require( 'ssp.class.php' );
		 
		//echo json_encode($columns);

		//$result = SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns );
	
		$result =  json_encode(
		 	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns )
		 );
		echo $result;
	
		$sqldrop = "DROP TABLE IF EXISTS `DBGUI`.`TEMP` ";
		$stmt0 = $mysqli -> prepare($sqldrop);
		$stmt0 -> execute();
		$stmt0 -> close();
	});
	$app-> run();
?>