<?php 
class Account
{
	private $_userName;
	private $_userPass;	
	private $_userId;
	
	const ERROR_LOG='Login ou mot de passe incorrect';
	
	public function __construct($data)
	{	if(!isset($data['connect'])){$data['connect']=null;}
		$this->setUserName(trim($data['name']),$data['connect']);
		$this->setUserPass(trim($data['pass']),$data['connect']);
	}
	
	private function setUserName($name,$connect)
	{
		if(preg_match('/[\$\^\+\\/\<\>\!\#\~|\`\']/',$name) || strlen($name) <3)// 3 à 15 caractères
			{
				if($connect!='connect')
				{
					throw new Exception('Login: Certains caractères sont interdits.');
				}
				else
				{
					throw new Exception(self::ERROR_LOG);
				}
			}
		$this->_userName=$name;
	}
	
	private function setUserPass($pass,$connect)
	{
		if(!preg_match('/[^\<\>]/',$pass)) // 6 à 15 caractères
			{
				if($connect!='connect'){
					throw new Exception('Password: Certains caractères sont interdits.');
				}
				else{
					throw new Exception(self::ERROR_LOG);
				}
			}
		else 
		{
			if(strlen($pass) <6 || strlen($pass) >15) // 6 à 15 caractères
			{
				if($connect!='connect'){
					throw new Exception('Password: mini 6, maxi 15 caractères.');
				}
				else{
					throw new Exception(self::ERROR_LOG);
				}				
			}
		}
		$this->_userPass=md5($pass);
	}
	
	public function setUserId($id)
	{
		if(is_numeric($id)){
			$this->_userId=$id;
		}
		else {
			throw new Exception('L\'id doit être un int.');
		}
	}
	
	public function checkPass($pass2){
		if(md5($pass2) != $this->getUserPass())
		{
			throw new Exception('Mots de passes différents.');
		}
		return true;
	}
	
	public function getUserId()
	{
		return $this->_userId;
	}
	public function getUserName()
	{
		return $this->_userName;
	}
	
	public function getUserPass()
	{
		return $this->_userPass;
	}
}

class AccountManager
{	
	private $_db;
	
	public function __construct(PDO $db)
	{
		$this->_db=$db;
	}
	
	public function getUserId(Account $Account){
		$pass=$Account->getUserPass();
		$user=$Account->getUserName();
			
		$sql='SELECT account_id FROM account WHERE user=:user AND pass=:pass';
		$stmnt=$this->_db->prepare($sql);
		$stmnt->bindParam(':user',$user);
		$stmnt->bindParam(':pass',$pass);
		$stmnt->execute();
		if(!$row=$stmnt->fetch(PDO::FETCH_ASSOC))
				{
					throw new Exception('Login ou mot de passe incorrect.');
				}	
		return $row['account_id'];
	}
	
	public function checkLogin(Account $Account)
	{
		$user=$Account->getUserName();
		
		$sql='SELECT COUNT(account_id) FROM account WHERE user=:user';
		$stmnt=$this->_db->prepare($sql);
		$stmnt->bindParam(':user',$user);		
		$stmnt->execute();
		$row=$stmnt->fetch(PDO::FETCH_ASSOC);	
		return $row['COUNT(account_id)'];
	}
	
	public function addUser(Account $Account)
	{
		$pass=$Account->getUserPass();
		$user=$Account->getUserName();
		
		$sql='INSERT INTO account(user,pass) VALUES (:user,:pass)';
		$stmnt=$this->_db->prepare($sql);
		$stmnt->bindParam(':user',$user);	
		$stmnt->bindParam(':pass',$pass);
		$stmnt->execute();
		if($stmnt->rowCount() == 0)
		{
			throw new Exception('L\'enregistrement semble avoir échoué.');
		}
		return $this->getUserId($Account);
	}
	
	public function getSpoukId()
	{
		$sql='SELECT account_id FROM account WHERE user="spouk"';
		$stmnt=$this->_db->prepare($sql);		
		$stmnt->execute();
		if(!$row=$stmnt->fetch(PDO::FETCH_ASSOC))
				{
					throw new Exception('Erreur');
				}	
		return $row['account_id'];
	}
	
	public function getNameByAccountId($id)
	{
		$sql='SELECT user FROM account WHERE account_id=:id';
		$stmnt=$this->_db->prepare($sql);		
		$stmnt->bindParam(':id',$id);		
		$stmnt->execute();
		if(!$row=$stmnt->fetch(PDO::FETCH_ASSOC))
				{
					throw new Exception('Erreur lors de la récupération du nom account.');
				}	
		return $row['user'];
	}
	public function getNameByPlayerId($id)
	{
		$sql='SELECT user FROM account 
		INNER JOIN player ON player.account_id=account.account_id 
		WHERE player_id=:id';
		$stmnt=$this->_db->prepare($sql);		
		$stmnt->bindParam(':id',$id);		
		$stmnt->execute();
		if(!$row=$stmnt->fetch(PDO::FETCH_ASSOC))
				{
					throw new Exception('Erreur lors de la récupération du nom player.');
				}	
		return $row['user'];
	}
}