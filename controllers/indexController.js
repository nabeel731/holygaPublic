//const nameInput = document.querySelector('#profile-name');


 const setupHomePage= () =>{
	
    makeAjaxCall('matchableUsers').then(res=> {
		  let data=res.data;
		  
        },errorHandler);
		
		

	};

