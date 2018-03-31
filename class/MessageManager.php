<?php 
class MessageManager
{
	
	private $_db;
	
	public function __construct(PDO $db)
	{
		$this->_db=$db;
	}	
	
	public function countMessage($data)
	{
		// verifier si account ou player
		
		$accountId=$data['accountId'];
		
		if(isset($data['playerId']))//alors on recherche les message de player
		{
			$playerId=$data['playerId'];
			$where='(toPlayerId=:playerId OR toAccountId=:accountId)';
		}
		else if(isset($data['accountId']))//alors on recherche les message d'account
		{
			$where='toAccountId=:accountId';
		}
		else{
			throw new Exception('pas de player ni de account.');
		}
		
		$sql='SELECT COUNT(message_linkId) FROM message_link WHERE '.$where.' AND everRead=0';
		$stmnt=$this->_db->prepare($sql);	
		$stmnt->bindParam(':accountId',$accountId);
		if(isset($playerId)){
			$stmnt->bindParam(':playerId',$playerId);
		}		
		$stmnt->execute();
		$row=$stmnt->fetch(PDO::FETCH_ASSOC);
		
		
		$count=$row['COUNT(message_linkId)'];				
		return $count;
	}
	
	public function getMessageList($data)
	{
		if(isset($data['playerId']))// alors on recherche les messges d'account et de player
		{
			$playerId=$data['playerId'];
			$accountId=$data['accountId'];
			$where='toPlayerId=:playerId OR toAccountId=:accountId';
			//$from=$playerId;
			//$join='INNER JOIN player ON player.player_id=message.fromPlayerId INNER JOIN account ON account.account_id=player.account_id';
		}
		elseif(isset($data['accountId']))// alors on recherche les messges d'account
		{
			$accountId=$data['accountId'];
			$where='toAccountId=:accountId';
			//$from=$accountId;
			//$join='INNER JOIN account ON account.account_id=message.fromAccountId';
		}
		else{
			throw new Exception('Il manque des variables');
		}
		
		$sql='SELECT message_linkId,messageId,everRead,toPlayerId,toAccountId FROM message_link WHERE ('.$where.') ORDER BY message_linkId DESC';
		$stmnt=$this->_db->prepare($sql);	
		$stmnt->bindParam(':accountId',$accountId);
		if(isset($data['playerId'])){
			$stmnt->bindParam(':playerId',$playerId);
		}		
		$stmnt->execute();
		$i=0;
		while($row=$stmnt->fetch(PDO::FETCH_ASSOC))
		{
			$list[$i]['message_linkId']=$row['message_linkId'];
			$list[$i]['messageId']=$row['messageId'];
			$list[$i]['everRead']=$row['everRead'];
			$list[$i]['toPlayerId']=$row['toPlayerId'];
			$list[$i]['toAccountId']=$row['toAccountId'];
			//print_r($row);
			$i++;
		}
		
		if(!isset($list)){
			
			return null;
			}
		
		foreach($list as $key => $value)
		{
			if($value['toPlayerId']!=0)
			{
				$sql='SELECT fromPlayerId,title,dateMessage,user 
				FROM message
				INNER JOIN player ON player.player_id=message.fromPlayerId
				INNER JOIN account ON account.account_id=player.account_id
				WHERE messageId=:messageId OR link=:messageId ORDER BY dateMessage DESC';
			}
			if($value['toAccountId']!=0)
			{
				$sql='SELECT fromAccountId,title,dateMessage,user 
				FROM message				
				INNER JOIN account ON account.account_id=message.fromAccountId
				WHERE messageId=:messageId OR link=:messageId ORDER BY dateMessage DESC';
			}
			$messageId=$value['messageId'];
			/*
			$sql='SELECT fromPlayerId,fromAccountId,title,dateMessage,user 
			FROM message '.$join.' 			
			WHERE messageId=:messageId OR link=:messageId ORDER BY dateMessage DESC';*/
			
			$stmnt=$this->_db->prepare($sql);	
			$stmnt->bindParam(':messageId',$messageId);			
			$stmnt->execute();
			$row=$stmnt->fetch(PDO::FETCH_ASSOC);
			/*
			if($row['fromPlayerId']!=null)
			{
				$list[$key]['fromPlayerId']=$row['fromPlayerId'];
			}
			if($row['fromAccountId']!=null)
			{
				$list[$key]['fromAccountId']=$row['fromAccountId'];
			}
			*/
			if($row['title']!=null)
			{
				$list[$key]['title']=$row['title'];
			}
			//print_r($row);
			$list[$key]['dateMessage']=$row['dateMessage'];
			$list[$key]['fromName']=$row['user'];
			
		}
		
		$list=$this->sort_arr_of_obj($list,'dateMessage','desc');
		return $list;
	}
	
