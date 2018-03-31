</div>
</div>
<?php // ******** Messagerie Box *****
?><div id="backFont">
	<div id="wrapperMessage">
		<?php include(DIR_ROOT.'inc/loading.php');?>
	</div>
</div>

<?php 
// ************ end messagerie Box *****
?>
<footer>
<div>
	<p class="left">Forge of Empires est la propriété d'Innogames GMBH</p>
	<p class="button3 left"><a href="<?php echo URL_ROOT.'page/info.php';?>">Informations</a></p>
	<p class="right">Created by Spouk</p>
	<p class="button3 right"><a href="<?php echo URL_ROOT.'page/guide.php';?>">Guide d'utilisation</a></p>
	<?php if(isset($_SESSION['account'])){ ?>
	<p class="button3 right"><a onclick="openMessageBox();">Nous contacter</a></p>
	<?php } ?>
</div>
</footer>

<script>
	function openMessageBox()
	{
		var auth=<?php if(isset($_SESSION['account'])){echo 1;} else {echo 0;}?>;
		if(auth==1)
		{
			$('#backFont').css('display','inline-block');
			$('#wrapperMessage').css('display','inline-block');
			$('#wrapperMessage').load('<?php echo URL_ROOT.'/inc/inc_message_spouk.php';?>');
		}
		else{
			alert('Vous devez être connecté pour envoyer un message.');
		}
		
	}
		
	function closeFont()
	{
		$('#backFont').css('display','none');
		$('#wrapperMessage').load('<?php echo URL_ROOT.'/inc/loading.php';?>');
	}
	function sendMessage(){		
		var message=$('#textMessage').val();
		//alert(auth);
		if(message!=''){
			$.post( "<?php echo URL_ROOT.'/proc/message_account.php';?>", { message: message })
			.done(function( data ) {
				if(data!=''){
					alert(data); 
				}
				else{
					// le message est bien parti
					//alert('Votre message à été envoyé avec succès.'); // a modifier
					//confirm($('#backFont').css('display','none'));	
					$('#textMessage').replaceWith('<div>'+message+'</div>');
					$('#wrapperMessage #messageUndo').css('display','none');
					$('#wrapperMessage #messageValid').replaceWith('<img src="<?php echo URL_ROOT.'/img/valid.png';?>" width="40" height="40">');
					setTimeout(closeFont, 2000);
				}
			
		  });
		}
		else{
			$('#textMessage').css('border','1px solid red');
			//alert('Votre message est vide.');
		}
		
	}
		
	$(document).ready(function() {
	   var haut=$(window).height();
	   //alert(haut);
	   $('#backFont').css('line-height',haut+'px');
	   $( "#textMessage" ).keyup(function() {
			$('#textMessage').css('border','1px solid grey');
		});	
		
		
		/*
		$('#gmListSelector').scroll(function(e){
			e.preventDefault();
			posY=$('#gmListSelector').scrollTop();
			if(posY>lastScrollTop)
			{
				$('#gmListSelector').scrollTop(lastScrollTop+143);
				lastScrollTop=posY;
			}
			else{
				$('#gmListSelector').scrollTop(lastScrollTop-143);
				lastScrollTop=posY;
			}*/
			/*
			if($('#gmListSelector').scrollTop()>0 && $('#gmListSelector').scrollTop()<144)
			{
				$('#gmListSelector').scrollTop(144);
			}
			if($('#gmListSelector').scrollTop()>144 && $('#gmListSelector').scrollTop()<288)
			{
				$('#gmListSelector').scrollTop(288);
			}
			*//*
		});*/
		var lastScrollTop = 0;
		
			$('#gmListSelector').on('mousewheel DOMMouseScroll', function (e) {

			var direction = (function () {

				var delta = (e.type === 'DOMMouseScroll' ?
							 e.originalEvent.detail * -40 :
							 e.originalEvent.wheelDelta);

				return delta > 0 ? 0 : 1;
			}());
			  e.preventDefault();
                e.stopPropagation();

			if(direction === 1) {
				lastScrollTop=lastScrollTop+143;
			   $('#gmListSelector').scrollTop(lastScrollTop);
			   lastScrollTop=$('#gmListSelector').scrollTop();
			}
			if(direction === 0) {
				lastScrollTop=lastScrollTop-143;
			   $('#gmListSelector').scrollTop(lastScrollTop);
			    lastScrollTop=$('#gmListSelector').scrollTop();
			}
			
			});
		
		
		
	});
	
</script>
</body>
</html>