$(document).ready(function(){
	$("#signin").click(Login);
	$(".logout").click(logout);
});

function Login(event){
	event.preventDefault();

	////////////////////////////////////////////////////////
	/////////// 		Check form 			////////////////
	////////////////////////////////////////////////////////
	if(document.getElementById("emailLogin").value == "") {
		alert("Error: Enter your email address! 1");
		$("#emailLogin").focus();
		return false;
	}

	re = /^\w+$/;
	if(re.test(document.getElementById("emailLogin").value)) {
		alert("Error: Enter your email address! 2");
		$("#emailLogin").focus();
		return false;
	}

	if(!validateEmail(document.getElementById("emailLogin").value)){
		alert("Error: Email not valid 1");
		$("emailLogin").focus();
		return false;
	}

	if(!re.test(validateEmail(document.getElementById("email").value))){
		alert("Error: Email not valid 2");
		$("#emailLogin").focus();
		return false;
	}
	
	if(document.getElementById("pwdLogin").value == "") {
		alert("Error: Enter your password!");
		$("#pwdLogin").focus();
		return false;
	}

	re = /^\w+$/;
	if(!re.test(document.getElementById("pwdLogin").value)) {
		alert("Error: Enter your password!");
		$("#pwdLogin").focus();
		return false;
	}		

	////////////////////////////////////////////////////////
	/////////// 		Create JSON			////////////////
	////////////////////////////////////////////////////////
	var dataString = 
	{
	  "email": document.getElementById("emailLogin").value,
	  "password": document.getElementById("pwdLogin").value,
	};


	// AJAX code to submit form.
	$.ajax({
		type: "POST",
		url: "api/index.php/login",		
		datatype:"json",
		data: dataString,
		success: function(data) {
		  var value = JSON.parse(data);
		  var success = value['success'];
          if(success == false){
          	console.log("Succes is reading fail?");
        	var error = value['message'];
            alert(error); // just in case somebody to click on share witout writing anything :
		  }
          if(success == true) {
				   $('#login-box , .form').fadeOut(300 , function() {
				   $('#login-box').remove();  
                 });// end fadeOut function()
                 
            $('#mask').remove();
			//This needs to be replaced with a function that checks if there is a session 
            $(".login-window").css("display", 'none');
  			$(".logout").css("display", 'inline-block');
  			$(".register-window").css("display", 'none');
  			$(".user-window").css("display", 'inline-block');
  			
		    //setTimeout("location.href = 'api/index.php/logout';",1000);                          
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
		success: function(){
			window.location.href = 'index.html';
			//This needs to be replaced with a function that checks if there is a session 
			// $(".login-window").css("display", 'inline-block');
  	// 		$(".logout").css("display", 'none');
  	// 		$(".register-window").css("display", 'inline-block');
  	//	    $(".user-window").css("display", 'none');
		}
	});
}