	private function sort_arr_of_obj($array, $sortby, $direction) 
	{
    
		$sortedArr = array();
		$tmp_Array = array();
		
		foreach($array as $k => $v) {
			$tmp_Array[] = strtolower($v[$sortby]);
		}
		
		if($direction=='asc'){
			asort($tmp_Array);
		}else{
			arsort($tmp_Array);
		}
		
		foreach($tmp_Array as $k=>$tmp){
			$sortedArr[] = $array[$k];
		}
		
		return $sortedArr;

	}
	
	public function getMessageContent($messageId)
	{
		$sql='SELECT messageId,message,dateMessage,fromPlayerId,fromAccountId
		FROM message		
		WHERE messageId=:messageId OR link=:messageId ORDER BY dateMessage';
		$stmnt=$this->_db->prepare($sql);	
		$stmnt->bindParam(':messageId',$messageId);
		$stmnt->execute();
		
		$i=0;
		while($row=$stmnt->fetch(PDO::FETCH_ASSOC)){
			$data[$i]['messageId']=$row['messageId'];
			$data[$i]['message']=nl2br(htmlentities($row['message']));
			$data[$i]['dateMessage']=$row['dateMessage'];
			$data[$i]['fromPlayerId']=$row['fromPlayerId'];
			$data[$i]['fromAccountId']=$row['fromAccountId'];	
			$i++;
		}
		if(!isset($data)){
			throw new Exception('Il semblerai y avoir une erreur lors de la récupération du contenu');
			}
		foreach($data as $key => $value)
		{
			if($value['fromPlayerId']!=null)
			{
				$fromId=$value['fromPlayerId'];
				$sql='SELECT user
				FROM account
				INNER JOIN player ON account.account_id=player.account_id			
				WHERE player.player_id=:fromId';
			}
			elseif($value['fromAccountId']!=null)
			{
				$fromId=$value['fromAccountId'];
				$sql='SELECT user
				FROM account					
				WHERE account.account_id=:fromId';
			}
			else {
				throw new Exception('Pas de fromId');
			}
			$stmnt=$this->_db->prepare($sql);	
			$stmnt->bindParam(':fromId',$fromId);
			$stmnt->execute();
			$row=$stmnt->fetch(PDO::FETCH_ASSOC);
			$data[$key]['fromName']=$row['user'];	
		}
		
		return $data;
	}
	
	public function everRead($data,$messageId)
	{		
		if($data['playerId']!=null)
		{
			$toPlayerId=$data['playerId'];
			$toAccountId=0;
		}
		elseif($data['accountId']!=null)
		{
			$toAccountId=$data['accountId'];
			$toPlayerId=0;
		}
		else{
			throw new Exception('Erreur de récup des données du Id');
		}		
		
		$sql='SELECT message_linkId,everRead FROM message_link 
			WHERE messageId=:messageId AND toAccountId=:toAccountId AND toPlayerId=:toPlayerId';
			$stmnt=$this->_db->prepare($sql);
			$stmnt->bindParam(':toPlayerId',$toPlayerId);					
			$stmnt->bindParam(':toAccountId',$toAccountId);					
			$stmnt->bindParam(':messageId',$messageId);					
			$stmnt->execute();
			$row=$stmnt->fetch(PDO::FETCH_ASSOC);
			
			$everRead=$row['everRead'];
			$message_linkId=$row['message_linkId'];
			
			
		if($everRead==='0')
		{
			$this->setEverRead($message_linkId,'1');
		}		
	}
	
	private function setEverRead($message_linkId,$bool)
	{
		$sql='UPDATE message_link SET everRead=:bool
			WHERE message_linkId=:message_linkId';
			$stmnt=$this->_db->prepare($sql);					
			$stmnt->bindParam(':message_linkId',$message_linkId);					
			$stmnt->bindParam(':bool',$bool);					
			$stmnt->execute();
			if($stmnt->rowCount() == 0)
			{
				throw new Exception('L\'enregistrement semble avoir échoué everRead.');
			}
	}
	
