$(document).ready(function(){
	$("#register").click(checkForm);
});
  
function checkForm(event)
{
	event.preventDefault();
	//if(form.firstName.value == "") {
	if($("#firstName").val() == "") {
		alert("Error: First Name!");
		$("#firstName").focus();
		return false;
	}
	
	re = /^\w+$/;
	//if(!re.test(form.firstName.value)) {
	if(!re.test($("#firstName").val())) {
		alert("Error: First Name!");
		$("#firstName").focus();
		return false;
	}
	
	if($("#lastName").val() == "") {
		alert("Error: Last Name!");
		$("#lastName").focus();
		return false;
	}
	
	if(!re.test($("#lastName").val())) {
		alert("Error: Last Name!");
		$("#lastName").focus();
		return false;
	}
	
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
	
	request.done(function (response, textStatus, jqXHR){
		console.log("Request Test");
	});
	
	request.fail(function(jqXHR, textStatus, errorThrown){
		console.error("The following error occured: " + textStatus, errorThrown);
	});
	
	return false;
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


