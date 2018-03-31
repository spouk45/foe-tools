<?php 

class Resources{
	
	private $_amount;
	private $_production;
	private $_need;
	private $_boost;
	private $_id;
	
	public function __construct(array $data)
	{
		if(isset($data['boost']))
		{
			$this->setBoost($data['boost']);			
		}
		if(isset($data['production']))
		{
			$this->setProduction($data['production']);		
		}
		if(isset($data['need']))
		{
			$this->setNeed($data['need']);		
		}
		if(isset($data['amount']))
		{
			$this->setAmount($data['amount']);		
		}
		if(!isset($data['id']))
		{
			throw new Exception('Erreur de données.');
		}
		else 
		{
			$this->setId($data['id']);
		}
	}
	
	private function setAmount($amount)
	{
		if(!is_numeric($amount))
		{
			throw new Exception('La quantité de ressources doit être un nombre.');
		}
		if ($amount<0)
		{
			throw new Exception('La quantité de ressources ne peut être inférieur à 0.');
		}
		$this->_amount=$amount;
	}
	
	private function setProduction($production)
	{
		if(!is_numeric($production))
		{
			throw new Exception('La production doit être un nombre.');
		}
		$this->_production=$production;
	}
	private function setNeed($need)
	{
		if(!is_numeric($need))
		{
			throw new Exception('Les besoins doivent être un nombre.');
		}
		$this->_need=$need;
	}
	
	private function setBoost($boost)
	{		
		if($boost == true || $boost == false)
		{			
			if($boost=='true')
			$this->_boost=1;
			
			if($boost=='false')
			{
				$this->_boost=0;
			}
		}
		else {
			throw new Exception('La production doit être vrai ou faux.');
		}
	}
	
	private function setId($id)
	{
		if(!is_numeric($id))
		{
			throw new Exception('L\'id doit être un nombre.');
		}
		$this->_id=$id;
	}
	
	public function getAmount()
	{
		return $this->_amount;
	}
	public function getNeed()
	{
		return $this->_need;
	}
	public function getProduction()
	{
		return $this->_production;
	}
	public function getBoost()
	{
		return $this->_boost;
	}	
	public function getId()
	{
		return $this->_id;
	}
	
	
}