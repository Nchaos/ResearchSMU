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
		url: "api/index.php/login",		
		datatype:"json",
		data: dataString,
		success: function(data) {
			console.log("SUCCESS!!!!!!!!!!!!!");
			//window.location.href = "index.html";
		  
		  var value = JSON.parse(data);
		  var success = value['success'];
		  console.log(success);
          if(success == false){
          	console.log("Succes is reading fail?");
        	var error = value['message'];
            alert(error); // just in case somebody to click on share witout writing anything :
		  }
			console.log("Between if blocks");
          if(success == true) {
				   $('#login-box , .login-popup').fadeOut(300 , function() {
				   $('#login-box').remove();  
                 });// end fadeOut function()
		    //setTimeout("location.href = 'index.php/logout';",1000);  
		    console.log("Href here");
		    //window.location.href = "index.html";                             
          }
		}
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

