$(document).ready(function(){
	$("#register").click(checkForm);
});
  
function checkForm(event)
{
	event.preventDefault();
	//////////////////////////////////////////////////
	//  checks to see if user filled first name out //
	//////////////////////////////////////////////////
	//if(form.firstName.value == "") {
	if($("#firstName").val() == "") {
		alert("Error: First Name!");
		$("#firstName").focus();
		return false;
	}


	//////////////////////////////////////////////////
	//  checks to see if user filled first name out //
	//////////////////////////////////////////////////
	re = /^\w+$/;
	//if(!re.test(form.firstName.value)) {
	if(!re.test($("#firstName").val())) {
		alert("Error: First Name!");
		$("#firstName").focus();
		return false;
	}
	
	//////////////////////////////////////////////////
	//  checks to see if user filled last name out //
	//////////////////////////////////////////////////
	if($("#lastName").val() == "") {
		alert("Error: Last Name!");
		$("#lastName").focus();
		return false;
	}
	
	//////////////////////////////////////////////////
	//  checks to see if user filled first name out //
	//////////////////////////////////////////////////
	if(!re.test($("#lastName").val())) {
		alert("Error: Last Name!");
		$("#lastName").focus();
		return false;
	}
	

	//////////////////////////////////////////////////
	//  validates password field confirm correct   ///
	//  Not function at this moment                ///
	//////////////////////////////////////////////////
	if($("#password").val() != "" && $("#password").val() == $("#password2").val()) {
		/*if(!checkPassword($("#password").val())) {
		alert("The password you have entered is not valid!");
		form.password.focus();
		return false;
		}*/
		console.log("Fix password check...");
	} else {
		alert("Error: Please check that you've entered and confirmed your password!");
		$("#password").focus();
		return false;
	}

	//////////////////////////////////////////////////
	//  The JSON variable that should be POST to php//
	//////////////////////////////////////////////////
	// Returns successful data submission message when the entered information is stored in database.
	var dataString = {
		"firstName": $("#firstName").val(),
		"lastName": $("#lastName").val(),
		"email": $("#email").val(),
		"password": $("#password").val(),
		"check": $("#studentOrFaculty").val(),
		"major": $("#Department").val()
	};
  
	console.log(dataString);

	// AJAX code to submit form.
	request = $.ajax({
		type: "POST",
		url: "api/index.php/createAccount",
		datatype:"json",
		data: dataString,
		/*success: function(result) {
			console.log("Success");
		}*/
	});
	

	//////////////////////////////////////////////////
	//  check to see if submited  //
	//////////////////////////////////////////////////
	request.done(function (response, textStatus, jqXHR){
		console.log("Request Test");
	});
	
	request.fail(function(jqXHR, textStatus, errorThrown){
		console.error("The following error occured: " + textStatus, errorThrown);
	});
	
	return false;
}


	//////////////////////////////////////////////////
	//  checks OP form validatation //
	//////////////////////////////////////////////////

// function checkOPForm(form)
// {
// if(form.OPtitle.value == "") {
//   alert("Error: Need a title!");
//   form.Optitle.focus();
//   return false;
// }
// re = /^\w+$/;
// if(!re.test(form.OPtitle.value)) {
//   alert("Error: Need a title!");
//   form.OPtitle.focus();
//   return false;
// }
// return true;
// }


