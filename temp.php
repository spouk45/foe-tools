
<!DOCTYPE html>
<html>
<head>
<title>Infobulles simples avec jQuery</title>
 
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript">
 
$(document).ready(function() {
 
    // Sélectionner tous les liens ayant l'attribut rel valant tooltip
    $('a[rel=tooltip]').mouseover(function(e) {
 
        // Récupérer la valeur de l'attribut title et l'assigner à une variable
        var tip = $(this).attr('title');   
 
        // Supprimer la valeur de l'attribut title pour éviter l'infobulle native
        $(this).attr('title','');
 
        // Insérer notre infobulle avec son texte dans la page
        $(this).append('<div id="tooltip"><div class="tipHeader"></div><div class="tipBody">' + tip + '</div><div class="tipFooter"></div></div>');    
 
        // Ajuster les coordonnées de l'infobulle
        $('#tooltip').css('top', e.pageY + 10 );
        $('#tooltip').css('left', e.pageX + 20 );
 
        // Faire apparaitre l'infobulle avec un effet fadeIn
        $('#tooltip').fadeIn('500');
        $('#tooltip').fadeTo('10',0.8);
 
    }).mousemove(function(e) {
 
        // Ajuster la position de l'infobulle au déplacement de la souris
        $('#tooltip').css('top', e.pageY + 10 );
        $('#tooltip').css('left', e.pageX + 20 );
 
    }).mouseout(function() {
 
        // Réaffecter la valeur de l'attribut title
        $(this).attr('title',$('.tipBody').html());
 
        // Supprimer notre infobulle
        $(this).children('div#tooltip').remove();
 
    });
 
});
 
</script>
 
<style>
body {font-family:arial;font-size:12px;text-align:center;}
div#paragraph {width:300px;margin:0 auto;text-align:left}
a {color:#aaa;text-decoration:none;cursor:pointer;cursor:hand}
a:hover {color:#000;text-decoration:none;}
 
/* Infobulle */
 
#tooltip {
    position:absolute;
    z-index:9999;
    color:#fff;
    font-size:10px;
    width:180px;
 
}
 
#tooltip .tipHeader {
    height:8px;
    background:url(images/tipHeader.gif) no-repeat;
}
 
/* hack IE */
*html #tooltip .tipHeader {margin-bottom:-6px;}
 
#tooltip .tipBody {
    background-color:#000;
    padding:5px;
}
 
#tooltip .tipFooter {
    height:8px;
    background:url(images/tipFooter.gif) no-repeat;
}
 
</style>
 
</head>
 
<body>
 
<div id="paragraph">
A paragraph typically consists of a unifying main point, thought, or idea accompanied by supporting details. The non-fiction paragraph usually begins with the general and moves towards the more specific so as to advance an argument or point of view. Each <a rel="tooltip" title="A paragraph typically consists of a unifying main point, thought, or idea accompanied by <b>supporting details</b>">paragraph</a> builds on what came before and lays the ground for what comes next. Paragraphs generally range three to seven sentences all combined in a single paragraphed statement. In prose fiction successive details, for example; but it is just as common for the point of a prose paragraph to occur in the middle or the end. A paragraph can be as short as one word or run the length of multiple pages, and may consist of one or many <a rel="tooltip" title="Testing of Sentences">sentences</a>. When dialogue is being quoted in fiction, a new paragraph is used each time the person being quoted changed.
</div>
 
</body>
</html>
