

$(document).ready(function(){
	$("#edit").click(editInfo);
	$(window).load(userInfo);
	
	
	
	$("#menu-toggle").click(function(e) {
	        e.preventDefault();
	        $("#wrapper").toggleClass("active");
	});
});

function userInfo(){
	
	$("#submission").css("display", 'none');
	console.log("userInfo");
	

	$.ajax({
		type: "POST",
		url: "api/index.php/userinfo",
		dataType: "json",
		success: function(data){

			var email = data['email'];
			var fname = data['firstName'];
			var lname = data['lastName'];
			var major = data['department'];
			var status = data['studentType'];
	
			var html_string1 = "<table><tr><td>Email: </td><td style='padding-left: 8em'>"+email+"</td></tr><tr><td>First Name: </td><td style='padding-left: 8em'>"+fname+"</td></tr><tr><td>Last Name: </td><td style='padding-left: 8em'>"+lname;
			var html_string2 = "</td></tr><tr><td>Department: </td><td style='padding-left: 8em'>"+major+"</td></tr><tr id='student'><td>Grad or Undergrad: </td><td style='padding-left: 8em'>"+status+"</td></tr><tr id='staff'><td></td><td style='padding-left: 8em'>"+status;
			var html_string3 = "</td></tr></table>";
			
			
			document.getElementById("info").innerHTML = html_string1 + html_string2 + html_string3;
			
			if(status == "Student"){
					$("#staff").css("display", 'none');
			}
			if(status == "Faculty"){
					$("#student").css("display", 'none');
					$("#resume").css("display", 'none');
			}
				
		},
		error: function(jqXHR, textStatus, errorThrown){alert(errorThrown);}
	});
	
	console.log("Done");
}

