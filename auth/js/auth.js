const phoneNumberDIV =document.querySelector('#phoneNumberDIV');
const phoneNumberField = document.getElementById('phoneNumber'); 
const authButton = document.querySelector('#authButton');

const auth = firebase.auth();

// listen for auth status changes

		if(localStorage.getItem("token"))
		{
			ShowPage('HomePage','SigninPage');
			console.log("you are logged in");
		}
		else
		{
			let URL="login?phone="+phoneNumberField.value;
			makeAjaxCall(URL,"GET").then(res => {
				console.log(res);
				if(res.status===200)
				{
					const user=res.data;
					localStorage.setItem("token",user.token);
					localStorage.setItem("phone",user.phone);
					localStorage.setItem("email",user.email);
					localStorage.setItem("userID",user.id);
					localStorage.setItem("userType",user.status);
					ShowPage('HomePage','SigninPage');
				}
				else if(res.status===404)
				{
					ShowPage('SigninPage','SigninPage');
				}
				else
				{
					console.log("ERROR : phone number is not being sent correctly to login API");
				}
			},errorHandler );
		}
  
  const signupButton = document.querySelector('#signupButton');
  signupButton.addEventListener('click', (e) => {
    e.preventDefault();
	const firstName=$("#user_first_name").val();
	const lastName=$("#user_last_name").val();
	const email=$("#user_email").val();
	const address=$("#user_address").val();
	const phone=auth.currentUser.phoneNumber;
	const deviceID="23eed";
	
	if(email.length<10 || phone.length<10 || firstName.length<2 || lastName.length<2)
    {
      Swal.fire(
        'Fields Cannot be empty!',
        'Please Fill All the fields',
        'error'
      ); 
      return false;
    }
	
	let serializeData="first_name="+firstName+"&last_name="+lastName+"&email="+email+"&device_id="+deviceID+"&phone="+phone;
	//console.log(serializeData);

	makePostAjaxCall('register','POST',serializeData).then(res => {
				console.log(res);
				if(res.status===200)
				{
					const user=res.data;
					console.log(user);
					localStorage.setItem("token",user.token);
					localStorage.setItem("phone",user.phone);
					localStorage.setItem("email",user.email);
					localStorage.setItem("email",user.email);
					localStorage.setItem("userID",user.id);
					localStorage.setItem("userType",user.status);
					Swal.fire(
					'Successful!',
					'Registered Successfully',
					'success'
				  ); 
					ShowPage('HomePage','SignupPage');
				}
				else
				{
					console.log("ERROR :Registration Unsuccessful");
				}
			},errorHandler );
	
  });
  
  function signout(){
	  localStorage.clear();
	auth.signOut().then(() => {
    console.log('user signed out from firebase');
  });
		replaceOTPWithPhone();
  ShowPage('SigninPage','HomePage');
}


  
  // logout
const logout = document.querySelector('#logout_button');
logout.addEventListener('click', (e) => {
  e.preventDefault();
  signout();
});



function phoneAuth() {
    var number=phoneNumberField.value;
	if(number.length<11 || number.indexOf("+")===-1)
	{
		Swal.fire(
			'Invalid Phone Number!',
			"Enter a valid phone number with country code",
			'error'
        );
		document.getElementById("signInWithPhone").disabled=false;
		return false;
	}
    //it takes two parameter first one is number,,,second one is recaptcha
    auth.signInWithPhoneNumber(number,window.recaptchaVerifier).then(function (confirmationResult) {
        window.confirmationResult=confirmationResult;
        coderesult=confirmationResult;
		replacePhoneWithOTP();
        console.log("Message sent");
    }).catch(function (error) {
       console.log(error);
		Swal.fire(
			'Error!',
			error.message,
			'error'
        );
		document.getElementById("signInWithPhone").disabled=false;
    });
}

function codeverify() {
	verifyCodeButton.disabled=true;
    var code=document.getElementById('otp').value;
	
    coderesult.confirm(code).then(function (cred) {
        console.log("Successfully registered");
        console.log(cred.user);
		let URL="login?phone="+cred.user.phoneNumber;
	  	makeAjaxCall(URL,"GET").then(res => {
			if(res.status===200)
			{
				const user=res.data;
				localStorage.setItem("token",user.token);
				localStorage.setItem("phone",user.phone);
				localStorage.setItem("email",user.email);
				localStorage.setItem("userID",user.id);
				localStorage.setItem("userType",user.status);
				ShowPage('HomePage','SigninPage');
			}
			else
			{
				ShowPage('SignupPage','SigninPage');
			}
    },errorHandler );
		
    }).catch(function (error) {
        console.error(error);
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
		document.getElementById("verifyCodeButton").disabled=false;
    });
}

	

function signInWithPhone(){
		event.preventDefault();
		if(phoneNumberField.value.length<1) return false;
		document.getElementById("signInWithPhone").disabled=true;
		auth.useDeviceLanguage();
		window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('signInWithPhone', {
		  'size': 'invisible',
		  'callback': function(response) {
			console.log("reCAPTCHA solved, allow signInWithPhoneNumber.");
			//phoneAuth();
		  }
		});
		phoneAuth();
	}

	
	function resendCode(){
		event.preventDefault();
		document.getElementById("signInWithPhone").disabled=true;
		phoneAuth();
	};
	
	
	function replaceOTPWithPhone(){
		
		let html=`
		<label class="mdl-button mdl-button--icon block" for="phone">
            <i class="material-icons primaryColor">smartphone</i>
          </label>
          <input class="mdl-textfield__input pl-50" type="text" pattern="[0-9]*" id="phoneNumber" required>
          <label class="mdl-textfield__label pl-50" for="phone">Mobile Number</label>
          <span class="mdl-textfield__error">Digits only</span>
		`;
		phoneNumberDIV.innerHTML=html;
		
		let btnHTMl=`
			<button id="signInWithPhone" onClick="signInWithPhone()"  class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored bigBtn round">
          Send Code
			</button>
		`;
		
		authButton.innerHTML=btnHTMl;
	}
	
	function replacePhoneWithOTP(){
		
		let html=`
			<span class="fullWidth leftAlign gray">Enter OTP sent to above phone number</span>
            <input class="mdl-textfield__input fullBorderInput tcenter pl-50" type="number" minlength="6" maxlength="6" id="otp" placeholder="- - - - - -">
            <span class="halfWidth leftAlign gray">00:30</span>
            <span class="halfWidth rightAlign gray"><a id="resendCodeButton" onclick="resendCode()" href="#">Resend OTP</a></span>
            <span class="mdl-textfield__error">enter valid otp</span>
		`;
		
		phoneNumberDIV.innerHTML=html;
		
		let btnHTMl=`
			<button id="verifyCodeButton" onClick="codeverify()" href="#"  class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored bigBtn round">
			 Verify
			</button>
		`;
		
		authButton.innerHTML=btnHTMl;
		
	}
	