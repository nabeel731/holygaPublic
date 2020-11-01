const auth = firebase.auth();
function signInWithPhone(){
		auth.useDeviceLanguage();
		window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
		  'size': 'invisible',
		  'callback': function(response) {
			console.log("reCAPTCHA solved, allow signInWithPhoneNumber.");
			//phoneAuth();
		  }
		});
		phoneAuth();
	}
	
	
function resend ()
{
	if(phoneInput.value)
	{
		otpInput.value="";
		if(timeLeft>0 && timeLeft<40)
			{alert(" you can resend otp after "+timeLeft+" seconds"); return false;}
		phoneAuth();
	}
}
	
function phoneAuth(){
		let countryCode=countryCodeInput.options[countryCodeInput.selectedIndex].value;
	    let phone = phoneInput.value;
		let phoneNumber=countryCode+phone;
		//console.log(phoneNumber);
			//it takes two parameter first one is number,,,second one is recaptcha
    auth.signInWithPhoneNumber(phoneNumber,window.recaptchaVerifier).then(function (confirmationResult) {
        window.confirmationResult=confirmationResult;
        coderesult=confirmationResult;
		buttonEnable();
        console.log("Message sent");
		timeLeft=40;
		timerDiv.style.display="block";
		timerId = setInterval(countdown, 1000);
		document.querySelector('#phoneDiv').style.display="none";
		document.querySelector('#otpDiv').style.display="block";
    }).catch(function (error) {
       console.log(error);
	   buttonEnable();
		Swal.fire(
			'Error!',
			error.message,
			'error'
        );
		
    });
}



function codeverify(code) {
	buttonDisable();
    coderesult.confirm(code).then(function (cred) {
        console.log("Successfully registered");
        console.log(cred.user);
		localStorage.setItem("owl_phone",cred.user.phoneNumber);
		
		let URL="login?phone="+cred.user.phoneNumber;
	  	makeAjaxCall(URL,"GET").then(res => {
			if(res.status==200)
			{
				const user=res.data;
				localStorage.setItem("owl_token",user.token);
				localStorage.setItem("owl_phone",user.phone);
				localStorage.setItem("owl_userID",user.id);
				if(user.pictures.picture1)
					localStorage.setItem("owl_profilePic",user.pictures.picture1);
				else if(user.pictures.picture2)
					localStorage.setItem("owl_profilePic",user.pictures.picture2);
				else if(user.pictures.picture3)
					localStorage.setItem("owl_profilePic",user.pictures.picture3);
				else if(user.pictures.picture4)
					localStorage.setItem("owl_profilePic",user.pictures.picture4);
				location.href="index.html";
			}
			else
				location.href="register.html";
			
    },errorHandler );
		
    }).catch(function (error) {
        console.error(error);
		buttonEnable();
		if(error.code!="auth/code-expired")
		{
			Swal.fire(
			'Error!',
			"Code is invalid,Please Enter the code Again",
			'error'
			);
		}
		else
		{
			Swal.fire(
				'Error!',
				error.message,
				'error'
			);
		}
		otpInput.value="";
    });
}

function logout(){
	// LOG OUT FROM FIREBASE
	auth.signOut().then(function() {
	console.log("Sign-out successful.");
	}).catch(function(error) {
	  console.log("Error while Signing-out.");
	});
	
	// REMOVE SESSION VALUES
	localStorage.setItem("owl_token",null);
	localStorage.setItem("owl_phone",null);
	localStorage.setItem("owl_userID",null);
	localStorage.setItem("owl_profilePic",null);
	localStorage.setItem("owlsentToServer",0);
	
	location.href="login.html";
}