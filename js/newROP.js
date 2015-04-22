$(document).ready(function(){
  #("#user_form").click(checkOPForm);
});
  
function checkOPForm(form){
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
    
    var dataString = 
	{
		"name": document.getElementsByName("name")[0].value,
		"description": document.getElementsByName("description")[0].value,
		"dateStart": document.getElementsByName("startDate")[0].value,
		"dateEnd": document.getElementsByName("endDate")[0].value,
		"numPositions": document.getElementsByName("numOfPos")[0].value,
		"paid": document.getElementsByName("paid")[0].value,
		"workStudy": document.getElementsByName("workStudy")[0].value,
		"graduate": document.getElementsByName("undergraduate")[0].value,
		"undergraduate": document.getElementsByName("graduate")[0].value,
		"deptId": document.getElementsByName("deptId")[0].value
	};
	
	console.log(dataString);

	// AJAX code to submit form.
	$.ajax({
		type: "POST",
		url: "api/index.php/createResearchOpporunity",
		datatype:"json",
		data: dataString,
		/*success: function(result) {
			var json = JSON.parse(result);
			if(json === null){
				window.alert("Failure");
				return false;
			}
			else {
				alert("success!");
			}
		}*/
	});
    
}