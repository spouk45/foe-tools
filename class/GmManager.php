<?php 
class GmManager
{
	
	private $_db;
	
	public function __construct(PDO $db)
	{
		$this->_db=$db;
	}
	
	public function getGmPlayer($playerId)
	{
		$sql='SELECT gm.gmId,gmName,ageId,gmImage,level,pfAmount,pfMax,dateMajPf FROM gm_list
		INNER JOIN gm ON gm.gmId=gm_list.gmId
		WHERE playerId=:playerId ORDER BY ageId';
		$stmnt=$this->_db->prepare($sql);
		$stmnt->bindParam(':playerId',$playerId);		
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}
		$data=null;
		$i=0;
		while($row=$stmnt->fetch(PDO::FETCH_ASSOC))
		{
			$data[$i]['gmId']=$row['gmId'];
			$data[$i]['gmName']=$row['gmName'];
			$data[$i]['ageId']=$row['ageId'];
			$data[$i]['gmImage']=$row['gmImage'];
			$data[$i]['level']=$row['level'];
			$data[$i]['pfAmount']=$row['pfAmount'];
			$data[$i]['pfMax']=$row['pfMax'];
			$data[$i]['dateMajPf']=$row['dateMajPf'];			
			$i++;
		}		
		
		return $data;
	}

    /**
     * @param $playerId
     * @return null
     * @throws Exception
     */
    public function getGmList($playerId=null)// si playerId, on recherche les GM que le player n'a pas
	{
		// faire une recherche des gmId que le joueur possède => retirer les gm que le joueur possède.
		//echo 'playerId:'.$playerId;
		 // si playerId, on deduit ses GM
		if($playerId!=null)
		{
			$sql='SELECT gmListId,gmId FROM gm_list
			WHERE playerId=:playerId';
			$stmnt=$this->_db->prepare($sql);
			$stmnt->bindParam(':playerId',$playerId);
			$stmnt->execute();
			$error=$stmnt->errorInfo();
			if($error[0]!=00000){
				throw new Exception($error[2]);
			}
			$GmListPlayer=array();
			while($row=$stmnt->fetch(PDO::FETCH_ASSOC))
			{
				$GmListPlayer[]=$row['gmId'];
			}
			$in=implode(',',$GmListPlayer);
			$where = $in == '' ? '' : ' WHERE gmId NOT IN (' . $in . ')';
		}
		else{
			$where='';
		}
		
		
		$sql='SELECT gmId,gmName,ageId,gmImage FROM gm'.$where.' ORDER BY gmId';
		$stmnt=$this->_db->prepare($sql);	
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}
		$i=0;
		$data=null;
		while($row=$stmnt->fetch(PDO::FETCH_ASSOC))
		{
			$data[$i]['gmId']=$row['gmId'];
			$data[$i]['gmName']=$row['gmName'];
			$data[$i]['ageId']=$row['ageId'];
			$data[$i]['gmImage']=$row['gmImage'];			
			$i++;
		}
		/*?><pre><?php print_r($data);?></pre><?php*/
		return $data;
	}
	public function getGmContent($gmId,$playerId) // pas fini
	{
		$sql='SELECT gmListId,gmId FROM gm_list
			WHERE playerId=:playerId ';
			$stmnt=$this->_db->prepare($sql);
			$stmnt->bindParam(':playerId',$playerId);		
			$stmnt->execute();
			$error=$stmnt->errorInfo();
			if($error[0]!=00000){
				throw new Exception($error[2]);
			}
	}
	
	public function AddGm(Gm $Gm)
	{		
		$gmId=$Gm->getGmId();
		$playerId=$Gm->getPlayerId();		
		$sql='INSERT INTO gm_list (playerId,gmId) VALUES (:playerId,:gmId)';
		$stmnt=$this->_db->prepare($sql);
		$stmnt->bindParam(':playerId',$playerId);		
		$stmnt->bindParam(':gmId',$gmId);		
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){throw new Exception($error[2]);}	
		
	}
	
	public function getGmName(Gm $Gm)
	{
		$gmId=$Gm->getGmId();
		
		$sql='SELECT gmName FROM gm WHERE gmId=:gmId';
		$stmnt=$this->_db->prepare($sql);
		$stmnt->bindParam(':gmId',$gmId);		
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){throw new Exception($error[2]);}	
		$row=$stmnt->fetch(PDO::FETCH_ASSOC);
		return $row['gmName'];
	}

	public function setGmData(Gm $Gm){
		$gmId=$Gm->getGmId();
		$playerId=$Gm->getPlayerId();
		$level=$Gm->getLevel();
		$pfAmount=$Gm->getPf();
		$pfMax=$Gm->getPfMax();
		$type=$Gm->getType();
		$dateMajPf=$Gm->getDate();
		switch($type){
			case 'level':
				$setType ='level';
				$value =$level;
				break;
			case 'pfAmount':
				$setType='pfAmount';
				$value=$pfAmount;
				break;
			case 'pfMax':
				$setType='pfMax';
				$value=$pfMax;
				break;
			default: throw new Exception('Erreur de valeur pour setType');
		}

		$sql='UPDATE gm_list SET '.$setType.'=:value,dateMajPf=:dateMajPf WHERE playerId=:playerId AND gmId=:gmId';
		$stmnt=$this->_db->prepare($sql);
		$stmnt->bindParam(':playerId',$playerId);
		$stmnt->bindParam(':gmId',$gmId);
		$stmnt->bindParam(':value',$value);
		$stmnt->bindParam(':dateMajPf',$dateMajPf);
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){throw new Exception($error[2]);}

	}

	public function getAllGm($worldId,$start=0,$limit=10) // a suprimer
	{

		$sql='SELECT gmListId,user,gm.gmId,gmName,gmImage,gm_list.level,pfAmount,pfmax,dateMajPf FROM gm_list
			INNER JOIN player ON (player.player_id=gm_list.playerId)
			INNER JOIN account ON (player.account_id=account.account_id)
			INNER JOIN gm ON(gm.gmId=gm_list.gmId)
			WHERE world_Id=:worldId ORDER BY dateMajPf DESC
			LIMIT '.$start.','.$limit;
		$stmnt=$this->_db->prepare($sql);
		$stmnt->bindParam(':worldId',$worldId);
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}
		$i=0;
		$data=array();
		while($row=$stmnt->fetch(PDO::FETCH_ASSOC)) {
			$data[$i]['gmListId']=$row['gmListId'];
			$data[$i]['PlayerName']=$row['user'];
			$data[$i]['gmId']=$row['gmId'];
			$data[$i]['gmName']=$row['gmName'];
			$data[$i]['gmImage']=$row['gmImage'];
			$data[$i]['level']=$row['level'];
			$data[$i]['pfAmount']=$row['pfAmount'];
			$data[$i]['pfmax']=$row['pfmax'];
			$data[$i]['dateMajPf']=$row['dateMajPf'];
			$i++;
		}
		return $data;
	}

	/**
	 * @param GmFilter $gmFilter
	 * @return array
	 * @throws Exception
     */
	public function getGmFiltered(GmFilter $gmFilter){

		// nombre de req max

		// --- initialisation des var ----
		$gmId=$gmFilter->getGmId();//array
		$worldId=$gmFilter->getWorldId();
		$guildId=$gmFilter->getGuildFilter();
		$levelMini=$gmFilter->getLevelMini();
		$levelMaxi=$gmFilter->getLevelMaxi();
		$dateMaj=$gmFilter->getDateMaj();
		//$dateNow=$gmFilter->getDateNow();
		$gmProgressMin=$gmFilter->getGmProgressMin();
		$gmProgressMax=$gmFilter->getGmProgressMax();
		$interval=$gmFilter->getLimit(); // départ de req
		$intervalLimit=$gmFilter->getIntervalLimit(); // départ de req

		// --- construction des where ---
		$bind=array();
		$where='';

		// **** gmId ****
		if($gmId!=null){
			$list=implode(",",$gmId);
			$where.=' AND gm_list.gmId IN('.$list.')';
		}
			// **** guild ****
		if($guildId!=null){
			$where.=' AND player.guild_id=:guildId';
			$bind['guildId']=$guildId;
		}

			// **** level ****
		if($levelMaxi==null && $levelMini!=null) // mini < level < inf
		{
			$where.=' AND gm_list.level >= :levelMini';
			$bind['levelMini']=$levelMini;

		}
		elseif( $levelMaxi!=null){ // mini < level < maxi
			$where.=' AND gm_list.level >= :levelMini AND gm_list.level <= :levelMaxi';
			$bind['levelMini']=$levelMini;
			$bind['levelMaxi']=$levelMaxi;
		}

			// ***** dateMaj ****
		if($dateMaj!=null){
			$where.=' AND dateMajPf >= :dateMaj';
			$bind['dateMaj']=$dateMaj;
		}

			// **** gmProgress ****
		if($gmProgressMin!=0 || $gmProgressMax!=100){
			$where.=' AND ((pfAmount/pfMax)*100)>:gmProgressMin AND ((pfAmount/pfMax)*100)<:gmProgressMax';
			$bind['gmProgressMin']=$gmProgressMin;
			$bind['gmProgressMax']=$gmProgressMax;
		}

		$sql='SELECT gmListId,account.user,gm.gmId,gmName,gmImage,gm_list.level,pfAmount,pfMax,dateMajPf FROM gm_list
			INNER JOIN player ON (player.player_id=gm_list.playerId)
			INNER JOIN account ON (player.account_id=account.account_id)
			INNER JOIN gm ON(gm.gmId=gm_list.gmId)
			WHERE world_Id=:worldId'.$where.' ORDER BY dateMajPf,gmId,account.user DESC
			LIMIT '.$interval.','.$intervalLimit;
		//echo $sql;
		$stmnt=$this->_db->prepare($sql);
		$stmnt->bindParam(':worldId',$worldId);
		foreach($bind as $key => &$value){
			$stmnt->bindParam(':'.$key,$value);
		}
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}
		$i=0;
		//$test=0;
		$data=array();
		while($row=$stmnt->fetch(PDO::FETCH_ASSOC)) {
			$data['gm'][$i]['gmListId']=$row['gmListId'];
			$data['gm'][$i]['playerName']=$row['user'];
			$data['gm'][$i]['gmId']=$row['gmId'];
			$data['gm'][$i]['gmName']=$row['gmName'];
			$data['gm'][$i]['gmImage']=$row['gmImage'];
			$data['gm'][$i]['level']=$row['level'];
			$data['gm'][$i]['pfAmount']=$row['pfAmount'];
			$data['gm'][$i]['pfMax']=$row['pfMax'];
			$data['gm'][$i]['dateMajPf']=$row['dateMajPf'];
			$i++;
			//$test++;
		}
//echo '<br>'.$test.'<br>';
		$sql='SELECT COUNT(gmListId) FROM gm_list
			INNER JOIN player ON (player.player_id=gm_list.playerId)
			INNER JOIN account ON (player.account_id=account.account_id)
			INNER JOIN gm ON(gm.gmId=gm_list.gmId)
			WHERE world_Id=:worldId'.$where;
		$stmnt=$this->_db->prepare($sql);
		$stmnt->bindParam(':worldId',$worldId);
		foreach($bind as $key => &$value){
			$stmnt->bindParam(':'.$key,$value);
		}
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}
		if($row=$stmnt->fetch(PDO::FETCH_ASSOC)){
			$data['countResult']=$row['COUNT(gmListId)'];
		}

		return $data;
	}


}
