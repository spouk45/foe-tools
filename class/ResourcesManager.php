<?php 
class ResourcesManager
{
	private $_db;
	
	public function __construct(PDO $db)
	{
		$this->_db=$db;
	}
	
	public function checkStock($playerId)
	{
		if(!is_numeric($playerId) || $playerId==null)
		{
			throw new Exception('l\'id doit être un int');
		}
		
		$sql='SELECT COUNT(id) FROM stock WHERE player_id=:player_id';
		$stmnt=$this->_db->prepare($sql);
		$stmnt->bindParam(':player_id',$playerId);
		$stmnt->execute();
		$row=$stmnt->fetch(PDO::FETCH_ASSOC);
		//print_r($row);
		$countStockId=$row['COUNT(id)'];
				
		if ($countStockId==0)
		{			
			return false;
		}
		
		$sql='SELECT COUNT(id) FROM resources';
		$stmnt=$this->_db->prepare($sql);		
		$stmnt->execute();
		$row2=$stmnt->fetch(PDO::FETCH_ASSOC);
		$error=$stmnt->errorInfo();
		if($error[0]!=00000)
			{
				throw new Exception($error[2]);
			}
		$countResourceId=$row2['COUNT(id)'];
		
		if($countResourceId==$countStockId){
			return true;
		}
		else {
			$this->updatePlayerResource($playerId);
		}	
		return true;
	}
	
	public function updatePlayerResource($playerId)
	{
		// extraire les données manquantes
		$sql='SELECT id FROM resources';
		$stmnt=$this->_db->prepare($sql);		
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}
		
		while($row=$stmnt->fetch(PDO::FETCH_ASSOC))
		{			
			$resources[]=$row['id'];			
		}		
		
