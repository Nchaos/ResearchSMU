  function checkForm(form)
  {
    if(form.firstName.value == "") {
      alert("Error: First Name!");
      form.firstName.focus();
      return false;
    }
    re = /^\w+$/;
    if(!re.test(form.firstName.value)) {
      alert("Error: First Name!");
      form.firstName.focus();
      return false;
    }
	if(form.lastName.value == "") {
      alert("Error: Last Name!");
      form.lastName.focus();
      return false;
    }
    if(!re.test(form.lastName.value)) {
      alert("Error: Last Name!");
      form.lastName.focus();
      return false;
    }
    if(form.password.value != "" && form.password.value == form.password2.value) {
      if(!checkPassword(form.password.value)) {
        alert("The password you have entered is not valid!");
        form.password.focus();
        return false;
      }
    } else {
      alert("Error: Please check that you've entered and confirmed your password!");
      form.password.focus();
      return false;
    }
    return true;
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


function myFunction() {
    var firstName = document.getElementById("firstName").value;
    var lastName = document.getElementById("lastName").value;
    var email = document.getElementById("email").value;
    var password = document.getElementById("password").value;
    var check = document.getElementById("check").value;
    // Returns successful data submission message when the entered information is stored in database.
    var dataString = 'firstName=' + firstname + '&lastName=' + lastName + '&email=' + email + '&password=' + password + '&check=' + check;
    if (firstName == '' ||  lastName == '' || email == '' || password == '' || check == '') {
      alert("Please Fill All Fields");
    }
   else {
    // AJAX code to submit form.
    $.ajax({
    type: "POST",
    url: "index.php",
    dataType:json,
    data: dataString,
    success: function() {
      alert("success!");
    }
    });
    }
    return false;
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

