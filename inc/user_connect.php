<div id="connect">
	<form method="POST" action="<?php echo URL_ROOT.'proc/proc_user_connect.php';?>" id="form_connection">
		<p><input autofocus placeholder="Login" type="text" id="user_name" name="user_name" />
		<input placeholder="Password" type="password" id="user_password" name="user_password" />
		<input type="submit" id="user_submit" name="user_submit" value="ok" /></p>
	</form>
	<div id="error_box" class="text_error"></div>
	<p><a href="<?php echo URL_ROOT;?>page/registration.php">Inscription</a></p>
</div>

<script>
$(document).ready(function() {
    // Lorsque je soumets le formulaire
    $('#form_connection').on('submit', function(e) {
        e.preventDefault(); // J'empêche le comportement par défaut du navigateur, c-à-d de soumettre le formulaire
 
        var $this = $(this); // L'objet jQuery du formulaire
 
        // Je récupère les valeurs
        var user_name = $('#user_name').val();
        var user_password = $('#user_password').val();
        
        // Je vérifie une première fois pour ne pas lancer la requête HTTP
        // si je sais que mon PHP renverra une erreur
        if(user_name === '' || user_password === '') {
			$('#error_box').html('<p>Les champs doivent êtres remplis<p>');
		}
        else if(/[$^+<>!#~`()]/.test(user_name) || /[\<\>]/.test(user_password) )
			  {
				  $('#error_box').html('<p>Login ou mot de pass incorrect.<p>');
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