<?php 
class Seek
{
	private $_db;
	public function __construct(PDO $db)
	{
		$this->_db=$db;
	}
	
	public function addSeek(array $data)
	{	
		$tabSeek=$data['tabSeek'];
		$tab=explode(':',$tabSeek);
		$link=serialize($tab);
		if($data['resource1']==0)
		{
			$data['resource1']=null;
			$data['nb_resource1']=null;
			$data['resource2']=null;
			$data['nb_resource2']=null;
		}
			
		$sql='INSERT INTO seek (name,pf,resource1_id,resource2_id,nb_resource1,nb_resource2,age_id,link,col,li) 
		VALUES (:name,:pf,:resource1_id,:resource2_id,:nb_resource1,:nb_resource2,:age_id,:link,:col,:li)';
		$stmnt=$this->_db->prepare($sql);
		$stmnt->bindParam(':name',$data['name']);
		$stmnt->bindParam(':pf',$data['pf']);
		$stmnt->bindParam(':resource1_id',$data['resource1']);
		$stmnt->bindParam(':resource2_id',$data['resource2']);
		$stmnt->bindParam(':nb_resource1',$data['nb_resource1']);
		$stmnt->bindParam(':nb_resource2',$data['nb_resource2']);
		$stmnt->bindParam(':age_id',$data['age']);
		$stmnt->bindParam(':link',$link);
		$stmnt->bindParam(':col',$data['col']);
		$stmnt->bindParam(':li',$data['li']);
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}
	}
	
	public function readSeek()
	{
		$sql='SELECT id,name,pf,resource1_id,resource2_id,nb_resource1,nb_resource2,age_id		
		FROM seek';
		
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
			$data[$i]['pf']=$row['pf'];
			$data[$i]['resource1Id']=$row['resource1_id'];
			$data[$i]['resource2Id']=$row['resource2_id'];
			$data[$i]['nbResource1']=$row['nb_resource1'];
			$data[$i]['nbResource2']=$row['nb_resource2'];
			$data[$i]['ageId']=$row['age_id'];
			$i++;
		}
		
		return $data;
	}
}