		// les ajouter
		$sql='SELECT resource_id FROM stock WHERE player_id=:playerId';
		$stmnt=$this->_db->prepare($sql);
		$stmnt->bindParam(':playerId',$playerId);
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}
	
		while($row2=$stmnt->fetch(PDO::FETCH_ASSOC))
		{			
			$stock[]=$row2['resource_id'];
		}
	
		//$data=array_merge($resources,$stock);
		//$data=array_unique($data);
		$data=array_diff_assoc($resources,$stock);
		/*?><pre><?php print_r($data);?></pre><?php
		*/
		foreach ($data as $value)
		{					
			$sql='INSERT INTO stock (resource_id,player_id,amount,production,need,boost) VALUES (:resource_id,:player_id,0,0,0,0)';
			$stmnt=$this->_db->prepare($sql);
			$stmnt->bindParam(':player_id',$playerId);
			$stmnt->bindParam(':resource_id',$value);
			$stmnt->execute();
			if($stmnt->rowCount()==0)
			{
				throw new Exception('Il semblerai y avoir une erreur lors de la création des stocks.');
			}
			
		}		
		
	}
	
	public function addStockData($playerId)
	{
		if(!is_numeric($playerId))
		{
			throw new Exception('l\'id doit être un int');
		}
		
		$sql='SELECT id FROM resources';
		$stmnt=$this->_db->prepare($sql);
		$stmnt->execute();
		$i=0;
		while($row=$stmnt->fetch(PDO::FETCH_ASSOC))
			{
				$data[$i]=$row['id'];
				$i++;
			}
		if(!isset($data))
		{
			throw new Exception('le player n\'existe pas dans la base.');
		}
		foreach ($data as $value)
		{	
			
			$sql='INSERT INTO stock (resource_id,player_id,amount,production,need,boost) VALUES (:resource_id,:player_id,0,0,0,0)';
			$stmnt=$this->_db->prepare($sql);
			$stmnt->bindParam(':player_id',$playerId);
			$stmnt->bindParam(':resource_id',$value);
			$stmnt->execute();
			if($stmnt->rowCount()==0)
			{
				throw new Exception('Il semblerai y avoir une erreur lors de la création des stocks.');
			}
		}		
	}
	
	public function getStockData($playerId)
	{
		if(!is_numeric($playerId))
		{
			throw new Exception('l\'id doit être un int');
		}
		
		$sql='SELECT stock.id,stock.resource_id,resources.name,resources.age_id,resources.image_name,stock.amount,stock.need,stock.production,stock.boost
		FROM stock 
		INNER JOIN resources ON stock.resource_id=resources.id
		WHERE stock.player_id=:player_id ORDER BY resources.id';
		$stmnt=$this->_db->prepare($sql);
		$stmnt->bindParam(':player_id',$playerId);
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}
		$i=0;
		while($row=$stmnt->fetch(PDO::FETCH_ASSOC))
		{			
			$data[$i]['id']=$row['id'];			
			$data[$i]['resource_id']=$row['resource_id'];
			$data[$i]['name']=$row['name'];
			$data[$i]['age_id']=$row['age_id'];
			$data[$i]['image_name']=$row['image_name'];
			$data[$i]['amount']=$row['amount'];
			$data[$i]['production']=$row['production'];
			$data[$i]['need']=$row['need'];
			$data[$i]['boost']=$row['boost'];
			$i++;			
		}
		if(!isset($data))
		{
			throw new Exception('Erreur lors de la récupération des données.');
		}
		return $data;
		
	}
	
	public function updateData(Resources $Resources, $playerId)
	{
		$boost=$Resources->getBoost();		
		$production=$Resources->getProduction();
		$need=$Resources->getNeed();
		$amount=$Resources->getAmount();
		$id=$Resources->getId();
		
		if($boost!==null){$field='boost';$value=$boost;}
		if($production!==null){$field='production';$value=$production;}
		if($amount!==null){$field='amount';$value=$amount;}
		if($need!==null){$field='need';$value=$need;}
		
		$sql='UPDATE stock SET '.$field.'=:value WHERE id=:id';
		$stmnt=$this->_db->prepare($sql);
		//$stmnt->bindParam(':field',$field);
		$stmnt->bindParam(':value',$value);
		$stmnt->bindParam(':id',$id);
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}
		$this->updateDateMaj($playerId);
	}
	
	public function getResourceGuild(array $data)
	{		
		//print_r($data);
		foreach($data as $key => $value)
		{			
			$data[$key]['resource']=$this->getStockData($value['playerId']);
		}
		if(!isset($data))
		{
			throw new Exception('Il semblerait y avoir un problème entre guild et joueur.');
		}
		return $data;
	}
	
	public function getTotalResource(array $data)
	{
		/*$tab['id']['amount']=0;
		$tab['id']['need']=0;
		$tab['production']=0;*/
		foreach($data as $key => $data2)
		{		
		
			foreach ($data2['resource'] as $key2=>$value)
			{
				if(!isset($datat[$value['resource_id']]['amount'])){
					$datat[$value['resource_id']]['amount']=$value['amount'];
				}
				else{
					$datat[$value['resource_id']]['amount']=$datat[$value['resource_id']]['amount']+$value['amount'];
				}
				
				if(!isset($datat[$value['resource_id']]['production'])){
					$datat[$value['resource_id']]['production']=$value['production'];
				}
				else{
					$datat[$value['resource_id']]['production']=$datat[$value['resource_id']]['production']+$value['production'];
				}
				if(!isset($datat[$value['resource_id']]['need'])){
					$datat[$value['resource_id']]['need']=$value['need'];
				}
				else{
					$datat[$value['resource_id']]['need']=$datat[$value['resource_id']]['need']+$value['need'];
				}
			}
			
		}
		
		return $datat;
	}
	
	public function getListAge()
	{
		$sql='SELECT id_age,age_name FROM age';
		$stmnt=$this->_db->prepare($sql);
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}			
		$i=0;
		while($row=$stmnt->fetch(PDO::FETCH_ASSOC))
		{
			$data[$i]['id']=$row['id_age'];
			$data[$i]['name']=$row['age_name'];
			$i++;			
		}
		if(!isset($data) || $data==null)
		{				
			throw new Exception('Aucun age dans la base.');
		}		
		return $data;	
	}
	
	public function getListResource()
	{
		$sql='SELECT id,name,image_name FROM resources';
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
			$data[$i]['image']=$row['image_name'];
			$data[$i]['age_id']=$row['age_id'];
			$i++;			
		}
		if(!isset($data) || $data==null)
		{				
			throw new Exception('Aucune ressources dans la base.');
		}		
		return $data;	
	}
	public function getListResourceById()
	{
		$sql='SELECT id,image_name FROM resources';
		$stmnt=$this->_db->prepare($sql);
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}			
		
		while($row=$stmnt->fetch(PDO::FETCH_ASSOC))
		{
			$data[$row['id']]=$row['image_name'];						
		}
		if(!isset($data) || $data==null)
		{				
			throw new Exception('Aucune ressources dans la base.');
		}		
		return $data;	
	}
	
	public function getAmountStock($playerId)
	{
		$sql='SELECT amount,resource_id FROM stock WHERE player_id=:playerId';
		$stmnt=$this->_db->prepare($sql);
		$stmnt->bindParam(':playerId',$playerId);		
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}	
		$data=null;
		while($row=$stmnt->fetch(PDO::FETCH_ASSOC))
		{
			$data[$row['resource_id']]=$row['amount'];						
		}
		return $data;
	}
	
	public function getResource($id)
	{
		$sql='SELECT id,name,image_name FROM resources WHERE id=:id';
		$stmnt=$this->_db->prepare($sql);
		$stmnt->bindParam(':id',$id);		
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}
		$data=null;
		while($row=$stmnt->fetch(PDO::FETCH_ASSOC))
		{
			$data['name']=$row['name'];
			$data['image_name']=$row['image_name'];
		}
		
		return $data;			
	}
	
	public function getAmountStockById($playerId,$resourceId)
	{
		$sql='SELECT amount FROM stock WHERE player_id=:playerId AND resource_id=:resourceId';
		$stmnt=$this->_db->prepare($sql);
		$stmnt->bindParam(':playerId',$playerId);		
		$stmnt->bindParam(':resourceId',$resourceId);		
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}	
		$data=null;
		while($row=$stmnt->fetch(PDO::FETCH_ASSOC))
		{
			$data['stock']=$row['amount'];						
		}
		return $data;
	}
	
	public function getProduction($playerId,$resourceId)
	{
		$sql='SELECT production FROM stock WHERE player_id=:playerId AND resource_id=:resourceId';
		$stmnt=$this->_db->prepare($sql);
		$stmnt->bindParam(':playerId',$playerId);		
		$stmnt->bindParam(':resourceId',$resourceId);		
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}	
		$data=null;
		while($row=$stmnt->fetch(PDO::FETCH_ASSOC))
		{
			$data=$row['production'];						
		}
		return $data;
	}
	
	public function updateNeed(array $data,$playerId) // mise à jour depuis les recherches
	{
		// on doit réinitialiser toutes les demandes du joueurs à 00000
		$sql='UPDATE stock SET need=0 WHERE player_id=:playerId';
		$stmnt=$this->_db->prepare($sql);		
		$stmnt->bindParam(':playerId',$playerId);
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}	
			
		foreach($data as $key => $value)
		{
			if(isset($value['need']))
			{
				//echo $key.'->'.$value['missed'];
				$resourceId=$key;
				$need=$value['need'];
				
				// On vérifie que l'ressourceid du joueur ds la bdd existe
				$sql='SELECT COUNT(id) FROM stock WHERE player_id=:playerId AND resource_id=:resourceId';
				$stmnt=$this->_db->prepare($sql);
				$stmnt->bindParam(':playerId',$playerId);		
				$stmnt->bindParam(':resourceId',$resourceId);		
				$stmnt->execute();
				$error=$stmnt->errorInfo();
				if($error[0]!=00000){
					throw new Exception($error[2]);
				}	
				$row=$stmnt->fetch(PDO::FETCH_ASSOC);
				$count=$row['COUNT(id)'];						
				
				if($count==0)
				{
					// on doit créer -> pas besoin tous créée de base					
				}
				else{
					// on update
					$sql='UPDATE stock SET need=:need WHERE player_id=:playerId AND resource_id=:resourceId';
					$stmnt=$this->_db->prepare($sql);		
					$stmnt->bindParam(':playerId',$playerId);
					$stmnt->bindParam(':resourceId',$resourceId);
					$stmnt->bindParam(':need',$need);
					$stmnt->execute();
					$error=$stmnt->errorInfo();
					if($error[0]!=00000){
						throw new Exception($error[2]);
						}
					$this->updateDateMaj($playerId);
				}
			
	
				
			}
		}
	}
	
	public function updateDateMaj($playerId)
	{
		// mise à jour de la date de player
		$Date=new Datetime();
		$date=$Date->format('U');
		
		$sql='UPDATE player SET resources_date_update=:date WHERE player_id=:playerId';
		$stmnt=$this->_db->prepare($sql);		
		$stmnt->bindParam(':playerId',$playerId);		
		$stmnt->bindParam(':date',$date);
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){	throw new Exception($error[2]);	}
	}
	
	public function getDateUpdate($playerId)
	{
		$sql='SELECT resources_date_update FROM player WHERE player_id=:playerId';
		$stmnt=$this->_db->prepare($sql);
		$stmnt->bindParam(':playerId',$playerId);		
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}	
		$data=null;
		$row=$stmnt->fetch(PDO::FETCH_ASSOC);
		$data=$row['resources_date_update'];
		// on retourne en format horaire
		if($data!=0)
		{
			$Date=new DateTime();
			$Date->setTimestamp($data);
			$data=$Date->format('d/m/Y');
		}		
		return $data;
	}
}