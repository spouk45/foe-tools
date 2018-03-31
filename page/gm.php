<?php include('../config.php');?>
<?php include(HEAD);?>
<?php include(HEADER);?>

<h2>Grands monuments</h2>

<?php 
if(!isset($_SESSION['account']) || !isset($_SESSION['player']) )
{
	echo 'Erreur d\'authentification';
	exit();
}

try{
	// on recherche les Gm du joueur
	include(CONNECT);
	include(DIR_ROOT.'class/GmManager.php');
	$GmManager=new GmManager($db);
	$gmPlayer=$GmManager->getGmPlayer($_SESSION['player']['id']);
	
	$gmList=$GmManager->getGmList($_SESSION['player']['id']);	
	// 
}
catch(Exception $e)
{
	echo $e->getMessage();
	exit();
}

?>

<article id="gm">
<div><p class="button button2 button3" id="newGm">Ajouter un Gm</p></div>

	<div id="gmListPlayer">
		<ul id="gmList"><!--
			<?php			
			if($gmPlayer!=null)
			{			
				foreach($gmPlayer as $value)
				{
					?> --><li id="<?php echo $value['gmId'];?>">
						<div id="<?php echo 'gmId'.$value['gmId'];?>" class="imgGm">
							<img draggable="false" src="<?php echo URL_ROOT.'img/GM/'.$value['gmImage'];?>" alt="">
						</div><!--
						--><div class="wrapperGm"><!--						
							--><div class="gmName"><p><?php echo $value['gmName'];?></p></div><!--
							--><div class="gmLevel">
								<p class="thGm">Niveau</p>
								<p class="gmtd"><input class="lvl" type="text" data-id="<?php echo $value['gmId'];?>" data-type="level"
													   value="<?php echo $value['level'];?>">
								</p>
							</div><!--
							--><div class="gmPf">
								<p class="thGm">Points de forge</p>
								<p class="gmtd">
									<input class="pfAmount" type="text" data-id="<?php echo $value['gmId'];?>" data-type="pfAmount" value="<?php echo $value['pfAmount'];?>">
									<span> sur </span>
									<input class="pfMax" type="text" data-id="<?php echo $value['gmId'];?>" data-type="pfMax" value="<?php echo $value['pfMax'];?>">
								</p>
							</div><!--
							--><div class="detail">
								<p class="thGm">Mise à jour</p>
								<?php $date=$value['dateMajPf'];
								$Date= new DateTime();
								$Date->setTimestamp($date);
								$now=new DateTime();
								$interval = $Date->diff($now);
								if($interval->y>45){
									$str='jamais';
								}
								elseif($interval->y>0){
									$str='%Y ans';
								}
								elseif($interval->m>0){
									$str='%m mois';
								}
								elseif($interval->d>0){
									//$str='\i\l \y \a d \h\e\u\r\e\s';
									$str='%d jours';
								}
								elseif($interval->h>0){
									//$str='\i\l \y \a d \h\e\u\r\e\s';
									$str='%H heures';
								}
								else {
									$str='%i minutes';
								}


								?>
								<p class="gmtd"><?php echo $interval->format($str);?></p>
							</div>
						</div>
					</li><!--<?php				
				}
			}	?>		
			--><li><div id="insertGm" class="imgGm imgAdd" ondrop="drop(event)" ondragover="allowDrop(event)"></div><!--			
			--></li><!--
		--></ul>
	</div>
	
	<div id="wrapperGmList">
		<div id="gmListSelector"><!--
		<?php foreach($gmList as $value2){
	?> --><img draggable="true" alt="" id="<?php echo 'gmId'.$value2['gmId'];?>" src="<?php echo URL_ROOT.'img/GM/'.$value2['gmImage'];?>"><!--
<?php } ?>			
		--></div>
		<p><span class="infoGm">Glissez/déposez les images vers <img src="<?php echo URL_ROOT.'img/add.png';?>" alt="" width="20" height="20"></span></p>
	</div>
	
