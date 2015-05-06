

$(document).ready(function(){
	$("#edit").click(editInfo);
	$(window).load(userInfo);
	
	
	
	$("#menu-toggle").click(function(e) {
	        e.preventDefault();
	        $("#wrapper").toggleClass("active");
	});
});

function userInfo(){
	
	
	console.log("userInfo");
	
	document.getElementById("info").innerHTML = "Insert Info";
	
	
	
	
	
	
	
	
}

function editInfo() {
	var fname = document.createElement('div');
    fname.innerHTML = "First Name: <br><input type='text' name='fname'>";
    document.getElementById(fname).appendChild(fname);
    
    var lname = document.createElement('div');
    lname.innerHTML = "Last Name: <br><input type='text' name='lname'>";
    document.getElementById(lname).appendChild(lname);
    
    var pwd1 = document.createElement('div');
    pwd1.innerHTML = "Password: <br><input type='text' name='pwd'>";
    document.getElementById(pwd1).appendChild(pwd1);
    
    var pwd2 = document.createElement('div');
    pwd2.innerHTML = "Password Confirmation: <br><input type='text' name='confirm'>";
    document.getElementById(pwd2).appendChild(pwd2);
    
    var dept = document.createElement('div');
    dept.innerHTML = "Department: <br><input type='text' name='dept'>";
    document.getElementById(dept).appendChild(dept);
}

function uploadResume(){
	var form = document.getElementById('uploadForm');
	var file = document.getElementById('file-select');
	var button = document.getElementById('upload');
	
	form.onsubmit = function(event){
		event.preventDefault();
		
		//Update Button
		button.innerHTML = 'Browsing...';
		
		//code here
		var files = file.files;
		var formData = new FormData();
		
		for (var i = 0; i < files.length; i++) {
			  var file = files[i];
			
			  // Check the file type. pdf.* mat be wrong
			  if (!file.type.match('pdf.*')) {
			    console.log("Warning, I think you picked the wrong file type!!");
			    continue;
			  }
			
			  // Add the file to the request.
			  formData.append('resume[]', file, file.name);
		}
		
		
		var request = new XMLHttpRequest();
		request.open('POST', 'api/index.php/uploadFunction', true);
		
		request.onload = function() {
			if (request.status === 200)
				button.innerHTML = 'Uploaded';
			else
				console.log('An error occured in request');
		};
		
		request.send(formData);
	};
}
































