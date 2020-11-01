const nameInput = document.querySelector('#profile-name');
const phoneInput = document.querySelector('#profile-phone');
const emailInput = document.querySelector('#profile-email');
const bioInput = document.querySelector('#profile-about');
const jobInput = document.querySelector('#profile-job');
const distanceFilterInput = document.querySelector('#distance_filter');
const maleCheckbox = document.querySelector('#custom-switch-1');
const femaleCheckbox = document.querySelector('#custom-switch-4');
const picture1Div=document.querySelector('#picture1Div');
const updateProfileForm = document.querySelector('#updateProfileForm');



 const setupSettingsPage= () =>{
	
    makeAjaxCall('profile').then(res=> {
		  let data=res.data;
		  
		  //Profile
          nameInput.value=data.name;
		  phoneInput.value=data.phone;
		  emailInput.value=data.email;
		  bioInput.value=data.bio;
		  jobInput.value=data.job;
		  
		  //uploads
		  if(data.pictures.picture1)
			    picture1Div.style.backgroundImage="url("+data.pictures.picture1+")";
		  if(data.pictures.picture2)
				picture2Div.style.backgroundImage="url("+data.pictures.picture2+")";
		  if(data.pictures.picture3)
				picture3Div.style.backgroundImage="url("+data.pictures.picture3+")";
		  if(data.pictures.picture4)
				picture4Div.style.backgroundImage="url("+data.pictures.picture4+")";
			
		  if(data.pictures.picture1)
				localStorage.setItem("owl_profilePic",data.pictures.picture1);
		  else if(!data.pictures.picture1 && data.pictures.picture2)
			  localStorage.setItem("owl_profilePic",data.pictures.picture2);
				
		  //Filters
		  $(".js-range-slider-distance").ionRangeSlider({
			type: "single",
			min: 10,
			max: 400,
			from:data.distance_range,
			grid: true
		  });
		  
		$(".js-range-slider").ionRangeSlider({
        type: "double",
        min: 16,
        max: 80,
        from: data.age_from,
        to: data.age_to,
        grid: true
		});
		
		// INTERESTS
		if(data.interests=="men")
			femaleCheckbox.checked = false;
		else if(data.interests=="women")
			maleCheckbox.checked = false;
		
        },errorHandler);
		
		

	};
	
	
	
	
	const updateFilters= () =>{
	let input = Object.create( {} );
	input.distance_range=document.querySelector('#distance_filter').value;
	let ageFilter=document.querySelector('#age_filter').value.split(";");
	input.age_from=ageFilter[0];
	input.age_to=ageFilter[1];
    makePostAjaxCall('updateFilters',input).then(res=> {
		  let data=res.data;
		  Swal.fire(
				'Updated!',
				"filters updated successfully",
				'success'
			);
        },errorHandler);
	};
		
	const updateInterests= () =>{
	let interests="any";
	if(femaleCheckbox.checked === false && maleCheckbox.checked === false)
	{
		Swal.fire(
				'Both unchecked!',
				"One of the above must be checked",
				'error'
			);
			return false;
	}
	else if(femaleCheckbox.checked === false && maleCheckbox.checked === true)
		interests="men";
	else if(femaleCheckbox.checked === true && maleCheckbox.checked === false)
		interests="women";
	
	let input = Object.create( {} );
	input.interests=interests;
    makePostAjaxCall('updateProfile',input).then(res=> {
		  let data=res.data;
		  Swal.fire(
				'Updated!',
				"Interests updated successfully",
				'success'
			);
        },errorHandler);
		
		

	};

	updateProfileForm.addEventListener('submit', (e) => {
	e.preventDefault();
	let input = Object.create( {} );
	input.name=nameInput.value;
	input.phone=phoneInput.value;
	input.email=emailInput.value;
	input.bio=bioInput.value;
	input.job=jobInput.value;
	
	if(localStorage.getItem("owl_userLat") && localStorage.getItem("owl_userLat"))
	{
		input.latitude=localStorage.getItem("owl_userLat");
		input.longitude=localStorage.getItem("owl_userLng");
	}

    makePostAjaxCall('updateProfile',input).then(res=> {
		  let data=res.data;
		  Swal.fire(
				'Updated!',
				"Profile updated successfully",
				'success'
			);
        },errorHandler);
	});
	
function showSelectedImage(input,id) 
{
    if (input.files && input.files[0]) 
	{
		var reader = new FileReader();
		reader.onload = function (e) {
	
		var fd = new FormData();
        var files = input.files[0];
        fd.append('picture'+id,files);
		
		Swal.fire({
		  title: 'Do you want to Upload this image?',
		  showDenyButton: true,
		  showCancelButton: false,
		  imageUrl: e.target.result,
		  confirmButtonText: `Upload`,
		  denyButtonText: `No`,
		  showLoaderOnConfirm: true,
		}).then((result) => {
		  /* Read more about isConfirmed, isDenied below */
		  if (result.isConfirmed) {
			  
			Swal.fire({
			  position: 'center',
			  type: 'info',
			  title: 'Picture is being uploaded',
			  showConfirmButton: false,
			  timer: 5000
			});
			
			 $.ajax({
            url: baseURL+'updatePictures?token='+localStorage.getItem("owl_token"),
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success: function(res){
				console.log(res);
			res=JSON.parse(res);
			if(res.status===200)
			{
				Swal.fire('Saved!', '', 'success');
				if(id==1)
					picture1Div.style.backgroundImage="url("+e.target.result+")";
				else if(id==2)
					picture2Div.style.backgroundImage="url("+e.target.result+")";
				else if(id==3)
					picture3Div.style.backgroundImage="url("+e.target.result+")";
				else if(id==4)
					picture4Div.style.backgroundImage="url("+e.target.result+")";
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
                
            },
        });
		
		  } else if (result.isDenied) {
			  document.querySelector('#upload-user-photo-'+id).value="";
			//Swal.fire('Changes are not saved', '', 'info')
		  }
		})
		
		
		}
		
		  
		reader.readAsDataURL(input.files[0]);
	}
}

