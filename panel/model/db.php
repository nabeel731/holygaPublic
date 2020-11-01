<?php
require_once dirname(dirname(dirname(__FILE__)))."/config.php";
require_once 'classes/DbHelper.php';

class DB extends DbHelper
{
	protected $stmt;
	protected $conn;
	
   function __construct(){
	   mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	   // Create connection
	   $this->conn= new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
		// Check connection
		if ($this->conn->connect_error) {
			die("Connection failed: " . $this->conn->connect_error);
		}
		$this->conn -> set_charset("utf8mb4");
   } 
   
   public function getConnection(){
	   return $this->conn;
   }
   

	public function insertRow($table,$array){
		  if(empty($array))  Die('Attributes Array Cannot be empty');
			$return=$this->buildInsertQuery($table,$array);
			$query=$return[0]; $types=$return[1]; $values=$return[2];
		  $this->stmt = $this->conn -> prepare($query) or die($this->conn -> error);
		  $this->bind_custom_param($values,$types);
		  $this->stmt->execute() or die($this->conn -> error);
		  if($this->conn->affected_rows === 0)
			return false;
		else
		{
			$data=$array;
			if(isset($data['password']))
				unset($data['password']);
			$data['id']=$this->conn->insert_id;
			return $data;
		}
	 }
	 
	 
	 
	 
	 public function exist($table,$col,$val,$opr="=")
	 {
		$query="SELECT * FROM $table WHERE $col$opr?";
		$this->stmt = $this->conn -> prepare($query) or die($this->conn -> error);
		 $this->stmt -> bind_param("s",$val);
		 $this->stmt->execute();
		$result = $this->stmt->get_result();
		if($result->num_rows === 0)
			return FALSE;
			return TRUE;
	 }
	 
	 
	 
	 
	 
	 public function getTable($table,$order="DESC"){
		 $order_by=$this->returnPrimaryKeyofTable($table);
		 $query="select * from $table ORDER BY $order_by $order LIMIT 900";
		 if(strtoupper($order)=='RAND')
			 $query="select * from $table ORDER BY rand() LIMIT 900";
		 $result = $this->conn -> query($query) or die($this->conn->error);
		 $data=array();
		 while ($row = $result->fetch_assoc()){
			 if(isset($row['password']))
				unset($row['password']);
			$data[] = $row;
		}
		 return $data;
	 }
	
	
	public function getJoinedRowsIfMatch($tables,$col,$val,$select="*",$opr="=",$join="INNER JOIN"){
		 $query=$this->buildSelectJoinQuery($tables,$col,$val,$select,$opr,$join);		 
		 $type=$this->returnTypeOfVar($val);
		 $this->stmt = $this->conn -> prepare($query) or die($this->conn -> error);
		 $this->stmt -> bind_param($type,$val) or die($this->conn -> error); 
		 $this->stmt->execute();
		$result = $this->stmt->get_result();
		$data=array();
		while($row = $result->fetch_assoc())
		$data[]=$row;
		return $data;
	 }
	
	
	public function updateRow($table,$array,$col,$val,$opr="="){
	   
		  if(empty($array))  die('Attributes Array Cannot be empty');
		  //if($this->doesExist($table,$col,$val,$opr)==false)
			// die("No data Found against this condition ");
		  if(isset($array[$col])) unset($array[$col]);
			$return=$this->buildUpdateQuery($table,$array,$col,$val,$opr);
		 $query=$return[0]; $types=$return[1]; $values=$return[2];
		 $values[sizeof($values)]=$val;
		 $this->stmt = $this->conn -> prepare($query) or die($this->conn->error);
		 $this->bind_custom_param($values,$types);
		 $this->stmt->execute() or die($this->conn->error);
		  $data=$array;
		$data[$col]=$val;
		  if($this->conn->affected_rows === 0)
		  {
			  if(isset($this->stmt->error) && !empty($this->stmt->error))
			 die('Query Error ');
			else
				return $data;
		  }
		else
		{
			return $data;
		}
	 }
	 
	 
	 
	 
	 public function getSingleRowIfMatch($table,$col,$val,$opr="="){
		 $query="SELECT * from $table where $col$opr?";
		 $type=$this->returnTypeOfVar($val);
		 $this->stmt = $this->conn -> prepare($query) or die($this->conn -> error);
		 $this->stmt -> bind_param($type,$val) or die($this->conn -> error);
		 $this->stmt->execute();
		$result = $this->stmt->get_result();
		$row = $result->fetch_assoc();
		if(isset($row['password']))
			unset($row['password']);
		return $row;
	 }
	 
	 
	  public function getDataWIthQuery($query,$vals="",$types=""){
		  $data=array();
		 if(empty($types) && empty($vals)) 
		 {
			$result = $this->conn->query($query) or die($this->conn->error);
			while($row = $result->fetch_assoc())
			$data[]=$row;
		 }
		 else
		 {
			$this->stmt = $this->conn -> prepare($query) or die($this->conn -> error);
			$this->bind_custom_param($vals,$types);
			$this->stmt->execute() or die($this->stmt->error);;
			$result = $this->stmt->get_result();
			while($row = $result->fetch_assoc())
			$data[]=$row;
		 }
		return $data;
	 }
	 
	 
	 
	 
	 public function deleteDataWIthQuery($query,$vals="",$types=""){
		  $data=array();
		 if(empty($types) && empty($vals)) 
		 {
			$result = $this->conn->query($query) or die($this->conn->error);
		 }
		 else
		 {
			$this->stmt = $this->conn -> prepare($query) or die($this->conn -> error);
			$this->bind_custom_param($vals,$types);
			$this->stmt->execute() or die($this->stmt->error);
		 }
		if($this->conn->affected_rows>0)
			 return true;
		else
			 return false;
	 }
	 



