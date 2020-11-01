const loggedUser=localStorage.getItem("owl_userID");

const like= (id,type=1) =>{
	makePostAjaxCall('like?liked_id='+id+"&type="+type).then(res=> {
		//console.log(res);
		if(res.message=="Matched Successfully")
		{
			Swal.fire(
				'Hurrah!',
				"It's a match",
				'success'
				);
		}
	},errorHandler);
}



const dislike= (id) =>{
	makePostAjaxCall('dislike?disliked_id='+id).then(res=> {
		//console.log(res);
	},errorHandler);
}



const setupChatPage= () =>{
	makeAjaxCall('matchedUsers').then(res=> {
	const matchedUsersDiv=document.querySelector('#mathced_users_div');
	const onlineMatchesDiv=document.querySelector('#online_matches_div');
		let html=''; let name=''; let id; let picture=null; let html2="";
		let time=new Date().getTime() / 1000; let lastOnline=000;
		//console.log(time-120);
		
		// SHOWING ALL MATCHED USERS
	res.data.matchedUsers.forEach(match => {
		if(loggedUser!=match.first_id)
		{
			id=match.first_id;
			name=match.first_name;
			picture=match.first_picture;
			lastOnline=match.first_last_online;
		}
		else
		{
			id=match.second_id;
			name=match.second_name;
			picture=match.second_picture;
			lastOnline=match.second_last_online;
		}
		if(!picture)
				picture="images/profile.svg";
			
		html2=`
			<a href="conversation.html?id=${id}" class="d-block text-reset mr-7 mr-lg-6">
				<div class="avatar avatar-sm avatar-online mb-3">
					  <img class="avatar-img" src="${picture}" alt="${name} is online">
				</div>
				<div class="small">${name}</div>
			</a>
			
			`;
		onlineMatchesDiv.innerHTML+=html2;
	});
	
	
	// SHOWING LAST MESSAGE OF EACH USER
	
	res.data.lastMessages.forEach(message => {
		if(loggedUser!=message.sender_id)
		{
			id=message.sender_id;
			name=message.sender_name;
			picture=message.sender_picture;
			lastOnline=message.sender_last_online;
		}
		else
		{
			id=message.reciever_id;
			name=message.reciever_name;
			picture=message.reciever_picture;
			lastOnline=message.reciever_last_online;
		}
		if(!picture)
				picture="images/profile.svg";
		let sentAt= new Date(message.created_at);
		 sentAt =sentAt.getHours() + ":" + sentAt.getMinutes();
		html=`
		<a class="text-reset nav-link p-0 mb-6" href="conversation.html?id=${id}">
              <div class="card card-active-listener">
					<div class="card-body">

                        <div class="media">
                                                        
                                                        
                            <div class="avatar mr-5">
                                <img class="avatar-img" src="${picture}" alt="">
                             </div>
                                                        
                             <div class="media-body overflow-hidden">
                                <div class="d-flex align-items-center mb-1">
                                  <h6 class="text-truncate mb-0 mr-auto">${name}</h6>
                                  <p class="small text-muted text-nowrap ml-4">${sentAt}</p>
                                 </div>
                              <div ${message.status==2?'':'style="color:black; font-size:16px"' }class="text-truncate">${message.sender_name}: ${message.message}</div>
                                  </div>
                              </div>

                            </div>

                                                
                        <!--<div class="badge badge-circle badge-primary badge-border-light badge-top-right">
                             <span>3</span>
                         </div>-->
                                                
                </div>
            </a>
		`;
	
		matchedUsersDiv.innerHTML+=html;
		matchedUsersDiv.scrollTop = matchedUsersDiv.scrollHeight;
	});
	
	if(!html)
	{
		html=`
			<a style="text-align:center; margin:0 auto;" class="text-reset nav-link p-0 mb-6" href="#">
              <div class="card card-active-listener">
					<div class="card-body">
						<h1 class="text-muted">No conversation yet</h1>
					</div>
			  </div>
			</a>
		`;
		matchedUsersDiv.innerHTML=html;
	}
		
	},errorHandler);
};


if(location.href.indexOf('index.html')===-1)
{
	const db=firebase.firestore();

	db.collection('chat').where("reciever_id",'==',parseInt(loggedUser)).onSnapshot(snapshot => {
      let cchanges=snapshot.docChanges();
      //console.log(cchanges);
      cchanges.forEach(change => {
        if(change.type == 'added'){
	
		  let message=change.doc.data();
		  let id=change.doc.id;
		  console.log(message);
		  
		  db.collection('chat').doc(id).delete().then(function() {
		     
        //console.log("Document successfully deleted!");
         location.href="chat.html";
        }).catch(function(error) {
            console.error("Error removing document: ", error);
        });

		 
        } else if (change.type == 'removed'){
          console.log("removed");
        }
      });

    }, err => console.log(err.message));
}
