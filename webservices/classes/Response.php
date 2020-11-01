<?php
class Response{
	
	function __construct()
	{
	 
	}

  public  function success($message="",$data=NULL){
        if($data===NULL)
            $data=array();
		 $res= array( 'status'=>200,'message'=> $message,'data'=>$data);
		 //print_r(json_encode($res,JSON_UNESCAPED_UNICODE));
		 print_r(json_encode($res));
		 die;
	 }
	 public  function successMessage($message=""){
		 $res= array( 'status'=>200,'message'=> $message);
		 print_r(json_encode($res));
		 die;
	 }
  public function error($message=""){
		 $res= array( 'status'=>400,'message'=> $message);
		 print_r(json_encode($res));
		 die;
	 }
	 
	 public function notFound($message=""){
		 $res= array( 'status'=>404,'message'=> $message);
		 print_r(json_encode($res));
		 die;
	 }
	 
	 public function forbidden($message=""){
		 $res= array( 'status'=>403,'message'=> $message);
		 print_r(json_encode($res));
		 die;
	 }
	 
	 public function unauthorized($message=""){
		 $res= array( 'status'=>401,'message'=> $message);
		 print_r(json_encode($res));
		 die;
	 }
	 
	 
	 
    private function utf8_converter($array)
	{
		array_walk_recursive($array, function(&$item, $key){
			if(!mb_detect_encoding($item, 'utf-8', true)){
				$item = utf8_encode($item);
			}
		});
		return $array;
	}
}
