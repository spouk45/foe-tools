<?php include('../config.php');?>
<?php include(HEAD);?>
<?php include(HEADER);?>
<?php include(DIR_ROOT.'admin/inc/check_admin.php'); ?>

<h2>News</h2>
<?php 
include(DIR_ROOT.'class/NewsManager.php');
try{
include(CONNECT);
$NewsManager=new NewsManager($db);
$data=$NewsManager->readNews();
}
catch(Exception $e)
{
	echo $e->getMessage();
	exit();
}
//print_r($data);

?>
<article id="news">	
		<?php 
		if ($data==null){
			echo '<p>Aucune news</p>';
		}
		else {		
			foreach($data as $value)
			{	?>
			<div id="info_box<?php echo $value['id'];?>"></div>
			<div id="wrap_news<?php echo $value['id'];?>">
				<h4><?php echo $value['title'];?>
					<img onclick="delete_news(<?php echo $value['id'];?>);" class="delete" src="<?php echo URL_ROOT.'img/croix_rouge.png';?>" alt="delete" width="10" height="10">
				</h4>
				<div class="news_content"><?php echo '<p>'.nl2br(htmlentities($value['content'])).'</p>';?></div>
				<div class="foot_news">
					<p class="left back_blue" onclick="show_comment(<?php echo $value['id'];?>);">					
						<span><?php echo $value['countComment'];?> commentaire<?php if($value['countComment']>1){echo 's';}?></span>
					</p>					
					<p class="right"><?php echo 'Le '.date('d-m-Y',$value['date']);?></p>
					
				</div>	<div class="clear"></div>			
				<div class="news_comment" id="news<?php echo $value['id'];?>">
					<div id="text_comment<?php echo $value['id'];?>"></div>							
				</div>
				</div>
			<?php
			}	
			
		}
?>
</article>

<script>
function delete_news(id)
	{
			$.post("<?php echo URL_ROOT.'admin/proc/delete_news.php';?>",{
				id: id
				},function(html) {$('#info_box'+id).html(html)}
				);
	}
function show_comment(id)
{
	
	if($('#news'+id).is(':hidden')){
		$.post("<?php echo URL_ROOT.'admin/proc/read_comment.php';?>",{
				id: id
				},function(html) {$('#text_comment'+id).html(html)}
				);
				
		$('#news'+id).css({
			display:'block'			
		});
		$('#add_comment'+id).css('display','none');
		
	}
	else {
		$('#news'+id).css('display','none');
	}	
}

function delete_comment(news_id,id)
{
	$.post("<?php echo URL_ROOT.'admin/proc/delete_comment.php';?>",{
				id: id,
				news_id:news_id
				},function(html) {$('#info_box'+news_id).html(html)}
				);
}
</script>