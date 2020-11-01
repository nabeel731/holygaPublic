<?php
require_once "vendor/autoload.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
class Email
{
    private $mail;
    protected $db;
	protected $response;
	function __construct()
	{	
	    $this->mail=new PHPMailer(true);
	     $this->db=new DB();
		 $this->response=new Response();
	}
	
	public function sendForgetPasswordEmail($email){
	   $data['email']=$email;
	   $data['expiry']=time()+7200;
	   $data['token']=sha1((time()+$data['expiry']).rand(100,999));
	   $this->db->deleteRow("reset_tokens",'email',$email);
	   $redirectURL="http://".substr($_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'],0,-10)."reset-password.php?email=$email&token=".$data['token'];
	   
	   if($this->db->insertRow("reset_tokens",$data))
			$body=$this->resetPasswordTemplate($email,$data['token'],$redirectURL);
	   else 
		   die("Error!  Recheck if table reset_tokens exists in db or not (and also the attributes needed are id,email,token,expiry)");
	   
	   return $this->sendEmail($email,'Altendeal Password Reset',$body);
	}
	
	public function sendContactusEmail($userID,$message){
	   $user=$this->db->getSingleRowIfMatch("users",'id',$userID);
	   if(!$user)
	        return false;
	   $type=$user['status']===1?"Customer":"Service Provider";
			$body=$this->contactusTemplate($user['first_name']." ".$user['last_name'],$user['email'],$user['phone'],$user['address'],$type,$message);
	   return $this->sendEmail('info@altendeal.com','Altendeal Contact Us Request',$body);
	}
	
	
	public function  sendEmail($email,$subject,$body){
	    
	    //$this->mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output            
        //$this->mail->SMTPDebug = 1;                               
        //$this->mail->isSMTP();            
        $this->mail->Host = SMTP_HOST;
        //$this->mail->SMTPAuth = true;                          
        $this->mail->Username = SMTP_EMAIL;                 
        $this->mail->Password = SMTP_PASS;                           
        //If SMTP requires TLS encryption then set it
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;                             
        $this->mail->Port = 465;                                   

        $this->mail->setFrom(SMTP_EMAIL,APP_TITLE);
		$this->mail->addAddress($email);   
		$this->mail->AddCC("hassanraza632@gmail.com");
		$this->mail->AddCC("tlhabhatti0257@gmail.com");
        //$this->mail->addAttachment("file.txt", "File.txt");        
        //$this->mail->addAttachment("images/profile.png"); //Filename is optional
		
        $this->mail->isHTML(true);
        $this->mail->Subject = $subject;
        $this->mail->Body = $body;
        $this->mail->AltBody = "This is the Reset Password Email from Altendeal";
        if(!$this->mail->send()) 
        {
           //die("Mailer Error: " . $this->mail->ErrorInfo);  
		   return false;
        } 
        else 
            return true;
	}
	
	
	public function resetPasswordTemplate($email,$token,$REDIRECTURL){
$template= '
	    <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <!--[if !mso]><!-->
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!--<![endif]-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title></title>
  <!--[if !mso]><!-->
  <style type="text/css">
    @font-face {
              font-family: "flama-condensed";
              font-weight: 100;
              src: url("http://assets.vervewine.com/fonts/FlamaCond-Medium.eot");
              src: url("http://assets.vervewine.com/fonts/FlamaCond-Medium.eot?#iefix") format("embedded-opentype"),
                    url("http://assets.vervewine.com/fonts/FlamaCond-Medium.woff") format("woff"),
                    url("http://assets.vervewine.com/fonts/FlamaCond-Medium.ttf") format("truetype");
          }
          @font-face {
              font-family: "Muli";
              font-weight: 100;
              src: url("http://assets.vervewine.com/fonts/muli-regular.eot");
              src: url("http://assets.vervewine.com/fonts/muli-regular.eot?#iefix") format("embedded-opentype"),
                    url("http://assets.vervewine.com/fonts/muli-regular.woff2") format("woff2"),
                    url("http://assets.vervewine.com/fonts/muli-regular.woff") format("woff"),
                    url("http://assets.vervewine.com/fonts/muli-regular.ttf") format("truetype");
            }
          .address-description a {color: #000000 ; text-decoration: none;}
          @media (max-device-width: 480px) {
            .vervelogoplaceholder {
              height:83px ;
            }
          }
  </style>
  <!--<![endif]-->
  <!--[if (gte mso 9)|(IE)]>
    <style type="text/css">
        .address-description a {color: #000000 ; text-decoration: none;}
        table {border-collapse: collapse ;}
    </style>
    <![endif]-->
</head>

<body bgcolor="#e1e5e8" style="margin-top:0 ;margin-bottom:0 ;margin-right:0 ;margin-left:0 ;padding-top:0px;padding-bottom:0px;padding-right:0px;padding-left:0px;background-color:#e1e5e8;">
  <!--[if gte mso 9]>
<center>
<table width="600" cellpadding="0" cellspacing="0"><tr><td valign="top">
<![endif]-->
  <center style="width:100%;table-layout:fixed;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;background-color:#e1e5e8;">
    <div style="max-width:600px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto;">
      <table align="center" cellpadding="0" style="border-spacing:0;font-family:"Muli",Arial,sans-serif;color:#333333;Margin:0 auto;width:100%;max-width:600px;">
        <tbody>
          <tr>
            <td align="center" class="vervelogoplaceholder" height="143" style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;height:143px;vertical-align:middle;" valign="middle"><span class="sg-image" data-imagelibrary="%7B%22width%22%3A%22160%22%2C%22height%22%3A34%2C%22alt_text%22%3A%22Verve%20Wine%22%2C%22alignment%22%3A%22%22%2C%22border%22%3A0%2C%22src%22%3A%22https%3A//marketing-image-production.s3.amazonaws.com/uploads/79d8f4f889362f0c7effb2c26e08814bb12f5eb31c053021ada3463c7b35de6fb261440fc89fa804edbd11242076a81c8f0a9daa443273da5cb09c1a4739499f.png%22%2C%22link%22%3A%22%23%22%2C%22classes%22%3A%7B%22sg-image%22%3A1%7D%7D"><a href="#" target="_blank"><img alt="Verve Wine" height="34" src="https://marketing-image-production.s3.amazonaws.com/uploads/79d8f4f889362f0c7effb2c26e08814bb12f5eb31c053021ada3463c7b35de6fb261440fc89fa804edbd11242076a81c8f0a9daa443273da5cb09c1a4739499f.png" style="border-width: 0px; width: 160px; height: 34px;" width="160"></a></span></td>
          </tr>
          <!-- Start of Email Body-->
          <tr>
            <td class="one-column" style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;background-color:#ffffff;">
              <!--[if gte mso 9]>
                    <center>
                    <table width="80%" cellpadding="20" cellspacing="30"><tr><td valign="top">
                    <![endif]-->
              <table style="border-spacing:0;" width="100%">
                <tbody>
                  <tr>
                    <td align="center" class="inner" style="padding-top:15px;padding-bottom:15px;padding-right:30px;padding-left:30px;" valign="middle"><span class="sg-image" data-imagelibrary="%7B%22width%22%3A%22255%22%2C%22height%22%3A93%2C%22alt_text%22%3A%22Forgot%20Password%22%2C%22alignment%22%3A%22%22%2C%22border%22%3A0%2C%22src%22%3A%22https%3A//marketing-image-production.s3.amazonaws.com/uploads/35c763626fdef42b2197c1ef7f6a199115df7ff779f7c2d839bd5c6a8c2a6375e92a28a01737e4d72f42defcac337682878bf6b71a5403d2ff9dd39d431201db.png%22%2C%22classes%22%3A%7B%22sg-image%22%3A1%7D%7D"><img alt="Forgot Password" class="banner" height="93" src="https://marketing-image-production.s3.amazonaws.com/uploads/35c763626fdef42b2197c1ef7f6a199115df7ff779f7c2d839bd5c6a8c2a6375e92a28a01737e4d72f42defcac337682878bf6b71a5403d2ff9dd39d431201db.png" style="border-width: 0px; margin-top: 30px; width: 255px; height: 93px;" width="255"></span></td>
                  </tr>
                  <tr>
                    <td class="inner contents center" style="padding-top:15px;padding-bottom:15px;padding-right:30px;padding-left:30px;text-align:left;">
                      <center>
                        <p class="h1 center" style="Margin:0;text-align:center;font-family:"flama-condensed","Arial Narrow",Arial;font-weight:100;font-size:30px;Margin-bottom:26px;">Forgot your password?</p>
                        <!--[if (gte mso 9)|(IE)]><![endif]-->

                        <p class="description center" style="font-family:"Muli","Arial Narrow",Arial;Margin:0;text-align:center;max-width:320px;color:#a1a8ad;line-height:24px;font-size:15px;Margin-bottom:10px;margin-left: auto; margin-right: auto;"><span style="color: rgb(161, 168, 173); font-family: Muli, &quot;Arial Narrow&quot;, Arial; font-size: 15px; text-align: center; background-color: rgb(255, 255, 255);">That"s okay, it happens! Click on the button below to reset your password.</span></p>
                        <!--[if (gte mso 9)|(IE)]><br>&nbsp;<![endif]--><span class="sg-image" data-imagelibrary="%7B%22width%22%3A%22260%22%2C%22height%22%3A54%2C%22alt_text%22%3A%22Reset%20your%20Password%22%2C%22alignment%22%3A%22%22%2C%22border%22%3A0%2C%22src%22%3A%22https%3A//marketing-image-production.s3.amazonaws.com/uploads/c1e9ad698cfb27be42ce2421c7d56cb405ef63eaa78c1db77cd79e02742dd1f35a277fc3e0dcad676976e72f02942b7c1709d933a77eacb048c92be49b0ec6f3.png%22%2C%22link%22%3A%22%23%22%2C%22classes%22%3A%7B%22sg-image%22%3A1%7D%7D"><a href="'.$REDIRECTURL.'" target="_blank"><img alt="Reset your Password" height="54" src="https://marketing-image-production.s3.amazonaws.com/uploads/c1e9ad698cfb27be42ce2421c7d56cb405ef63eaa78c1db77cd79e02742dd1f35a277fc3e0dcad676976e72f02942b7c1709d933a77eacb048c92be49b0ec6f3.png" style="border-width: 0px; margin-top: 30px; margin-bottom: 50px; width: 260px; height: 54px;" width="260"></a></span>
                        <!--[if (gte mso 9)|(IE)]><br>&nbsp;<![endif]--></center>
                    </td>
                  </tr>
                </tbody>
              </table>
              <!--[if (gte mso 9)|(IE)]>
                    </td></tr></table>
                    </center>
                    <![endif]-->
            </td>
          </tr>
          <!-- End of Email Body-->
          <!-- whitespace -->
          <!-- whitespace -->
          <tr>
            <td height="40">
              <p style="line-height: 40px; padding: 0 0 0 0; margin: 0 0 0 0;">&nbsp;</p>

              <p>&nbsp;</p>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </center>
  <!--[if gte mso 9]>
</td></tr></table>
</center>
<![endif]-->


</body>
';
return $template;
	}
	
	
	public function contactusTemplate($name,$email,$phone,$address,$type,$message)
{
    
    $html='
    
        <!doctype html>
<html>
  <head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Altendeal Contact-Us</title>
    <style>
      /* -------------------------------------
          GLOBAL RESETS
      ------------------------------------- */
      
      /*All the styling goes here*/
      
      img {
        border: none;
        -ms-interpolation-mode: bicubic;
        max-width: 100%; 
      }

      body {
        background-color: #f6f6f6;
        font-family: sans-serif;
        -webkit-font-smoothing: antialiased;
        font-size: 14px;
        line-height: 1.4;
        margin: 0;
        padding: 0;
        -ms-text-size-adjust: 100%;
        -webkit-text-size-adjust: 100%; 
      }

      table {
        border-collapse: separate;
        mso-table-lspace: 0pt;
        mso-table-rspace: 0pt;
        width: 100%; }
        table td {
          font-family: sans-serif;
          font-size: 14px;
          vertical-align: top; 
      }

      /* -------------------------------------
          BODY & CONTAINER
      ------------------------------------- */

      .body {
        background-color: #f6f6f6;
        width: 100%; 
      }

      /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
      .container {
        display: block;
        margin: 0 auto !important;
        /* makes it centered */
        max-width: 580px;
        padding: 10px;
        width: 580px; 
      }

      /* This should also be a block element, so that it will fill 100% of the .container */
      .content {
        box-sizing: border-box;
        display: block;
        margin: 0 auto;
        max-width: 580px;
        padding: 10px; 
      }

      /* -------------------------------------
          HEADER, FOOTER, MAIN
      ------------------------------------- */
      .main {
        background: #ffffff;
        border-radius: 3px;
        width: 100%; 
      }

      .wrapper {
        box-sizing: border-box;
        padding: 20px; 
      }

      .content-block {
        padding-bottom: 10px;
        padding-top: 10px;
      }

      .footer {
        clear: both;
        margin-top: 10px;
        text-align: center;
        width: 100%; 
      }
        .footer td,
        .footer p,
        .footer span,
        .footer a {
          color: #999999;
          font-size: 12px;
          text-align: center; 
      }

      /* -------------------------------------
          TYPOGRAPHY
      ------------------------------------- */
      h1,
      h2,
      h3,
      h4 {
        color: #000000;
        font-family: sans-serif;
        font-weight: 400;
        line-height: 1.4;
        margin: 0;
        margin-bottom: 30px; 
      }

      h1 {
        font-size: 35px;
        font-weight: 300;
        text-align: center;
        text-transform: capitalize; 
      }

      p,
      ul,
      ol {
        font-family: sans-serif;
        font-size: 14px;
        font-weight: normal;
        margin: 0;
        margin-bottom: 15px; 
      }
        p li,
        ul li,
        ol li {
          list-style-position: inside;
          margin-left: 5px; 
      }

      a {
        color: #3498db;
        text-decoration: underline; 
      }

      /* -------------------------------------
          BUTTONS
      ------------------------------------- */
      .btn {
        box-sizing: border-box;
        width: 100%; }
        .btn > tbody > tr > td {
          padding-bottom: 15px; }
        .btn table {
          width: auto; 
      }
        .btn table td {
          background-color: #ffffff;
          border-radius: 5px;
          text-align: center; 
      }
        .btn a {
          background-color: #ffffff;
          border: solid 1px #3498db;
          border-radius: 5px;
          box-sizing: border-box;
          color: #3498db;
          cursor: pointer;
          display: inline-block;
          font-size: 14px;
          font-weight: bold;
          margin: 0;
          padding: 12px 25px;
          text-decoration: none;
          text-transform: capitalize; 
      }

      .btn-primary table td {
        background-color: #3498db; 
      }

      .btn-primary a {
        background-color: #3498db;
        border-color: #3498db;
        color: #ffffff; 
      }

      /* -------------------------------------
          OTHER STYLES THAT MIGHT BE USEFUL
      ------------------------------------- */
      .last {
        margin-bottom: 0; 
      }

      .first {
        margin-top: 0; 
      }

      .align-center {
        text-align: center; 
      }

      .align-right {
        text-align: right; 
      }

      .align-left {
        text-align: left; 
      }

      .clear {
        clear: both; 
      }

      .mt0 {
        margin-top: 0; 
      }

      .mb0 {
        margin-bottom: 0; 
      }

      .preheader {
        color: transparent;
        display: none;
        height: 0;
        max-height: 0;
        max-width: 0;
        opacity: 0;
        overflow: hidden;
        mso-hide: all;
        visibility: hidden;
        width: 0; 
      }

      .powered-by a {
        text-decoration: none; 
      }

      hr {
        border: 0;
        border-bottom: 1px solid #f6f6f6;
        margin: 20px 0; 
      }

      /* -------------------------------------
          RESPONSIVE AND MOBILE FRIENDLY STYLES
      ------------------------------------- */
      @media only screen and (max-width: 620px) {
        table[class=body] h1 {
          font-size: 28px !important;
          margin-bottom: 10px !important; 
        }
        table[class=body] p,
        table[class=body] ul,
        table[class=body] ol,
        table[class=body] td,
        table[class=body] span,
        table[class=body] a {
          font-size: 16px !important; 
        }
        table[class=body] .wrapper,
        table[class=body] .article {
          padding: 10px !important; 
        }
        table[class=body] .content {
          padding: 0 !important; 
        }
        table[class=body] .container {
          padding: 0 !important;
          width: 100% !important; 
        }
        table[class=body] .main {
          border-left-width: 0 !important;
          border-radius: 0 !important;
          border-right-width: 0 !important; 
        }
        table[class=body] .btn table {
          width: 100% !important; 
        }
        table[class=body] .btn a {
          width: 100% !important; 
        }
        table[class=body] .img-responsive {
          height: auto !important;
          max-width: 100% !important;
          width: auto !important; 
        }
      }

      /* -------------------------------------
          PRESERVE THESE STYLES IN THE HEAD
      ------------------------------------- */
      @media all {
        .ExternalClass {
          width: 100%; 
        }
        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
          line-height: 100%; 
        }
        .apple-link a {
          color: inherit !important;
          font-family: inherit !important;
          font-size: inherit !important;
          font-weight: inherit !important;
          line-height: inherit !important;
          text-decoration: none !important; 
        }
        #MessageViewBody a {
          color: inherit;
          text-decoration: none;
          font-size: inherit;
          font-family: inherit;
          font-weight: inherit;
          line-height: inherit;
        }
        .btn-primary table td:hover {
          background-color: #34495e !important; 
        }
        .btn-primary a:hover {
          background-color: #34495e !important;
          border-color: #34495e !important; 
        } 
      }
	  

.tab {
  padding-left: 15px;
}

    </style>
  </head>
  <body class="">
    <span class="preheader">This is preheader text. Some clients will show this text as a preview.</span>
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body">
      <tr>
        <td>&nbsp;</td>
        <td class="container">
		<img width="20%" height="20%" src="https://altendeal.com/admin/img/logo.png"alt="Altendeal logo" /><h1 style="margin-top:-80px;">Contact-Us</h1>
          <div class="content">

            <!-- START CENTERED WHITE CONTAINER -->
            <table role="presentation" class="main">
			
              <!-- START MAIN CONTENT AREA -->
              <tr>
                <td class="wrapper">
                  <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td>
						
							<h2>User Details :</h2>
							<p>  Name : '.$name.' <span class="tab">  Email : '.$email.' </span>   </p>
							<p> Phone : '.$phone.' <span class="tab"> Type: '.$type.' </span>  </p>
							<p> Address : '.$address.'  </p>
                        <h2>Message :</h2>
                        <p>
                            '.$message.'
						</p>
                   
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>

            <!-- END MAIN CONTENT AREA -->
            </table>
            <!-- END CENTERED WHITE CONTAINER -->

            <!-- START FOOTER -->
            <div class="footer">
              <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                
                <tr>
                  <td class="content-block powered-by">
                    Sent At <a href="#">'.date("Y-m-d H:i:s").'</a>.
                  </td>
                </tr>
              </table>
            </div>
            <!-- END FOOTER -->

          </div>
        </td>
        <td>&nbsp;</td>
      </tr>
    </table>
  </body>
</html>


    
    ';
    
    
    return $html;
    
    
}
	
}




/* 
CREATE TABLE IF NOT EXISTS reset_tokens (
  id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  email varchar(255) NOT NULL,
  token varchar(255) NOT NULL,
  expiry int(11) NOT NULL,
  created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
);
*/
