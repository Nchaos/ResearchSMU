$(document).ready(function(){
	$("#signin").click(Login);
});

function Login(event){
	event.preventDefault();

	////////////////////////////////////////////////////////
	/////////// 		Check form 			////////////////
	////////////////////////////////////////////////////////
	if(document.getElementsByName("email")[0].value == "") {
		alert("Error: Enter your email address! 1");
		$("#email").focus();
		return false;
	}

	re = /^\w+$/;
	if(re.test(document.getElementsByName("email")[0].value)) {
		alert("Error: Enter your email address! 2");
		$("#email").focus();
		return false;
	}

	if(!validateEmail(document.getElementsByName("email")[0].value)){
		alert("Error: Email not valid 1");
		$("eEmail").focus();
		return false;
	}

	if(!re.test(validateEmail(document.getElementsByName("email")[0].value))){
		alert("Error: Email not valid 2");
		$("#email").focus();
		return false;
	}
	
	if(document.getElementsByName("password")[0].value == "") {
		alert("Error: Enter your password!");
		$("#password").focus();
		return false;
	}

	re = /^\w+$/;
	if(!re.test(document.getElementsByName("password")[0].value)) {
		alert("Error: Enter your password!");
		$("#password").focus();
		return false;
	}		

	////////////////////////////////////////////////////////
	/////////// 		Create JSON			////////////////
	////////////////////////////////////////////////////////
	var dataString = 
	{
	  "email": document.getElementsByName("email")[0].value,
	  "password": document.getElementsByName("password")[0].value,
	};

	console.log(dataString);

	// AJAX code to submit form.
	$.ajax({
		type: "POST",
		url: "api/index.php/login",		//changed to login from loginUser
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

	return false;
}

function validateEmail(email) { 
  
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

	////////////////////////////////////////////////////////
	/////////// 		LOGOUT				////////////////
	////////////////////////////////////////////////////////

$(document).ready(function(){
	$("#signout").click(logout);
});

function logout(event){
	event.preventDefault();


	$.ajax({
		type: "POST",
		url: "api/index.php/logout",
	});
}

