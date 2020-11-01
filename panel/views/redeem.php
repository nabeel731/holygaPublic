<!DOCTYPE html>
<html lang="en">
<?php include_once 'includes/head.php';?>

<body class="fix-header">
    <!-- ============================================================== -->
    <!-- Preloader -->
    <!-- ============================================================== -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
        </svg>
    </div>
    <!-- ============================================================== -->
    <!-- Wrapper -->
    <!-- ============================================================== -->
    <div id="wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
         <?php include_once 'includes/nav.php'?>
        <!-- End Top Navigation -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
       <?php include_once 'includes/sidebar.php' ?>
        <!-- ============================================================== -->
        <!-- End Left Sidebar -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page Content -->
        <!-- ============================================================== -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /row -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="white-box">
                            <h3 class="box-title">Redeem Code</h3>
							<button type="button" class="btn btn-primary" style="margin-bottom:10px;margin-left:85%;" onClick="showRedeemAddModal()">Add Redeem Code</button
                            <div class="table-responsive">
                                <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                          
                                            <th>User Name</th>
                                            <th>Redeem Code</th>
                                            <th>Validity</th>
											<th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                  <?php 

                                       foreach( $redeemdata as $user )
                                       { ?>
                                        <tr>
											<td><?=$user['name']?></td>
                                            <td><?=$user['redeem_code']?></td>
                                            <td><?=$user['validity']?></td>
											<td>
												<div style="text-align:center"  class="dropdown">
													<a style="cursor:pointer" class=" dropdown-toggle" type="button" data-toggle="dropdown">
													<i class="fa fa-ellipsis-v" style="font-size:22px;" aria-hidden="true"></i>
													</a>
													<ul class="dropdown-menu">
													  <li><a href="#" onClick="showRedeemUpdateModal(<?=$user['id']?>)"><i class="fa fa-edit"></i> Update</a></li>
													  
													  <li><a href="#" onClick="deleteRedeem(<?=$user['id']?>)"><i class="fa fa-trash"></i> Delete</a></li>
													  
													</ul>
											  </div>
											</td>
                                         </tr>
                                      <?php }
                                       ?>
							
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
            </div>
			<!-- /.USER-MODAL -->
            <?php include_once 'includes/userModal.php' ?>
            <!-- /.container-fluid -->
            <?php include_once 'includes/footer.php' ?>
			<?php include_once 'includes/RedeemCode.php' ?>
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
    <!-- jQuery -->
		<?php include_once 'includes/updateRedeemCode.php' ?>
  <?php include_once 'includes/scripts.php' ?>
  <?php include_once 'includes/js.php' ?>
  <?php include_once 'includes/responses.php' ?>
  
  <script>

$( "#addUserForm" ).submit(function(e) {
	let date=new Date();
	let dob= new Date(document.querySelector("#dob").value);
	date.setFullYear(date.getFullYear() - 16 );
	if(dob>date)
	{
		e.preventDefault();
		Swal.fire(
			'Under 16!',
			"You must be atleast 16 years old to use this application",
			'error'
			);
			return false;
	}
});

	
</script>
  
</body>

</html>
