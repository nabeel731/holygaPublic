<?php
class Helper{
	public $response;
	// FUNCTIONS 
	function __construct()
	{
	    $this->response=new Response();
	}
	
	public function getInput(){
		 if($_SERVER['REQUEST_METHOD']!="POST")
		       $this->response->error("Only Post Method Allowed");
	$data=$this->object_to_array(json_decode(file_get_contents('php://input')));
	if(empty($data)) 
		$data=$_POST;
	return $data;
}

  public function validateInput($method,$array,$max_size=50)
  {
	 if(isset($_GET['route']))
		 unset($_GET['route']);
	 
	  if(!is_array($method))
	  {
		$method=strtoupper($method);
		if($method=="POST")
		{
			if(sizeof($_POST)>$max_size)
				 $this->response->error("input array cannot hold more than $max_size attributes");
		}
		else if($method=="GET")
		{
			if(sizeof($_GET)>$max_size)
				 $this->response->error("input array cannot hold more than $max_size attributes");
		}
	  }
	else{
			if(sizeof($method)>$max_size)
				 $this->response->error("input array cannot hold more than $max_size attributes");
		}
  
	  $allowedMethods=array('GET','POST','PUT','DELETE');
	  if (!in_array($method,$allowedMethods) && !is_array($method))
		 die("only ".implode(',',$allowedMethods)." Methods can be validated"); 
	 
	  for($i=0;$i<sizeof($array);$i++)
	  {
		  $attr=$array[$i];
		  if($method=='POST')
		  {
			  
			  if(!isset($_POST[$attr]) || empty($_POST[$attr]))
				 $this->response->error('Function Requires '.implode(',',$array));
		  }
		  else if($method=='GET')
		  {
			  if(!isset($_GET[$attr]) || empty($_GET[$attr]))
				 $this->response->error('Function Requires '.implode(',',$array));
		  }
		  else
		{
			  if(!isset($method[$attr]) || empty($method[$attr]) )
				   $this->response->error('Function Requires '.implode(',',$array));
			  if( strpos($attr,'id') !== false && $attr!="device_id" &&  !is_numeric($method[$attr]) )
				   $this->response->error("$attr can only be Numeric");
		  }
			  
	  }
	  
  }
  
	  
	  public function object_to_array($data)
	{
		if (is_array($data) || is_object($data))
		{
			$result = array();
			foreach ($data as $key => $value)
			{
				$result[$key] = $this->object_to_array($value);
			}
			return $result;
		}
		return $data;
	}
  
  
   public function encrypt_decrypt($action, $string) 
	{
		$output = false;
		$encrypt_method = "AES-256-CBC";
		$secret_key = 'ery4ajvf11224vb';
		$secret_iv = 'gery2211bfhhj34hb';
		// hash
		$key = hash('sha256', $secret_key);
		
		// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
		$iv = substr(hash('sha256', $secret_iv), 0, 16);
		if ( $action == 'encrypt' ) {
			$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
			$output = base64_encode($output);
		} else if( $action == 'decrypt' ) {
			$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
		}
		return $output;
	}
	
	function isAssocArray(array $arr)
    {
        if (array() === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
	
	
	function base64_to_jpeg($base64_string, $output_file) {
    // open the output file for writing
    $ifp = fopen( "../".$output_file, 'wb' ); 

    // split the string on commas
    // $data[ 0 ] == "data:image/png;base64"
    // $data[ 1 ] == <actual base64 string>
    $data = explode( ',', $base64_string );
	
    if(!isset($data[1]))
		 $this->response->error("Invalid base64");
    fwrite( $ifp, base64_decode( $data[ 1 ] ) );

    // clean up the file resource
    fclose( $ifp ); 

    return $output_file; 
}
	
	
	
	
}
