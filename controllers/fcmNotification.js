// Retrieve Firebase Messaging object.
const messaging = firebase.messaging();


function requestNotificationPermission(){
      messaging.requestPermission()
    .then(function() {
        console.log('Notification permission granted.');
        // TODO(developer): Retrieve an Instance ID token for use with FCM.
        if(isTokenSentToServer()){
            console.log("Token already saved");
        } else {
            getRegToken();
        }
       // getRegToken();
    })
    .catch(function(err) {
        console.log('Unable to get permission to notify.', err);
    });
}

if(!isTokenSentToServer())
	requestNotificationPermission();



 /*messaging.getToken().then((currentToken) => {
  if (currentToken) {
    sendTokenToServer(currentToken);
    updateUIForPushEnabled(currentToken);
  } else {
    // Show permission request.
    console.log('No Instance ID token available. Request permission to generate one.');
    // Show permission UI.
    updateUIForPushPermissionRequired();
    setTokenSentToServer(false);
  }
}).catch((err) => {
  console.log('An error occurred while retrieving token. ', err);
  //showToken('Error retrieving Instance ID token. ', err);
  setTokenSentToServer(false);
}); */



// Callback fired if Instance ID token is updated.
messaging.onTokenRefresh(() => {
  messaging.getToken().then((refreshedToken) => {
    console.log('Token refreshed.');
    // Indicate that the new Instance ID token has not yet been sent to the
    // app server.
    setTokenSentToServer(false);
    // Send Instance ID token to app server.
    sendTokenToServer(refreshedToken);
    // ...
  }).catch((err) => {
    console.log('Unable to retrieve refreshed token ', err);
    showToken('Unable to retrieve refreshed token ', err);
  });
});


function getRegToken() {
    // Get Instance ID token. Initially this makes a network call, once retrieved
    // subsequent calls to getToken will return from cache.
    messaging.getToken()
    .then(function(currentToken) {
        if (currentToken) {
        
        console.log(currentToken);
        sendTokenToServer(currentToken);
        setTokenSentToServer(true);
        //updateUIForPushEnabled(currentToken);
        } else {
            // Show permission request.
            console.log('No Instance ID token available. Request permission to generate one.');
        // Show permission UI.
            setTokenSentToServer(false);
        }
    })
    .catch(function(err) {
        console.log('An error occurred while retrieving token. ', err);
        // showToken('Error retrieving Instance ID token. ', err);
        setTokenSentToServer(false);
    });
}

function setTokenSentToServer(sent) {
    window.localStorage.setItem('owlsentToServer', sent ? 1 : 0);
}

function isTokenSentToServer() {
    return window.localStorage.getItem('owlsentToServer') == 1;
}

function sendTokenToServer(currentToken){
   let input = Object.create( {} );
	input.fcm_token=currentToken;
  makePostAjaxCall('updateProfile',input).then(res=> {
		console.log(res);
  });
}



function sendNotification(userID,title,message){
    let image='';
    const serverKey="AAAAWDmUq5o:APA91bHg9UAB8umgQGcrPeZX6XjYDN8he6AGsRN3LN35n380pn2fnN_Src9x_12G0-4vR_GNVQ-xqOwUqoqHyERY66kwHsxMAg6eYl6qWMH2l6g1-11YAEPfhAlN2G_TI4eQEk_-ZUI4";
         
         let fcm_token="get from db";
         
         
         if(fcm_token.length<32)
            return false;
            
    
        var notificationData = {
                  "to": fcm_token,
                  "data": {
                    "title": title,
                    "message":message
                  },
                  "notification": {
                    "sound": "default",
                    "title": title,
                    "body": message,
                    "image":image
                  }
                };
    
	    $.ajax({
              type: 'POST',
              url: 'https://fcm.googleapis.com/fcm/send',
              headers: {
                'Content-Type': 'application/json',
                'Authorization': 'key='+serverKey
              },
              data: JSON.stringify(notificationData),
              success: function(response){
                console.log(response);
              },
            });
        
}


