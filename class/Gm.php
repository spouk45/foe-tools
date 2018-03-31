<?php 
class Gm
{
	private $_gmId;
	private $_playerId;
	private $_level;
	private $_pfAmount;
	private $_setType;
	private $_pfMax;
	private $_date;
	
	public function __construct($data)
	{
		if(isset($data['playerId']))$this->setPlayerId($data['playerId']);
		if(isset($data['gmId']))$this->setGmId($data['gmId']);
		if(isset($data['level']))$this->setLevel($data['level']);
		if(isset($data['pfAmount']))$this->setPf($data['pfAmount']);
		if(isset($data['setType']))$this->setType($data['setType']);
		if(isset($data['pfMax']))$this->setPfMax($data['pfMax']);
		$this->setDate();
	}
	
	private function setPlayerId($playerId)
	{
		if(!is_numeric($playerId))throw new Exception('setPlayerId doit avoir un int');
		$this->_playerId=$playerId;
	}
	private function setGmId($gmId)
	{
		if(!is_numeric($gmId))throw new Exception('gmId doit avoir un int');
		$this->_gmId=$gmId;
	}
	private function setLevel($lvl)
	{
		if(!is_numeric($lvl))throw new Exception('lvl doit avoir un int');
		$this->_level=$lvl;
	}
	private function setPf($pf)
	{
		if(!is_numeric($pf))throw new Exception('pf doit avoir un int');
		$this->_pfAmount=$pf;
	}
	private function setType($type)	{
		$this->_setType=$type;
	}
	private function setPfMax($pf)	{
		if(!is_numeric($pf))throw new Exception('pf doit avoir un int');
		$this->_pfMax=$pf;
	}
	private function setDate(){
		$Date=new DateTime();
		$date=$Date->format('U');
		$this->_date=$date;
	}

	public function getPlayerId()
	{
		return $this->_playerId;
	}
	public function getGmId()
	{
		return $this->_gmId;
	}
	public function getLevel()
	{
		return $this->_level;
	}
	public function getPf()
	{
		return $this->_pfAmount;
	}
	public function getType()
	{
		return $this->_setType;
	}
	public function getPfMax()
	{
		return $this->_pfMax;
	}
	public function getDate()
	{
		return $this->_date;
	}
}