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


// function format ( d ) {
// 	console.log(d);
//     return 'Title: '+d.rName+'<br>'+
//         'Professor: '+d.pName+'<br>'+
//         'Department: '+d.dName+'<br>'+ 
//         'School: '+d.iName+'<br>'+
//         'The child row can contain any data you wish, including links, images, inner tables etc.';
// }

/* Formatting function for row details - modify as you need */
function format ( d ) {
    // `d` is the original data object for the row
    myButton = document.createElement("input");
	myButton.type = "button";
	myButton.value = "my button";
    return '<div class="slider">'+
        '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
            '<tr>'+
                '<td>Title:</td>'+
                '<td>'+d.rName+'</td>'+
            '</tr>'+
            '<tr>'+
                '<td>Professor:</td>'+
                '<td>'+null+'</td>'+
            '</tr>'+
            '<tr>'+
                '<td>Start Date:</td>'+
                '<td>'+null+'</td>'+
            '</tr>'+
            '<tr>'+
                '<td>End Date:</td>'+
                '<td>'+null+'</td>'+
            '</tr>'+
            '<tr>'+
                '<td>Paid:</td>'+
                '<td>'+null+'</td>'+
            '</tr>'+
            '<tr>'+
                '<td>Work Study:</td>'+
                '<td>'+null+'</td>'+
            '</tr>'+
            '<tr>'+
                '<td>Accepts Undergrad:</td>'+
                '<td>'+null+'</td>'+
            '</tr>'+
            '<tr>'+
                '<td>Accepts Grad:</td>'+
                '<td>'+null+'</td>'+
            '</tr>'+
            '<tr>'+
                '<td>Description:</td>'+
                '<td>And any further details here (images etc)...</td>'+
            '</tr>'+
            '<tr>'+
                '<td>Apply</td>'+
                '<td>' +myButton+ '</td>'+
            '</tr>'+
        '</table>'+
    '</div>';
}



function tabDeptHandler(num) {

  	var deptValue = num;
  	var searchString = {"department" : deptValue};

  	$("#filtersbox").css("visibility", 'visible');
  	$("#description").css("display", 'none');
  	$("#resultsTable").css("display", 'inline-table');

	if ( $.fn.dataTable.isDataTable( '#resultsTable' ) ) {
    	var dt = $('#resultsTable').DataTable();
    	dt.destroy();
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
            { "data": "pName" },
            { "data": "dName" },
            { "data": "iName" }
        ],
        	"order": [[1, 'asc']]
	});
 
    $('#resultsTable tbody').on( 'click', 'tr td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = dt.row( tr );
 
        if ( row.child.isShown() ) {
        	row.child.hide();
            tr.removeClass( 'shown' );
        }
        else {
        	row.child( format( row.data() ), 'no-padding' ).show();
            tr.addClass( 'shown' );

        }
    } );
  

}


function tabInstitutionHandler(num) {
  
  	var instValue = num; 
  	var searchString = {"institution" : instValue};

  	$("#filtersbox").css("visibility", 'visible');
  	$("#description").css("display", 'none');
  	$("#resultsTable").css("display", 'inline-table');

	if ( $.fn.dataTable.isDataTable( '#resultsTable' ) ) {
    	var dt = $('#resultsTable').DataTable();
		dt.destroy();
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
            { "data": "pName" },
            { "data": "dName" },
            { "data": "iName" }
        ],
            "order": [[1, 'asc']]
	});

 
    $('#resultsTable tbody').on( 'click', 'tr td.details-control', function () {
        var tr = $(this).closest('tr');
        //console.log(tr);
        var row = dt.row( tr );
        //console.log(row);
		//console.log(row.data().rName);
 
        if ( row.child.isShown() ) {
			$('div.slider', row.child()).slideUp( function () {
    		row.child.hide();
    		tr.removeClass('shown');
			} );
        }
        else {
            row.child( format( row.data() ), 'no-padding' ).show();
            tr.addClass( 'shown' );
			$('div.slider', row.child()).slideDown();
        }

    } );

}


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

