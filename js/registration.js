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
	  "instId": "1"
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


