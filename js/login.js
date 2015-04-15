$(document).ready(function(){
	$("#signin").click(LoginIn);
});

function checkForm(event){
	event.preventDefault();

	////////////////////////////////////////////////////////
	/////////// 		Check form 			////////////////
	////////////////////////////////////////////////////////
	if(document.getElementsByName("email")[0].value == "") {
		alert("Error: Enter your email address!");
		$("#email").focus();
		return false;
	}

	re = /^\w+$/;
	if(!re.test(document.getElementsByName("email")[0].value)) {
		alert("Error: Enter your email address!");
		$("#email").focus();
		return false;
	}

	if(document.getElementsByName("password")[0].value == "") {
		alert("Error: Enter your password!");
		$("#email").focus();
		return false;
	}

	if(!re.test(document.getElementsByName("password")[0].value)) {
		alert("Error: Enter your password!");
		$("#email").focus();
		return false;
	}		

	////////////////////////////////////////////////////////
	/////////// 		Create JSON			////////////////
	////////////////////////////////////////////////////////
	var dataString = 
	{
	  "email": document.getElementsByName("Email")[0].value,
	  "password": document.getElementsByName("password")[0].value,
	};

	console.log(dataString);

	// AJAX code to submit form.
	$.ajax({
		type: "GET",
		url: "api/index.php/loginUser",
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