  // Check browser support
// if (typeof(Storage) != "undefined") {
//     $(document).ready(function() {
//       $('.logout').css('display','none');
//     });
// } else {
//     $(document).ready(function() {
//       $('.login').css('display','none');
//       $('.register').hide();
//     });
// }

$(document).ready(function() {

	// Check all Departments functions

	//////////////////////
	//        Dedman
	//////////////////////
	function toggleDedman(source) {
	  checkboxes = document.getElementsByName('Dedman');
	  for(var i=0, n=checkboxes.length;i<n;i++) {
		checkboxes[i].checked = source.checked;
	  }
	}

	//////////////////////
	//        Lyle
	//////////////////////
	function toggleLyle(source) {
	  checkboxes = document.getElementsByName('Lyle');
	  for(var i=0, n=checkboxes.length;i<n;i++) {
		checkboxes[i].checked = source.checked;
	  }
	}

	//////////////////////
	//        Cox
	//////////////////////
	function toggleCox(source) {
	  checkboxes = document.getElementsByName('Cox');
	  for(var i=0, n=checkboxes.length;i<n;i++) {
		checkboxes[i].checked = source.checked;
	  }
	}

	//////////////////////
	//        Meadows
	//////////////////////
	function toggleMeadows(source) {
	  checkboxes = document.getElementsByName('Meadows');
	  for(var i=0, n=checkboxes.length;i<n;i++) {
		checkboxes[i].checked = source.checked;
	  }
	}

	//////////////////////
	//        Simmons
	//////////////////////
	function toggleSimmons(source) {
	  checkboxes = document.getElementsByName('Simmons');
	  for(var i=0, n=checkboxes.length;i<n;i++) {
		checkboxes[i].checked = source.checked;
	  }
	}


	$("input[name='AllDedman']").click(function(){
	  toggleDedman(this);
	});

	$("input[name='AllLyle']").click(function(){
	  toggleLyle(this);
	});

	$("input[name='AllCox']").click(function(){
	  toggleCox(this);
	});

	$("input[name='AllMeadows']").click(function(){
	  toggleMeadows(this);
	});

	$("input[name='AllSimmons']").click(function(){
	  toggleSimmons(this);
	});


});  

    <!-- //JAVASCRIPT to clear search text when the field is clicked -->
$(function() {
	$("#tfq2b").click(function() {
		if ($("#tfq2b").val() == "Search our website"){
			$("#tfq2b").val(""); 
		}
	});
});

(function($) {

  $.fn.menumaker = function(options) {
      
      var cssmenu = $(this), settings = $.extend({
        title: "Menu",
        format: "dropdown",
        sticky: false
      }, options);

      return this.each(function() {
        cssmenu.prepend('<div id="menu-button">' + settings.title + '</div>');
        $(this).find("#menu-button").on('click', function(){
          $(this).toggleClass('menu-opened');
          var mainmenu = $(this).next('ul');
          if (mainmenu.hasClass('open')) { 
            mainmenu.hide().removeClass('open');
          }
          else {
            mainmenu.show().addClass('open');
            if (settings.format === "dropdown") {
              mainmenu.find('ul').show();
            }
          }
        });

        cssmenu.find('li ul').parent().addClass('has-sub');

        multiTg = function() {
          cssmenu.find(".has-sub").prepend('<span class="submenu-button"></span>');
          cssmenu.find('.submenu-button').on('click', function() {
            $(this).toggleClass('submenu-opened');
            if ($(this).siblings('ul').hasClass('open')) {
              $(this).siblings('ul').removeClass('open').hide();
            }
            else {
              $(this).siblings('ul').addClass('open').show();
            }
          });
        };

        if (settings.format === 'multitoggle') multiTg();
        else cssmenu.addClass('dropdown');

        if (settings.sticky === true) cssmenu.css('position', 'fixed');

        resizeFix = function() {
          if ($( window ).width() > 768) {
            cssmenu.find('ul').show();
          }

          if ($(window).width() <= 768) {
            cssmenu.find('ul').hide().removeClass('open');
          }
        };
        resizeFix();
        return $(window).on('resize', resizeFix);

      });
  };
})(jQuery);

