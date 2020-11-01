const db=firebase.firestore();
const messagesMainDiv=document.querySelector('#messagesMainDiv');
const messageForm=document.querySelector("#chat-id-2-form");
const loggedUser=localStorage.getItem("owl_userID");	
const recepientName=document.querySelector("#recepient_name");
let myProfile;
let recipient;


messageForm.addEventListener('submit', (e) => {
	e.preventDefault();	
	let input = Object.create( {} );
	input.message=messageForm["chat-id-2-input"].value;
	input.reciever_id=recipient.id;
	if(!input.message.replace(/\s/g, '').length || input.message.length<1)
      return false;

    makePostAjaxCall('message',input).then(res=> {
    
			let message=res.data;
			// SEND TO FIREBASE
			 db.collection('chat').add({
				sender_id:parseInt(loggedUser),
				reciever_id:input.reciever_id,
				message_id:message.id
			  }).then(() => {
				console.log("Message Sent Successfully");
				//sendNotification(notifyID,notfyTitle,message);
			  }).catch(err => {
				console.log(err.message);
			  });
  
			messageForm["chat-id-2-input"].value="";
			showMessage(message,myProfile,recipient);
        },errorHandler);

	});


	
	const setupConversation= () =>{
		let url = new URL(location.href);
		let search_params = url.searchParams; 
		var id = search_params.get('id');
		if(!id)
			location.href="chat.html";
    makeAjaxCall('conversation?id='+id).then(res=> {
		  data=res.data;
		  recipient=res.data.recipient;
		  recepientName.textContent=recipient.name;
		  myProfile=res.data.myProfile;
		  recipient.picture=null;
		  myProfile.picture=null;
		  if(recipient.pictures.picture1)
			  recipient.picture=recipient.pictures.picture1;
		  else if(recipient.pictures.picture2)
			  recipient.picture=recipient.pictures.picture2;
		  else if(recipient.pictures.picture3)
			  recipient.picture=recipient.pictures.picture3;
		  else if(recipient.pictures.picture4)
			  recipient.picture=recipient.pictures.picture4;
		  if(myProfile.pictures.picture1)
			  myProfile.picture=myProfile.pictures.picture1;
		  else if(myProfile.pictures.picture2)
			  myProfile.picture=myProfile.pictures.picture2;
		  else if(myProfile.pictures.picture3)
			  myProfile.picture=myProfile.pictures.picture3;
		  else if(myProfile.pictures.picture4)
			  myProfile.picture=myProfile.pictures.picture4;
		  let i=0; let conversation=res.data.conversation;
		  
		  conversation.forEach(message => {
			  // IF DATE OF THIS MESSAGE AND LAST MESSAGE IS NOT SAME SHOW DIVIDER
			 if(i==0 || !sameDay(conversation[i]['created_at'],conversation[i-1]['created_at']))
			 {
				 let date=new Date(conversation[i]['created_at']);
				 var month = ["January", "February", "March", "April", "May", "June",
				"July", "August", "September", "October", "November", "December"][date.getMonth()];
					date=date.getDate()+" "+month+" "+date.getFullYear();
				 showDevider(date);
			 }
			 // DIVIDER
			
			// SHOW MESSAGE (if message contains media than it will be shown differently)
			  if(message.attachments)  
					showImagesMessage(message,myProfile,recipient);
				else
					showMessage(message,myProfile,recipient);
				i++;
		  });
		 
        },errorHandler);
		
		
	};
	
	
	const showMessage=(message,myProfile,otherProfile)=>{
		let myMessage=false
		if(loggedUser==message.sender_id)
			myMessage=true;
		let time=new Date(message.created_at);
		time=padLeadingZeros(time.getHours())+":"+padLeadingZeros(time.getMinutes());
	let html=`
	
	<div class="message ${myMessage?"message-right":''}">
	   <!-- Avatar -->
	   <div class="avatar avatar-sm ${myMessage?'ml-4' :'mr-4'} ml-lg-5 d-none d-lg-block">
		  <img class="avatar-img" src="${myMessage?myProfile.picture:otherProfile.picture}" alt="">
	   </div>
	   <!-- Message: body -->
	   <div class="message-body">
		  <!-- Message: row -->
		  <div class="message-row">
			 <div class="d-flex align-items-center ${myMessage?'justify-content-end':''}">
				<!-- Message: content -->
				<div class="message-content  ${myMessage?"bg-primary text-white":'bg-light'} ">
				   <div>${message.message}     &nbsp &nbsp<small class="opacity-65">${time?time:"just now"}</small></div>
				   
				</div>
				<!-- Message: content -->
			 </div>
		  </div>
		  <!-- Message: row -->
	   </div>
	   <!-- Message: body -->
	</div>
	
	`;
	messagesMainDiv.innerHTML+=html;
    messagesMainDiv.scrollTop = messagesMainDiv.scrollHeight;
	};
	
	const showImagesMessage= (message,myProfile,otherProfile) =>{
		let myMessage=false
		if(loggedUser==message.sender_id)
			myMessage=true;
		let time=new Date(message.created_at);
		time=padLeadingZeros(time.getHours())+":"+padLeadingZeros(time.getMinutes());
    let html=`
     <div class="message ${myMessage?"message-right":''}">
	<!-- Avatar -->
	<div class="avatar avatar-sm ${myMessage?'ml-4' :'mr-4'} ml-lg-5 d-none d-lg-block">
	<img class="avatar-img" src="${myMessage?myProfile.picture:otherProfile.picture}">
	</div>
	<!-- Message: body -->
	<div class="message-body">
	<!-- Message: row -->
	<div class="message-row">
	   <div class="d-flex align-items-center">
		  <!-- Message: content -->
		  <div class="message-content bg-light w-100">
			 <!--<h6 class="mb-2">${myMessage?myProfile.name:otherProfile.name} shared ${message.attachments.length} photo(s)</h6>-->
			 <p class="mb-2">${message.message}</p>
			 
			 
			 <div class="form-row py-3">
			 ${returnImagesHTML(message.attachments)}
				
				
				<!--<div class="col">

				   <img class="img-fluid rounded" src="images/profiles/chris-evans.jpg" data-action="zoom" alt="">
				</div>-->
			 </div>
			 <div class="mt-1">
				<small class="opacity-65">${time}</small>
			 </div>
		  </div>
		  <!-- Message: content -->
	   </div>
	</div>	
	`;
	messagesMainDiv.innerHTML+=html;
	messagesMainDiv.scrollTop = messagesMainDiv.scrollHeight;
	};
	
	
	function returnImagesHTML(attachments)
	{ 
		let html='';
		attachments.forEach(attachment => {
			html+=
				`<div class="col">
					   <img class="img-fluid rounded" src="${attachment}" data-action="zoom">
				</div>`
					;
		});
		return html;
	}
	
