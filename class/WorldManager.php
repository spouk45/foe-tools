<?php 
class WorldManager
{
	private $_db;
	
	public function __construct(PDO $db)
	{
		$this->_db=$db;
	}
	
	public function worldList()
	{
		$sql='SELECT id,name FROM world ORDER BY name';
		$stmnt=$this->_db->prepare($sql);
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}
		$i=0;
		while($row=$stmnt->fetch(PDO::FETCH_ASSOC))
		{
			$data[$i]['id']=$row['id'];
			$data[$i]['name']=$row['name'];
			$i++;
		}
		if(!isset($data))
		{
			return $data=null;
		}
		else {return $data;}
		
	}
}