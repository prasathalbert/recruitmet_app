// JavaScript Document
//validation section

function validate(id,cont,error_id,msg)
{
	/*** 
		id to validate
		cont - > required
		error_id - > to show error msg
		msg =  msg to show
	**/
	var ret = true;
	
	switch(cont)
	{
		case "required":
						if($("#"+id).val() == "")
						{
							//$("#"+id).focus();
							$("#"+error_id).html("<p>"+msg+"</p>");
							$("#"+id).addClass("error_brd");
							ret = false;
						}
						else
						{
							$("#"+id).removeClass("error_brd");
							$("#"+error_id).html("<p><img src='"+baseURL+"/images/icons/green_success.png'/></p>");	
						}
						break;	
		case "checked":
						if($("."+id+":checked").length==0)
						{
							$("#"+error_id).html("<p>"+msg+"</p>");							
							ret = false;
						}
						else
						{
							$("#"+error_id).html("");	
						}
						break;	
		case "numeric":
						if(isNaN($("#"+id).val()))
						{
							//$("#"+id).focus();
							$("#"+error_id).html("<p>"+msg+"</p>");		
							$("#"+id).addClass("error_brd");
							ret = false;
						}
						else
						{
							$("#"+error_id).html("");	
							$("#"+id).removeClass("error_brd");
						}
						break;	
						
	}
	return ret;
}