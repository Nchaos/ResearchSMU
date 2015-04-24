<?php

	$mysqli = new mysqli("localhost", "root", "toor", "DBGUI");
	// Arguments
	$institution = json_decode($_POST['institution']);
	$department = json_decode($_POST['department']);

	// $institution = "Lyle";
	// $department = "CSE";
	
	// TABLE MAGIC TIME, Create temp table
	$sql = "CREATE TABLE TEMP(ResearchOp_ID int primary key, rName VARCHAR(48), fName VARCHAR(45), lName VARCHAR(45), startDate DATE, endDate DATE, numPositions INT, dName VARCHAR(45), iName varchar(45), paid BOOL, workStudy BOOL, acceptsUndergrad BOOL, acceptsGrad BOOL)";
	$stmt = $mysqli -> prepare($sql);
	$stmt -> execute();
	$stmt -> close();
	
	// Insert required info into temporary table
	$sql1 = "Insert into TEMP Select ResearchOp.ResearchOp_ID, ResearchOp.name, Users.fName, Users.lName, ResearchOp.startDate, ResearchOp.endDate, ResearchOp.numPositions, Department.name, Institution.name, ResearchOp.paid, ResearchOp.workStudy, ResearchOp.acceptsUndergrad, ResearchOp.acceptsGrad from ResearchOp, Department, Users, Institution WHERE Department.dept_ID = ResearchOp.dept_ID AND ResearchOp.user_ID = Users.user_ID AND ResearchOp.inst_ID = Institution.inst_ID AND Institution.name = (?) AND Department.name = (?)";

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
		//array( 'db' => 'ResearchOp_ID',		'dt' => 0 ),
		array( 'db' => 'rName',    			'dt' => rName ),
		array( 'db' => 'fName',    			'dt' => fName ),
		// array( 'db' => 'lName',    			'dt' => 2 ),
		//array( 'db' => 'startDate',			'dt' => 4 ), 
		//array( 'db' => 'endDate',  			'dt' => 5 ),
		//array( 'db' => 'numPositions',    	'dt' => 6 ),
		array( 'db' => 'dName',    			'dt' => dName ),
		array( 'db' => 'iName',    			'dt' => iName ),
		//array( 'db' => 'paid',				'dt' => 9 ), 
		//array( 'db' => 'workStudy',  		'dt' => 10 ),
		//array( 'db' => 'acceptsUndergrad',	'dt' => 11 ), 
		//array( 'db' => 'acceptsGrad',  		'dt' => 12 ),
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
	 
	echo json_encode(
		SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
	);
	
	// $sql2 = "Drop TABLE TEMP";
	// $stmt2 = $mysqli -> prepare($sql2);
	// $stmt2 -> bind_param('ss', $institution, $department);
	// $stmt2 -> execute();
	// $stmt2 -> close();
	
?>