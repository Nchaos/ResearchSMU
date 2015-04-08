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

function register(form) {
    if(checkForm(form)) {
        $.ajax({
            url: "api/register.php",
            type: "post",
            data: {
                "firstName":$("#firstName").val(), 
                "lastName":$("#lastName").val(), 
                "email":$("#email").val(), 
                "password":$("#password").val()
            },
            dataType: "json",
            success: function(data) {
                if(data.success) {
                    alert("Welcome, " + data.firstName + "!");
                    window.location = "index.php";
                }
                else
                    alert("Error: " + data.errorType);
            }
        });
    }


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

