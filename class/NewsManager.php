<?php 

class NewsManager
{
	private $_db;
	public function __construct(PDO $db)
	{
		$this->_db=$db;
	}
	
	public function addNews(News $News)
	{
		$title=$News->getTitle();
		$content=$News->getContent();
		$date=$News->getDate();
		
		$sql='INSERT INTO news (title,content,date) VALUES (:title,:content,:date)';
			$stmnt=$this->_db->prepare($sql);
			$stmnt->bindParam(':title',$title);
			$stmnt->bindParam(':content',$content);
			$stmnt->bindParam(':date',$date);
			$stmnt->execute();
			$error=$stmnt->errorInfo();
			if($error[0]!=00000){
				throw new Exception($error[2]);
				}
	}
	public function readNews()
	{
		$sql='SELECT id,title,content,date FROM news ORDER BY date DESC';
		$stmnt=$this->_db->prepare($sql);		
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}			
		$i=0;
		while($row=$stmnt->fetch(PDO::FETCH_ASSOC))
		{
			$data[$i]['id']=$row['id'];
			$data[$i]['title']=$row['title'];
			$data[$i]['content']=$row['content'];
			$data[$i]['date']=$row['date'];
			$data[$i]['countComment']=$this->countComment($row['id']);			
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
	
	public function addComment(array $data)
	{
		$newsId=$data['newsId'];
		$comment=$data['comment'];
		$userId=$data['userId'];
		$date=new DateTime();		
		$date=$date->format('U');
		
		if($date==null || $newsId==null || $comment==null || $userId==null)
		{
			throw new Exception(ERROR_DATA);
		}
		
			$sql='INSERT INTO comment (news_id,user_id,date,content) VALUES (:news_id,:user_id,:date,:content)';
			$stmnt=$this->_db->prepare($sql);
			$stmnt->bindParam(':news_id',$newsId);
			$stmnt->bindParam(':user_id',$userId);
			$stmnt->bindParam(':date',$date);
			$stmnt->bindParam(':content',$comment);
			$stmnt->execute();
			$error=$stmnt->errorInfo();
			if($error[0]!=00000){
				throw new Exception($error[2]);
				}
	}
	
	public function countComment($id)
	{
		$sql='SELECT COUNT(id) FROM comment WHERE news_id=:id';
		$stmnt=$this->_db->prepare($sql);	
		$stmnt->bindParam(':id',$id);		
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}			
		$row=$stmnt->fetch(PDO::FETCH_ASSOC);
		$countId=$row['COUNT(id)'];
		
		return $countId;
	}
	
	public function readComment($id)
	{
		$sql='SELECT comment.id,news_id,account.user,date,content FROM comment 
		INNER JOIN account ON user_id=account_id
		WHERE news_id=:id ORDER BY date DESC';
		
		$stmnt=$this->_db->prepare($sql);	
		$stmnt->bindParam(':id',$id);		
		$stmnt->execute();
		$error=$stmnt->errorInfo();
		if($error[0]!=00000){
			throw new Exception($error[2]);
		}			
		$i=0;
		while($row=$stmnt->fetch(PDO::FETCH_ASSOC))
		{
			$data[$i]['id']=$row['id'];
			$data[$i]['news_id']=$row['news_id'];
			$data[$i]['user']=$row['user'];
			$data[$i]['date']=$row['date'];
			$data[$i]['content']=$row['content'];				
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
	
	public function deleteNews($id)
	{
			if($id==null)
			{
				throw new Exception('le id est null');
			}
			
			// d'abord supprimer les comment
			$sql='DELETE FROM comment WHERE news_id=:id';
			$stmnt=$this->_db->prepare($sql);
			$stmnt->bindParam(':id',$id);			
			$stmnt->execute();
			$error=$stmnt->errorInfo();
			if($error[0]!=00000){
				throw new Exception($error[2]);
			}
			
			$sql='DELETE FROM news WHERE id=:id';
			$stmnt=$this->_db->prepare($sql);
			$stmnt->bindParam(':id',$id);			
			$stmnt->execute();
			$error=$stmnt->errorInfo();
			if($error[0]!=00000){
				throw new Exception($error[2]);
			}
	}
	
	public function deleteComment($newsId,$commentId)
	{
		if($newsId==null || $commentId==null)
			{
				throw new Exception('le id est null');
			}
			$sql='DELETE FROM comment WHERE id=:id AND news_id=:newsId';
			$stmnt=$this->_db->prepare($sql);
			$stmnt->bindParam(':id',$commentId);			
			$stmnt->bindParam(':newsId',$newsId);			
			$stmnt->execute();
			$error=$stmnt->errorInfo();
			if($error[0]!=00000){
				throw new Exception($error[2]);
			}
	}
}