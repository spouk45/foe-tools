<?php include('../config.php');?>
<?php
include(HEAD);?>
<?php include(HEADER);?>
<?php include(DIR_ROOT.'admin/inc/check_admin.php'); ?>
<h2>Gestion des news</h2>

<article id="news">
	<form method="POST" action="<?php echo URL_ROOT.'admin/proc/proc_add_news.php';?>" id="add_news">
	
	<div>
		<p>Titre:<input type="text" id="title" name="title"></p>
		<p>Contenu:</p>
		<p><textarea id="news_content" name="news_content"></textarea></p>
		<p><input type="submit" value="ok"></p>
	</div>
	<div id="box_error" class="text_error"></div>
</article>
<script>

	$(document).ready(function() {
    // Lorsque je soumets le formulaire
    $('#add_news').on('submit', function(e) {
        e.preventDefault(); // J'empêche le comportement par défaut du navigateur, c-à-d de soumettre le formulaire
 
        var $this = $(this); // L'objet jQuery du formulaire
 
        // Je récupère les valeurs
        var title = $('#title').val();
        var content = $('#content').val();
        
        
        // Je vérifie une première fois pour ne pas lancer la requête HTTP
        // si je sais que mon PHP renverra une erreur
        if(title === '' || content === '') {
			$('#box_error').html('<p>Les champs doivent êtres remplis<p>');			
		}				
         else {
            // Envoi de la requête HTTP en mode asynchrone
            $.ajax({
                url: $this.attr('action'), // Le nom du fichier indiqué dans le formulaire
                type: $this.attr('method'), // La méthode indiquée dans le formulaire (get ou post)
                data: $this.serialize(), // Je sérialise les données (j'envoie toutes les valeurs présentes dans le formulaire)
                success: function(html) { // Je récupère la réponse du fichier PHP
                   $('#box_error').html(html); // J'affiche cette réponse
                }
            });
        }
    });
});
</script>