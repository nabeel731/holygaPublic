 <?php 
 use ReallySimpleJWT\Token;
 require_once 'vendor/autoload.php';
 define('SECRET','sec!ReT423*&^%OWL#talha');
 class JWT
{
    private $token;
    protected $response;
	function __construct()
	{ 
	    $this->response=new Response();
	    $allowedRoutes=['redeem','login','register','forgetPassword','terms_privacy','aboutUs'];
        // To VALIDATE JSON WEB TOKEN
        if(!in_array($_GET['route'], $allowedRoutes))
        {
             $headers = apache_request_headers();
             if(isset($headers['token']))
                {define('JW_TOKEN',$headers['token']); unset($headers['token']);}
            elseif(isset($_GET['token']))
                 { define('JW_TOKEN',$_GET['token']); unset($_GET['token']);}
            elseif(isset($_POST['token']))
                { define('JW_TOKEN',$_POST['token']); unset($_POST['token']);}
            else define('JW_TOKEN',"");
    	    $this->token=JW_TOKEN;
    	    if(empty($this->token))
            	$this->response->notFound('Authorization Token Missing');
        	if(!$this->validateToken($this->token))
        		$this->response->forbidden('Authorization Token is Invalid or Expired,Permission Denied');
        	define('LOGGED_USER',$this->user());
        }
	} 
	
	
	 public function generateToken($data)
	 {
	     if(empty($data))
	        die(" Input Data Cannot be empty in JWT::generateToken() ");
		if(isset($data['time']))
			$expiration =$data['expiry'];
		$expiration = time() + 9999999;
		$issuer = 'localhost';
		if(is_array($data))
		{
    		$payload = [
                'iat' => time(),
                'exp' => $expiration,
                'iss' => $issuer
            ];
            $payload=array_merge($data,$payload);
            return Token::customPayload($payload,SECRET);
		}
		  return Token::create($data,SECRET,$expiration,$issuer);
	 }
	  public function validateToken($token)
	 {
		return Token::validate($token,SECRET);
	 }
	 
	  public function getPayload()
	  {
		  return Token::getPayload($this->token,SECRET);
		 
	  }
	   public function getHeader()
	  {
		 return Token::getHeader($this->token,SECRET);
	  }
	  
	 public function refreshToken()
	 {
	     $payload=$this->getPayload();
	     if(isset($payload['iat']))
	         unset($payload['iat']);
	     if(isset($payload['exp']))
	           unset($payload['exp']);
	     if(isset($payload['iss']))
	           unset($payload['iss']);
		 return  $this->generateToken($payload);
	 }
	 
	 public function manualTokenRefresh(){
	     $payload=$this->getPayload();
	     if($payload && $payload['exp'] && ($payload['exp']-time())<3409600)
	        return $this->refreshToken();
	   else return $this->token;
	 }
	 
	 public function user()
	 {
	     return $this->getPayload()['user_id'];
	 }
	 
}

// Read About this library from https://github.com/RobDWaller/ReallySimpleJWT
