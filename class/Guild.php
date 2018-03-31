<?php 

class Guild{
	
	private $_playerId;
	private $_name;
	private $_worldId;
	
	public function __construct(array $data)
	{
		if(!isset($data['playerId']) && !isset($data['guildName']) && !isset($data['worldId']) )
		{
			throw new Exception('Erreur de transmission de données.');
		}
		$this->setPlayerId($data['playerId']);
		$this->setGuildName($data['guildName']);	
		$this->setWorldId($data['worldId']);	
	}
	
	private function setPlayerId($id)
	{
		if(!is_numeric($id))
		{
			throw new Exception('player_id doit être un nombre.');
		}
		$this->_playerId=$id;
	}
	
	private function setGuildName($name)
	{
		if(preg_match('/[\$\^\+\\/\<\>\!\#\~|\`\']/',$name) || strlen($name) <3)// 3 à 15 caractères
			{
				throw new Exception('Login: Certains caractères sont interdits ou le nom doit être d\'au moins 3 caractères.');
			}
		$this->_name=$name;
	}
	
	private function setWorldId($id)
	{
		if(!is_numeric($id))
		{
			throw new Exception('world_id doit être un nombre.');
		}
		$this->_worldId=$id;
	}
	
	public function getPlayerId()
	{
		return $this->_playerId;
	}
	public function getGuildName()
	{
		return $this->_name;
	}
	public function getWorldId()
	{
		return $this->_worldId;
	}
}