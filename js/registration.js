$(document).ready(function(){
	$("#register").click(checkForm);
});

function checkForm(event){
	event.preventDefault();
	
	/*console.log(document.getElementsByName("firstName")[0].value);
	console.log(document.getElementsByName("lastName")[0].value);
	console.log(document.getElementsByName("Email")[0].value);
	console.log(document.getElementsByName("password")[0].value);
	console.log(document.getElementsByName("studentOrFaculty")[0].value);
	console.log(document.getElementsByName("major")[0].value);*/

	
	if(document.getElementsByName("firstName")[0].value == "") {
		alert("Error: First Name!");
		$("#fName").focus();
		return false;
	}
	re = /^\w+$/;
	if(!re.test(document.getElementsByName("firstName")[0].value)) {
		alert("Error: First Name!");
		$("#fName").focus();
		return false;
	}
	if(document.getElementsByName("lastName")[0].value == "") {
		alert("Error: Last Name!");
		$("#lName").focus();
		return false;
	}
	if(!re.test(document.getElementsByName("lastName")[0].value)) {
		alert("Error: Last Name!");
		$("#lName").focus();
		return false;
	}
	if(!validateEmail(document.getElementsByName("Email")[0].value)){
		alert("Error: Email not valid");
		$("#Email").focus();
		return false;
	}
	if(re.test(!validateEmail(document.getElementsByName("Email")[0].value))){
		alert("Error: Email not valid");
		$("#Email").focus();
		return false;
	}
	if(document.getElementsByName("password")[0].value != "" && document.getElementsByName("password")[0].value == document.getElementsByName("password2")[0].value) {
		/*if(!checkPassword($("#password").val())) {
		alert("The password you have entered is not valid!");
		form.password.focus();
		return false;*/
		console.log("Fix password check...");
	} else {
		alert("Error: Please check that you've entered and confirmed your password!");
		$("#pwd").focus();
		return false;
	}
	
	
	var check = "";
	var grad = 0;
	var radios = document.getElementsByName("studentOrFaculty");
	var deptId = document.getElementsByName("major")[0].value;
	var instId = 0;


	//////////////////////////////////////////////////////
	//////////		Finds Instution ID 		//////////////
	//////////////////////////////////////////////////////

	if (deptId == "01") // Accounting
		instId = "02";
	else if (deptId == "02") // Advertising 
		instId = "03";
	else if (deptId == "03") // Anthropology 
		instId = "01";
	else if (deptId == "04") // Applied Physiology 
		instId = "04";
	else if (deptId == "05") // Art 
		instId = "03";
	else if (deptId == "06") // Art History
		instId = "03";
	else if (deptId == "07") // Art Management
		instId = "03";
	else if (deptId == "08") // Biological Sciences
		instId = "01";
	else if (deptId == "09") // Business
		instId = "02";
	else if (deptId == "10") // Chemistry 
		instId = "01";
	else if (deptId == "11") // Civil & Environmental Engineering
		instId = "00";
	else if (deptId == "12") // Communication Studies
		instId = "03";
	else if (deptId == "13") // Computer Science & Engineering
		instId = "00";
	else if (deptId == "14") // Counseling
		instId = "04";
	else if (deptId == "15") // Creative Computing 
		instId = "03";
	else if (deptId == "16") // Dance
		instId = "03";
	else if (deptId == "17") // Dispute Resolution 
		instId = "04";
	else if (deptId == "18") // Earth Sciences 
		instId = "01";
	else if (deptId == "19") // Economics
		instId = "01";
	else if (deptId == "20") // Electrical Engineering
		instId = "00";
	else if (deptId == "21") // English 
		instId = "01";
	else if (deptId == "22") // Film and Media Arts
		instId = "03";
	else if (deptId == "23") // Finance 
		instId = "02";
	else if (deptId == "24") // Higher Education 
		instId = "04";
	else if (deptId == "25") // History 
		instId = "01";
	else if (deptId == "26") // Journalism
		instId = "03";
	else if (deptId == "27") // Management 
		instId = "02"
	else if (deptId == "28") // Management Science
		instId = "00";
	else if (deptId == "29") // Marketing 
		instId = "02";
	else if (deptId == "30") // Mathematics
		instId = "01";
	else if (deptId == "31") // Mechanical Engineering 
		instId = "00";
	else if (deptId == "32") // Music
		instId = "03";
	else if (deptId == "33") // Phiolosophy 
		instId = "01";
	else if (deptId == "34") // Physics
		instId = "01";
	else if (deptId == "35") // Political Science 
		instId = "01";
	else if (deptId == "36") // Psychology 
		instId = "01";
	else if (deptId == "37") // Real Estate 
		instId = "02";
	else if (deptId == "38") // Religous Studies 
		instId = "01";
	else if (deptId == "39") // Risk Management and Insurance 
		instId = "02";
	else if (deptId == "40") // Sports Management
		instId = "04";
	else if (deptId == "41") // Sociology 
		instId = "01";
	else if (deptId == "42") // Statisical Science 
		instId = "01";
	else if (deptId == "43") // Teacher Education
		instId = "04";
	else if (deptId == "44") // Theatre
		instId = "03";
	else if (deptId == "45") // Wellness
		instId = "04";
	else if (deptId == "46") // World Language
		instId = "01";	
	else{
		// Error for no Dept
		alert("Error: Select a Department");
		$("#Department").focus();
		return false;
	}



	
	//Check for grad, undergrad or faculty 
	for (var i = 0, length = radios.length; i < length; i++){
		if(radios[i].checked){
			if(radios[i].checked == "Grad"){
				check = "Student";
				grad = 1;
			} else if(radios[i].value == "Undergrad"){
				check = "Student";
			} else {
				check = "Faculty";
			}
			break;
		}
	}
	
	
	/*if(document.getElementsByName("studentOrFaculty")[0].value == "Grad"){
		check = "Student";
		grad = 1;
	} else if(document.getElementsByName("studentOrFaculty")[0].value == "Undergrad"){
		check = "Student";
	} else {
		check = "Faculty";
	}*/
	
	var dataString = 
	{
	  "firstName": document.getElementsByName("firstName")[0].value,
	  "lastName": document.getElementsByName("lastName")[0].value,
	  "email": document.getElementsByName("Email")[0].value,
	  "password": document.getElementsByName("password")[0].value,
	  "check": check,
	  "deptId": document.getElementsByName("major")[0].value,
	  "grad": grad,
	  "instId": instId
	};
	
	console.log(dataString);

	// AJAX code to submit form.
	$.ajax({
		type: "POST",
		url: "api/index.php/createAccount",
		datatype:"json",
		data: dataString,
		/*success: function(result) {
			var json = JSON.parse(result);
			if(json === null){
				window.alert("Failure");
				return false;
			}
			else {
				alert("success!");
			}
		}*/
	});
	
	/*request.done(function (response, textStatus, jqXHR){
		console.log("Request Test");
	});
	
	request.fail(function(jqXHR, textStatus, errorThrown){
		console.error("The following error occurred: " + textStatus, errorThrown);
	});*/
	
	return false;
}

function validateEmail(email) { 
  
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}


