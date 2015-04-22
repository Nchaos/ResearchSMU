$(document).ready(function() {
    

});


var CheckboxHandler = new Object();


CheckboxHandler.isChecked = function (checkboxObj) {
  return(checkboxObj.checked == true);
};

function getFormValues(oForm, skip_elements) {
   
  var elements = oForm.elements; 
  var data = [];
  var element_value = null;

  for(var i = 0; i < elements.length; i++) {
     
	var field_type = elements[i].type.toLowerCase();
	var element_name = elements[i].getAttribute("name");
	
	if(!skip_elements.length ||  !skip_elements.in_array(element_name)) {
	
	switch(field_type) {
		case "checkbox":
			
			element_value = CheckboxHandler.isChecked(elements[i]);
			data.push(element_name + ': ' + element_value);
			break;

			default: 
			break;
	}
        }
  }	
  var rData = JSON.strigify($);
  return data; 
}




$(document).ready(function() {

function tabDeptHandler() {
	
	var filter = {"department" : document.getElementByName("#Dept")[0].value};
	var searchString = JSON.stringify(filter);
	
	$.ajax({
		type: "POST",
		url: "api/index.php/filterDepartment",
		datatype:"json",
		data: searchString
	});
	
	
}


function tabInstitutionHandler() {
	
	var filter = {"institution" : document.getElementByName("#Inst")[0].value};
	var searchString = JSON.stringify(filter);
	
	$.ajax({
		type: "POST",
		url: "api/index.php/filterSchool",
		datatype:"json",
		data: searchString
	});
	
	//window.location.href
}
});


$(document).ready(function() {
function autocomplete() {
		
	$("#searchField").autocomplete({
	source: "api/index.php/autocomplete",
	minLength: 2,//search after two characters
	select: function(event,ui){
	    //do something
	    }
	});
}
});



















