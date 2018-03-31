<?php 
class GuildManager {
	
	private $_db;
	
	public function __construct(PDO $db)
	{
		$this->_db=$db;
	}
	
	public function addGuild(Guild $Guild)
	{	
		$worldId=$Guild->getWorldId();
		$guildName=$Guild->getGuildName();
		$playerId=$Guild->getPlayerId();
		
		// anti doublon
		if($this->getGuildIdByName($guildName,$worldId)!= null)
		{
			throw new Exception('Cette guilde existe déjà.');
		}	
		$sql='INSERT INTO guild (name,world_id) VALUES (:name,:world_id)';
			$stmnt=$this->_db->prepare($sql);
			$stmnt->bindParam(':world_id',$worldId);
			$stmnt->bindParam(':name',$guildName);
			$stmnt->execute();
			$error=$stmnt->errorInfo();
			if($error[0]!=00000){
				throw new Exception($error[2]);
				}
		
		// récupération de l'id guild venant d'être crée		
		$guildId=$this->getGuildIdByName($guildName,$worldId);
		if($guildId==null)
		{
			throw new Exception('erreur lors de la récupération de la nouvelle guilde.');
		}		
		return $guildId;
		
	}
	
	public function getGuildIdByName($name,$worldId)
	{
		if(!is_numeric($worldId))
		{
			throw new Exception('wordlId doit etre un nombre.');
		}
		$sql='SELECT id FROM guild WHERE name=:name AND world_id=:world_id';
		$stmnt=$this->_db->prepare($sql);
		$stmnt->bindParam(':name',$name);
		$stmnt->bindParam(':world_id',$worldId);
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}
		$row=$stmnt->fetch(PDO::FETCH_ASSOC);				
		return $row['id'];		
		
	}
	
	public function deleteGuild($guildId)
	{
		if(!is_numeric($guildId))
		{
			throw new Exception('doit etre un id.');
		}
		$sql='DELETE FROM guild WHERE id=:guild_id';
		$stmnt=$this->_db->prepare($sql);		
		$stmnt->bindParam(':guild_id',$guildId);
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}
	}
	
	public function getGuildList($worldId)
	{
		$sql='SELECT id,name FROM guild WHERE world_id=:world_id';
		$stmnt=$this->_db->prepare($sql);		
		$stmnt->bindParam(':world_id',$worldId);
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}
		$i=0;
		while($row=$stmnt->fetch(PDO::FETCH_ASSOC))
		{
			$data[$i]['guildId']=$row['id'];
			$data[$i]['guildName']=$row['name'];
			$i++;
		}
		if (isset($data))
		{
			return $data;
		}		
	}
	
}