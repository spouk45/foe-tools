<?php 
class SeekManager{
	
	private $_db;
	public function __construct(PDO $db)
	{
		$this->_db=$db;
	}
	
	public function seekList()
	{
		$sql='SELECT id,name,pf,resource1_id,resource2_id,nb_resource1,nb_resource2,age_id,col,li		
		FROM seek ORDER BY age_id,col,li DESC';
		
		$stmnt=$this->_db->prepare($sql);
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}
		$i=0;
		$data=null;
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
			$data[$i]['col']=$row['col'];
			$data[$i]['li']=$row['li'];
			$i++;
		}
		
		return $data;
	}
	
	public function getPlayerSeek($id)
	{
		if(!is_numeric($id))
		{
			throw new Exception('<p>Doit être un INT</p>');
		}
		
		$sql='SELECT id,seek_id,pf,unlocked FROM pf WHERE player_id=:player_id';		
		$stmnt=$this->_db->prepare($sql);
		$stmnt->bindParam(':player_id',$id);
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}
		
		$data=null;
		while($row=$stmnt->fetch(PDO::FETCH_ASSOC))
		{			
			$data[$row['seek_id']]['id']=$row['id'];
			$data[$row['seek_id']]['pf']=$row['pf'];
			$data[$row['seek_id']]['unlocked']=$row['unlocked'];
			
		}
		
		return $data;		
	}
	
	public function updatePf(array $data)
	{		
		$maxPf=$this->getMaxPf($data['seekId']);
		if(!isset($data['pf'])) // mettre au max les pf
			{
				// récupére la valeur max pf
				
				$data2=$data;				
				$data2['pf']=$maxPf;
				$this->updatePf($data2);
				
			}
			else
			{
				if($data['pf']<=$maxPf)
				{
					$pfId=$this->getPf($data);
				
					if($pfId==null)
					{
						$this->addPf($data);
					}
					else
					{
						$sql='UPDATE pf SET pf=:pf WHERE id=:id';
						$stmnt=$this->_db->prepare($sql);			
						$stmnt->bindParam(':id',$pfId);
						$stmnt->bindParam(':pf',$data['pf']);
						$stmnt->execute();
						$error=$stmnt->errorInfo();
						if($error[0]!=00000){
							throw new Exception($error[2]);
							}
					}
				}				
			}
			if(isset($data2))
			{return $data2['pf'];}
		
		
	}
	public function getPf(array $data)
	{
		$seek_id=$data['seekId'];
		$player_id=$data['playerId'];
		$sql='SELECT id FROM pf WHERE player_id=:player_id AND seek_id=:seek_id';		
		$stmnt=$this->_db->prepare($sql);
		$stmnt->bindParam(':player_id',$player_id);
		$stmnt->bindParam(':seek_id',$seek_id);
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}
		$data=null;
		while($row=$stmnt->fetch(PDO::FETCH_ASSOC))
		{			
			$data=$row['id'];				
		}
		return $data;
	}
	
	public function addPf(array $data)
	{
		$seekId=$data['seekId'];
		$pf=$data['pf'];
		$player_id=$data['playerId'];
		if(!isset($data['unlocked']))
		{
			$unlocked=0;
		}
		else{
			$unlocked=$data['unlocked'];
		}
		$sql='INSERT INTO pf (seek_id,pf,unlocked,player_id) VALUES (:seek_id,:pf,:unlocked,:player_id)';
			$stmnt=$this->_db->prepare($sql);
			$stmnt->bindParam(':seek_id',$seekId);
			$stmnt->bindParam(':pf',$pf);
			$stmnt->bindParam(':unlocked',$unlocked);
			$stmnt->bindParam(':player_id',$player_id);
			$stmnt->execute();
			if($stmnt->rowCount()==0)
			{
				throw new Exception('Il semblerai y avoir une erreur lors de la création des pf.');
			}
	}
	
	public function getSeekLinkUnlocked($playerId)
	{
		if($playerId==null)
		{
			throw new Exception(ERROR_DATA);
		}
		$sql='SELECT seek.id,seek.link,pf.unlocked 
		FROM seek 
		LEFT JOIN pf ON seek.id=pf.seek_id AND pf.player_id=:player_id
		ORDER BY seek.id
		';		
		$stmnt=$this->_db->prepare($sql);
		$stmnt->bindParam(':player_id',$playerId);
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}
		
		$data=null;
		while($row=$stmnt->fetch(PDO::FETCH_ASSOC))
		{			
			$data[$row['id']]['link']=unserialize($row['link']);			
			$data[$row['id']]['unlocked']=$row['unlocked'];			
		}
		
		return $data;
	}
	
	public function updateUnlocked($data)
	{
		if($data['seekId']==null || $data['playerId']==null)
		{
			throw new Exception(ERROR_DATA);
		}
		$seekId=$data['seekId'];
		$playerId=$data['playerId'];
		
		// rechercher si existe dans la base, ou si unlocked ou locked
		$dataSeek=$this->getSeekLinkUnlocked($playerId);
		$unlocked=$dataSeek[$seekId]['unlocked'];
		if($unlocked===null)
		{
			$this->addSeekPlayer($playerId,$seekId);
			return 1;
		}
		if($unlocked==='0')
		{
			$this->updateSeekPlayer($playerId,$seekId,1);
			return 1;
		}
		if($unlocked==1)
		{
			$this->updateSeekPlayer($playerId,$seekId,0);
			return 0;
		}
			
	}
	
	public function getUnlockedPlayer($SeekId,$playerId)
	{
		if($seekId==null || $playerId==null)
		{
			throw new Exception(ERROR_DATA);
		}
		$sql='SELECT unlocked FROM pf WHERE player_id=:playerId AND seek_id=:seekId';
		$stmnt=$this->_db->prepare($sql);
		$stmnt->bindParam(':playerId',$playerId);
		$stmnt->bindParam(':seekId',$seekId);
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}
		
		$row=$stmnt->fetch(PDO::FETCH_ASSOC);
		$unlocked=$row['unlocked'];
		return $unlocked;
	}
	
	public function addSeekPlayer($playerId,$seekId)
	{
		// ---- verif antidoublon
		$sql='SELECT count(id) FROM pf WHERE player_id=:playerId and seek_id=:seek_id';
			$stmnt=$this->_db->prepare($sql);
			$stmnt->bindParam(':seek_id',$seekId);
			$stmnt->bindParam(':playerId',$playerId);
			$stmnt->execute();
			$error=$stmnt->errorInfo();
			if($error[0]!=00000){
				throw new Exception($error[2]);
			}
			$row=$stmnt->fetch(PDO::FETCH_ASSOC);
			/*print_r($row);*/
			if($row['count(id)']!=0)
			{
				return;
			}
		
			if($seekId==null || $playerId==null)
		{
			throw new Exception(ERROR_DATA);
		}
		$sql='INSERT INTO pf (seek_id,pf,unlocked,player_id) VALUES (:seek_id,0,1,:player_id)';
			$stmnt=$this->_db->prepare($sql);
			$stmnt->bindParam(':seek_id',$seekId);
			$stmnt->bindParam(':player_id',$playerId);
			$stmnt->execute();
			if($stmnt->rowCount()==0)
			{
				throw new Exception('Il semblerai y avoir une erreur lors de la création des pf.');
			}
	}
	
	public function updateSeekPlayer($playerId,$seekId,$valueChange)
	{
		if($seekId==null || $playerId==null)
		{
			throw new Exception(ERROR_DATA);
		}
		$this->addSeekPlayer($playerId,$seekId);
		
		$sql='UPDATE pf SET unlocked=:value WHERE player_id=:playerId AND seek_id=:seekId';
			$stmnt=$this->_db->prepare($sql);			
			$stmnt->bindParam(':seekId',$seekId);
			$stmnt->bindParam(':playerId',$playerId);
			$stmnt->bindParam(':value',$valueChange);
			$stmnt->execute();
			$error=$stmnt->errorInfo();
			if($error[0]!=00000){
				throw new Exception($error[2]);
				}		
	}
	
	public function getMaxPf($seekId)
	{
		$sql='SELECT pf FROM seek WHERE id=:seekId';
		$stmnt=$this->_db->prepare($sql);		
		$stmnt->bindParam(':seekId',$seekId);
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}
		
		$row=$stmnt->fetch(PDO::FETCH_ASSOC);
		$maxPf=$row['pf'];
		return $maxPf;
	}
	
	public function seekListPlayer($playerId)
	{ 
		if($playerId==null)
		{
			throw new Exception(ERROR_DATA);
		}
		$sql='SELECT seek.id,seek.resource1_id,seek.resource2_id,seek.nb_resource1,seek.nb_resource2
		FROM seek
		ORDER BY seek.id';
		$stmnt=$this->_db->prepare($sql);
		/*$stmnt->bindParam(':playerId',$playerId);*/
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}		
		
		$data=null;
		while($row=$stmnt->fetch(PDO::FETCH_ASSOC))
		{	
			if($row['resource1_id']!=null){
			$data[$row['id']][$row['resource1_id']]['nb_resource1']=$row['nb_resource1'];
			}
			if($row['resource2_id']!=null){
			$data[$row['id']][$row['resource2_id']]['nb_resource2']=$row['nb_resource2'];
			}
			
			
		}
		
		// intégrer les stock du joueur
	
		$sql='SELECT amount,resource_id,name,image_name
		FROM stock 
		INNER JOIN resources ON resource_id=resources.id
		WHERE player_id=:playerId
		ORDER BY resource_id';
		$stmnt=$this->_db->prepare($sql);
		$stmnt->bindParam(':playerId',$playerId);		
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
			}
		
		while($row=$stmnt->fetch(PDO::FETCH_ASSOC))
		{						
			$playerTab[$row['resource_id']]['amount']=$row['amount'];		
			$playerTab[$row['resource_id']]['name']=$row['name'];		
			$playerTab[$row['resource_id']]['image']=$row['image_name'];		
		}
				
		foreach($data as $key => $value)
		{
			
		}
		/*?><pre><?php print_r($playerTab);?></pre><?php*/
		return $data;
		
		
	}
	
	public function SelectSeekIdLocked($seekId,$playerId)
	{	
		if($seekId==null || $playerId==null)
			{
				throw new Exception(ERROR_DATA);
			}
		$dataSeek=$this->getSeekLinkUnlocked($playerId);
		$temp[]=$seekId;
			
					$createtab=1;
					// construction de la table de maj
					while($createtab!=0)
					{
						$event=0;
						foreach($temp as $value)
						{
							if($dataSeek[$value]['unlocked']!=1)
							{
								if(is_array($dataSeek[$value]['link']))
								{
									foreach($dataSeek[$value]['link'] as $value2)
									{
											if($dataSeek[$value2]['unlocked']!=1){
												if(!in_array($value2,$temp))
												{
												$temp[]=$value2;
												$event=1;
												}
											}
									
									}										
								}															
							}							
						}
						if($event==0)
						{$createtab=0;}						
					}
		
		
		return $temp;
	}
	
	public function getSeekResources($seekId)
	{
		if($seekId==null)
		{
			throw new Exception(ERROR_DATA);
		}
		$sql='SELECT resource1_id,resource2_id,nb_resource1,nb_resource2		
		FROM seek WHERE id=:seekId';
		
		$stmnt=$this->_db->prepare($sql);
		$stmnt->bindParam(':seekId',$seekId);	
		$stmnt->execute();		
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}
		
		
		while($row=$stmnt->fetch(PDO::FETCH_ASSOC))
		{	
			if($row['resource1_id']!=null){
				$data[$row['resource1_id']]=$row['nb_resource1'];
			}
			if($row['resource2_id']!=null){
				$data[$row['resource2_id']]=$row['nb_resource2'];
			}
		}
		if(isset($data)){
		return $data;}
	}

	public function updatePfProd($pf,$playerId)
	{
		if(!is_numeric($playerId) || $playerId==null)
		{
			throw new Exception('l\'id doit être un int');
		}
		if(!is_numeric($pf))
		{
			throw new Exception('pf doit être un int');
		}
		$sql='UPDATE player SET pf_prod=:pf WHERE player_id=:playerId';
		$stmnt=$this->_db->prepare($sql);			
		$stmnt->bindParam(':pf',$pf);
		$stmnt->bindParam(':playerId',$playerId);		
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
			}		
	}
	
	public function getPfProd($playerId)
	{
		$sql='SELECT pf_prod FROM player WHERE player_id=:playerId';
		$stmnt=$this->_db->prepare($sql);
		$stmnt->bindParam(':playerId',$playerId);	
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}		
		$row=$stmnt->fetch(PDO::FETCH_ASSOC);
		$pf=$row['pf_prod'];
		return $pf;
	}
	
	public function getPfTotal($seekId,$playerId)
	{
		$sql='SELECT seek.pf AS a,pf.pf AS b
		FROM seek 
		LEFT JOIN pf ON seek.id=pf.seek_id AND pf.player_id=:playerId
		WHERE seek.id=:seekId';
		$stmnt=$this->_db->prepare($sql);
		$stmnt->bindParam(':seekId',$seekId);
		$stmnt->bindParam(':playerId',$playerId);
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}		
		while($row=$stmnt->fetch(PDO::FETCH_ASSOC))
		{						
			$data=$row['a']-$row['b'];					
		}		
				
		return $data;
	}
}