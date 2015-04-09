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

// function register(form) {
//     if(checkForm(form)) {
//         $.ajax({
//             url: "api/register.php",
//             type: "post",
//             data: {
//                 "firstName":$("#firstName").val(), 
//                 "lastName":$("#lastName").val(), 
//                 "email":$("#email").val(), 
//                 "password":$("#password").val()
//             },
//             dataType: "json",
//             success: function(data) {
//                 if(data.success) {
//                     alert("Welcome, " + data.firstName + "!");
//                     window.location = "index.php";
//                 }
//                 else
//                     alert("Error: " + data.errorType);
//             }
//         });
//     }


// $(document).ready(function()
//       if(checkForm(form)){
//           var firstName = $("#firstName").val();
//           var lastName = $("#lastName").val();
//           var email = $("#email").val();
//           var password = $("#password").val();
//           var password2 = $("#password2").val();
//           $.post("index.php", {
//             firstName: firstName,
//             lastName: lastName,
//             email: email,
//             password:, password
//           }, function(data) {
//             if (data == "You have Successfully Registered.....") {
//               $("form")[0].reset();
//             }
//             alert(data);
//           });
//         }
//       });

