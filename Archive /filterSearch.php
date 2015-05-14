function filterLyle(){//all OPs in Lyle
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE inst_ID = 05";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
function filterDedman(){//all OPs in Dedman
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE inst_ID = 01";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
function filterCox(){//all OPs in Cox
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE inst_ID = 02";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
function filterMeadows(){//all OPs in Meadows
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE inst_ID = 03";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
function filterSimmons(){//all OPs in Simmons
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE inst_ID = 04";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
function filterAnthropolgy(){//all OPs in Anthropology
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1000";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
function filterBioScience(){//all OPs in BioScience
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1001";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
function filterChemistry(){//all OPs in Chemistry
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1002";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
function filterEarthScience(){//all OPs in EarthScience
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1003";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
function filterEconomics(){//all OPs in Economics
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1004";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	
function filterEnglish(){//all OPs in English
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1005";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
function filterHistory(){//all OPs in History
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1006";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
function filterMath(){//all OPs in Math
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1007";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
function filterPhilosophy(){//all OPs in Philosophy
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1008";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
function filterPhysics(){//all OPs in Physics
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1009";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
function filterPoliScience(){//all OPs in PoliScience
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1010";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
function filterPsychology(){//all OPs in Psychology
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1011";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
function filterReligionScience(){//all OPs in ReligionScience
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1012";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
function filterSociology(){//all OPs in Sociology
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1013";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
function filterStatScience(){//all OPs in StatScience
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1014";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
function filterWorldLang(){//all OPs in WorldLang
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1015";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
function filterCivilEnviroEngin(){//all OPs in CivilEnviroEngin
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1016";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
function filterCSCSE(){//all OPs in CSCSE
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1017";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
function filterEE(){//all OPs in EE
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1018";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
function filterManageScience(){//all OPs in ManageScience
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1019";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}

function filterMechEngin(){//all OPs in MechEngin
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1020";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
function filterAccounting(){//all OPs in Accounting
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1021";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
function filterFinance(){//all OPs in Finance
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1022";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
function filterMarketing(){//all OPs in Marketing
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1023";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
function filterManagement(){//all OPs in Management
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1024";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
function filterRealEstate(){//all OPs in RealEstate
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1025";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
function filterRiskManage(){//all OPs in RiskManage
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1026";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
function filterAdvertising(){//all OPs in Advertising
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1027";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
function filterArt(){//all OPs in Art
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1028";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
function filterArtHistory(){//all OPs in ArtHistory
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1029";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
function filterArtManage(){//all OPs in ArtManage
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1030";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
function filterCommunication(){//all OPs in Communications
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1031";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
function filterCreativeComp(){//all OPs in CreativeComp
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1032";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
function filterDance(){//all OPs in Dance
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1033";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
function filterFilmMediaArts(){//all OPs in FilmMedia
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1034";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
function filterJournalism(){//all OPs in Journalism
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1035";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
function filterMusic(){//all OPs in Music
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1036";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
function filterTheatre(){//all OPs in Theatre
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1037";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	
function filterAppliedPhys(){//all OPs in AppliedPhys
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1038";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
function filterCounseling(){//all OPs in Counseling
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1039";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
function filterDisputeResolution(){//all OPs in DisputeResolution
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1040";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
function filterHigherEd(){//all OPs in HigherEd
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1041";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
function filterSportsManage(){//all OPs in SportsManage
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1042";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
function filterTeacherEd(){//all OPs in TeacherEd
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1043";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}
	
function filterWellness(){//all OPs in Wellness
		global $mysqli;
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		 
		$s = "SELECT * 
				FROM researchOP
				WHERE dept_ID = 1044";
		if($mysqli->query($s) === TRUE) {
			$result = $mysqli->query($s);
		} else {
			echo "Error creating database: " . $mysqli->error;
		}
		
		return $result;
	}