<?php 
include('../config.php');
include(HEAD);
include(HEADER);
?>
<h2>Création d'un compte</h2>
<article id="registration">
	<form method="POST" action="<?php echo URL_ROOT;?>proc/proc_registration.php" id="form_registration">
		<p><input type="text" name="registration_user_name" id="registration_user_name" placeholder="Login"></p>
		<p><input type="password" name="registration_user_pass1" id="registration_user_pass1" placeholder="Password"></p>
		<p><input type="password" name="registration_user_pass2" id="registration_user_pass2" placeholder="Password again"></p>
		<p id="registration_error_box" class="text_error"></p>
		<p><input type="submit" value="Confirmer"></p>	
		
	</form>
	
</article>
<?php include(FOOTER);?>
<script>

	$(document).ready(function() {
    // Lorsque je soumets le formulaire
    $('#form_registration').on('submit', function(e) {
        e.preventDefault(); // J'empêche le comportement par défaut du navigateur, c-à-d de soumettre le formulaire
 
        var $this = $(this); // L'objet jQuery du formulaire
 
        // Je récupère les valeurs
        var user_name = $('#registration_user_name').val();
        var user_password = $('#registration_user_pass1').val();
        var user_password2 = $('#registration_user_pass2').val();
        
        // Je vérifie une première fois pour ne pas lancer la requête HTTP
        // si je sais que mon PHP renverra une erreur
        if(user_name === '' || user_password === '' || user_password2 === '') {
			$('#registration_error_box').html('<p>Les champs doivent êtres remplis<p>');			
		}				
			else if(/[$^+<>!#~`()]/.test(user_name) )
			  {
				  $('#registration_error_box').html('<p>Certains caractères sont interdits<p>');
			  }			
			  else if(user_name.length<3)
			  {
				   $('#registration_error_box').html('<p>Login: minimum 3 caractères.<p>');
			  }
			   else if(user_name.length>15)
			  {
				   $('#registration_error_box').html('<p>Login: maximum 15 caractères.<p>');
			  }
			else if(user_password !== user_password2)
			{
				$('#registration_error_box').html('<p>Les mots de passes doivent être identiques<p>');
			}
         else {
            // Envoi de la requête HTTP en mode asynchrone
            $.ajax({
                url: $this.attr('action'), // Le nom du fichier indiqué dans le formulaire
                type: $this.attr('method'), // La méthode indiquée dans le formulaire (get ou post)
                data: $this.serialize(), // Je sérialise les données (j'envoie toutes les valeurs présentes dans le formulaire)
                success: function(html) { // Je récupère la réponse du fichier PHP
                   $('#registration_error_box').html(html); // J'affiche cette réponse
                }
            });
        }
    });
});
</script>
