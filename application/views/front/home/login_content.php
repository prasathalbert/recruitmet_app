<div class="login_panel">
    <div class="b_left">
    <div id="logo-login">
			<h3 class="bl_title">GKM Login</h3>
			</div>
		
    </div>
    <?php
	$frm = array("name"=>"admin-login-frm","id"=>"admin-login-frm","method"=>"post");
	echo form_open("login/do_login",$frm);
    ?>
    <div id="b_right" style="float: none; margin: 0;">
		
        <div class="flashmsg">
        <?php	
	
	echo getFlashMsg();
	echo getErrorMsg($error);
	
        ?>
        </div>
		<div id="i_title">
            <label for="ad_uname">User Name</label>
			<input type="text" id="ad_uname" name="ad_uname" placeholder="User Name"/>
		</div>
		<br />
	
		<div id="i_content">
            <label for="ad_pwd">Password</label>
			<input id="ad_pwd" name="ad_pwd" type="password" placeholder="Password"/>
		</div>
		<br />
	
		
		<div id="i_publish">
			<ul>
				
				<li><input type="submit" class="sub_button" name="login_now" value="Login" /></li>
                
                <li><input type="submit" id="forgot_password_btn" class="sub_button flt_rght" name="forgot_password" value="Forgot Password" /></li>
			</ul>
		</div>
        <br />
		
	</div>
</label>
    </form>
</div>
<script type="text/javascript">
$("#forgot_password_btn").live("click",function(){
if($("#ad_uname").val()!="")
{

	if(confirm("Are you sure you want to reset your password?"))
		return true;
	return false;
}
else
{
	alert("Please enter your username");
	return false;
}

});
</script>