<?php 
include('../config.php');
include(HEAD);
include(HEADER);

?>
<h2>Les membres</h2>
<article id="member">
<?php 
// ------- CONTROLE DES PERMISSION  ------------
 if(!isset($_SESSION['guild']) ){
	 echo GUILD_AUTH;
	 exit();
 }
 // ---------------------------------------
 /*
 ?><pre><?php print_r($_SESSION);?></pre><?php
 */
 $data=$PlayerManager->getPlayerGuild($_SESSION['guild']['id']);
 //print_r($data);
 $i=1;
 ?><p><span class="col_member th">Membre</span><span class="col_level th">Niveau</span></p><?php
 foreach ($data as $value)
 {
	 if($value['levelName']!='coming'){
		 ?><p><span class="col_member back<?php echo $i;?>"><?php echo $value['playerName'];?></span><span class="col_level back<?php echo $i;?>"><?php echo $value['levelName'];?></span></p><?php
	if($i==1){$i=2;}else{$i=1;}
	}
	 
 }
 ?>
 </article>
 <?php include(FOOTER);?>