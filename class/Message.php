<?php

class Message
{
	private $_message;
	private $_fromAccountId;
	private $_fromPlayerId;
	private $_toAccountId; // array
	private $_toPlayerId; // array
	private $_linkId;
	private $_date;
	private $_title;
	
	public function __construct($data)
	{
		//print_r($data);
		if(!isset($data['link'])){ // si c'est un nouveau message
			if(!isset($data['message']) || (!isset($data['toPlayerId']) && !isset($data['toAccountId'])) || (!isset($data['fromAccountId']) && !isset($data['fromPlayerId'])) )
			{
				throw new Exception('Il manque des paramètre pour la création du message');
			}
			if(isset($data['title']))
			{
				$this->setTitle($data['title']);
			}
			
			if(isset($data['fromPlayerId']))
				{
					$this->setToPlayerId($data['toPlayerId']);
					$this->setFromPlayerId($data['fromPlayerId']);
				}
			if(isset($data['fromAccountId']))
			{
				$this->setFromAccountId($data['fromAccountId']);
				$this->setToAccountId($data['toAccountId']);
			}	
		}
		else{ // si c'est une réponse
			if(!isset($data['message']) || !isset($data['fromAccountId'])){
				throw new Exception('Il manque des paramètre pour la création de la réponse.');
			}
			$this->setLinkId($data['link']);
			$this->setFromAccountId($data['fromAccountId']);
			if(isset($data['fromPlayerId']))
			{
				$this->setFromPlayerId($data['fromPlayerId']);
			}
			
		}
		
		// dans tous les cas
		$this->setMessage($data['message']);				
		$this->setDate();	
	}
	
	private function setMessage($id)
	{
		$this->_message=$id;
	}
	
	private function setToPlayerId(array $id) 
	{		
		$this->_toPlayerId=$id;
	}
	private function setFromPlayerId($id) 
	{
		$this->_fromPlayerId=$id;
	}
	
	private function setFromAccountId($id)
	{
		$this->_fromAccountId=$id;
	}
	
	private function setToAccountId(array $id) 
	{		
		$this->_toAccountId=$id;
	}
	private function setLinkId($link){
		$this->_linkId=$link;
	}
	private function setDate(){
		$Date=new DateTime();
		$date=$Date->format('U');
		$this->_date=$date;
	}	
	
	private function setTitle($title){
		$this->_title=$title;
	}
	public function getMessage()
	{
		return $this->_message;
	}
	
	public function getFromAccountId()
	{
		return $this->_fromAccountId;
	}	
	public function getToAccountId()
	{
		return $this->_toAccountId;
	}
	public function getFromPlayerId()
	{
		return $this->_fromPlayerId;
	}
	public function getToPlayerId()
	{
		return $this->_toPlayerId;
	}
	public function getLinkId()
	{
		return $this->_linkId;
	}
	public function getDate()
	{
		return $this->_date;
	}
	public function getTitle()
	{
		return $this->_title;
	}
}