function editInfo() {
	$("#info").css("display", 'none');
	$("#edit").css("display", 'none');
	$("#submission").css("display", 'inline-block');
	
	var fname = document.createElement('div');
    fname.innerHTML = "First Name: <br><input type='text' id='first' name='fname'>";
    document.getElementById("newInfo").appendChild(fname);
    
    var lname = document.createElement('div');
    lname.innerHTML = "Last Name: <br><input type='text' id='last' name='lname'>";
    document.getElementById("newInfo").appendChild(lname);

    var oldpwd = document.createElement('div');
    oldpwd.innerHTML = "Old Password: <br><input type='password' id='oldpwd' name='password'>";
    document.getElementById("newInfo").appendChild(oldpwd);    
    
    var pwd1 = document.createElement('div');
    pwd1.innerHTML = "Password: <br><input type='password' id='pwd' name='password'>";
    document.getElementById("newInfo").appendChild(pwd1);
    
    var pwd2 = document.createElement('div');
    pwd2.innerHTML = "Password Confirmation: <br><input type='password' id='pwdCheck' name='confirm'>";
    document.getElementById("newInfo").appendChild(pwd2);
    
    // var dept = document.createElement('div');
    // dept.innerHTML = "Department: <br><input type='text' id='major' name='dept'>";
    // document.getElementById("newInfo").appendChild(dept);

    var dept =document.createElement('div');
    dept.id ='deptment';
    document.getElementById('newInfo').appendChild(dept);
 //    dept.innerHTML = "Department: <br><select name='major>'
	// 				<option selected disabled hidden label='Department'></option>
	// 				<option value='01'>Accounting</option>
	// 				<option value='02'>Advertising</option>
	// 				<option value='03'>Anthropology</option>
	// 				<option value='04'>Applied Physiology</option>
	// 				<option value='05'>Art</option>
	// 				<option value='06'>Art History</option>
	// 				<option value='07'>Art Management</option>
	// 				<option value='08'>Biological Sciences</option>
	// 				<option value='09'>Business</option>
	// 				<option value='10'>Chemistry</option>
	// 				<option value='11'>Civil &amp; Environmental Engineering</option>
	// 				<option value='12'>Communication Studies</option>
	// 				<option value='13'>Computer Science &amp; Engineering</option>
	// 				<option value='14'>Counseling</option> 
	// 				<option value='15'>Creative Computing</option>
	// 				<option value='16'>Dance</option>
	// 				<option value='17'>Dispute Resolution</option>
	// 				<option value='18'>Earth Sciences</option>
	// 				<option value='19'>Economics</option>
	// 				<option value='20'>Electrical Engineering</option>
	// 				<option value='21'>English</option>
	// 				<option value='22'>Film and Media Arts</option>
	// 				<option value='23'>Finance</option>
	// 				<option value='24'>Higher Eduction</option>
	// 				<option value='25'>History</option>
	// 				<option value='26'>Journalism</option>
	// 				<option value='27'>Management</option>
	// 				<option value='28'>Management Science</option>
	// 				<option value='29'>Marketing</option>
	// 				<option value='30'>Mathematics</option>
	// 				<option value='31'>Mechanical Engineering</option>
	// 				<option value='32'>Music</option>
	// 				<option value='33'>Philosophy</option>
	// 				<option value='34'>Physics</option>
	// 				<option value='35'>Political Science</option>
	// 				<option value='36'>Psychology</option>
	// 				<option value='37'>Real Estate</option>
	// 				<option value='38'>Religious Studies</option>
	// 				<option value='39'>Risk Management and Insurance</option>
	// 				<option value='40'>Sports Management</option>
	// 				<option value='41'>Sociology</option>
	// 				<option value='42'>Statisical Sciences</option>
	// 				<option value='43'>Teacher Education</option>
	// 				<option value='44'>Theatre</option>
	// 				<option value='45'>Wellness</option>
	// 				<option value='46'>World Language</option>
	// 			</select>";
	// document.getElementById("newInfo").appendChild(dept);

    var resume = document.createElement('div');
    resume.innerHTML = "Upload a Resume: <br><input type='file' name='resume'/><br>";
    document.getElementById("newInfo").appendChild(resume);
    
	var cancelButton = document.getElementById('submission').clicked = false;
	cancelButton.addEventListener('click', function() { 
		clicked = !clicked; 
		if(clicked){
	    	var fnameValue = document.getElementById("first").value;
	    	var lnameValue = document.getElementById("last").value;
	    	var pwd1Value = document.getElementById("pwd").value;
	    	var pwd2Value = document.getElementById("pwdCheck").value;
	    	var majorValue = document.getElementById("major").value;
	    	
	    	var pwdChecker = $("#submission").click(checkPassword);
		    
		    if(pwdChecker == true){
		    	var json_string = '{"fname":"'+fnameValue+'","lname":"'+lnameValue+'","password":"'+pwd1Value+'","confirm":"'+pwd2Value+'","major":"'+majorValue+'"}';
		    	console.log(json_string);
		    	
		    	$.ajax({
					type: "POST",
					url: "api/index.php/changeinfo",		
					datatype:"json",
					data: json_string,
					success: function() {
					  	windo.location.href = "user.html";   
					}
				});
		   }
    	}
	});
}

function checkPassword(event){
	event.preventDefault();
	
	///////////////////////////////////////////////////////////
	////////////		Password Validation 		///////////
	///////////////////////////////////////////////////////////
    if(document.getElementsByName("password")[0].value != "" && document.getElementsByName("password")[0].value == document.getElementsByName("confirm")[0].value) {
      if(document.getElementsByName("password")[0].value.length < 8) {
        alert("Error: Password must contain at least eight characters!");
        $("pwd").focus();
        return false;
      }
      re = /[0-9]/;
      if(!re.test(document.getElementsByName("password")[0].value)) {
        alert("Error: password must contain at least one number (0-9)!");
        $("pwd").focus();
        return false;
      }
      re = /[a-z]/;
      if(!re.test(document.getElementsByName("password")[0].value)) {
        alert("Error: password must contain at least one lowercase letter (a-z)!");
        $("pwd").focus();
        return false;
      }
      re = /[A-Z]/;
      if(!re.test(document.getElementsByName("password")[0].value)) {
        alert("Error: password must contain at least one uppercase letter (A-Z)!");
        $("pwd").focus();
        return false;
      }
    } else {
      alert("Error: Please check that you've entered and confirmed your password!");
      $("pwd").focus();
      return false;
    }
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
































