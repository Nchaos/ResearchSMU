
// function format ( d ) {
// 	console.log(d);
//     return 'Title: '+d.rName+'<br>'+
//         'Professor: '+d.pName+'<br>'+
//         'Number of Positions: ' +d.numPositions+'<br>'
//         'Start Date: '+d.startDate+'<br>'+ 
//         'End Date: '+d.endDate+'<br>'+
//         '';
// }

/* Formatting function for row details - modify as you need */
function format ( d ) {
    // `d` is the original data object for the row
    return '<div class="slider">'+
        '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
            '<tr>'+
                '<td>Title:</td>'+
                '<td>'+d.rName+'</td>'+
            '</tr>'+
            '<tr>'+
                '<td>Professor:</td>'+
                '<td>'+d.pName+'</td>'+
            '</tr>'+
            '<tr>'+
                '<td>Graduate Students:</td>'+
                '<td>'+d.gradStudents+'</td>'+
            '</tr>'+
            // '<tr>'+
            //     '<td>Number of Positions:</td>'+
            //     '<td>'+d.numPositions+'</td>'+
            // '</tr>'+
            // '<tr>'+
            //     '<td>Start Date:</td>'+
            //     '<td>'+d.startDate+'</td>'+
            // '</tr>'+
            // '<tr>'+
            //     '<td>End Date:</td>'+
            //     '<td>'+d.endDate+'</td>'+
            // '</tr>'+
            // '<tr>'+
            //     '<td>Paid:</td>'+
            //     '<td>'+d.paid+'</td>'+
            // '</tr>'+
            // '<tr>'+
            //     '<td>Work Study:</td>'+
            //     '<td>'+d.workStudy+'</td>'+
            // '</tr>'+
            // '<tr>'+
            //     '<td>Accepts Undergrad:</td>'+
            //     '<td>'+d.acceptsUndergrad+'</td>'+
            // '</tr>'+
            // '<tr>'+
            //     '<td>Accepts Grad:</td>'+
            //     '<td>'+d.acceptsGrad+'</td>'+
            // '</tr>'+
            '<tr>'+
                '<td>Description:</td>'+
                '<td>'+d.descript+'</td>'+
            '</tr>'+
            '<tr>'+
                '<td></td>'+
                //<td><button type="button" class="btn btn-primary">Primary</button></td>
                '<td><button type="button" class="btn btn-primary" onClick="apply('+d.ResearchOp_ID+');">Apply</button></td>'+
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

    window.copyDt = dt;
    console.log(window.copyDt);


}

function tabInstitutionHandler(num) {
  	//console.log("Inst handler: " + num);
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

   	 window.copyDt = dt;
   	 console.log(window.copyDt);

       //var tr = $(this).closest('tr');


}

    $('#resultsTable tbody').on( 'click', 'tr td.details-control', function () {
        var tr = $(this).closest('tr');
        //window.tr = tr;
        //console.log(tr);
        var row = window.copyDt.row(tr);
        //var row = dt.row( tr );
        //window.row = row;
        //console.log(row);
        //console.log(row.data().rName);

        //console.log("Row is...");

        if ( row.child.isShown() ) {
            //console.log("Hiding");
            $('div.slider', row.child()).slideUp( function () {
            row.child.hide();
            tr.removeClass('shown');
            } );
        }
        else {
            //console.log("Showing")
            row.child( format( row.data() ), 'no-padding' ).show();
            tr.addClass( 'shown' );
            $('div.slider', row.child()).slideDown();
        }

    } );




function filter() {
	getCheckedBoxes();
	//console.log(CheckboxHandler);
    //window.alert("It Works!!!!!!! Filter: " + CheckboxHandler);
  	var searchString = CheckboxHandler.dept;
  	//console.log(searchString);

  	searchString = JSON.stringify(searchString);
  	//console.log(searchString);

  	if ( $.fn.dataTable.isDataTable( '#resultsTable' ) ) {
   	 var dt = $('#resultsTable').DataTable();
		dt.destroy();
	}

	var dt = $('#resultsTable').DataTable( {
		"processing": true,
		"serverSide": true,
		"ajax":{
		    type: 'POST',
		    url: 'api/filtertables.php/datatable',
		    data: CheckboxHandler, 
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


    window.copyDt = dt;
    console.log(window.copyDt);
}


// Object to store a json on all the depts selected
var CheckboxHandler = new Object();
CheckboxHandler.dept = [];

function getCheckedBoxes() {
	CheckboxHandler.dept = [];
	var checkboxes = document.getElementsByClassName('Dept');
	//alert(checkboxes.length);
	for (var i =0; i < checkboxes.length; i++){
		if (checkboxes[i].checked == true){
			//alert("holy shit it works!");
			CheckboxHandler.dept[i] = checkboxes[i].value;
		}
		// else{
		// 	CheckboxHandler.dept[i] = null;
		// }
	}

}

// Function for applying to a Research Op
function apply(researchOp){
    console.log(researchOp);
    var applyData = {"opID" : researchOp};
    console.log(applyData);
    $.ajax({
        type: "POST",
        url: "api/index.php/apply",
        datatype: JSON,
        data: applyData,
        success: function(){
            alert('You have applied!');
            //window.location.href = 'index.html';

        },
        error: function() {
            alert("Internal Server Error: Log in as a student to Apply.")
        }
    });


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

