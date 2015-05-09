

$(document).ready(function(){
	$("#edit").click(editInfo);
	$(window).load(userInfo);
	$("#accountInfo").click(function(){
		$("#newInfo").css("display", 'none');
		userInfo();
	});
	$("#positions").click(position);
	
	$("#menu-toggle").click(function(e) {
	        e.preventDefault();
	        $("#wrapper").toggleClass("active");
	});
});

function position(){
	$("#edit").css("display", 'none');
	$("#info").css("display", 'none');
	$("#submission").css("display", 'none');
	$("#table").css("display", 'inline-block');
	$('#newInfo').empty();
	
	var count = 3;
	var titles = {0:"Title", 1:"Faculty Member", 2:"Department", 3:"Wage Type", 4:"Status"};
	var string1 = "<tr><td class='columns'>"+titles[0]+"</td><td class='columns'>"+titles[1]+"</td><td class='columns'>"+titles[2]+"</td><td class='columns'>"+titles[3];
	var string2 = "</td><td class='columns'>"+titles[4]+"</td>";
	var titleString = string1 + string2;
	var list = {titles:[
			{0:"Biometrics Analyst"},
			{1:"Secretary"},
			{2:"Scape Goat"}
		], 
		professors:[
			{0:"Jennifer Dworak"},
			{1:"Martin Lawrence"},
			{2:"Dexter"}
		],
		depts:[
			{0:"Computer Science"},
			{1:"Law"},
			{2:"Murder"}
		], 
		wage:[
			{0:"Paid"},
			{1:"Paid"},
			{2:"Work Study"}
		], 
		posStatus:[
			{0:"Open"},
			{1:"Filled"},
			{2:"Open"}
		]};
	
	
	
	var heading = document.createElement('div');
	heading.innerHTML = "<table>"+titleString+"</table>";
	document.getElementById("table").appendChild(heading);
	
	for(i = 0; i < count; i++){
		var highSet = list["titles"];
		var value = highSet[i];
		var jobs = value[i];
		
		var highSet = list["professors"];
		var value = highSet[i];
		var names = value[i];
		
		var highSet = list["depts"];
		var value = highSet[i];
		var majors = value[i];
		
		var highSet = list["wage"];
		var value = highSet[i];
		var wages = value[i];
		
		var highSet = list["posStatus"];
		var value = highSet[i];
		var status = value[i];
		
		var table = document.createElement('div');
	    var string1 = "<table id='table'><tr><td class='columns'>"+jobs+"</td><td class='columns'>"+names+"</td><td class='columns'>"+majors+"</td><td class='columns'>"+wages;
	    var string2 = "</td><td class='columns'>"+status+"</td></tr></table>"
	    table.innerHTML = string1 + string2;
	    document.getElementById("table").appendChild(table);
	
	}
	
}

function userInfo(){
	
	$("#edit").css("display", 'inline-block');
	$("#info").css("display", 'inline-block');
	$("#submission").css("display", 'none');
	$('#table').empty();

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
	
}

function editInfo() {
	$("#info").css("display", 'none');
	$("#edit").css("display", 'none');
	$("#submission").css("display", 'inline-block');
	$("#newInfo").css("display", 'inline-block');
	$('#table').empty();
	$('#newInfo').empty();
	
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
    pwd2.innerHTML = "Password Confirmation: <br><input type='password' id='pwdCheck' name='confirm'><br><br>";
    document.getElementById("newInfo").appendChild(pwd2);
    
    // var dept = document.createElement('div');
    // dept.innerHTML = "Department: <br><input type='text' id='major' name='dept'>";
    // document.getElementById("newInfo").appendChild(dept);


    var deptArray = ["Accounting",
    				 "Advertising",
    				 "Anthropology", 
    				 "Applied Physiology", 
    				 "Art",
    				 "Art History",
    				 "Art Management",
    				 "Biological Sciences",
    				 "Business",
    				 "Chemistry", 
    				 "Civil & Environmental Engineering",
    				 "Communication Studies",
    				 "Computer Sciences & Engineering",
    				 "Counseling",
    				 "Creative Computing",
    				 "Dance",
    				 "Dispute Resolution",
    				 "Earth Sciences",
    				 "Economics",
    				 "Electrical Engineering",
    				 "English",
    				 "Film and Media Arts",
    				 "Finance",
    				 "Higher Education",
    				 "History",
    				 "Journalism",
    				 "Management",
    				 "Management Science",
    				 "Marketing",
    				 "Mathematics",
    				 "Mechanical",
    				 "Music",
    				 "Philosophy",
    				 "Physics",
    				 "Political Science",
    				 "Psychology",
    				 "Real Estate",
    				 "Religious Studies",
    				 "Risk Management and Insurance",
    				 "Sports Management",
    				 "Sociology",
    				 "Statisical Sciences",
    				 "Teacher Education",
    				 "Theatre",
    				 "Wellness",
    				 "World Language"
    ];


    var dept =document.createElement('select');
    dept.id ='deptment';
    dept.label = "Department: ";
    document.getElementById('newInfo').appendChild(dept);



    for (var i = 0; i < deptArray.length; i++) {
    var option = document.createElement("option");
    option.value = i;
    option.text = deptArray[i];
    dept.appendChild(option);
}

 //    dept.innerHTML = "Department: <br><select name='major>'
	// 				
	// document.getElementById("newInfo").appendChild(dept);

    var resume = document.createElement('div');
    resume.innerHTML = "<br>Upload a Resume: <br><input type='file' name='resume'/><br>";
    document.getElementById("newInfo").appendChild(resume);
    
	//var cancelButton = document.getElementById('submission').clicked = false;
	$('#submission').on('click', function(){
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
					  	window.location.href = "user.html";   
					}
				});
		   }
    	}

	})
	//cancelButton.addEventListener('click', function() { 
		// clicked = !clicked; 
		// if(clicked){
	 //    	var fnameValue = document.getElementById("first").value;
	 //    	var lnameValue = document.getElementById("last").value;
	 //    	var pwd1Value = document.getElementById("pwd").value;
	 //    	var pwd2Value = document.getElementById("pwdCheck").value;
	 //    	var majorValue = document.getElementById("major").value;
	    	
	 //    	var pwdChecker = $("#submission").click(checkPassword);
		    
		//     if(pwdChecker == true){
		//     	var json_string = '{"fname":"'+fnameValue+'","lname":"'+lnameValue+'","password":"'+pwd1Value+'","confirm":"'+pwd2Value+'","major":"'+majorValue+'"}';
		//     	console.log(json_string);
		    	
		//     	$.ajax({
		// 			type: "POST",
		// 			url: "api/index.php/changeinfo",		
		// 			datatype:"json",
		// 			data: json_string,
		// 			success: function() {
		// 			  	window.location.href = "user.html";   
		// 			}
		// 		});
		//    }
  //   	}
	//});
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
































