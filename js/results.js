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
  var rData = JSON.strigify($)
  return data; 
}

