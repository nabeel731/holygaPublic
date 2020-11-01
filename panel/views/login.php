<!DOCTYPE html>  
<html lang="en">
<?php include_once 'includes/head.php'?>
<body>
<!-- Preloader -->
<div class="preloader">
  <div class="cssload-speeding-wheel"></div>
</div>
<section id="wrapper" class="new-login-register" style="background-image:url('assets/images/bg.jpg'); background-repeat: repeat-x;">
      
      <div class="new-login-box" style="margin:0 auto; margin-top:90px;">
                <div class="white-box">
                    <h3 class="box-title m-b-0">Sign In to OWL Admin</h3>
                    <small>Enter your details below</small>
                  <form class="form-horizontal new-lg-form" method="POST" id="loginform" action="loginSubmit">
                    
                    <div class="form-group  m-t-20">
                      <div class="col-xs-12">
                        <label>Email Address</label>
                        <input class="form-control" type="email" minlength="10" required name="email" placeholder="Email" >
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-xs-12">
                        <label>Password</label>
                        <input class="form-control" type="password" minlength="6" name="password" required placeholder="Password">
                      </div>
                    </div>
                    
                    <div class="form-group text-center m-t-20">
                      <div class="col-xs-12">
                        <button class="btn btn-info btn-lg btn-block btn-rounded text-uppercase waves-effect waves-light" type="submit">Log In</button>
                      </div>
                    </div>
                    
                  </form>
                  
                </div>
      </div>            
  
  
</section>
<?php include_once 'includes/scripts.php'?>
<?php include_once 'includes/responses.php'?>
</body>
</html>
