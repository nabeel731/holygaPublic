<?php
class DbHelper{
	
	// FUNCTIONS 
	function __construct()
	{
	}

 protected function returnTypeOfVar($value){
		 if(is_numeric($value) && $value<999999)
			  {
				  if($value == intval($value))
					  return 'i';
				  else return 'd';
			  }
			  else return 's';
	 }
	 
	 
	  protected function returnPrimaryKeyofTable($table){
		 $val='PRIMARY';
		 $query=" SHOW KEYS FROM $table WHERE Key_name =?";
		 $this->stmt = $this->conn -> prepare($query) or die($this->conn -> error);
		 $this->stmt -> bind_param("s",$val) or die($this->conn -> error);
		 $this->stmt->execute();
		$result = $this->stmt->get_result();
		$row = $result->fetch_assoc();
		if(empty($row))
		die('This Table Does Not  have any primary key');
		return $row['Column_name'];
	 }
	 protected function doesTableExistinDB($table){
		 $query="select 1 from $table LIMIT 1";
		  $result = $this->conn -> query($query);
		  if(!$result)
			  return false;
		 $row = $result->fetch_assoc();
		if(isset ($row[1]) && $row[1]!=FALSE)
			return true;
		     return false;
	 }
	 protected function validateTable($table){
		 $query="SHOW TABLES LIKE '$table'";
		  $result = $this->conn ->query($query);
		  if(!$result || $result->num_rows !== 1)
			  die("table ".$table." Does Not Exist");
		 $row = $result->fetch_assoc();
		if(empty($row))
		      die("table ".$table." Does Not Exist");
		  else
			  return true;
	 }
	 protected function buildInsertQuery($table,$array)
	 {
		 $i=0;  $types=''; $qvals=''; $qatrr='';
		  foreach ($array as $key => $value) {
				if($i>0)
				{
					$qatrr.=",";
					$qvals.=',';
				}
				$qatrr.=$key; $qvals.='?';
			  $types.=$this->returnTypeOfVar($value);
			  $values[]=$value;
			  $i++;
			}
			$this->query="INSERT INTO $table (".$qatrr.') VALUES ('.$qvals.')';
			return array($this->query,$types,$values);
	 }
	 protected function buildSelectJoinQuery($tables,$col,$val,$select,$opr,$join){
		if(!is_array($tables))
			$query="SELECT $select from $tables";
		else
		{	
			$sizeOfTables=sizeof($tables);
			if($sizeOfTables% 2 != 0)
				die("Join Array Cannot be odd (".implode(',',$tables).")");
			$query="SELECT $select from $tables[0]";
			for($i=0;$i<$sizeOfTables;$i+=2)
			{
				$this->validateTable($tables[$i]);  $this->validateTable($tables[$i+1]);
				$table1=$tables[$i]; $table2=$tables[$i+1];
				$primaryKey=$this->returnPrimaryKeyofTable($tables[$i]);
				$foreignKey=$this->getKeyReference($table1,$table2);
				//$query.=" CROSS JOIN $table2  ";
				$query.=" $join $table2 On $table1.$primaryKey=$table2.$foreignKey ";
			}
		}
		 $query.="where $table2.$col$opr?";
		 return  $query;
	 }
	 protected function buildUpdateQuery($table,$array,$col,$val,$opr)
	 {
		 $i=0;  $types=''; $qvals=''; $qatrr='';  $this->query="Update $table SET ";
		  foreach ($array as $key => $value) {
				if($i>0)
				{
					$qatrr.=",";
					$qvals.=',';
					$this->query.=",";
				}
				$this->query.="$key=?";
			  $types.=$this->returnTypeOfVar($value);
			  $values[]=$value;
			  $i++;
			}
			$this->query.=" WHERE $col$opr?";
			$types.=$this->returnTypeOfVar($val);
			return array($this->query,$types,$values);
	 }
	  protected function doesExist($table,$col,$val,$opr="="){
		 $type=$this->returnTypeOfVar($val);
		 $query="SELECT * from $table where $col$opr?";
		 $this->stmt = $this->conn -> prepare($query) or die($this->conn -> error);
		 $this->stmt -> bind_param($type,$val) or die($this->conn -> error);
		 $this->stmt->execute();
		$result = $this->stmt->get_result();
		if($result->num_rows === 0)
			return false;
			return true;
	 }
	 protected function getColumnsOfTable($table){
		 $query="SHOW COLUMNS FROM $table";
		 $result = $this->conn -> query($query);
		 $data=array();
		while($row=$result->fetch_assoc())
		{
			$data[]=$row;
		}
		return $data;
	 }
	 
