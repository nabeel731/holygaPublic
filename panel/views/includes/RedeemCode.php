<div id="redeem-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="modal-title">Add Redeem Code</h4>
         </div>
         <div class="modal-body">
            <form method="POST" action="addRedeem" id="addUserForm" enctype="multipart/form-data">
			 <div class="form-group">
			 <?php $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
				$randomCoupon=rand(1,9).substr(str_shuffle($permitted_chars), 0,5).rand(11,99);
			 ?>
                  <label class="control-label">Redeem Coupon:</label>
                  <input type="text" class="form-control" placeholder="Redeem Coupon" name="redeem_code" value="<?=$randomCoupon?>" required> 
               </div>
				
				<div class="form-group" >
						<select  class="form-control" name="user_id" required>
							<option value="" disabled selected>Slect User</option>
							<?php foreach($usersdata as $user)
							{
							?>
							<option value="<?php echo $user['id'] ?>"><?php echo $user['name'] ?></option>
							<?php }?>
                        </select>
			   </div>
			   <div class="form-group" >
						<select  class="form-control" name="validity" required>
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