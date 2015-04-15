  function checkForm(event)
  {
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
      if(!checkPassword($("#password").val())) {
        alert("The password you have entered is not valid!");
        form.password.focus();
        return false;
      }
    } else {
      alert("Error: Please check that you've entered and confirmed your password!");
      $("#password").focus();
      return false;
    }
    var firstName = document.getElementById("firstName").value;
    var lastName = document.getElementById("lastName").value;
    var email = document.getElementById("email").value;
    var password = document.getElementById("password").value;
    var check = document.getElementById("check").value;
    // Returns successful data submission message when the entered information is stored in database.
    //var dataString = 'firstName=' + form.firstName + '&lastName=' + form.lastName + '&email=' + form.email + '&password=' + form.password + '&check=' + form.check;
    var dataString = 
    {
      "firstName": $("#firstName").val(),
      "lastName": $("#lastName").val(),
      "email": $("#email").val(),
      "password": $("#password").val(),
      "check": $("#check").val()
    };
	console.log(dataString);

    // AJAX code to submit form.
    $.ajax({
    type: "POST",
    url: "api/index.php/createAccount",
    dataType:"json",
    data: dataString,
    success: function() {
      alert("success!");
    }
    });
    return false;
  }