(function($){
$(document).ready(function(){

$(document).ready(function() {
  $("#cssmenu").menumaker({
    title: "Menu",
    format: "multitoggle"
  });

  $("#cssmenu").prepend("<div id='menu-line'></div>");

var foundActive = false, activeElement, linePosition = 0, menuLine = $("#cssmenu #menu-line"), lineWidth, defaultPosition, defaultWidth;

$("#cssmenu > ul > li").each(function() {
  if ($(this).hasClass('active')) {
    activeElement = $(this);
    foundActive = true;
  }
});

if (foundActive === false) {
  activeElement = $("#cssmenu > ul > li").first();
}

//defaultWidth = lineWidth = activeElement.width();

//defaultPosition = linePosition = activeElement.position().left;

menuLine.css("width", lineWidth);
menuLine.css("left", linePosition);

$("#cssmenu > ul > li").hover(function() {
  activeElement = $(this);
  lineWidth = activeElement.width();
  linePosition = activeElement.position().left;
  menuLine.css("width", lineWidth);
  menuLine.css("left", linePosition);
}, 
function() {
  menuLine.css("left", defaultPosition);
  menuLine.css("width", defaultWidth);
});

});


});
})(jQuery);


// Login JS stuff

$(document).ready(function() {
  $('a.login-window').click(function() {
    
    // Getting the variable's value from a link 
    var loginBox = $(this).attr('href');

    //Fade in the Popup and add close button
    $(loginBox).fadeIn(300);
    
    //Set the center alignment padding + border
    var popMargTop = ($(loginBox).height() + 24) / 2; 
    var popMargLeft = ($(loginBox).width() + 24) / 2; 
    
    $(loginBox).css({ 
      'margin-top' : -popMargTop,
      'margin-left' : -popMargLeft
    });
    
    // Add the mask to body
    $('body').append('<div id="mask"></div>');
    $('#mask').fadeIn(300);
    
    return false;
  });
  
  // When clicking on the button close or the mask layer the popup closed
  
  $('a.close, #mask').on('click',function() { 
    $('#mask , .login-popup').fadeOut(300 , function() {
    $('#mask').remove();  
  }); 
  return false;
  });

});  


function checkSession() {
    // AJAX code to submit form.
  $.ajax({
    type: "GET",
    url: "api/index.php/sessionStatus" ,
    success: function(data){
      //console.log(data);
      if(data == false){
        //console.log("logged out");
        //alert("logged out");
        $(".login-window").css("display", 'inline-block');
        $(".logout").css("display", 'none');
        $(".register-window").css("display", 'inline-block');
        $(".user-window").css("display", 'none');
      }
      else if (data == true){
        //console.log("logged in");
        //alert("logged in");
        $(".login-window").css("display", 'none');
        $(".logout").css("display", 'inline-block');
        $(".register-window").css("display", 'none');
        $(".user-window").css("display", 'inline-block');
      }
      else{
        //if no response php/ajax error 
        console.log("no response");
        //alert("not logged in");
        $(".login-window").css("display", 'inline-block');
        $(".logout").css("display", 'inline-block');
        $(".register-window").css("display", 'inline-block');
        $(".user-window").css("display", 'inline-block');
      }
    }  
  });
  //var session;
  return false;
}
window.onload = checkSession;

//Create a new Research Opportunity 
function newROP(){

  var title = document.getElementsByName("name")[0].value;
  var desc = document.getElementsByName("description")[0].value;
  //var dateStart = document.getElementsByName("startDate")[0].value;
  //var dateEnd = document.getElementsByName("endDate")[0].value;
  var numPositions = document.getElementsByName("numOfPos")[0].value;
  var paid = document.getElementsByName("paid")[0].checked;
  var workStudy = document.getElementsByName("workStudy")[0].checked;
  var graduate = document.getElementsByName("graduate")[0].checked;
  var undergrad = document.getElementsByName("undergraduate")[0].checked;
  var deptId = document.getElementByID("Department")[0].value


  //console.log(numPositions);


    var dataString = 
  {
    "name": title,
    //"dateStart": dateStart,
    //"dateEnd": dateEnd,
    "numPositions": numPositions,
    "description": desc,
    "paid": paid,
    "workStudy": workStudy,
    "graduate": graduate,
    "undergraduate": undergrad,
    "deptId": deptId
  };

console.log(dataString);


  $.ajax({
    type: "POST",
    url: "api/index.php/createResearchOpportunity",
    datatype: JSON,
    data: dataString,
    success: function(){
      alert("success!");
    },
    error: function(jqXHR, textStatus, errorThrown){alert(errorThrown);}

  });
  return false
}
