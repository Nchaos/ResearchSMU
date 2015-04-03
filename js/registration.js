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
    if(form.pwd1.value != "" && form.pwd1.value == form.pwd2.value) {
      if(!checkPassword(form.pwd1.value)) {
        alert("The password you have entered is not valid!");
        form.pwd1.focus();
        return false;
      }
    } else {
      alert("Error: Please check that you've entered and confirmed your password!");
      form.pwd1.focus();
      return false;
    }
    return true;
  }