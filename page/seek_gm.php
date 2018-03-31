<?php include('../config.php');?>
<?php /** @noinspection PhpIncludeInspection */
include(HEAD);?>
<?php include(HEADER);?>


<?php

if(!isset($_SESSION['account']) || !isset($_SESSION['player']) )
{
    echo 'Erreur d\'authentification';
    exit();
}

try{
    include(CONNECT);
    include(DIR_ROOT.'class/GmManager.php');
    $GmManager=new GmManager($db);
    $gmList=$GmManager->getGmList();
}
catch(Exception $e)
{
    echo $e->getMessage();
    exit();
}
/* ---- PrÃ©paration des filtres ----- */
/* --FILTRES--
    guild -> checkbox / gmName -> selectMultiple / levelGm -> 2 input mini maxi / dateMaj -> inférieur nb day / ratio[pfAmount/pfMax] -> en % + ou - /
*/
?>
<article id="seekGm">
    <h2>Recherche de Grands Monuments</h2>
    <div id="propFilter"><p><span class="button button2 button3"> Afficher les filtres </span></p></div>
    <div id="filterGm">
        <form method="POST" id="formFilterGm" action="<?php echo URL_ROOT.'proc/seek_gm.php';?>">
            <?php if(isset($_SESSION['guild']['id']))
            { ?>


          <?php  } ?>
             <div class="filterGroup">
                 <label for="gmId">Choississez les grands monuments recherchés:</label>
                 <select id="gmId" multiple="multiple" name="gmId[]">
                     <?php foreach($gmList as $value)
                        {
                            echo '<option value="'.$value['gmId'].'">hidden</option>';
                        } ?>
                </select>
                 <div id="selectGm">
                <?php foreach($gmList as $value)
                {
                  ?><span class="option" data-gmid="<?php echo $value['gmId'];?>"><?php echo $value['gmName'];?></span>
               <?php } ?>
                  </div>
            </div>

            <div class="filterGroup">
                <p>
                    <label for="guildFilter"></label><input type="checkbox" name="guildFilter" id="guildFilter" value="1" title="Cochez pour selectionner uniquement les GM des joueurs de la guilde."> Guilde
                </p>
                <p>
                   Niveau de Gm:
                    <label><input type="number" id="levelMini" min="0" max="500" name="levelMini" value="" placeholder="Mini" title="Le plus petit niveau de GM recherché"></label>
                    <label><input type="number" id="levelMaxi" min="0" max="500" name="levelMaxi" value="" placeholder="Maxi" title="Le plus grand niveau de GM recherché"></label>
                </p>


                <p>
                    <label title="Visualiser uniquement les Gm mis à jour depuis 'x' jours maximum">
                        Mis à jour depuis<input type="number" id="dateMaj" name="dateMaj" min="0" max="1000" value="8" > jours maximum</label>
                </p>
                    <div>
                        <p>Progression du niveau du GM: <span id="SliderAmount"></span>
                           <p id="slider-range" title="Selectionnez l'interval de progression de GM"></p>
                        <label for="gmProgressMin"></label><input type="text" id="gmProgressMin" name="gmProgressMin" value="0" readonly>
                        <label for="gmProgressMax"></label><input type="text" id="gmProgressMax" name="gmProgressMax" value="100" readonly>
                    </div>

                     <p><input type="submit" value="Ok"></p>
                </div>

        </form>
    </div>
    <div id="showResultNext" data-page-active="0" data-page-max="0">
       <p><span id="previous" class="button button2 button3">Précédent</span>
        <span id="next" class="button button2 button3">Suivant</span></p>
    </div>
    <div id="respondBox">
    </div>
</article>

