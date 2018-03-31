<?php 
include('../config.php');
include(HEAD);
include(HEADER);
?>
<h2>Gestion de la guilde</h2>
<article id="player_level">

<?php 
// test des permissions
if(!isset($_SESSION['player']['id']))
{
	echo 'Permission refusée.';exit();
}

try{
	$levelId=$PlayerManager->getLevelId($_SESSION['player']['id']);
	if($levelId!=1)
	{
		echo 'Permission refusé.';
		exit();
	}
	
$data=$PlayerManager->getPlayerGuild($guildId);
$levels=$PlayerManager->getLevelList();// voir plus tard si besoin de faire une class level

}
catch(Exception $e)
{
	echo $e->getMessage();exit();
}

?><div class="col_name">
		<p class="th_name">Joueur</p>
	<?php foreach($data as $key => $value)
	{
		?><p class="tr<?php echo $value['playerId'];?>"><?php echo $value['playerName'];?></p><?php
	}

	?></div><!--
--><div class="col_level">
	<p class="th_level">Niveau</p>
	
	<?php foreach($data as $key => $value)
	{ ?><p class="tr<?php echo $value['playerId'];?>"><select id="select<?php echo $value['playerId'];?>" onChange="setLevel(this.value,<?php echo $value['playerId'];?>);">
		<option value="<?php echo $value['levelId'];?>"><?php echo $value['levelName'];?></option><?php
		foreach ($levels as $value2)
				{
					if($value2['levelId'] != $value['levelId'])
					{
						?><option value="<?php echo $value2['levelId'];?>"><?php echo $value2['fullName'];?></option><?php 
					}
					
				} 
	?></select></p><?php
	}
	?>
</div><!--
--><div class="delete_player">
	<p class="th_delete">supprimer</p>
	<?php foreach($data as $key => $value)
	{
		?><p class="tr<?php echo $value['playerId'];?>"><img class="delete2" src="<?php echo URL_ROOT.'img/croix_rouge.png';?>" width="12" height="12" onclick="deletePlayer(<?php echo $value['playerId'];?>);"></p><?php
	}
	?>
</div>
<div id="error_box" class="text_error"></div>
</article>
<?php include(FOOTER);?>
<script>
	function setLevel(levelId,playerId){
		var data={'levelId': levelId , 'playerId': playerId };
		$.ajax({
                url: '<?php echo URL_ROOT.'proc/set_player_level.php';?>', // Le nom du fichier indiqué dans le formulaire
                type: 'POST', // La méthode indiquée dans le formulaire (get ou post)
                data: data, //.serialize(), // Je sérialise les données (j'envoie toutes les valeurs présentes dans le formulaire)
                success: function(html) { // Je récupère la réponse du fichier PHP
                   $('#error_box').html(html); // J'affiche cette réponse
                }
            });
	}
	
	function deletePlayer(id)
	{
		//alert('supprimer le joueur:'+id);
		var data={'deleteId': id};
		$.ajax({
                url: '<?php echo URL_ROOT.'proc/delete_player_guild.php';?>', // Le nom du fichier indiqué dans le formulaire
                type: 'POST', // La méthode indiquée dans le formulaire (get ou post)
                data: data, //.serialize(), // Je sérialise les données (j'envoie toutes les valeurs présentes dans le formulaire)
                success: function(html) { // Je récupère la réponse du fichier PHP
                   $('#error_box').html(html); // J'affiche cette réponse
                }
            });
	}
	
// survol de la souris	
$( "[class^='tr']" ).hover(
  function() {
	  truc=$(this).attr('class'); 
	  
    $('.'+truc).addClass("hover_back");
	
  }, function() {
    $('.'+truc).removeClass( "hover_back" );
  }
);


</script>