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



$(document).ready(function() {

function tabDeptHandler() {
    var deptValue = document.getElementsByTagName("a")[0].getAttribute("data-value");
	var filter = {"department" : deptValue};
	var searchString = JSON.stringify(filter);
	     
	$.ajax({
		type: "POST",
		url: "api/index.php/filterDepartment",
		datatype:"json",
		data: searchString
	});
	
	window.location.href = "search.html";
}


function tabInstitutionHandler() {
	var instValue = document.getElementsByTagName("a")[0].getAttribute("data-value");
	var filter = {"institution" : instValue};
	var searchString = JSON.stringify(filter);
	
	$.ajax({
		type: "POST",
		url: "api/index.php/filterSchool",
		datatype:"json",
		data: searchString
	});
	
	window.location.href = "search.html";


}
});

// $(document).ready(function() {
//     $('#resultsTable').dataTable( {
//     	"processing": true,
//      	"serverSide": true,
//         "ajaxSource": "api/datatables.php",
//         "columns": [
//             { "data": "rName" },
//             { "data": "fName" },
//             { "data": "dName" },
//             { "data": "iName" }
//         ]
//     } );
// } );



// $(document).ready(function() {
//     $('#resultsTable').dataTable( {
//         "ajaxSource": "ajaxtest/data.txt",
//         // "columns": [
//         //     { "data": "rName" },
//         //     { "data": "fName" },
//         //     { "data": "dName" },
//         //     { "data": "iName" }
//         // ]
//     } );
// } );


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
		    data: JSON.stringify(dataTest), 
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


//uncomment to check if it works. probably doesnt. comment out other two methods if you try this one


// $(document).ready(function() {
// 	var dataTest = {
// 	    'institution' : "Lyle",
// 	    'department' : "CSE"
// 		};
// 	$('#resultsTable').dataTable( {
// 		"ajaxSource": $.ajax({
// 		    type: 'POST',
// 		    url: 'api/datatables.php',
// 		    data: JSON.stringify(dataTest), 
// 		    datatype: "json",
// 		}),

//         "columns": [
//             { "data": "rName" },
//             { "data": "fName" },
//             { "data": "dName" },
//             { "data": "iName" }
//         ]
// 	});
// });


//try this out. comment out and uncomment others if it doesnt work, which it probably wont

// $(document).ready(function() {
// 	var dataTest = {
// 	    'institution' : "Lyle",
// 	    'department' : "CSE"
// 		};
// 	$('#resultsTable').dataTable( {
// 		$.ajax({
// 		    type: 'POST',
// 		    url: 'api/datatables.php',
// 		    data: JSON.stringify(dataTest), 
// 		    datatype: "json",
// 		});
//         "columns": [
//             { "data": "rName" },
//             { "data": "fName" },
//             { "data": "dName" },
//             { "data": "iName" }
//         ]
// 	});
// });


//uncomment to check if it works. probably doesnt. comment out other two methods if you try this one

// $(document).ready(function() {					//dont think this is working.
// 	var dataTest = {
// 	    'institution' : "Lyle",
// 	    'department' : "CSE"
// 		};
//     $('#resultsTable').dataTable( {
//         "ajax": {
//         	url:"api/datatables.php",
//         	type: "POST"
//         	data: JSON.stringify(dataTest)
//         },
//         "columns": [
//             { "data": "rName" },
//             { "data": "fName" },
//             { "data": "dName" },
//             { "data": "iName" }
//         ]
//     } );
// } );

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
