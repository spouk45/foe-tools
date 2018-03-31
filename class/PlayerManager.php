<?php 
class PlayerManager
{
	private $_db;
	
	public function __construct(PDO $db)
	{
		$this->_db=$db;
	}
	
	public function getPlayerId(array $data)
	{
		if(isset($data['worldId']) && isset($data['accountId']))
		{
			$sql='SELECT player_id FROM player WHERE world_id=:world_id AND account_id=:accountId';
			$stmnt=$this->_db->prepare($sql);
			$stmnt->bindParam(':world_id',$data['worldId']);
			$stmnt->bindParam(':accountId',$data['accountId']);
			$stmnt->execute();
			if(!$row=$stmnt->fetch(PDO::FETCH_ASSOC))
				{
					return null;
				}
			else 
			{
				return $row['player_id'];
			}		
		}
		else
		{
			throw new Exception('Erreur lors de la récupération des données du joueur.');
		}
	}
	
	public function addPlayer(array $data)
	{
		if(!isset($data['accountId']) || !isset($data['worldId']))
		{
			throw new Exception('Impossible de créer les données. Erreur de réception données pour la création.');
		}
		
		$sql='INSERT INTO player (account_id,world_id) VALUES (:account_id,:world_id)';
		$stmnt=$this->_db->prepare($sql);
		$stmnt->bindParam(':world_id',$data['worldId']);
		$stmnt->bindParam(':account_id',$data['accountId']);
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}
		
	}
	
	public function getGuildId($playerId)
	{
		if(!is_numeric($playerId))
		{
			throw new Exception('Le playerId doit être un nombre.');
		}
		
		$sql="SELECT guild_id FROM player WHERE player_id=:player_id";
		$stmnt=$this->_db->prepare($sql);
		$stmnt->bindParam(':player_id',$playerId);
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}
		if(!$row=$stmnt->fetch(PDO::FETCH_ASSOC))
		{
			return null;
		}		
		else 
			{
				return $row['guild_id'];
			}		
	}
	
	public function getGuildName($playerId)
	{
		if(!is_numeric($playerId))
		{
			throw new Exception('Le playerId doit être un nombre.');
		}
		
		$sql="SELECT guild.name FROM guild 
		INNER JOIN player ON player_id=:player_id
		WHERE guild.id=player.guild_id ";
		$stmnt=$this->_db->prepare($sql);
		$stmnt->bindParam(':player_id',$playerId);
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}
		if(!$row=$stmnt->fetch(PDO::FETCH_ASSOC))
		{
			return null;
		}		
		else 
			{
				return $row['name'];
			}		
	}
	
	public function setLevel($playerId,$levelName)
	{
		if(!is_numeric($playerId))
		{
			throw new Exception('Le playerId doit être un nombre.');
		}
		$sql='SELECT id FROM level WHERE name=:name';
		$stmnt=$this->_db->prepare($sql);
		$stmnt->bindParam(':name',$levelName);
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}
		if($row=$stmnt->fetch(PDO::FETCH_ASSOC))
		{
			$levelId=$row['id'];
		}
		else {
			throw new Exception('Le niveau d\'utilisateur demandé ne semble pas exister.');
		}
		
		$sql='UPDATE player SET level_id=:level_id WHERE player_id=:player_id';
		$stmnt=$this->_db->prepare($sql);
		$stmnt->bindParam(':level_id',$levelId);
		$stmnt->bindParam(':player_id',$playerId);
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}		
	}
	
	public function setGuildId($playerId,$guildId)
	{		
		if(!is_numeric($playerId) || !is_numeric($guildId))
		{
			throw new Exception('playerid et guildId doivent être des nombres.');
		}
		$sql='UPDATE player SET guild_id=:guild_id WHERE player_id=:player_id';
		$stmnt=$this->_db->prepare($sql);
		$stmnt->bindParam(':guild_id',$guildId);
		$stmnt->bindParam(':player_id',$playerId);
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}		
	}
	
	public function leaveGuild($playerId)
	{
		if(!is_numeric($playerId))
		{
			throw new Exception('playerid et guildId doivent être des nombres.');
		}
		$sql='UPDATE player SET guild_id=NULL,level_id=NULL WHERE player_id=:player_id';
		$stmnt=$this->_db->prepare($sql);		
		$stmnt->bindParam(':player_id',$playerId);
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}		
	}
	
	public function getLevelId($playerId)
	{
		if(!is_numeric($playerId))
		{
			throw new Exception('playerid doit être un nombre.');
		}
		
		$sql='SELECT level_id FROM player WHERE player_id=:player_id';
		$stmnt=$this->_db->prepare($sql);
		$stmnt->bindParam(':player_id',$playerId);
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}		
		if($row=$stmnt->fetch(PDO::FETCH_ASSOC))
		{
			return $row['level_id'];
		}
		else {
			throw new Exception('Aucun level.');
		}
	}
	
	public function countPlayerComing($guildId)
	{
		
		if(!is_numeric($guildId))
		{
			throw new Exception('playerid doit être un nombre.');
		}
		
		$sql='SELECT COUNT(player_id) FROM player WHERE guild_id=:guild_id AND level_id=2';
		$stmnt=$this->_db->prepare($sql);
		$stmnt->bindParam(':guild_id',$guildId);
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}		
		if($row=$stmnt->fetch(PDO::FETCH_ASSOC))
		{
			return $row['COUNT(player_id)'];
		}
		else {
			throw new Exception('Aucun level.');
		}
	}
	
	public function getPlayerGuild($guildId)
	{	
			if(!is_numeric($guildId))
		{
			throw new Exception('guildId doit être un nombre.');
		}
		
		$sql='SELECT player.player_id,level.fullName,level.id,account.user FROM player 
		INNER JOIN level ON player.level_id=level.id
		INNER JOIN account ON player.account_id=account.account_id
		WHERE guild_id=:guild_id ORDER BY account.user';
		$stmnt=$this->_db->prepare($sql);
		$stmnt->bindParam(':guild_id',$guildId);
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}			
		$i=0;
		while($row=$stmnt->fetch(PDO::FETCH_ASSOC))
		{
			$data[$i]['playerId']=$row['player_id'];
			$data[$i]['levelName']=$row['fullName'];
			$data[$i]['levelId']=$row['id'];
			$data[$i]['playerName']=$row['user'];
			$i++;			
		}
		
		if(!isset($data) || $data==null)
		{				
			return null;
		}
		else {
			return $data;
		}
		
	}
	public function getLevelList()
	{
		$sql='SELECT id,name,fullName FROM level';
		$stmnt=$this->_db->prepare($sql);
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}			
		$i=0;
		while($row=$stmnt->fetch(PDO::FETCH_ASSOC))
		{
			$data[$i]['levelId']=$row['id'];
			$data[$i]['levelName']=$row['name'];
			$data[$i]['fullName']=$row['fullName'];
			$i++;			
		}
		if(!isset($data) || $data==null)
		{				
			throw new Exception('Aucun level dans la base.');
		}		
		return $data;		
	}
	
	public function deletePlayerGuild($playerId)// le player id a sup
	{
		if(!is_numeric($playerId))
		{
			throw new Exception('playerId doit être un nombre.');
		}
		$sql='UPDATE player SET guild_id=NULL , level_id=NULL WHERE player_id=:player_id';
		$stmnt=$this->_db->prepare($sql);
		$stmnt->bindParam(':player_id',$playerId);
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}
		return true;
	}
	
	public function setLevelId($playerId,$LevelId)
	{
		if(!is_numeric($playerId) ||!is_numeric($LevelId) )
		{
			throw new Exception('playerId doit être un nombre.');
		}
		$sql='UPDATE player SET level_id=:level_id WHERE player_id=:player_id';
		$stmnt=$this->_db->prepare($sql);
		$stmnt->bindParam(':player_id',$playerId);
		$stmnt->bindParam(':level_id',$LevelId);
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}
		return true;
	}
	
	public function countLevelAdmin($playerId)
	{
		
		if(!is_numeric($playerId))
		{
			throw new Exception('playerid doit être un nombre.');
		}
		$guildId=$this->getGuildId($playerId);
						
		$sql='SELECT COUNT(level_id) FROM player WHERE guild_id=:guild_id AND level_id=1';
		$stmnt=$this->_db->prepare($sql);
		$stmnt->bindParam(':guild_id',$guildId);
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}		
		if($row=$stmnt->fetch(PDO::FETCH_ASSOC))
		{
			return $row['COUNT(level_id)'];
		}
		else {
			throw new Exception('Aucun level.');
		}
	}
	public function countPlayerGuild($guildId)
	{
		
		if(!is_numeric($guildId))
		{
			throw new Exception('guildId doit être un nombre.');
		}
		
		$sql='SELECT COUNT(player_id) FROM player WHERE guild_id=:guild_id';
		$stmnt=$this->_db->prepare($sql);
		$stmnt->bindParam(':guild_id',$guildId);
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}		
		if($row=$stmnt->fetch(PDO::FETCH_ASSOC))
		{
			return $row['COUNT(player_id)'];
		}
		else {
			throw new Exception('Aucun player.');
		}
	}
	
	public function getPlayerIdByName(array $data)
	{
		if(!isset($data['worldId']) ||!isset($data['name']) )
		{
			throw new Exception('infos manquantes');
		}
		$sql='SELECT player_id FROM player 
		INNER JOIN account ON account.account_id=player.account_id
		WHERE world_id=:world_id AND user=:name';
			$stmnt=$this->_db->prepare($sql);
			$stmnt->bindParam(':world_id',$data['worldId']);
			$stmnt->bindParam(':name',$data['name']);
			$stmnt->execute();
			if(!$row=$stmnt->fetch(PDO::FETCH_ASSOC))
				{
					return null;
				}
			else 
			{
				return $row['player_id'];
			}		
	}
	
	public function getPlayersNameWorld($worldId,$player)
	{
		if($worldId==null || $player==null) 
		{
			throw new Exception('infos manquantes');
		}
		$pattern=$player.'%';
		$sql='SELECT user 
		FROM player 
		INNER JOIN account ON account.account_id=player.account_id
		WHERE world_id=:world_id AND user LIKE :player';
			$stmnt=$this->_db->prepare($sql);
			$stmnt->bindParam(':world_id',$worldId);			
			$stmnt->bindParam(':player',$pattern);			
			$stmnt->execute();
			while($row=$stmnt->fetch(PDO::FETCH_ASSOC))
				{					
					$data[]=$row['user'];
					
				}
			if(!isset($data)){$data=null;}
			return $data;
	}
	
	public function getIdAdministrator()
	{
		$admin='spouk';
		$sql='SELECT account_id FROM account 
		WHERE user=:user';
		$stmnt=$this->_db->prepare($sql);					
		$stmnt->bindParam(':user',$admin);					
		$stmnt->execute();
		$row=$stmnt->fetch(PDO::FETCH_ASSOC);
		if(!isset($row))
		{
			throw new Exception('Administrateur introuvable');
		}
		return $row['account_id'];
	}

	public function getPlayerAdminGuild($guildId)
	{
		if(!is_numeric($guildId))
		{
			throw new Exception('guildId doit être un nombre.');
		}
		$admin='admin';
		$sql='SELECT player.player_id FROM player 		
		INNER JOIN level ON level.id=player.level_id
		WHERE player.guild_id=:guild_id AND level.name=:admin';		
		$stmnt=$this->_db->prepare($sql);
		$stmnt->bindParam(':guild_id',$guildId);
		$stmnt->bindParam(':admin',$admin);
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}			
		
		while($row=$stmnt->fetch(PDO::FETCH_ASSOC))
		{
			$data[]=$row['player_id'];					
		}
		
		if(!isset($data) || $data==null)
		{				
			return null;
		}
		else {
			return $data;
		}
	}
}