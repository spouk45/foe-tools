<?php
include('../config.php');
header(CHARSET);
session_start();
if(!isset($_SESSION['account']) || !isset($_SESSION['player']))
{
    echo ERROR_AUTH;
    exit();
}


if(!isset($_SESSION['account']) || !isset($_SESSION['player']) || !isset($_SESSION['world']) )
{
echo ERROR_AUTH;
exit();
}
if(!isset($_POST)){
    echo ERROR_DATA;
}

$data=$_POST;
if(isset($_POST['guildFilter']) && isset($_SESSION['guild']['id']))$data['guildFilter']=$_SESSION['guild']['id'];
$data['worldId']=$_SESSION['world']['id'];
/*
if(!isset($_POST['gmId'])){
    $data['gmId']=array();
}*/
//print_r($_POST);
$limit=18;// nombre max de GM affiché -1 (faut compter le 0^^)
$data['intervalLimit']=$limit;
try{
/** @noinspection PhpIncludeInspection */
    include(CONNECT);
    include(DIR_ROOT.'class/GmFilter.php');
    $GmFilter=new GmFilter($data);

    include(DIR_ROOT.'class/GmManager.php');
    $GmManager=new GmManager($db);
    $gm=array();
    $gm=$GmManager->getGmFiltered($GmFilter);
}
catch(Exception $e)
{
echo $e->getMessage();
exit();
}

/*?><pre><?php   print_r($gm); ?></pre><?php*/
// ----- R�sultat: --------
$countGm=$gm['countResult'];
$pageActive=intval($data['limit']);
$nbPageMax=intval($gm['countResult']/$limit);


if($gm['countResult']!=0){
?><div class="infoPage">
    <p><?php echo 'Page '.($pageActive + 1) .' sur '. ($nbPageMax +1);?></p>
    <p><?php echo $countGm.' résultats trouvés.';?></p>
</div>


<div id="gmListPlayer">

    <ul id="gmList"><!--<?php
foreach ($gm as $content){
        foreach ($content as $value ){

        ?>
        --><li id="<?php echo $value['gmId']; ?>"><!--
            --><div id="<?php echo 'gmId' . $value['gmId']; ?>" class="imgGm">
                <img draggable="false" src="<?php echo URL_ROOT . 'img/GM/' . $value['gmImage']; ?>" alt="">
            </div><!--
                        --><div class="wrapperGm"><!--
			--><div class="gmName"><p><?php echo $value['gmName']; ?> <span
                            class="playerName">chez <?php echo ucfirst($value['playerName']); ?></span></p></div><!--
                             --><div class="gmLevel">
                    <p class="thGm">Niveau</p>

                    <p class="gmtd"><?php echo $value['level']; ?></p>
                </div><!--
                --><div class="gmPf">
                    <p class="thGm">Points de forge</p>

                    <p class="gmtd"><?php echo $value['pfAmount'] . ' sur ' . $value['pfMax']; ?>
                    </p>
                </div><!--
                    --><div class="detail">
                    <p class="thGm">Mise à jour</p>
                    <?php $date = $value['dateMajPf'];
                    $Date = new DateTime();
                    $Date->setTimestamp($date);
                    $now = new DateTime();
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
					</div><!--
           --></div><!--
       --></li><!--
       <?php  }
        }
   ?> --></ul></div>
<?php }
if($gm['countResult']==0){
    echo '<p>Aucun résultat</p>';
}
?><script>
$(window).ready(function(){
    $('#showResultNext').attr('data-page-max',<?php echo $nbPageMax;?>);
});
</script>