	public function addRespond(Message $Message)
	{
		// init des var 
		$message=$Message->getMessage();
		$link=$Message->getLinkId();// messageId d'origine		
		$fromAccountId=$Message->getFromAccountId();
		$fromPlayerId=$Message->getFromPlayerId();
		
		// ------ test pour plus bas ------
		if($fromPlayerId!=0){
			$from=$fromPlayerId;
			$type='toPlayerId';
		}
		else {
			$from=$fromAccountId;
			$type='toAccountId';
			}
		
		//---------------------------
		$date=$Message->getDate();
		
		// vérif des var 
		if($message==null || $link == null || ($fromAccountId==null && $fromPlayerId==null) || $date==null)
		{ throw new Exception('Il semblerai manquer des info.');}
	
		// il faut rechercher les infos du message d'origine si player ou account
		$sql='SELECT fromPlayerId,fromAccountId FROM message 
		WHERE messageId=:messageId';
		$stmnt=$this->_db->prepare($sql);					
		$stmnt->bindParam(':messageId',$messageId);					
		$stmnt->execute();
		$row=$stmnt->fetch(PDO::FETCH_ASSOC);
		if($row['fromPlayerId']!=null)// alors c'est de type player
		{
			$accountId=null;
		}
		else{// alors c'est de type account
			$playerId=null;
		}
		
		// on ajoute le nouveau message
		$sql='INSERT INTO message(message,dateMessage,fromPlayerId,fromAccountId,link) VALUES (:message,:date,:fromPlayerId,:fromAccountId,:link)';
		$stmnt=$this->_db->prepare($sql);
		$stmnt->bindParam(':date',$date);	
		$stmnt->bindParam(':message',$message);
		$stmnt->bindParam(':link',$link);
		$stmnt->bindParam(':fromAccountId',$fromAccountId);
		$stmnt->bindParam(':fromPlayerId',$fromPlayerId);
		$stmnt->execute();
		if($stmnt->rowCount() == 0)
		{
			throw new Exception('L\'enregistrement de la réponse semble avoir échoué.');
		}
		
		// on récupère les destinataires
		$dataMessage_link=$this->getMessage_linkId($link);
		
		
		// on passe les everRead des destinataires à 0 sauf l'auteur 
		foreach ($dataMessage_link as $value)
		{
			if($value[$type]!=$from)
			{
				if($value['everRead']==1)
				{
					$this->setEverRead($value['message_linkId'],0);
				}
			}
			
		}
	}
	
	private function getMessage_linkId($messageId){
		$sql='SELECT message_linkId,toPlayerId,toAccountId,everRead FROM message_link 
		WHERE messageId=:messageId';
		$stmnt=$this->_db->prepare($sql);					
		$stmnt->bindParam(':messageId',$messageId);					
		$stmnt->execute();
		$i=0;
		while($row=$stmnt->fetch(PDO::FETCH_ASSOC)){
			$data[$i]['message_linkId']=$row['message_linkId'];
			$data[$i]['everRead']=$row['everRead'];
			$data[$i]['toPlayerId']=$row['toPlayerId'];
			$data[$i]['toAccountId']=$row['toAccountId'];
			$i++;
		}
		if(!isset($data))
		{
			throw new Exception('Aucun destinataires trouvé.');
		}
		return $data;
	}
		
