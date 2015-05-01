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


function format ( d ) {
    return 'Full name: '+d.rName+' '+d.fName+'<br>'+
        'Salary: '+d.salary+'<br>'+
        'The child row can contain any data you wish, including links, images, inner tables etc.';
}

function tabDeptHandler(num) {
	

  	var deptValue = num;
  	var searchString = {"department" : deptValue};
  	// var searchString = JSON.stringify(filter);
       // console.log(deptValue);
       // console.log(searchString);
  
  	// $.ajax({
   //  	type: "POST",
   //  	url: "api/index.php/filterDepartment",
   //  	success: null,
   //  	datatype:"json",
   //  	data: searchString
  	// });

	if ( $.fn.dataTable.isDataTable( '#resultsTable' ) ) {
    	table = $('#resultsTable').DataTable();
    	table.destroy();
	}

  	var dt = $('#resultsTable').DataTable( {
		"processing": true,
		"serverSide": true,
		"ajax":{
		    type: 'POST',
		    url: 'api/datatables.php/datatable',
		    data: searchString, 
		    datatype: "json",
		},

        "columns": [
            {
                "className":      'details-control',
                "orderable":      false,
                "data":           null,
                "defaultContent": ''
            },
            { "data": "rName" },
            { "data": "fName" },
            { "data": "dName" },
            { "data": "iName" }
        ],
        	"order": [[1, 'asc']]
	});

    // Array to track the ids of the details displayed rows
    var detailRows = [];
 
    $('#resultsTable tbody').on( 'click', 'tr td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = dt.row( tr );
        var idx = $.inArray( tr.attr('id'), detailRows );
 
        if ( row.child.isShown() ) {
            tr.removeClass( 'details' );
            row.child.hide();
 
            // Remove from the 'open' array
            detailRows.splice( idx, 1 );
        }
        else {
            tr.addClass( 'details' );
            row.child( format( row.data() ) ).show();
 
            // Add to the 'open' array
            if ( idx === -1 ) {
                detailRows.push( tr.attr('id') );
            }
        }
    } );
 
    // On each draw, loop over the `detailRows` array and show any child rows
    dt.on( 'draw', function () {
        $.each( detailRows, function ( i, id ) {
            $('#'+id+' td.details-control').trigger( 'click' );
        } );
    } );
  

  //window.location.href = "search.html";
}


function tabInstitutionHandler(num) {
  
  	var instValue = num; 
  	var searchString = {"institution" : instValue};
  	// var searchString = JSON.stringify(filter);

       // console.log(instValue);
       // console.log(searchString);

  	// $.ajax({
   //  	type: "POST",
   //  	url: "api/index.php/filterSchool",
   //  	success: null,
   //  	datatype:"json",
   //  	data: searchString
  	// });

	if ( $.fn.dataTable.isDataTable( '#resultsTable' ) ) {
    	table = $('#resultsTable').DataTable();
    	table.destroy();
	}

   	var dt = $('#resultsTable').DataTable( {
		"processing": true,
		"serverSide": true,
		"ajax":{
		    type: 'POST',
		    url: 'api/datatables.php/datatable',
		    data: searchString, 
		    datatype: "json",
		},

        "columns": [
            {
                "className":      'details-control',
                "orderable":      false,
                "data":           null,
                "defaultContent": ''
            },
            { "data": "rName" },
            { "data": "fName" },
            { "data": "dName" },
            { "data": "iName" }
        ],
            "order": [[1, 'asc']]
	});

    // Array to track the ids of the details displayed rows
	var detailRows = [];
 
    $('#resultsTable tbody').on( 'click', 'tr td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = dt.row( tr );
        var idx = $.inArray( tr.attr('id'), detailRows );
 
        if ( row.child.isShown() ) {
            tr.removeClass( 'details' );
            row.child.hide();
 
            // Remove from the 'open' array
            detailRows.splice( idx, 1 );
        }
        else {
            tr.addClass( 'details' );
            row.child( format( row.data() ) ).show();
 
            // Add to the 'open' array
            if ( idx === -1 ) {
                detailRows.push( tr.attr('id') );
            }
        }
    } );
 
    // On each draw, loop over the `detailRows` array and show any child rows
    dt.on( 'draw', function () {
        $.each( detailRows, function ( i, id ) {
            $('#'+id+' td.details-control').trigger( 'click' );
        } );
    } );
  	
  //window.location.href = "search.html";


}


// $(document).ready(function() {
// 	// var dataTest = {
// 	//     institution : "Lyle",
// 	//     department : "CSE"
// 	// 	};
// 	$('#resultsTable').dataTable( {
// 		"processing": true,
// 		"serverSide": true,
// 		"ajax":{
// 		    type: 'POST',
// 		    url: 'api/datatables.php/datatable',
// 		    data: dataTest, 
// 		    datatype: "json",
// 		},

//         "columns": [
//             { "data": "rName" },
//             { "data": "fName" },
//             { "data": "dName" },
//             { "data": "iName" }
//         ]
// 	});
// });



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

