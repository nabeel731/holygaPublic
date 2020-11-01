<script>

function showUserAddModal()
{
	$('#user-modal').modal('show');
}

function showRedeemAddModal()
{
	$('#redeem-modal').modal('show');
}


function showRedeemUpdateModal(id)
{
	alert("hy")
	makeAjaxCall('getRedeem?id='+id).then(res=> {
	redeem=res.data;
	$('#redeem_code').val(redeem.redeem_code);
	$('#user_id').val(redeem.user_id);
	$('#validity').val(redeem.validity);
	$('#redeem_id').val(redeem.id);
	$('#updateredeem-modal').modal('show');
  });
  
}


function showUserUpdateModal(id)
{
	makeAjaxCall('getUser?id='+id).then(res=> {
	user=res.data;
	$('#user-name').val(user.name);
	$('#user-phone').val(user.phone);
	$('#user-email').val(user.email);
	$('#device_id').val(user.device_id);
	$('#user_id').val(user.id);
	$('#update-modal').modal('show');
  });
  
}




function deleteUser(userId){
		
        if (confirm("Are you sure?")) {
							 $.ajax({
						 type: "POST",
						 url: 'deleteUser',
						 data: {userId:userId},
						 success: function(data){
							 console.log(data);
							 if(data==1)
							 { 
								  swal.fire({
                                      title: "Deleted!",
                                      text: "User Deleted Successfully ",
                                      icon: "success",
                                    });
									window.setTimeout(function(){location.reload()},3000)
									  
							 }
							 
							 
							
							 else if(data=="0")
							{
								swal.fire({
                                      title: "OOO00ppppss",
                                      text: "Error Processing Your Request",
                                      icon: "error",
                                    });
							 }
						 }
						 });	
          }
}





function deleteRedeem(redeemId){
		
        if (confirm("Are you sure?")) {
							 $.ajax({
						 type: "POST",
						 url: 'deleteRedeem',
						 data: {redeemId:redeemId},
						 success: function(data){
							 console.log(data);
							 if(data==1)
							 { 
								  swal.fire({
                                      title: "Deleted!",
                                      text: "Redeem Deleted Successfully ",
                                      icon: "success",
                                    });
									window.setTimeout(function(){location.reload()},3000)
									  
							 }
							 
							 
							
							 else if(data=="0")
							{
								swal.fire({
                                      title: "OOO00ppppss",
                                      text: "Error Processing Your Request",
                                      icon: "error",
                                    });
							 }
						 }
						 });	
          }
}




function blockunblockUser(userID,status){
		
        if (confirm("Are you sure?")) {
							 $.ajax({
						 type: "POST",
						 url: 'blockunblockUser',
						 data: {blockID:userID,status:status},
						 success: function(data){
							 console.log(data);
							 if(data==1)
							 { 
								  swal.fire({
                                      title: "Block!",
                                      text: "user UnBlocked Successfully ",
                                      icon: "success",
                                    });
									window.setTimeout(function(){location.reload()},3000)
									  
							 }
							 
							  else if(data==0)
							 { 
								  swal.fire({
                                      title: "Block!",
                                      text: "user Blocked Successfully ",
                                      icon: "success",
                                    });
									window.setTimeout(function(){location.reload()},3000)
									  
							 }
							
							 else if(data=="unsuccessful")
							{
								swal.fire({
                                      title: "OOO00ppppss",
                                      text: "Error Processing Your Request",
                                      icon: "error",
                                    });
							 }
						 }
						 });	
          }
     
	}
	
	
	
function WarningToUser(userID){
		
        if (confirm("Are you sure?")) {
							 $.ajax({
						 type: "POST",
						 url: 'WarningToUser',
						 data: {userID:userID},
						 success: function(data){
							 console.log(data);
							 if(data==1)
							 { 
								  swal.fire({
                                      title: "Notification!",
                                      text: "Notification Send Successfully ",
                                      icon: "success",
                                    });
									window.setTimeout(function(){location.reload()},3000)
									  
							 }
							 else if(data==0)
							{
								swal.fire({
                                      title: "OOO00ppppss",
                                      text: "Error Processing Your Request",
                                      icon: "error",
                                    });
							 }
						 }
						 });	
          }
     
	}
	
	
	
	
	


function makeAjaxCall(url,methodType="GET"){
    //url=""+url;
   var promiseObj = new Promise(function(resolve, reject){
      var xhr = new XMLHttpRequest();
	
      xhr.open(methodType, url, true);
	
	 xhr.send();
      xhr.onreadystatechange = function(){
      if (xhr.readyState === 4){
         if (xhr.status === 200){
            console.log("xhr done successfully");
            var resp = xhr.responseText;
			console.log(resp);
            var respJson = JSON.parse(resp);
			if(respJson.status!==200 && respJson.status!==404)
			{
				Swal.fire(
				'Error!',
				respJson.message,
				'error'
				);
				return false;
			}
			
            resolve(respJson);
         } else {
            reject(xhr.status);
            console.log("xhr failed");
         }
      } else {
         console.log("xhr processing going on");
      }
   };
   console.log("request sent succesfully");
 });
 return promiseObj;
}


</script>