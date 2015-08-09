$(document).ready(function(){
	$("#register").click(checkForm);
});


function checkForm(event){
	event.preventDefault();
	
	if(document.getElementById("fName").value == "") {
		alert("Error: First Name!");
		$("#fName").focus();
		return false;
	}
	re = /^\w+$/;
	if(!re.test(document.getElementById("fName").value)) {
		alert("Error: First Name!");
		$("#fName").focus();
		return false;
	}
	if(document.getElementById("lName").value == "") {
		alert("Error: Last Name!");
		$("#lName").focus();
		return false;
	}
	if(!re.test(document.getElementById("lName").value)) {
		alert("Error: Last Name!");
		$("#lName").focus();
		return false;
	}
	if(!(validateEmail(document.getElementById("email").value))){
		alert("Error: Email not valid");
		$("#email").focus();
		return false;
	}
	if(!(re.test(validateEmail(document.getElementById("email").value)))){
		alert("Error: Email not valid");
		$("#email").focus();
		return false;
	}

	///////////////////////////////////////////////////////////
	////////////		Password Validation 		///////////
	///////////////////////////////////////////////////////////
    if(document.getElementById("pwd").value != "" && document.getElementById("pwd").value == document.getElementById("pwd2").value) {
      if(document.getElementById("pwd").value.length < 8) {
        alert("Error: Password must contain at least eight characters!");
        $("pwd").focus();
        return false;
      }
      re = /[0-9]/;
      if(!re.test(document.getElementById("pwd").value)) {
        alert("Error: password must contain at least one number (0-9)!");
        $("pwd").focus();
        return false;
      }
      re = /[a-z]/;
      if(!re.test(document.getElementById("pwd").value)) {
        alert("Error: password must contain at least one lowercase letter (a-z)!");
        $("pwd").focus();
        return false;
      }
      re = /[A-Z]/;
      if(!re.test(document.getElementById("pwd").value)) {
        alert("Error: password must contain at least one uppercase letter (A-Z)!");
        $("pwd").focus();
        return false;
      }
    } else {
      alert("Error: Please check that you've entered and confirmed your password!");
      $("pwd").focus();
      return false;
    }
	
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
		instId = "05";
	else if (deptId == "12") // Communication Studies
		instId = "03";
	else if (deptId == "13") // Computer Science & Engineering
		instId = "05";
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
		instId = "05";
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
		instId = "05";
	else if (deptId == "29") // Marketing 
		instId = "02";
	else if (deptId == "30") // Mathematics
		instId = "01";
	else if (deptId == "31") // Mechanical Engineering 
		instId = "05";
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
	
	var dataString = 
	{
	  "firstName": document.getElementById("fName").value,
	  "lastName": document.getElementById("lName").value,
	  "email": document.getElementById("email").value,
	  "password": document.getElementById("pwd").value,
	  "deptId": deptId,
	  "instId": instId
	};
	
	//console.log(dataString);

	// AJAX code to submit form.
	$.ajax({
		type: "POST",
		url: "api/index.php/createAccount",
		dataType: "json",
		data: dataString,
		success: function(data) {
			if(data.status == 'success')
			{
          		alert("You have successfully Activated your Account!");
			}
			else if(data.status == 'error2')
			{
				alert("Error: This email has already been activated, please sign in instead");
			}
			else if(data.status == 'error1')
			{
				alert("Error: The email you have enter is not a registered SMU faculty email. Try using the faculty email finder button.");
			}
			// $('#registerUser , .form').fadeOut(300 , function() {
			// 	//$('#registerUser').remove();  
			// });
			// $('#mask').remove();
			$('#mask , .register-popup').fadeOut(300 , function() {
    		$('#mask').remove();  
  			}); 
		},
		error: function(data){
			alert("Unknown Ajax Error");
		}
	});
	
	//window.location.href ="index.html";
	return false;
}


function lookUpfname(event){
	event.preventDefault();

	if(document.getElementById("fName").value == "") {
		alert("Error: First Name!");
		$("#fName").focus();
		return false;
	}
	re = /^\w+$/;
	if(!re.test(document.getElementById("fName").value)) {
		alert("Error: First Name!");
		$("#fName").focus();
		return false;
	}

		var dataString = 
	{
	  "firstName": document.getElementById("fName").value,
	};
	
	console.log(dataString);

	// AJAX code to submit form.
	$.ajax({
		type: "POST",
		url: "api/index.php/lookUpfname",
		dataType: "json",
		data: dataString
	});
	
	/*request.done(function (response, textStatus, jqXHR){
		console.log("Request Test");
	});
	
	request.fail(function(jqXHR, textStatus, errorThrown){
		console.error("The following error occurred: " + textStatus, errorThrown);
	});*/
	alert('You have entered a first name look up');
	window.location.href ="index.html";
	return false;
}

function lookUplname(event){
	event.preventDefault();

	if(document.getElementById("lName").value == "") {
		alert("Error: Last Name!");
		$("#lName").focus();
		return false;
	}
	if(!re.test(document.getElementById("lName").value)) {
		alert("Error: Last Name!");
		$("#lName").focus();
		return false;
	}

		var dataString = 
	{
	  "lastName": document.getElementById("lName").value,
	};
	
	console.log(dataString);

	// AJAX code to submit form.
	$.ajax({
		type: "POST",
		url: "api/index.php/lookUplname",
		dataType: "json",
		data: dataString
	});
	
	/*request.done(function (response, textStatus, jqXHR){
		console.log("Request Test");
	});
	
	request.fail(function(jqXHR, textStatus, errorThrown){
		console.error("The following error occurred: " + textStatus, errorThrown);
	});*/
	alert('You have entered a last name look up');
	window.location.href ="index.html";
	return false;
}



function validateEmail(email) { 
  
	var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    return re.test(email);
}

function cancelClickRegister() {
	//window.location.href = "index.html";
	// When clicking on the button close or the mask layer the popup closed
  
    $('#mask , .register-popup').fadeOut(300 , function() {
    $('#mask').remove();  
  }); 
}



