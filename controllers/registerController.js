const registerForm = document.querySelector('#registerForm');

registerForm.addEventListener('submit', (e) => {
	e.preventDefault();
	let reader = new FileReader();
	let files = e.target.picture1.files[0];
	let fileName = files.name;
	if(files.size>5000000)
	{
		 alert("Maximum upload size is 5mb"); return false;
	}
    let fileExtension = fileName.replace(/^.*\./, '');
    if (fileExtension != 'png' && fileExtension != 'jpg' && fileExtension != 'jpeg') {
        alert("Extension Not supported"); return false;
    }
   
	let input = Object.create( {} );
	reader.readAsDataURL(files);
	input.name=registerForm.name.value;
	input.phone=localStorage.getItem("owl_phone");
	input.job=registerForm.job.value;
	input.dob=registerForm.dob.value;
	input.gender=registerForm.gender.value;

	if(localStorage.getItem("owl_userLat") && localStorage.getItem("owl_userLng"))
	{
		input.latitude=localStorage.getItem("owl_userLat");
		input.longitude=localStorage.getItem("owl_userLng");
	}
	
	let date=new Date();
	let dob= new Date(input.dob);
	date.setFullYear( date.getFullYear() - 16 );
	if(dob>date)
	{
		Swal.fire(
			'Under 16!',
			"You must be atleast 16 years old to use this application",
			'error'
			);
			return false;
	}
	if(/^[a-zA-Z0-9- ]*$/.test(input.name) == false)
	{
		Swal.fire(
			'Invalid Name',
			"Name cannot contain any special character",
			'error'
			);
			return false;
	}
	
	reader.addEventListener("load", function () {
    input.picture1=reader.result;
		if(!input.picture1)
		{
			Swal.fire(
				'Empty Image',
				"Image cannot be empty",
				'error'
				);
				return false;
		}
		
		makePostAjaxCall('register',input).then(res=> {
				console.log(res);
				if(res.status===200)
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
				{
					Swal.fire(
					'Error!',
					res.message,
					'error'
					);
					return false;
				}
			},errorHandler);
		}, false);

	});