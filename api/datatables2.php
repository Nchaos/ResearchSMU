<?php
	header('Content-Type: application/json');
	$debug = false;
	require 'vendor/autoload.php';
	$app = new \Slim\Slim();


	$app->post('/datatable',function(){
		$mysqli = new mysqli("localhost", "root", "toor", "DBGUI");

		//Arguments
		if(isset($_POST['department']))
		{
			$department = $_POST['department'];
			foreach ($department as &$value) 
			{
				$value = ltrim($value, '0');
			}
			//$department = ltrim($department, '0');
		} else {
		 	//if($debug) echo "Department = %";
			$department = "%";
		}
		if(isset($_POST['institution']))
		{
			$institution = $_POST['institution'];
			foreach ($institution as &$value) 
			{
				$value = ltrim($value, '0');
			}
			//$institution = ltrim($institution, '0');
		} else {
		 	//if($debug) echo "Institution = %";
			$institution = "%";
		}
		// if(isset($_POST['department']))
		// {
		// 	$department = $_POST['department'];
		// } else {
		//  	//if($debug) echo "Department = %";
		// 	$department = "%";
		// }

		// echo $department;
		// echo $institution;

		//$institution = $_POST['institution'];
		//$department = $_POST['department'];

		// $institution = "Lyle";
		// $department = "CSE";
		
		// TABLE MAGIC TIME, Create temp table, Drop if it already exists
		$sqldrop = "DROP TABLE IF EXISTS `DBGUI`.`TEMP` ";
		$stmt0 = $mysqli -> prepare($sqldrop);
		$stmt0 -> execute();
		$stmt0 -> close();
		
		$sql = "CREATE TABLE TEMP(ResearchOp_ID int primary key, rName VARCHAR(48), 
					name VARCHAR(90), startDate DATE, endDate DATE, numPositions INT, 
					dName VARCHAR(45), iName VARCHAR(45), 
					paid VARCHAR(10), workStudy VARCHAR(10), acceptsUndergrad VARCHAR(10), acceptsGrad VARCHAR(10), 
					description MEDIUMTEXT)";
		
		$stmt = $mysqli -> prepare($sql);
		$stmt -> execute();
		$stmt -> close();
		
		// Insert required info into temporary table
		$sql1 = "INSERT into TEMP SELECT 
					ResearchOp.ResearchOp_ID, 
					ResearchOp.name, 
					Concat(Users.fName, ' ', Users.lName), 
					ResearchOp.startDate, 
					ResearchOp.endDate, 
					ResearchOp.numPositions, 
					Department.name, 
					Institution.name, 
					(CASE WHEN ResearchOp.paid = 1 THEN 'Yes' ELSE 'No' END) as paidval, 
					(CASE WHEN ResearchOp.workStudy = 1 THEN 'Yes' ELSE 'No' END) as wsval,
					(CASE WHEN ResearchOp.acceptsUndergrad= 1 THEN 'Yes' ELSE 'No' END) as ugval, 
					(CASE WHEN ResearchOp.acceptsGrad= 1 THEN 'Yes' ELSE 'No' END) as gval, 
					ResearchOp.description
				from 
					ResearchOp join Department on ResearchOp.Dept_ID = Department.Dept_ID 
					join Users on ResearchOp.user_ID = Users.user_ID 
					join Institution on ResearchOp.inst_ID = Institution.inst_ID
				WHERE
					Institution.inst_ID LIKE (?)";
		$i = 0;
		foreach($department as &$value)
		{
			if ($i == 0)
			{
				$sql1 .= " AND Department.dept_ID LIKE " . $value;
			} else
			{
				$sql1 .= " OR Department.dept_ID LIKE " . $value; 
			}
			$i++;
		}			
			
					/*AND Institution.inst_ID LIKE (?) 
					AND Department.dept_ID LIKE (?);*/

		$stmt1 = $mysqli -> prepare($sql1);
		$stmt1 -> bind_param('s', $institution);
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
			array( 'db' => 'name',    				'dt' => "pName" ),
			//array( 'db' => 'lName',    				'dt' => "fName" ),
			array( 'db' => 'startDate',				'dt' => "startDate" ), 
			array( 'db' => 'endDate',  				'dt' => "endDate" ),
			array( 'db' => 'numPositions',    		'dt' => "numPositions" ),
			array( 'db' => 'dName',    				'dt' => "dName" ),
			array( 'db' => 'iName',    				'dt' => "iName" ),
			array( 'db' => 'paid',					'dt' => "paid" ), 
			array( 'db' => 'workStudy',  			'dt' => "workStudy" ),
			array( 'db' => 'acceptsUndergrad',		'dt' => "acceptsUndergrad"), 
			array( 'db' => 'acceptsGrad',  			'dt' => "acceptsGrad" ),
			array( 'db' => 'description',			'dt' => "descript" )
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
	
		// $sqldrop = "DROP TABLE IF EXISTS `DBGUI`.`TEMP` ";
		// $stmt0 = $mysqli -> prepare($sqldrop);
		// $stmt0 -> execute();
		// $stmt0 -> close();
	});
	$app-> run();
?>