$(document).ready(function(){
    $("#researchOps").click(facultyROPHandler());
});


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
            '<tr>'+
                '<td>Description:</td>'+
                '<td>'+d.descript+'</td>'+
            '</tr>'+
        '</table>'+
    '</div>';
}

function facultyROPHandler() {

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
            //data: searchString, 
            //datatype: "json",
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