</article>
<?php include(FOOTER);?>
<script>
$(document).ready(function(){

		$('#newGm').click(function () {
			$('#wrapperGmList').css('display', 'block');
		});

		document.addEventListener("dragend", function (event) {
			$('#insertGm').css('box-shadow', 'none');
		}, false);

		addLi = $('#insertGm').parent().html();

		//----------
		document.addEventListener("dragstart", function (event) {
			// The dataTransfer.setData() method sets the data type and the value of the dragged data
			event.dataTransfer.setData("Text", event.target.id);
			//loaderGmListStart();
		});
		// -----------
		document.addEventListener("dragover", function (event) {
			$('#insertGm').css('box-shadow', '4px 4px 5px 4px rgba(77, 255, 0, 0.6),-4px -4px 5px 4px rgba(77, 255, 0, 0.6)');
		});
		/*
		document.addEventListener("dragend", function (event) {
		});*/

	atready();
});

// ------ fin onReady -----------
function atready() {
	$('input').focus(function () {
		$(this).select();
	});

	$('input.lvl, input.pfAmount, input.pfMax').keyup(function () {
		var $this = $(this);
		var value = $this.val().toString();

		//alert(value.length);
		if (!checkError(value)) {

			var newVal = value.substring(0, value.length - 1);
			//alert(newVal);
			$this.val(newVal);
		}

	});

	$('input.lvl, input.pfAmount, input.pfMax').change(function () {
		var $this = $(this);
		var id = $this.attr('data-id');
		var value = parseInt($this.val());
		var type = $this.attr('data-type');

		if (type == 'level' || type == 'pfAmount' || type == 'pfMax') {
			if (checkError(value)) {
				var data = {'id': id, 'value': value, 'type': type};
				sendData(data);
			}
		}
		if (type == 'pfAmount' || type == 'pfMax') {
			var $this2;
			if (type == 'pfAmount') {
				$this2 = $('input.pfMax[data-id=' + id + ']');

			}
			if (type == 'pfMax') {
				$this2 = $('input.pfAmount[data-id=' + id + ']');
			}

			var value2 = parseInt($this2.val());
			var type2 = $this2.attr('data-type');
			if (checkError(value2)) {
				if (value > value2 && type == 'pfAmount') {
					$this2.val(value);
					value2 = value;
				}
				else if (value < value2 && type == 'pfMax') {
					$this2.val(value);
					value2 = value;
				}
				var data2 = {'id': id, 'value': value2, 'type': type2};
				sendData(data2);
			}
		}
	});
}
function sendData(data){

	$.post('<?php echo URL_ROOT.'proc/set_gm_data.php';?>', data , function (html, status) {
		if(html!=''){
			alert(html);
		}
	});
}

function checkError(value){
	var reg=new RegExp(/^[0-9]{1,6}$/);
	if(!reg.test(value)){ // si erreur de valeur
		return false;
	}
	else {
		return true};
}

function allowDrop(ev) {
    ev.preventDefault();
	}

	/*
	function dragStart(ev) {		
		ev.dataTransfer.setData("text", ev.target.id);		
		
	}
	*/
	
	function drop(ev) {		
		ev.preventDefault();
		$('#insertGm').css('box-shadow','none');
		$('.imgAdd').removeClass('imgAdd');
		//$('.liAdd').removeClass('liAdd').addClass('newLi');
		var data = ev.dataTransfer.getData("text");	// récupération de l'id de gmlist
		idInt=parseInt(data.substring(4));		
		ev.target.appendChild(document.getElementById(data));// on envoie l'elem dans la div
		$('#'+data).removeAttr('ondragstart draggable'); // on supprime les drag de la cible				
		$('#insertGm').removeAttr('ondragover ondrop'); // on supprime les drag de la cible
		$('#insertGm img').attr('draggable','false'); // on supprime les drag de la cible
		$('#'+data).removeAttr('id');
		$('#insertGm').attr('id',data); // on ajoute l'id à la div
			
		// on charge le nouveau GM		
		contentGmList=$('#gmList').html();		
		
		$('#gmList').html(contentGmList+'<li>'+addLi+'</li>');
		contentText=$('#'+data).closest('li').html();	
		pointerWait();
		$.post('<?php echo URL_ROOT.'proc/new_gm_list.php';?>',{gmId:idInt,contentText:contentText},function(html,status){
				$('#'+data).closest('li').html(html);
				pointerNormal();
				atready();
			});
		
		
		//loaderGmListStop();
		
	}


	function pointerWait()
	{
		$('body').css('cursor','wait');
	}
	function pointerNormal()
	{
		$('body').css('cursor','initial');
	}
	
	

</script>
