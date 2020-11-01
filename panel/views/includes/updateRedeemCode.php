<div id="updateredeem-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="modal-title">Add Redeem Code</h4>
         </div>
         <div class="modal-body">
            <form method="POST" action="updateRedeem" id="addUserForm" enctype="multipart/form-data">
						<input type="hidden" name="redeem_id" id="redeem_id">
			 <div class="form-group">
                  <label class="control-label">Redeem Coupon:</label>
                  <input type="text" class="form-control" placeholder="Redeem Coupon" name="redeem_code" id="redeem_code" value="<?=$randomCoupon?>" required> 
               </div>
				
				<div class="form-group" >
						<select  class="form-control" name="user_id" id="user_id" required>
							<option value="" disabled selected>Slect User</option>
							<?php foreach($usersdata as $user)
							{
							?>
							<option value="<?php echo $user['id'] ?>"><?php echo $user['name'] ?></option>
							<?php }?>
                        </select>
			   </div>
			   <div class="form-group" >
						<select  class="form-control" id="validity" name="validity" required>
							<option value="" disabled selected>Validity in days</option>
							<?php for($i=1;$i<71;$i++)
							{
							?>
							<option value="<?=$i?>"><?=$i?> days</option>
							<?php }?>
                        </select>
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