const baseURL="webservices/";
const APIKEY="156c4675-9608-4591-b2ec-427503464aac";


function makePostAjaxCall(url,params,$type="json"){
	url=baseURL+url;
	if(url.indexOf('?') === -1)
        url=url+"?token="+localStorage.getItem("owl_token");
	else
		url=url+"&token="+localStorage.getItem("owl_token");
	console.log(url);
	var promiseObj = new Promise(function(resolve, reject){
		
		var xhr = new XMLHttpRequest();
		xhr.open("POST",url, true);
		xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-type", "application/json;charset=UTF-8");
		xhr.setRequestHeader("api-key",APIKEY);
		if($type=="json")
			xhr.send(JSON.stringify(params));
		else
			xhr.send(params);
		 xhr.onreadystatechange = function(){
      if (xhr.readyState === 4){
         if (xhr.status === 200){
            //console.log("xhr done successfully");
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
	 });
	 return promiseObj;
}


function makeAjaxCall(url,methodType="GET"){
    url=baseURL+url;
    if(url.indexOf('?') === -1)
        url=url+"?token="+localStorage.getItem("owl_token");
	else
		url=url+"&token="+localStorage.getItem("owl_token");
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


function errorHandler(statusCode){
 console.log("failed with status", status);
}

// TO GET CURRENT LOCATION OF USER
if (navigator.geolocation) {
       navigator.geolocation.getCurrentPosition(showPosition);
          } else {
            x.innerHTML = "Geolocation is not supported by this browser.";
   }      
  function showPosition(position) {
     console.log(position); 
	 localStorage.setItem("owl_userLat",position.coords.latitude);
	 localStorage.setItem("owl_userLng",position.coords.longitude);
   }	