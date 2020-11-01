<?php
if(isset($_GET['error']))
{
	$error=$_GET['error'];
	if($error=='INVALID_CREDENTIALS')
		printMessage('Invalid Credentials','Email or password is incorrect!');
	else if(strtolower($error)=='not_created')
		printMessage('Success','Oooops Could Not be added For Some Reason!');
	else if(strtolower($error)=='not_updated')
		printMessage('Success','Oooops Could Not be Updated For Some Reason!');
	else if($error=='EMAIL_TAKEN')
		printMessage('Email Taken','This Email is already registered!');
	else if($error=='device_id_TAKEN')
		printMessage('Device Id Taken','This Device-Id is already registered!');
	else if(strtolower($error)=='incorrectemail')
		printMessage('Success','Oooops Email You Entered is Incorrect!');
	else if($error=='coupon_already_exists')
		printMessage('Coupon Used','This coupon was already used,please add the unique coupon');
	else if($error=='PASSWORD_MISMATCH')
		printMessage('Error','password and confirm password are different!');
	else if($error=='INCORRECT_OLD_PASSWORD')
		printMessage('Incorrect Password','old Password is incorrect!');
}
elseif(isset($_GET['success']))
{
	$success=$_GET['success'];
	if(strtolower($success)=='created')
		printMessage('Success','Added Successfully!','success');
	elseif(strtolower($success)=='updated')
		printMessage('Success','Updated Successfully!','success');
		elseif(strtolower($success)=='delete')
		printMessage('Success','Sucessfully Successfully!','success');
	elseif($success=='PASSWORD_UPDATED')
		printMessage('Password Updated','Password updated successfully, Login with new credentials','success');
}


function printMessage($title,$text,$icon="error"){
		echo"
		<script>
		  Swal.fire({
		  icon: '".$icon."',
		  title: '".$title."',
		  text: '".$text."'
		})
		if (window.location.href.indexOf('?') > -1) 
			history.pushState('', document.title, window.location.pathname);
		</script>";
	}