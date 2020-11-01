<div id="update-password-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="modal-title">Update Password</h4>
         </div>
         <div class="modal-body">
            <form method="POST" action="updatePassword" id="updatePasswordForm" enctype="multipart/form-data">
			 <div class="form-group">
                  <label for="recipient-name" class="control-label">Old password:</label>
                  <input type="password" placeholder="Old Password" class="form-control" name="old_password" required> 
               </div>
			   
               <div class="form-group">
                  <label for="new-password" class="control-label">new password:</label>
                  <input type="password" class="form-control" name="new_password" placeholder="New Password"  required> 
               </div>
			   
			    <div class="form-group">
                  <label for="recipient-confirm" class="control-label">Confirm Password:</label>
                  <input type="text" class="form-control" name="confirm_password" placeholder="Confirm Password"  required> 
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