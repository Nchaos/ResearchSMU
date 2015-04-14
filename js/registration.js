$(document).ready(function () {
	
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
		$("#password").focus();
		return false;
	}
	
	
	/*var firstName = $("#firstName").val();
	var lastName = $("#lName").val();
	var email = $("#email").val();
	var password = $("#pwd").val();
	var check = $("#studeontOrFaculty").val();*/
	// Returns successful data submission message when the entered information is stored in database.
	
	
	var dataString = 
	{
	  "firstName": document.getElementsByName("firstName")[0].value,
	  "lastName": document.getElementsByName("lastName")[0].value,
	  "email": document.getElementsByName("Email")[0].value,
	  "password": document.getElementsByName("password")[0].value,
	  "check": document.getElementsByName("studentOrFaculty")[0].value,
	  "department": document.getElementsByName("major")[0].value
	};
	
	console.log(dataString);

	// AJAX code to submit form.
	$.ajax({
		type: "POST",
		url: "api/index.php/createAccount",
		datatype:"json",
		data: dataString,
		success: function(result) {
			var json = JSON.parse(result);
			if(json === null){
				window.alert("Failure");
				return false;
			}
			alert("success!");
		}
	});
	//return false;
}


  function checkOPForm(form)
  {
    if(form.OPtitle.value == "") {
      alert("Error: Need a title!");
      form.Optitle.focus();
      return false;
    }
    re = /^\w+$/;
    if(!re.test(form.OPtitle.value)) {
      alert("Error: Need a title!");
      form.OPtitle.focus();
      return false;
    }
    return true;
  }

