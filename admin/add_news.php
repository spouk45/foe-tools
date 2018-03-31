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
        e.preventDefault(); // J'emp�che le comportement par d�faut du navigateur, c-�-d de soumettre le formulaire
 
        var $this = $(this); // L'objet jQuery du formulaire
 
        // Je r�cup�re les valeurs
        var title = $('#title').val();
        var content = $('#content').val();
        
        
        // Je v�rifie une premi�re fois pour ne pas lancer la requ�te HTTP
        // si je sais que mon PHP renverra une erreur
        if(title === '' || content === '') {
			$('#box_error').html('<p>Les champs doivent �tres remplis<p>');			
		}				
         else {
            // Envoi de la requ�te HTTP en mode asynchrone
            $.ajax({
                url: $this.attr('action'), // Le nom du fichier indiqu� dans le formulaire
                type: $this.attr('method'), // La m�thode indiqu�e dans le formulaire (get ou post)
                data: $this.serialize(), // Je s�rialise les donn�es (j'envoie toutes les valeurs pr�sentes dans le formulaire)
                success: function(html) { // Je r�cup�re la r�ponse du fichier PHP
                   $('#box_error').html(html); // J'affiche cette r�ponse
                }
            });
        }
    });
});
</script>