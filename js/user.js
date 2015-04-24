$("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("active");
});

$("#edit").click(function() {
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
});
