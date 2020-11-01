<div id="user-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="modal-title">Add User</h4>
         </div>
         <div class="modal-body">
            <form method="POST" action="addUser" id="addUserForm" enctype="multipart/form-data">
			 <div class="form-group">
                  <label for="recipient-name" class="control-label">Name:</label>
                  <input type="text" class="form-control" placeholder="Name" name="name" required> 
               </div>
			   
               <div class="form-group">
                  <label for="recipient-name" class="control-label">Phone:</label>
                  <input type="text" class="form-control" name="phone" placeholder="Phone Number"  required> 
               </div>
			   
			    <div class="form-group">
                  <label for="recipient-name" class="control-label">Email:</label>
                  <input type="email" class="form-control" name="email" placeholder="Email"  required> 
               </div>
			   
			   <div class="form-group">
                  <label for="recipient-name" class="control-label">Device Unique_Id:</label>
                  <input type="text" class="form-control" name="device_id" placeholder="Device Unique_Id"  required> 
               </div>
			
            
         </div>
			 <div class="modal-footer">
			    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-danger waves-effect waves-light">Save changes</button>
			 </div>
		 </form>
      </div>
   </div>
</div