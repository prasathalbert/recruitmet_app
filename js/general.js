var validEmail = false;
var validMobile = false;
var emailResponse = '';
var mobileResponse = '';
var pwd_strength = false;

/**** email validation****/
function isValidEmail(value)
{
    var rx = /^[\w'+-]+(\.[\w'+-]+)*@[\w-]+(\.[\w-]+)*\.\w{1,8}$/;
    return rx.test(value);
}

/*** Numeric validation***/

$(function() {
$(".numbers").live("keydown",function(event) {
    // Allow: backspace, delete, tab, escape, and enter
    if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || 
         // Allow: Ctrl+A
        (event.keyCode == 65 && event.ctrlKey === true) || 
         // Allow: home, end, left, right
        (event.keyCode >= 35 && event.keyCode <= 39)) {
             // let it happen, don't do anything
             return;
    }
    else {
        // Ensure that it is a number and stop the keypress
        if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
            event.preventDefault(); 
        }   
    }
});


$(".has-hint").live("focus", function(){
		var tip_div = $(this).attr("id")+"-hint";
		var cont = $(this).attr("title");
		if($("#"+tip_div).length==0)
		{			
			$(this).after("<div id="+tip_div+" class='tool-tip'>"+cont+"</div>");
		}
		$("#"+tip_div).addClass("tool-tip");
});

$(".has-hint").live("blur", function(){	
	var tip_div = $(this).attr("id")+"-hint";
		
		if($("#"+tip_div).length>0)
		{			
			$("#"+tip_div).remove(); 
		}
		
});
});
function fullname_check(name,error_div)
{
	name = name.replace('  ','');
	var name_arr = name.split(' ');
	
	if(name_arr.length<2)
	{
		$("#"+error_div).html("<p><img src='"+baseURL+"/images/icons/red_cross.png' title='Please enter First and Last name'/></p>");
		return false;
	}
	else
	{
		if((name_arr[0].trim())=='')
		{
			$("#"+error_div).html("<p><img src='"+baseURL+"/images/icons/red_cross.png' title='Please enter valid Firstname'/></p>");
			return false;
		}
		if((name_arr[1].trim())=='')
		{
			$("#"+error_div).html("<p><img src='"+baseURL+"/images/icons/red_cross.png' title='Please enter valid Lastname'/></p>");
			return false;
		}
	}
	$("#"+error_div).html("<p><img src='"+baseURL+"/images/icons/green_success.png'/></p>")
	return true;
}

