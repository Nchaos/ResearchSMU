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