	 protected function getKeyReference($table1,$table2){
		 $query="SELECT TABLE_NAME,COLUMN_NAME, CONSTRAINT_NAME,REFERENCED_TABLE_NAME,REFERENCED_COLUMN_NAME
			FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
			WHERE
			REFERENCED_TABLE_NAME =?";
		$this->stmt = $this->conn -> prepare($query) or die($this->conn -> error);
		 $this->stmt -> bind_param('s',$table1) or die($this->conn -> error);
		 $this->stmt->execute();
		$result = $this->stmt->get_result();
		 $data=array();
		while($row=$result->fetch_assoc())
		{
			if($row['TABLE_NAME']==$table2)
			return $row['COLUMN_NAME'];
		}
		die("Table ".$table1." is not referenced to table ".$table2);
	 }
	 
	 
	 protected function bind_custom_param($values,$types=""){
		 if(!is_array($values))
		 {
			$value[0]=$values;
			$values=$value;
		 }
		 if(empty($types))
		 {
			 foreach($values as $value)
			 $types.=$this->returnTypeOfVar($value);
		 }
			 
		 switch(sizeof($values))
		  {
			case 1:
				$this->stmt -> bind_param($types,$values[0]);
				break;
			case 2:
				$this->stmt -> bind_param($types,$values[0],$values[1]);
				break;
			case 3:
				$this->stmt -> bind_param($types,$values[0],$values[1],$values[2]);
				break;
			case 4:
				$this->stmt -> bind_param($types,$values[0],$values[1],$values[2],$values[3]);
				break;
			case 5:
				$this->stmt -> bind_param($types,$values[0],$values[1],$values[2],$values[3],$values[4]);
				break;
			case 6:
				$this->stmt -> bind_param($types,$values[0],$values[1],$values[2],$values[3],$values[4],$values[5]);
				break;
			case 7:
				$this->stmt -> bind_param($types,$values[0],$values[1],$values[2],$values[3],$values[4],$values[5],$values[6]);
				break;
			case 8:
				$this->stmt -> bind_param($types,$values[0],$values[1],$values[2],$values[3],$values[4],$values[5],$values[6],$values[7]);
				break;
			case 9:
				$this->stmt -> bind_param($types,$values[0],$values[1],$values[2],$values[3],$values[4],$values[5],$values[6],$values[7],$values[8]);
				break;
			case 10:
				$this->stmt -> bind_param($types,$values[0],$values[1],$values[2],$values[3],$values[4],$values[5],$values[6],$values[7],$values[8],$values[9]);
				break;
			case 11:
				$this->stmt -> bind_param($types,$values[0],$values[1],$values[2],$values[3],$values[4],$values[5],$values[6],$values[7],$values[8],$values[9],$values[10]);
				break;
				case 12:
				$this->stmt -> bind_param($types,$values[0],$values[1],$values[2],$values[3],$values[4],$values[5],$values[6],$values[7],$values[8],$values[9],$values[10],$values[11]);
				break;
				case 13:
				$this->stmt -> bind_param($types,$values[0],$values[1],$values[2],$values[3],$values[4],$values[5],$values[6],$values[7],$values[8],$values[9],$values[10],$values[11],$values[12]);
				break;
				case 14:
				$this->stmt -> bind_param($types,$values[0],$values[1],$values[2],$values[3],$values[4],$values[5],$values[6],$values[7],$values[8],$values[9],$values[10],$values[11],$values[12],$values[13]);
				break;
			default:
			die("maximum attribute limit is 14 and you have sent ".sizeof($values)." attributes in your request");	
		  }
	 }
	
}