    public  function deleteRow($table,$col,$val,$opr="="){
		 //$this->validateTable($table);
		 $query="DELETE from $table where $col$opr?";
		 $this->stmt = $this->conn ->prepare($query) or die($this->conn -> error);
		 $this->stmt -> bind_param("i",$val);
		 $this->stmt->execute();
		 if($this->conn->affected_rows>0)
			 return true;
		 else
			 return false;
	 }
	 
	 
	 public function getMultipleRowsIFIN($table,$col,$array,$select="*"){
	    
	      $helper=new Helper();
	     if(!is_array($array) || $helper->isAssocArray($array))
	        die(" Third Attribute needs to be numeric Array");
	      $query="SELECT $select FROM $table WHERE  $col IN (".implode(',',$array).")";
	      $result = $this->conn->query($query) or die($this->conn->error);
		  $data=array();
		  while ($row = $result->fetch_assoc()){
			  $data[] = $row;
		  }
		 return $data;
	    
	 }
	 
	 
	 
	 public function getTotalCount($table=""){
		 $this->validateTable($table);
		 $result = $this->conn->query("SELECT COUNT(*) FROM $table");
		$count = $result->fetch_row();
		return $count[0];
	 }
	 
	  public function returnCount($table,$col,$val,$opr="="){
		 $this->validateTable($table);
		 $result = $this->conn->query("SELECT COUNT($col) FROM $table WHERE $col$opr$val");
		$count = $result->fetch_row();
		return $count[0];
	 }
	 
	
	 
	public function getMultipleRowsIfMatch($table,$col,$val,$select="*",$opr="="){
		  //$this->validateTable($table);
		  $type=$this->returnTypeOfVar($val);
		 $query="SELECT $select from $table where $col$opr?";
		 $this->stmt = $this->conn -> prepare($query) or die($this->conn -> error);
		 $this->stmt -> bind_param($type,$val);
		 $this->stmt->execute();
		$result = $this->stmt->get_result();
		$data=array();
		while($row = $result->fetch_assoc())
		$data[]=$row;
		return $data;
	 }
	 
	 public function getMultipleRows($table,$order="DESC",$order_by="id",$limit="150"){
		 if(empty($order_by))
			$order_by=$this->returnPrimaryKeyofTable($table);
		 if(empty($limit)) $limit=100;
		 $query="select * from $table ORDER BY $order_by $order LIMIT $limit";
		 if(strtoupper($order)=='RAND')
			 $query="select * from $table ORDER BY rand() LIMIT $limit";
		 $result = $this->conn->query($query) or die($this->conn->error);
		 $data=array();
		 while ($row = $result->fetch_assoc()){
			  if(isset($row['password']))
				unset($row['password']);
			$data[] = $row;
		}
		 return $data;
	 }
	 
	

}