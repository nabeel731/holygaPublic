<?php
class Helper{
	// FUNCTIONS 
	function __construct()
	{
	    
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
				 die("input array cannot hold more than $max_size attributes");
		}
		else if($method=="GET")
		{
			if(sizeof($_GET)>$max_size)
				 die("input array cannot hold more than $max_size attributes");
		}
	  }
	else{
			if(sizeof($method)>$max_size)
				 die("input array cannot hold more than $max_size attributes");
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
				 die('Function Requires '.implode(',',$array));
		  }
		  else if($method=='GET')
		  {
			  if(!isset($_GET[$attr]) || empty($_GET[$attr]))
				 die('Function Requires '.implode(',',$array));
		  }
		  else
		{
			  if(!isset($method[$attr]) || empty($method[$attr]) )
				   die('Function Requires '.implode(',',$array));
			  if( strpos($attr,'id') !== false && !is_numeric($method[$attr]) )
				   die("$attr can only be Numeric");
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
	
	
	 public function uploadFile($file,$tmpName,$path,$back=true)
	 {
		 if(empty($file))
			 die("file Cannot be empty");
	     $extensions= ['png','jpg','jpeg','bmp','pdf','doc','docx','txt'];
		 $fileExtension=strtolower(pathinfo($file, PATHINFO_EXTENSION));
		  if(in_array($fileExtension,$extensions))
		{
			$trailingSlash='';
			if(substr($path, -1)!=='/')  $trailingSlash='/';
    		 $filePath=$path.$trailingSlash.substr(md5(time()),0,11).rand(1001,9999).'.'.$fileExtension;
    		 
    		 if($back)
		        move_uploaded_file($tmpName,'../'.$filePath);
		      else
		      move_uploaded_file($tmpName,$filePath);
		}
		else
			die("illegal extension only".implode(',',$extensions)." extensions are allowed");
		return $filePath;
	 }
	
	
	
	
}
