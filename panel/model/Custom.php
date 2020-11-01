<?php
class Custom
{
    protected $db;
    protected $response;
	function __construct()
	{
	    $this->db=new DB();
	    $this->conn=$this->db->getConnection();
	}
	
	public function returnUserData($id){
		$query="SELECT users.*,picture1,picture2,picture3,picture4,picture5
		FROM users 
		INNER JOIN pictures on users.id=pictures.user_id 
		WHERE users.id=?";
		 $stmt = $this->conn ->prepare($query) or die($this->conn -> error);
		 $stmt -> bind_param('i',$id) or die($this->conn -> error);
		 $stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_assoc();
		return $row;
	}
	
	 public function getWebStats(){
		  $query=" Select  Count(id) as count FROM users AS t
			";
			$data = $this->db->getDatawithQuery($query);
			$res=[];
			if($data)
			{
				$res['total_users']=$data[0]['count'];
				$res['total_matches']=$data[1]['count'];
			}
			return $res;
	 }
	
}