	public function sendMessage(Message $Message)
	{
		$fromAccountId=$Message->getFromAccountId();
		$fromPlayerId=$Message->getFromPlayerId();
		$linkId=$Message->getLinkId();	
		$toAccountId=$Message->getToAccountId();// doit etre array
		$toPlayerId=$Message->getToPlayerId(); // doit etre array
		$message=$Message->getMessage();
		$date=$Message->getDate();
		$title=$Message->getTitle();
				
		
		if($toPlayerId!=null)
		{
			if(!is_array($toPlayerId))
			{
				throw new Exception('playerId doit etre array');
			}
			$toId=$toPlayerId;
			$to='player';			
		}
		else{
			if(!is_array($toAccountId))
			{
				throw new Exception('accountId doit etre array');
			}
			$toId=$toAccountId;
			$to='account';
		}	
		if($fromPlayerId!=null)
		{
			$toId[]=$fromPlayerId;
		}
		else{
			$toId[]=$fromAccountId;
		}
		
		//test
		//echo 'date:'.$date.' message:'.$message.' fromAccountId:'.$fromAccountId.' fromPlayerId:'.$fromPlayerId.' linkId:'.$linkId;
		
		// on créer un nouveau message
		$sql='INSERT INTO message(message,dateMessage,fromAccountId,fromPlayerId,title,link) VALUES (:message,:date,:fromAccountId,:fromPlayerId,:title,:linkId)';
		$this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
		$stmnt=$this->_db->prepare($sql);
		
		$stmnt->bindParam(':date',$date);	
		$stmnt->bindParam(':message',$message);
		$stmnt->bindParam(':fromAccountId',$fromAccountId);
		$stmnt->bindParam(':fromPlayerId',$fromPlayerId);
		$stmnt->bindParam(':linkId',$linkId);
		$stmnt->bindParam(':title',$title);
		$stmnt->execute();	
				
		if($stmnt->rowCount() == 0)
		{							
			throw new Exception('L\'enregistrement semble avoir échoué.');
		}
		
		// on doit récupérer le new messageId
		$sql='SELECT messageId FROM message WHERE dateMessage=:date AND (fromAccountId=:fromAccountId OR fromPlayerId=:fromPlayerId)';
		$this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
		$stmnt=$this->_db->prepare($sql);
		$stmnt->bindParam(':fromAccountId',$fromAccountId);						
		$stmnt->bindParam(':fromPlayerId',$fromPlayerId);						
		$stmnt->bindParam(':date',$date);		
		$stmnt->execute();
		if(!$row=$stmnt->fetch(PDO::FETCH_ASSOC))
				{
					throw new Exception('Impossible de trouver le nouveau message');
				}	
		$messageId=$row['messageId'];
		
		//on fait les linkId si c'est un nouveau message
		if($linkId==null)
		{
		$toId=array_unique($toId);
		foreach($toId as $value)
			{
				$sql='INSERT INTO message_link(toPlayerId,toAccountId,messageId,everRead)
				VALUES (:toPlayerId,:toAccountId,:messageId,:everRead)';
				$stmnt=$this->_db->prepare($sql);
				$zero=0;
				if($to=='player')
				{
					$stmnt->bindParam(':toPlayerId',$value);
					$stmnt->bindParam(':toAccountId',$zero);				
				}
				elseif($to=='account')
				{
					$stmnt->bindParam(':toAccountId',$value);
					$stmnt->bindParam(':toPlayerId',$zero);
				}
				else{throw new Exception('Erreur de destinataire: account ou player?');}
				$stmnt->bindParam(':messageId',$messageId);
				if($value==$fromPlayerId || $value==$fromAccountId){
					$everRead=1;
				}
				else{
					$everRead=0;
				}
				$stmnt->bindParam(':everRead',$everRead);
				$stmnt->execute();
				if($stmnt->rowCount() == 0)
				{
					throw new Exception('L\'enregistrement semble avoir échoué.');
				}
			}
		}
	}
	public function leaveMessage($messageId,array $data)
	{
		if(!is_numeric($messageId))
		{
			throw new Exception('deleteMessage: doit être numéric');
		}
		$accountId=$data['accountId'];
		if(isset($data['playerId'])){$playerId=$data['playerId'];}else{$playerId=0;}
				
		
		// on supprime les message_link
		$sql='DELETE FROM message_link WHERE messageId=:messageId AND (toAccountId=:accountId OR toPlayerId=:playerId)';
		$stmnt=$this->_db->prepare($sql);					
		$stmnt->bindParam(':messageId',$messageId);					
		$stmnt->bindParam(':accountId',$accountId);					
		$stmnt->bindParam(':playerId',$playerId);					
		$stmnt->execute();	
		if($stmnt->rowCount() == 0)
				{
					throw new Exception('la suppression du votre fil a échouée.');
				}		
		
		// on verifie si le message à toujours des lecteurs
		$sql='SELECT COUNT(message_linkId) FROM message_link
		WHERE messageId=:messageId';
		$stmnt=$this->_db->prepare($sql);					
		$stmnt->bindParam(':messageId',$messageId);					
		$stmnt->execute();
		$row=$stmnt->fetch(PDO::FETCH_ASSOC);
		$result=$row['COUNT(message_linkId)'];
		// si non, on supprime également les link
		if($result==0)// on doit supprimer le message
		{
			$this->deleteMessage($messageId);
		}
	}
	
	public function deleteMessage($messageId)
	{		
		$sql='DELETE FROM message WHERE messageId=:messageId OR link=:messageId';
		$stmnt=$this->_db->prepare($sql);					
		$stmnt->bindParam(':messageId',$messageId);					
		$stmnt->execute();		
		if($stmnt->rowCount() == 0)
				{
					throw new Exception('la suppression du message à échoué.');
				}	
				
	}
	
}