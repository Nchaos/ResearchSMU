// $(document).ready(function() {
    

// });


function getCheckedBoxes(checkboxName) {
	var checkboxes = document.querySelectorAll('input[class="' + checkboxName + '"]:checked'), values = [];
	Array.prototype.forEach.call(checkboxes, function(el)	{
		values.push(el.value);
	});
	return values;
}


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


function tabDeptHandlerSearch(num) {
	
  	var deptValue = num;
  	var searchString = {"department" : deptValue};
  	// var searchString = JSON.stringify(filter);
       // console.log(deptValue);
       // console.log(searchString);
  
  	$.ajax({
    	type: "POST",
    	url: "api/index.php/filterDepartment",
    	success: null,
    	datatype:"json",
    	data: searchString
  	});
  

  //window.location.href = "search.html";
}


function tabInstitutionHandlerSearch(num) {
  
  	var instValue = num; 
  	var searchString = {"institution" : instValue};
  	// var searchString = JSON.stringify(filter);

       // console.log(instValue);
       // console.log(searchString);

  	$.ajax({
    	type: "POST",
    	url: "api/index.php/filterSchool",
    	success: null,
    	datatype:"json",
    	data: searchString
  	});
  	
  //window.location.href = "search.html";


}


$(document).ready(function() {
	var dataTest = {
	    institution : "Lyle",
	    department : "CSE"
		};
	$('#resultsTable').dataTable( {
		"processing": true,
		"serverSide": true,
		"ajax":{
		    type: 'POST',
		    url: 'api/datatables.php/datatable',
		    data: dataTest, 
		    datatype: "json",
		},

        "columns": [
            { "data": "rName" },
            { "data": "fName" },
            { "data": "dName" },
            { "data": "iName" }
        ]
	});
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


function filter(filter) {
    window.alert("It Works!!!!!!! Filter: "+filter)
}


// $(document).ready( function () {
//     $('#resultsTable').DataTable();
// } );