const showFileMessage= (message,myProfile,otherProfile) =>{
		
	let html=`
	<div class="message ${myMessage?"message-right":''}">
	   <!-- Avatar -->
	   <div class="avatar avatar-sm ${myMessage?'ml-4' :'mr-4'} ml-lg-5 d-none d-lg-block">
		  <img class="avatar-img" src="${myMessage?myProfile.picture:otherProfile.picture}" alt="">
	   </div>
	   <!-- Message: body -->
	   <div class="message-body">
		  <!-- Message: row -->
		  <div class="message-row">
			 <div class="d-flex align-items-center ${myMessage?'justify-content-end':''}">
				<!-- Message: content -->
				<div class="message-content  ${myMessage?"bg-primary text-white":'bg-light'} ">
				   <div class="media">
					  <a href="#" class="icon-shape mr-5">
					  <i class="fe-paperclip"></i>
					  </a>
					  <div class="media-body overflow-hidden flex-fill">
						 <a href="#" class="d-block text-truncate font-medium text-reset">bootstrap.min.js</a>
						 <ul class="list-inline small mb-0">
							<li class="list-inline-item">
							   <span class="t">79.2 KB</span>
							</li>
							<li class="list-inline-item">
							   <span class="text-uppercase">js</span>
							</li>
						 </ul>
					  </div>
				   </div>
				</div>
				<!-- Message: content -->
			 </div>
		  </div>
		  <!-- Message: row -->
	   </div>
	   <!-- Message: body -->
	</div>
	<!-- Message -->
	`;
	messagesMainDiv.innerHTML+=html;
	};
	
	
	function showDevider(date){	
	let html=`
		<div class="message-divider my-9 mx-lg-5">
            <div class="row align-items-center">

                <div class="col">
                   <hr>
                </div>

				<div class="col-auto">
                    <small class="text-muted">${date}</small>
                </div>

                 <div class="col">
                     <hr>
                  </div>
             </div>
        </div>
	`;
	messagesMainDiv.innerHTML+=html;
	}
	

		
	db.collection('chat').where("reciever_id",'==',parseInt(loggedUser)).onSnapshot(snapshot => {
      let cchanges=snapshot.docChanges();
      console.log(cchanges);
      cchanges.forEach(change => {
        if(change.type == 'added'){
	
		  let message=change.doc.data();
		  let id=change.doc.id;
		  let messageID=message.message_id;
		  console.log(message);
		  if(message.sender_id==recipient.id)
		  { 
			   makeAjaxCall('getSingleMessage?id='+messageID).then(res=> {
				   showMessage(res.data,myProfile,recipient);
			   });
		  }
		   db.collection('chat').doc(id).delete();
        } else if (change.type == 'removed'){
          console.log("removed");
        }
      });

    }, err => console.log(err.message));	
	
function padLeadingZeros(num, size=2) {
    var s = num+"";
    while (s.length < size) s = "0" + s;
    return s;
}

function sameDay(d1, d2) {
	d1=new Date(d1); d2=new Date(d2);
  return d1.getFullYear() === d2.getFullYear() &&
    d1.getMonth() === d2.getMonth() &&
    d1.getDate() === d2.getDate();
}