<!--suppress JSJQueryEfficiency -->
<script>
    $(document).ready(function() {
        showResultNext();

        $('#formFilterGm').on('submit',function(e){
            e.preventDefault();
            resetError();
            var $this = $(this);
            var limit=$('#showResultNext').attr('data-page-active'); // numéro de page

           // -----  controler les entry avant post
            //var gmId=$('#gmId').find('option:selected').val();
            var levelMini = $('#levelMini').val();
            var levelMaxi=$('#levelMaxi').val();
            var dateMaj=$('#dateMaj').val();
            var gmError=false;

            // ------- controle -------
            if( !((/^[0-9]{0,4}$/).test(levelMaxi) && (/^[0-9]{0,4}$/).test(levelMini)) ){
                gmError=true;
                $('#levelMini').css('border','1px solid red');
                $('#levelMaxi').css('border','1px solid red');
            }
           else {
                if(levelMini==''){levelMini=0;}
                if(levelMaxi==''){levelMaxi=0;}
                if( levelMaxi!=0 ){
                    if(parseInt(levelMini) > parseInt(levelMaxi)){
                        gmError=true;
                        $('#levelMini').css('border','1px solid red');
                        $('#levelMaxi').css('border','1px solid red');
                    }
                }
            }
            if(!(/^[0-9]{0,4}$/).test(dateMaj)){
                gmError=true;
                $('#dateMaj').css('border','1px solid red');
            }

            // ---------- Envoie du form ------
            if(gmError==false) {

                var $data=$this.serialize();
                $data=$data+'&limit='+limit;
                $.ajax({
                    url: $this.attr('action'),
                    type: $this.attr('method'),
                    data: $data,
                    success: function (html) {
                        $('#respondBox').html(html); // affichage du résultat

                        var $next=$('#next');
                        var $showResult=$('#showResultNext');
                        var pageMax=parseInt($showResult.attr('data-page-max'));
                        var pageActive=parseInt($showResult.attr('data-page-active'));
                        var $previous=$('#previous');

                        if(pageActive<pageMax){// on peut afficher le bouton suivant
                            $next.css('display','inline');
                        }
                        else { $next.css('display','none');}
                        if(pageActive>0){// on peut afficher le bouton précédent
                            $previous.css('display','inline');
                        }
                        else{
                            $previous.css('display','none');
                        }
                        $('#seekGm #propFilter').css('display','inline');
                        $('#seekGm #filterGm').css('display','none');

                    }
                });

            }
        });

        // ----------  hack textarea -------
         $('.option').mousedown(function(){
             var gmid=$(this).attr('data-gmid');
             $('option[value='+gmid+']').prop('selected', !$('option[value='+gmid+']').prop('selected'));
             $(this).toggleClass('selected');
             resetPage();
         });

        // ------------ slide bar -----------
            $( "#slider-range" ).slider({
                range: true,
                min: 0,
                max: 100,
                step:1,
                values: [ 0, 100 ],
                slide: function( event, ui ) {
                    $( "#gmProgressMin" ).val(ui.values[ 0 ]);
                    $( "#gmProgressMax" ).val(ui.values[ 1 ]);
                    $( "#SliderAmount" ).text(ui.values[ 0 ] + "% à " + ui.values[ 1 ]+'%' );
                }
            });
            $( "#SliderAmount" ).text($( "#slider-range" ).slider( "values", 0 ) +'% à '
                 + $( "#slider-range" ).slider( "values", 1 )+'%' );

        $( document ).tooltip({
            track: true
        });

        // reset des page si changement des inputs
        $('#formFilterGm input').keyup(function(){
            resetPage();
        });
        $('#formFilterGm input').change(function(){
            resetPage();
        });

        $('#propFilter p span').click(function(){
           $('#filterGm').css('display','block');
        });

    });
    function resetError()
    {

        $('#selectGm').css('border','1px solid black');
        $('#levelMini').css('border','normal');
        $('#levelMaxi').css('border','normal');
        $('#dateMaj').css('border','none');
        $('#levelProgress').css('border','normal');
    }

    function resetPage(){
        $('#showResultNext').attr('data-page-active',0);
        $('#showResultNext').attr('data-page-Max',0);
        $('#next , #previous').css('display','none');

    }

    function showResultNext() {
        $('#next').click(function () {
            // on relance la recherche avec les 20 prochaines entrées
            var $this = $('#showResultNext');
            var pageActive = parseInt($this.attr('data-page-active'));
            var pageMax= parseInt($this.attr('data-page-max'));

            if(pageActive<pageMax){
            $this.attr('data-page-active',pageActive+1);
            $('#formFilterGm').trigger('submit');
            }
        });

        $('#previous').click(function () {
            // on relance la recherche avec les 20 précédentes entrées
            var  $this = $('#showResultNext');
            var pageActive = parseInt($this.attr('data-page-active'));
            var pageMax= parseInt($this.attr('data-page-max'));
            if(pageActive>0) {
                $this.attr('data-page-active', pageActive - 1);
                $('#formFilterGm').trigger('submit');
            }
        });
    }
</script>

<?php include(FOOTER);?>