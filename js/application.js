$(document).ready(function() {
    
collectInfo();

});


function collectInfo() {
	
	
	var jsonValue;
	
	$.getJSON('api/index.php/appInfo',function(jsonData){
		jsonValue = jsonData;
		$('.title-info').html(jsonValue.title);
		$('.professor').html(jsonValue.professor);
		$('.start').html(jsonValue.startDate);
		$('.count').html(jsonValue.position);
		$('.desc').html(jsonValue.desc);
	});
	
	//jsonValue = JSON.stringify(jsonValue);
	
	//alert(jsonValue);
	
	
	
	
	
	
}





/*
 * Position Description
 * Who posted it
 * Date of position start
 * Total Positions
 * Name of position
 * 
 */



