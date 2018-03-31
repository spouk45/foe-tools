<?php 
include ('../config.php');
include(HEAD);
include(HEADER);

if(!isset($_SESSION['player']))
{
	echo $ERROR_AUTH;
	exit();
}?>
<h2>Création d'une guilde</h2> 
<article id="create_guild">
<?php 
$guildId=$PlayerManager->getGuildId($_SESSION['player']['id']);
if($guildId != null)
{	
	echo 'Vous devez d\'abord quitter votre guilde avant d\'en créer une autre.';
	exit();	
}
?>

	<form method="POST" action="<?php echo URL_ROOT.'proc/create_guild.php';?>" id="form_guild">
		<p>Nom de la guilde:</p>
		<p><input type="text" name="guild_name" id="guild_name" value=""></p>
		<p><input type="submit" name="valid" id="valid" value="Valider">
		<div class="text_error" id="error_box"></div>
		
	</form>
</article>
<?php include(FOOTER);?>
<script>
$(document).ready(function() {
    // Lorsque je soumets le formulaire
    $('#form_guild').on('submit', function(e) {
        e.preventDefault(); // J'empêche le comportement par défaut du navigateur, c-à-d de soumettre le formulaire
 
        var $this = $(this); // L'objet jQuery du formulaire
 
        // Je récupère les valeurs
        var guild_name = $('#guild_name').val();  
			if(/[$^+<>!#~`()]/.test(guild_name) )
			  {
				  $('#error_box').html('<p>Certains caractères sont interdits<p>');
			  }			
			  else if(guild_name.length<3)
			  {
				   $('#error_box').html('<p>Nom de guilde: minimum 3 caractères.<p>');
			  }
			   else if(guild_name.length>30)
			  {
				   $('#error_box').html('<p>Nom de guilde: maximum 30 caractères.<p>');
			  }
			
         else {
            // Envoi de la requête HTTP en mode asynchrone
            $.ajax({
                url: $this.attr('action'), // Le nom du fichier indiqué dans le formulaire
                type: $this.attr('method'), // La méthode indiquée dans le formulaire (get ou post)
                data: $this.serialize(), // Je sérialise les données (j'envoie toutes les valeurs présentes dans le formulaire)
                success: function(html) { // Je récupère la réponse du fichier PHP
                   $('#error_box').html(html); // J'affiche cette réponse
                }
            });
        }
    });
});
</script>
