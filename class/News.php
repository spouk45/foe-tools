<?php 
class News{
	private $_title;
	private $_content;
	private $_date;
	
	public function __construct(array $data)
	{
		/*print_r($data);*/
		$this->setTitle($data['title']);
		$this->setContent($data['content']);
		$this->setDate();
	}
	
	function setTitle($title)
	{
		$this->_title=$title;
	}
	function setContent($content)
	{
		$this->_content=$content;
	}
	function setDate()
	{
		$date=new DateTime();		
		$this->_date=$date->format('U');			
	}
	
	function getTitle()
	{
		return $this->_title;
	}
	function getContent()
	{
		return $this->_content;
	}
	function getDate()
	{
		return $this->_date;
	}
}