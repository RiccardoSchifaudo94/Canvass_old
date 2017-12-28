<!DOCTYPE html>

<?php



include '../configura.php';

session_start();

?>

<html lang="en-gb" dir="ltr" class="uk-height-1-1">



    <head>

        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Canvass</title>

        <link rel="stylesheet" href="../css/uikit.min.css">
        <link rel="stylesheet" type="text/css" href="../css/correct.css?<?php echo time();?>">
        <script src="../js/jquery.js"></script>

        <script src="../js/uikit.min.js"></script>

        <script src="../js/core/nav.min.js"></script>

        <script src="../js/core/offcanvas.js"></script>

        <script src="../js/core/dropdown.min.js"></script>





    </head>



    <body class="uk-height-1-1"> 

    <?php   





          $_SESSION['ID_controllo'] = 0;

          $ID_utente = $_SESSION['ID_utente'];

          if($ID_utente != "")



          $_SESSION['ID_controllo'] = 1;  

          

          //echo $ID_controllo;

          $con = connetti("my_canvass");

          $sql_ruolo = "SELECT * FROM utente WHERE cancellato = 'NO' AND ID_utente = '$ID_utente';";

          $row_ruolo = esegui_query_stringa($con,$sql_ruolo);

          





?>

<?php

if($_SESSION['ID_controllo']==1)

{



?>     

        <?php

        if($row_ruolo['ID_ruolo']<=2)

          {

        ?>    

         <?php 

            // ESTRAPOLO NOME UTENTE LOGGATO    

            $con = connetti("my_canvass");

            $sql_nome = "SELECT * FROM utente WHERE cancellato = 'NO' AND ID_utente = '$ID_utente';"; 

            $row = esegui_query_stringa($con, $sql_nome);

        

            ?>  



         <?php   

               //<<<<<<<<<<<-------                  SISTEMA DI SEGNALA NOTIFICA ALL'UTENTE                   ---------------->>>>>>>>>>>>>>

               $con = connetti("my_canvass");

    

               $conta_notifiche = 0;

               $conta_msg = 0;

               $conta_msg_inv = 0;

               $conta_msg_risp = 0;

               $conta_sugg = 0;

               $conta_del = 0;

               $conta_idee = 0;

               $conta_discussioni = 0;

               

               //conto notifiche dei messaggi associati a questo utente

               $sql_conta_notifiche_msg = "SELECT COUNT(*) AS conta FROM notifica WHERE ID_utente = '$ID_utente' AND tipo='Messaggio' AND letto = 'NO';";

               $row_conta_notifiche_msg = esegui_query_stringa($con, $sql_conta_notifiche_msg);

              

               //aggiorno contatore messaggi

               $conta_msg_inv = $row_conta_notifiche_msg['conta'];

               

                //conto notifiche dei messaggi associati a questo utente come risposta del mittente

               $sql_conta_notifiche_msg = "SELECT COUNT(*) AS conta FROM notifica WHERE ID_utente = '$ID_utente' AND tipo='Risposta_Messaggio' AND letto = 'NO';";

               $row_conta_notifiche_msg = esegui_query_stringa($con, $sql_conta_notifiche_msg);

              

               //aggiorno contatore messaggi di risposta a questo utente

               $conta_msg_risp = $row_conta_notifiche_msg['conta'];

               

                

               //conto notifiche suggerimenti associati a questo utente

               $sql_conta_notifiche_sugg = "SELECT COUNT(*) AS conta FROM notifica WHERE ID_utente = '$ID_utente' AND tipo='Suggerimento' AND letto = 'NO';";

               $row_conta_notifiche_sugg = esegui_query_stringa($con, $sql_conta_notifiche_sugg);

               

               //aggiorno contatore suggerimenti 

               $conta_sugg = $row_conta_notifiche_sugg['conta'];

               

               //conto quante sono le delibere in attesa di giudizio da parte del proponente dell'idea.

                $sql_conta_notifiche_delibere = "SELECT COUNT(*) AS conta FROM associa_delibere_utente INNER JOIN delibere ON associa_delibere_utente.ID_delibera=delibere.ID_delibera WHERE  delibere.confermato = 'NO' AND stato = 'In Attesa di Conferma' AND associa_delibere_utente.ID_utente = '$ID_utente';";

               // echo $sql;

                $row_conta_notifiche_delibere = esegui_query_stringa($con,$sql_conta_notifiche_delibere);

                $conta_del = $row_conta_notifiche_delibere['conta'];

                

              //conto quanti soggetti sono interessati a portare avanti le mie idee

                $sql_conta_notifiche_interessati_idee = "SELECT COUNT(*) AS conta FROM notifica "

                        . "WHERE letto = 'NO' AND tipo = 'Idea' AND ID_utente = '$ID_utente';";

                $row_conta_notifiche_interessati_idee = esegui_query_stringa($con, $sql_conta_notifiche_interessati_idee);

                //aggiorno contatore interessati alle mie idee

                $conta_idee = $row_conta_notifiche_interessati_idee['conta'];

                

                          

               //faccio la conta notifiche tra messaggi ricevuti come primo invio e di risposta

                $conta_msg = $conta_msg_inv+$conta_msg_risp;

               

               //faccio la conta totale di tutte le notifiche arrivate

               $conta_notifiche = $conta_sugg+$conta_del+$conta_idee;

             

               ?>



     <div class="uk-margin-large-bottom">    



       <nav class="uk-navbar uk-margin-large-bottom">

                <a class="uk-navbar-brand uk-hidden-small" href="">Canvass</a>

                <ul class="uk-navbar-nav uk-hidden-small">



                      <li class="uk-parent" data-uk-dropdown>

                                    <a href="../utente/home.php">Spazio Personale</a>



                                    <div class="uk-dropdown uk-dropdown-navbar">

                                        <ul class="uk-nav uk-nav-navbar">

                                            <li><a href="../utente/propose_idea.php"><i class="uk-icon-edit"></i> Proponi idea</a></li>

                                            <li><a href="../utente/home.php"><i class="uk-icon-lightbulb-o"></i> Le tue idee</a></li>

                                            <li><a href="../utente/delibere_da_confermare.php"><i class="uk-icon-check"></i> Delibere da confermare</a></li>

                                        </ul>

                                    </div>



                    </li>

                    <li>

                        <a href="../bachecaIdee/dashboard_idea.php">Bacheca Idee</a>

                    </li>

                    <li>

                        <a href="../bachecaDelibere/dashboard_delibera.php">Bacheca Delibere</a>

                    </li>

                    <li class="uk-parent" data-uk-dropdown>

                                    <a href="../messaggistica/home.php">Messaggi<?php if($conta_msg>0) { ?> <span class="uk-badge  uk-badge-warning"><?php echo $conta_msg; ?></span><?php } ?></a>



                                    <div class="uk-dropdown uk-dropdown-navbar">

                                        <ul class="uk-nav uk-nav-navbar">

                                            <li><a href="../messaggistica/ricevuti.php"><i class="uk-icon-envelope-o"></i> Arrivati</a></li>

                                            <li><a href="../messaggistica/scrivi.php"><i class="uk-icon-edit"></i> Scrivi</a></li>

                                            <li><a href="../messaggistica/cestino.php"><i class="uk-icon-trash-o"></i> Cestino</a></li>

                                        </ul>

                                    </div>



                    </li>

                     <li class="uk-parent" data-uk-dropdown>

                                    <a href=""><i class="uk-icon-bell-o"></i> Notifiche<?php if($conta_notifiche>0) { ?> <span class="uk-badge  uk-badge-success"><?php echo $conta_notifiche; ?></span><?php } ?></a>



                                    <div class="uk-dropdown uk-dropdown-navbar">

                                        <ul class="uk-nav uk-nav-navbar">

                                            <li><a href="../notificheUtente/suggerimenti.php"><i class="uk-icon-comment-o"></i> <span class="uk-badge  uk-badge-success"><?php echo $conta_sugg; ?></span> Suggerimenti </a></li>

                                            <li><a href="../notificheUtente/interessati.php"><i class="uk-icon-group"></i> <span class="uk-badge  uk-badge-success"><?php echo $conta_idee; ?></span> Interessati alle Idee </a></li>

                                            <li><a href="../notificheUtente/delibere_da_confermare.php"><i class="uk-icon-check"></i> <span class="uk-badge  uk-badge-success"><?php echo $conta_del; ?></span> Delibere da confermare </a></li>

                                        </ul>

                                    </div>



                    </li>

                    <li class="uk-parent" data-uk-dropdown>

                                    <a href="../profilo/home.php"><i class="uk-icon-user"></i> <?php echo filtraOutput($row['nome']); ?></a>



                                    <div class="uk-dropdown uk-dropdown-navbar">

                                        <ul class="uk-nav uk-nav-navbar">

                                            <li><a href="../profilo/home.php"><i class="uk-icon-user"></i> Profilo</a></li>

                                            <li><a href="../impostazioni/home.php"><i class="uk-icon-cog"></i> Impostazioni</a></li>

                                            <li><a href="../logout.php"><i class="uk-icon-sign-out"></i> Logout</a></li>

                                        </ul>

                                    </div>



                    </li>

                </ul>

                <a href="#offcanvas" class="uk-navbar-toggle uk-visible-small" data-uk-offcanvas></a>

                <div class="uk-navbar-brand uk-navbar-center uk-visible-small">Canvass</div>

            </nav>





            <div id="offcanvas" class="uk-offcanvas">

            <div class="uk-offcanvas-bar">

                 <ul class="uk-nav uk-nav-offcanvas uk-nav-parent-icon" data-uk-nav>

                 

                     <li class="uk-parent">

                                <a href="#">Spazio Personale</a>

                                <ul class="uk-nav-sub">

                                    <li><a href="../utente/propose_idea.php"><i class="uk-icon-edit"></i> Proponi idea</a></li>

                                    <li><a href="../utente/home.php"><i class="uk-icon-lightbulb-o"></i> Le tue idee</a></li>

                                    <li><a href="../utente/delibere_da_confermare.php"><i class="uk-icon-check"></i> Delibere da confermare</a></li>

                                </ul>

                     </li>

                     <li>

                        <a href="../bachecaIdee/dashboard_idea.php">Bacheca Idee</a>

                     </li>

                     <li>

                        <a href="../bachecaDelibere/dashboard_delibera.php">Bacheca Delibere</a>

                     </li>

                     <li class="uk-parent">

                                <a href="#">Messaggi<?php if($conta_msg>0) { ?> <span class="uk-badge  uk-badge-warning"><?php echo $conta_msg; ?></span><?php } ?></a>

                                <ul class="uk-nav-sub">

                                    <li><a href="../messaggistica/ricevuti.php"><i class="uk-icon-envelope-o"></i> Arrivati</a></li>

                                    <li><a href="../messaggistica/scrivi.php"><i class="uk-icon-edit"></i> Scrivi</a></li>

                                    <li><a href="../messaggistica/cestino.php"><i class="uk-icon-trash-o"></i> Cestino</a></li>

                                </ul>

                     </li>

                     <li class="uk-parent">

                                <a href="#">Notifiche <?php if($conta_notifiche>0) { ?> <span class="uk-badge  uk-badge-success"><?php echo $conta_notifiche; ?></span><?php } ?></a>

                                <ul class="uk-nav-sub">

                                     <li><a href="../notificheUtente/suggerimenti.php"><i class="uk-icon-comment-o"></i> <span class="uk-badge  uk-badge-success"><?php echo $conta_sugg; ?></span> Suggerimenti </a></li>

                                     <li><a href="../notificheUtente/interessati.php"><i class="uk-icon-group"></i> <span class="uk-badge  uk-badge-success"><?php echo $conta_idee; ?></span> Interessati alle Idee </a></li>

                                     <li><a href="../notificheUtente/delibere_da_confermare.php"><i class="uk-icon-check"></i> <span class="uk-badge  uk-badge-success"><?php echo $conta_del; ?></span> Delibere da confermare </a></li>

                                </ul>

                     </li>

                     <li>

                        <a href="../profilo/home.php">Profilo</a>

                     </li>

                    <li>

                        <a href="../impostazioni/home.php">Impostazioni</a>

                    </li>

                    <li>

                        <a href="logout.php">Logout</a>

                    </li>

                </ul>

            </div>

        </div>



        <?php 

        }//chidi if di $row_ruolo['ID_ruolo']<=2 CHE VISUALIZZA LA BARRA DEL MENU DEI CITTADINI E MEMBRI DI ASSOCIAZIONE

        else

        {



          ?>



                     <?php 

                    // ESTRAPOLO NOME UTENTE LOGGATO    

                    $con = connetti("my_canvass");

                    $sql_nome = "SELECT * FROM utente WHERE cancellato = 'NO' AND ID_utente = '$ID_utente';"; 

                    $row = esegui_query_stringa($con, $sql_nome);

                

                    ?>  



                       <?php   

                       //setto i contatoti per tipo di notifica + contatore generale;

                       $conta_notifiche = 0;

                       $conta_del_confermate = 0;

                       $conta_del_respinte = 0;

                       $conta_sugg_del = 0;

                       

                       //setto contatori notifiche messaggi in entrata in uscita e totali

                       

                       $con = connetti("my_canvass");

                      

                       //conto delibere confermate non lette

                       $sql_conta_delibere_confermate = "SELECT COUNT(*) AS conta FROM notifica WHERE ID_utente = '$ID_utente' AND letto = 'NO' AND tipo='Delibera_confermata';";

                       $row_conta_delibere_confermate = esegui_query_stringa($con,$sql_conta_delibere_confermate);

                       $conta_del_confermate = $row_conta_delibere_confermate['conta'];

                       

                       //conte delibere respinte non lette

                       $sql_conta_delibere_respinte = "SELECT COUNT(*) AS conta FROM notifica  WHERE ID_utente = '$ID_utente' AND letto = 'NO' AND tipo='Delibera_respinta';";

                       $row_conta_delibere_respinte = esegui_query_stringa($con,$sql_conta_delibere_respinte);

                       $conta_del_respinte = $row_conta_delibere_respinte['conta'];

                       

                       //conto i suggerimenti associati alle delibere

                       $sql_conta_sugg_delibere = "SELECT COUNT(*) AS conta FROM notifica WHERE ID_utente = '$ID_utente' AND letto = 'NO' AND tipo ='Suggerimento_delibera';";

                       $row_conta_sugg_delibere = esegui_query_stringa($con,$sql_conta_sugg_delibere);

                       $conta_sugg_del = $row_conta_sugg_delibere['conta'];

                       

                        //conto notifiche dei messaggi associati a questo utente

                       $sql_conta_notifiche_msg = "SELECT COUNT(*) AS conta FROM notifica WHERE ID_utente = '$ID_utente' AND tipo='Messaggio' AND letto = 'NO';";

                       $row_conta_notifiche_msg = esegui_query_stringa($con, $sql_conta_notifiche_msg);

                      

                       //aggiorno contatore messaggi

                       $conta_msg_inv = $row_conta_notifiche_msg['conta'];

                       

                        //conto notifiche dei messaggi associati a questo utente come risposta del mittente

                       $sql_conta_notifiche_msg = "SELECT COUNT(*) AS conta FROM notifica WHERE ID_utente = '$ID_utente' AND tipo='Risposta_Messaggio' AND letto = 'NO';";

                       $row_conta_notifiche_msg = esegui_query_stringa($con, $sql_conta_notifiche_msg);

                      

                       //aggiorno contatore messaggi di risposta a questo utente

                       $conta_msg_risp = $row_conta_notifiche_msg['conta'];

                       

                       //conto tutte le notifiche 

                       $conta_notifiche = $conta_del_confermate+$conta_del_respinte+$conta_sugg_del;

                       

                                   

                       //faccio la conta notifiche tra messaggi ricevuti come primo invio e di risposta

                        $conta_msg = $conta_msg_inv+$conta_msg_risp;

                       

                       ?>

                



             <div class="uk-margin-large-bottom">    



               <nav class="uk-navbar uk-margin-large-bottom">

                        <a class="uk-navbar-brand uk-hidden-small" href="">Canvass</a>

                        <ul class="uk-navbar-nav uk-hidden-small">



                              <li class="uk-parent" data-uk-dropdown>

                                            <a href="../membroIstituzione/agenda_idee.php">Agenda Personale</a>



                                            <div class="uk-dropdown uk-dropdown-navbar">

                                                <ul class="uk-nav uk-nav-navbar">

                                                    <li><a href="../membroIstituzione/agenda_idee.php"><i class="uk-icon-lightbulb-o"></i> Idee in Agenda</a></li>

                                                    <li><a href="../membroIstituzione/home.php"><i class="uk-icon-tasks"></i> Le tue delibere</a></li>

                                                </ul>

                                            </div>



                            </li>

                            <li>

                                <a href="../bachecaIdee/dashboard_idea.php">Bacheca Idee</a>

                            </li>

                            <li>

                                <a href="../bachecaDelibere/dashboard_delibera.php">Bacheca Delibere</a>

                            </li>

                            <li class="uk-parent" data-uk-dropdown>

                                            <a href="../messaggistica/home.php">Messaggi<?php if($conta_msg>0) { ?> <span class="uk-badge  uk-badge-warning"><?php echo $conta_msg; ?></span><?php } ?></a>



                                            <div class="uk-dropdown uk-dropdown-navbar">

                                                <ul class="uk-nav uk-nav-navbar">

                                                    <li><a href="../messaggistica/ricevuti.php"><i class="uk-icon-envelope-o"></i> Arrivati</a></li>

                                                    <li><a href="../messaggistica/scrivi.php"><i class="uk-icon-edit"></i> Scrivi</a></li>

                                                    <li><a href="../messaggistica/cestino.php"><i class="uk-icon-trash-o"></i> Cestino</a></li>

                                                </ul>

                                            </div>



                            </li>

                             <li class="uk-parent" data-uk-dropdown>

                                            <a href=""><i class="uk-icon-bell-o"></i> Notifiche<?php if($conta_notifiche>0) { ?> <span class="uk-badge  uk-badge-success"><?php echo $conta_notifiche; ?></span><?php } ?></a>



                                            <div class="uk-dropdown uk-dropdown-navbar">

                                                <ul class="uk-nav uk-nav-navbar">

                                                    <li><a href="../notificheMembroIstituzione/suggerimenti.php"><i class="uk-icon-comments"></i> <span class="uk-badge  uk-badge-success"><?php echo $conta_sugg_del; ?></span> Suggerimenti</a></li>

                                                    <li><a href="../notificheMembroIstituzione/confermate.php"><i class="uk-icon-check"></i> <span class="uk-badge  uk-badge-success"><?php echo $conta_del_confermate; ?></span> Delibere Confermate </a></li>

                                                    <li><a href="../notificheMembroIstituzione/respinte.php"><i class="uk-icon-remove"></i> <span class="uk-badge  uk-badge-success"><?php echo $conta_del_respinte; ?></span> Delibere Respinte </a></li>

                                                </ul>

                                            </div>



                            </li>

                            <li class="uk-parent" data-uk-dropdown>

                                            <a href="../profiloUtente/home.php"><i class="uk-icon-user"></i> <?php echo filtraOutput($row['nome']); ?></a>



                                            <div class="uk-dropdown uk-dropdown-navbar">

                                                <ul class="uk-nav uk-nav-navbar">

                                                    <li><a href="../profiloUtente/home.php"><i class="uk-icon-user"></i> Profilo</a></li>

                                                    <li><a href="../impostazioni/home.php"><i class="uk-icon-cog"></i> Impostazioni</a></li>

                                                    <li><a href="../logout.php"><i class="uk-icon-sign-out"></i> Logout</a></li>

                                                </ul>

                                            </div>



                            </li>

                        </ul>

                        <a href="#offcanvas" class="uk-navbar-toggle uk-visible-small" data-uk-offcanvas></a>

                        <div class="uk-navbar-brand uk-navbar-center uk-visible-small">Canvass</div>

                    </nav>





                    <div id="offcanvas" class="uk-offcanvas">

                    <div class="uk-offcanvas-bar">

                         <ul class="uk-nav uk-nav-offcanvas uk-nav-parent-icon" data-uk-nav>

                         

                             <li class="uk-parent">

                                        <a href="#">Agenda Personale</a>

                                        <ul class="uk-nav-sub">

                                            <li><a href="../membroIstituzione/agenda_idee.php"><i class="uk-icon-lightbulb-o"></i> Idee in Agenda</a></li>

                                            <li><a href="../membroIstituzione/home.php"><i class="uk-icon-tasks"></i> Le tue delibere</a></li>

                                        </ul>

                             </li>

                             <li>

                                <a href="../bachecaIdee/dashboard_idea.php">Bacheca Idee</a>

                             </li>

                             <li>

                                <a href="../bachecaDelibere/dashboard_delibera.php">Bacheca Delibere</a>

                             </li>

                             <li class="uk-parent">

                                        <a href="#">Messaggi<?php if($conta_msg>0) { ?> <span class="uk-badge  uk-badge-warning"><?php echo $conta_msg; ?></span><?php } ?></a>

                                        <ul class="uk-nav-sub">

                                            <li><a href="../messaggistica/ricevuti.php"><i class="uk-icon-envelope-o"></i> Arrivati</a></li>

                                            <li><a href="../messaggistica/scrivi.php"><i class="uk-icon-edit"></i> Scrivi</a></li>

                                            <li><a href="../messaggistica/cestino.php"><i class="uk-icon-trash-o"></i> Cestino</a></li>

                                        </ul>

                             </li>

                             <li class="uk-parent">

                                        <a href="#">Notifiche <?php if($conta_notifiche>0) { ?> <span class="uk-badge  uk-badge-success"><?php echo $conta_notifiche; ?></span><?php } ?></a>

                                        <ul class="uk-nav-sub">

                                            <li><a href="../notificheMembroIstituzione/suggerimenti.php"><i class="uk-icon-comments"></i> <span class="uk-badge  uk-badge-success"><?php echo $conta_sugg_del; ?></span> Suggerimenti</a></li>

                                            <li><a href="../notificheMembroIstituzione/confermate.php"><i class="uk-icon-check"></i> <span class="uk-badge  uk-badge-success"><?php echo $conta_del_confermate; ?></span> Delibere Confermate </a></li>

                                            <li><a href="../notificheMembroIstituzione/respinte.php"><i class="uk-icon-remove"></i> <span class="uk-badge  uk-badge-success"><?php echo $conta_del_respinte; ?></span> Delibere Respinte </a></li>

                                        </ul>

                             </li>

                             <li>

                                <a href="../profiloUtente/home.php">Profilo</a>

                             </li>

                            <li>

                                <a href="../impostazioni/home.php">Impostazioni</a>

                            </li>

                            <li>

                                <a href="../logout.php">Logout</a>

                            </li>

                        </ul>

                    </div>

                </div>





          <?php

        }//chiudi else di $row_ruolo['ID_ruolo'] CHE MOSTRA LA BARRA DEL MENU DEI MEMBRI DI ISTITUZIONE

        ?>



        <?php   //----------------------------->>>>>>>>>>>>>>>>>>>>>    SISTEMA DI RANKING DELLE IDEE    <<<<<<<<<<<<<<<<------------------------------?>



        <?php



             if(isset($_POST['token_cancella_rank']))

             {



              $con = connetti("my_canvass");

              $ID_idea = pulisci($_POST['ID_idea']);

              

              $sql_controllo = "SELECT COUNT(*) AS conta FROM voto_idea WHERE ID_idea = '$ID_idea' AND ID_utente = '$ID_utente';";

              $row_controllo = esegui_query_stringa($con,$sql_controllo);

              $conta = 0;

              $conta = $row_controllo['conta'];



              if($conta!=0)

              {  

              $sql_delete = "DELETE FROM voto_idea WHERE ID_utente = '$ID_utente' AND ID_idea ='$ID_idea';";

              $ris_delete = esegui_query($con,$sql_delete);

             // echo $sql_delete;

              }



              $sql_conta_up = "SELECT COUNT(*) AS max FROM voto_idea WHERE stato = 'up' AND ID_idea = '$ID_idea';";

              $row_conta_up = esegui_query_stringa($con,$sql_conta_up);



              $sql_conta_down = "SELECT COUNT(*) AS min FROM voto_idea WHERE stato = 'down' AND ID_idea = '$ID_idea';";

              $row_conta_down = esegui_query_stringa($con,$sql_conta_down);



              $max = 0;

              $min = 0;

              $diff = 0;



              $max = $row_conta_up['max'];

              $min = $row_conta_down['min'];



              $diff = $max - $min;

              if($diff>0)

              {

                $sql_aggiorna_voti_idea = "UPDATE idee SET voti = '$diff' WHERE ID_idea = '$ID_idea';";

                $ris_aggiorna_voti_idea = esegui_query($con,$sql_aggiorna_voti_idea);

               // echo $sql_aggiorna_voti_idea;

              }  

              else

              {



                $sql_aggiorna_voti_idea = "UPDATE idee SET voti = '0' WHERE ID_idea = '$ID_idea';";

                $ris_aggiorna_voti_idea = esegui_query($con,$sql_aggiorna_voti_idea);

                //echo $sql_aggiorna_voti_idea;

              }//close else







             }//close token





             ?>





             <?php



             if(isset($_POST['token_rank_up']))

             {



              $con = connetti("my_canvass");

              $ID_idea = pulisci($_POST['ID_idea']);

              

              $sql_controllo = "SELECT COUNT(*) AS conta FROM voto_idea WHERE ID_idea = '$ID_idea' AND ID_utente = '$ID_utente';";

              $row_controllo = esegui_query_stringa($con,$sql_controllo);

              $conta = 0;

              $conta = $row_controllo['conta'];



              if($conta==0)

              {  

              $sql_up = "INSERT INTO voto_idea(ID_utente,ID_idea,stato) VALUES ('$ID_utente','$ID_idea','up');";

              $ris_up = esegui_query($con,$sql_up);

              }



              $sql_conta_up = "SELECT COUNT(*) AS max FROM voto_idea WHERE stato = 'up' AND ID_idea = '$ID_idea';";

              $row_conta_up = esegui_query_stringa($con,$sql_conta_up);



              $sql_conta_down = "SELECT COUNT(*) AS min FROM voto_idea WHERE stato = 'down' AND ID_idea = '$ID_idea';";

              $row_conta_down = esegui_query_stringa($con,$sql_conta_down);



              $max = 0;

              $min = 0;

              $diff = 0;



              $max = $row_conta_up['max'];

              $min = $row_conta_down['min'];



              $diff = $max - $min;



              if($diff>=0)

              {

                $sql_aggiorna_voti_idea = "UPDATE idee SET voti = '$diff' WHERE ID_idea = '$ID_idea';";

                $ris_aggiorna_voti_idea = esegui_query($con,$sql_aggiorna_voti_idea);

                //echo $sql_aggiorna_voti_idea;

              }  

              else

              {



                $sql_aggiorna_voti_idea = "UPDATE idee SET voti = '0' WHERE ID_idea = '$ID_idea';";

                $ris_aggiorna_voti_idea = esegui_query($con,$sql_aggiorna_voti_idea);

                //echo $sql_aggiorna_voti_idea;

              }//close else





             }//close token







             ?>





             <?php



             if(isset($_POST['token_rank_down']))

             {



              $con = connetti("my_canvass");

              $ID_idea = pulisci($_POST['ID_idea']);



              $sql_controllo = "SELECT COUNT(*) AS conta FROM voto_idea WHERE ID_idea = '$ID_idea' AND ID_utente = '$ID_utente';";

              $row_controllo = esegui_query_stringa($con,$sql_controllo);

              $conta = 0;

              $conta = $row_controllo['conta'];



              if($conta==0)

              {  

              $sql_up = "INSERT INTO voto_idea(ID_utente,ID_idea,stato) VALUES ('$ID_utente','$ID_idea','down');";

              $ris_up = esegui_query($con,$sql_up);

              }



              $sql_conta_up = "SELECT COUNT(*) AS max FROM voto_idea WHERE stato = 'up' AND ID_idea = '$ID_idea';";

              $row_conta_up = esegui_query_stringa($con,$sql_conta_up);



              $sql_conta_down = "SELECT COUNT(*) AS min FROM voto_idea WHERE stato = 'down' AND ID_idea = '$ID_idea';";

              $row_conta_down = esegui_query_stringa($con,$sql_conta_down);



              $max = 0;

              $min = 0;

              $diff = 0;



              $max = $row_conta_up['max'];

              $min = $row_conta_down['min'];

            //  echo $max;

            //  echo $min;



              $diff = $max - $min;

              if($diff>0)

              {

                $sql_aggiorna_voti_idea = "UPDATE idee SET voti = '$diff' WHERE ID_idea = '$ID_idea';";

                $ris_aggiorna_voti_idea = esegui_query($con,$sql_aggiorna_voti_idea);

              }  

              else

              {



                $sql_aggiorna_voti_idea = "UPDATE idee SET voti = '0' WHERE ID_idea = '$ID_idea';";

                $ris_aggiorna_voti_idea = esegui_query($con,$sql_aggiorna_voti_idea);



              }//close else





             }//close token







             ?>





        <?php   //  --------------------------------------- AREA DEI TOKEN DI ESECUZIONE -------------------------------------------?>



    











        <div class="uk-grid">

        <div class="uk-width-medium-1-6 uk-width-small-1-6"></div> 

        <div class="uk-width-medium-4-6 uk-width-small-4-6">

          <?php 

        

        // ABILITO PULSANTE PER PROPOSTA IDEE SOLO SE SEI UN CITTADINO O MEMBRO DI ASSOCIAZIONE 



        $con = connetti("my_canvass");

        $sql_ruolo = "SELECT * FROM utente WHERE cancellato = 'NO' AND ID_utente = '$ID_utente';";

        $row_ruolo = esegui_query_stringa($con,$sql_ruolo);

        if($row_ruolo['ID_ruolo']<=2)

        {  



        ?>  

        <div class="uk-form">

          <form action="../utente/propose_idea.php" method="post">

            <button type="submit" class="uk-button uk-button-primary"><i class="uk-icon-lightbulb-o"></i> Proponi Idea</button>

          </form>  

        </div> 

       <?php 

       }//chiudi if che contralla il ruolo dell'utente e abiliti il pulsante di proponi idea se si trattadi un cittadino o membro di associazione

       ?>   

        <br>

            <center><h2>Bacheca delle Idee - Tematiche</h2></center>

          

                    <ul class="uk-subnav uk-subnav-pill" aling="right">

                        <li><a href="dashboard_idea.php"><i class="uk-icon-calendar"></i> Recenti</a></li>

                        <li><a href="dashboard_idea_popular.php"><i class="uk-icon-sort-amount-desc"></i> Popolari</a></li>

                        <li class="uk-active"><a href="dashboard_idea_theme.php"><i class="uk-icon-key"></i> Tematiche</a></li>

                    </ul>

                    <br>

                    <br>

                    <ul class="uk-subnav uk-subnav-pill" data-uk-switcher="{connect:'#switcher-content'}">

                        <li class="uk-active" id="economy"><a href="#"><i class="uk-icon-eur"></i> Economia Virtuosa</a></li>

                        <li id="mobility"><a href="#"><i class="uk-icon-train"></i> Mobilita' Sostenibile</a></li>

                        <li id="citizen"><a href="#"><i class="uk-icon-wheelchair"></i>Cittadinanza Attiva</a></li>

                        <li id="school"><a href="#"><i class="uk-icon-mortar-board"></i> Istruzione e Ricerca</a></li>

                        <li id="ambient"><a href="#"><i class="uk-icon-tree"></i> Ambiente</a></li>

                        <li id="innovation"><a href="#"><i class="uk-icon-laptop"></i> Innovazione</a></li>

                        <li id="other"><a href="#"><i class="uk-icon-cube"></i> Altro</a></li>

                    </ul>  

           

          <?php  //   ----------------------->>>>>>>>>>>>>>>>>>>>  STAMPO DELLE IDEE ORDINATE DALLE PIU' RECENTI ALLE MENO RECENTI  <<<<<<<<<<<<<<<<<--------------------------?>





          <div id="1">

          <?php



                    //         ---------------------------------->>>>>>>>>>>>>>>>>              CATEGORIA ECONOMIA VIRTUOSA   <<<<<----------------------------

                      $sql = "SELECT * FROM idee INNER JOIN utente ON idee.ID_utente=utente.ID_utente WHERE utente.cancellato='NO' AND idee.cancellato='NO' AND idee.ID_tema = '1' ORDER BY idee.voti DESC;";



                       $con = connetti("my_canvass") ;

                                             

                      $result=esegui_query($con,$sql);



                      /*conta numero righe*/

                      $num_row=calcola_righe_query($con,$sql);



                      //$i = 0;

                      if($num_row!=0){

                            while( $row=mysqli_fetch_assoc($result))

                            {



                                    



                                  ?>



                               <!-- Project One -->

                              <div class="uk-grid post_block_2">

                                <div class="uk-width-medium-1-10 uk-width-small-1-10"></div>

                                 <div class="uk-width-medium-1-10 uk-width-small-1-10">

                                  <br>

                                  <br>

                                                                  

                                                                                  <?php      



                                                                                 $con = connetti("my_canvass");

                                                                                 $sql2 = "SELECT COUNT(*) as conta FROM voto_idea WHERE ID_idea = '".filtraInput($con,$row['ID_idea'])."' AND ID_utente ='$ID_utente';";

                                                                                 $row2 = esegui_query_stringa($con,$sql2);

                                                                                 $sql_rank = "SELECT * FROM voto_idea WHERE ID_idea = '".filtraInput($con,$row['ID_idea'])."' AND ID_utente = '$ID_utente';";

                                                                                 $row_rank = esegui_query_stringa($con,$sql_rank);

                                                                                 $conta = 0;

                                                                                 $conta = $row2['conta'];



                                                                                

                                                                                 if($conta == 0)

                                                                                 {

                                                                                ?>

                                                                               

                                                                                 <form action="dashboard_idea_theme.php" method="post">

                                                                                 <input name="ID_utente" value="<?php echo filtraOutput($ID_utente);?>" type="hidden"> 

                                                                                 <input name="ID_idea" value="<?php echo filtraOutput($row['ID_idea']);?>" type="hidden">

                                                                                 <input name="token_rank_up" value="1" type="hidden"> 

                                                                                 <center><button class="uk-button uk-button-primary" type="submit">/\</button></center>

                                                                                 <br>

                                                                                 </form>

                                                                                 <?php

                                                                                 echo "<center><h3>".$row['voti']."</h3></center>";

                                                                                 ?>

                                                                                  <?php

                                                                                 if($row['voti']==1){

                                                                                 echo "<center>Voto</center>";

                                                                                 }

                                                                                 else

                                                                                 {

                                                                                 echo "<center>Voti</center>"; 

                                                                                 }

                                                                                 ?>

                                                                                 <form action="dashboard_idea_theme.php" method="post">

                                                                                  <br>

                                                                                 <input name="ID_utente" value="<?php echo filtraOutput($ID_utente);?>" type="hidden"> 

                                                                                 <input name="ID_idea" value="<?php echo  filtraOutput($row['ID_idea']);?>" type="hidden">

                                                                                 <input name="token_rank_down" value="1" type="hidden">  

                                                                                 <center><button class="uk-button uk-button-primary" type="submit">\/</button></center>

                                                                                 </form>

                                                                                 <?php  



                                                                                 }





                                                                                 else





                                                                                 {



                                                                                 ?> 



                                                                                 <form action="dashboard_idea_theme.php" method="post">

                                                                                 <input name="ID_utente" value="<?php echo filtraOutput($ID_utente);?>" type="hidden"> 

                                                                                 <input name="ID_idea" value="<?php echo filtraOutput($row['ID_idea']);?>" type="hidden">

                                                                                 <input name="token_cancella_rank" value="1" type="hidden"> 

                                                                                 <center><button type="submit" <?php if($row_rank['stato']=="up")echo "disabled"; else echo "";?> class="<?php if($row_rank['stato']=="up")echo "uk-button uk-button-primary";

                                                                                 else echo "uk-button uk-button-primary";?>">/\</button></center>

                                                                                 </form>

                                                                                 <br>

                                                                                 <?php

                                                                                 echo "<center><h3>".$row['voti']."</h3></center>";

                                                                                 ?>

                                                                                  <?php

                                                                                 if($row['voti']==1){

                                                                                 echo "<center>Voto</center>";

                                                                                 }

                                                                                 else

                                                                                 {

                                                                                 echo "<center>Voti</center>"; 

                                                                                 }

                                                                                 ?>

                                                                                 <form action="dashboard_idea_theme.php" method="post">

                                                                                  

                                                                                 <input name="ID_utente" value="<?php echo filtraOutput($ID_utente);?>" type="hidden"> 

                                                                                 <input name="ID_idea" value="<?php echo filtraOutput($row['ID_idea']);?>" type="hidden">

                                                                                 <input name="token_cancella_rank" value="1" type="hidden">  

                                                                                 <center><button type="submit" <?php if($row_rank['stato']=="down")echo "disabled"; else echo "";?> class="<?php if($row_rank['stato']=="down")echo "uk-button uk-button-primary";

                                                                                 else echo "uk-button uk-button-primary";?>">\/</button></center>

                                                                                 </form>





                                                                                 <?php









                                                                                 } 



                                                                                 ?>

                                                                            

                                 </div>

                                 <br>

                                 <br>

                                  <div class="uk-width-medium-4-10 uk-width-small-4-10">

                                    <br>

                                      <a href="#">

                                        <?php

                                        $con = connetti("my_canvass");

                                        $sql_estrai_img = "SELECT * FROM associa_foto_idea INNER JOIN foto ON associa_foto_idea.ID_foto=foto.ID_foto WHERE foto.cancellato = 'NO' AND ID_idea = '".$row['ID_idea']."';";

                                        //echo $sql_estrai_img."<br>";

                                        $row_estrai_img = esegui_query_stringa($con,$sql_estrai_img);

                                       // echo $row_estrai_img['percorso'];

                                        ?>

                                          <img class="uk-position-relative" src="<?php if($row_estrai_img['percorso']!='') echo "../".filtraOutput($row_estrai_img['percorso']); else echo "http://placehold.it/1200x720";?>" alt="" width="660" height="400">

                                      </a>

                                  </div>

                                

                                  <div class="uk-width-medium-3-10 uk-width-small-3-10">

                                      <h3><?php echo "<h2>".filtraOutput($row['titolo'])."</h2>"; ?></h3>

                                      <h4><?php echo "<i class='uk-icon-user'></i> <b>Autore:</b><br> ".filtraOutput($row['nome'])." ".filtraOutput($row['cognome']);?></h4>

                                      <p><?php 

                                      $dataDaConvertire = strtotime($row['data']);

                                      $data_corretta = date("d-m-Y H:i:s",$dataDaConvertire);

                                      echo "<i class = 'uk-icon-calendar'></i> <b>Data di pubblicazione:</b><br> ".$data_corretta;

                                      ?></p> 

                                      <h5>

                                              <?php 

                                              

                                              $sql_tema = "SELECT * FROM tema WHERE cancellato = 'NO' AND ID_tema = '".$row['ID_tema']."';";

                                              $row_tema = esegui_query_stringa($con,$sql_tema);

                                              echo "<i class='uk-icon-key'></i><b>Tema:</b> ".filtraOutput($row_tema['nome']); 

                                              

                                              ?>

                                      </h5> 

                                       <form action="visualizza_idea.php" method="post">  

                                            <input type="hidden" name="ID_idea" value="<?php echo filtraOutput($row['ID_idea'])?>">   

                                            <button class="uk-button uk-button-primary" type="submit">Vedi Dettagli &raquo;</button>

                                      </form>

                                  </div>

                                  <div class="uk-width-medium-1-10 uk-width-small-1-10"></div>

                              </div>

                              <!-- /.row -->

                              

                              <hr>





                      <?php

                        }//close while

                         

                      }//close if $num_row!=0       



                      ?>

        </div><!--chiudi div = 1 ECONOMIA VIRTUOSA-->              



         <div id="2">

          <?php



                    //         ---------------------------------->>>>>>>>>>>>>>>>>              CATEGORIA ECONOMIA VIRTUOSA   <<<<<----------------------------

                      $sql = "SELECT * FROM idee INNER JOIN utente ON idee.ID_utente=utente.ID_utente WHERE utente.cancellato='NO' AND idee.cancellato='NO' AND idee.ID_tema = '2' ORDER BY idee.voti DESC;";



                       $con = connetti("my_canvass") ;

                                             

                      $result=esegui_query($con,$sql);



                      /*conta numero righe*/

                      $num_row=calcola_righe_query($con,$sql);



                      //$i = 0;

                      if($num_row!=0){

                            while( $row=mysqli_fetch_assoc($result))

                            {



                                    



                                  ?>



                               <!-- Project One -->

                              <div class="uk-grid post_block_2">

                                <div class="uk-width-medium-1-10 uk-width-small-1-10"></div>

                                 <div class="uk-width-medium-1-10 uk-width-small-1-10">

                                  <br>

                                  <br>

                                                                  

                                                                                  <?php      



                                                                                 $con = connetti("my_canvass");

                                                                                 $sql2 = "SELECT COUNT(*) as conta FROM voto_idea WHERE ID_idea = '".filtraInput($con,$row['ID_idea'])."' AND ID_utente ='$ID_utente';";

                                                                                 $row2 = esegui_query_stringa($con,$sql2);

                                                                                 $sql_rank = "SELECT * FROM voto_idea WHERE ID_idea = '".filtraInput($con,$row['ID_idea'])."' AND ID_utente = '$ID_utente';";

                                                                                 $row_rank = esegui_query_stringa($con,$sql_rank);

                                                                                 $conta = 0;

                                                                                 $conta = $row2['conta'];



                                                                                

                                                                                 if($conta == 0)

                                                                                 {

                                                                                ?>

                                                                               

                                                                                 <form action="dashboard_idea_theme.php" method="post">

                                                                                 <input name="ID_utente" value="<?php echo filtraOutput($ID_utente);?>" type="hidden"> 

                                                                                 <input name="ID_idea" value="<?php echo filtraOutput($row['ID_idea']);?>" type="hidden">

                                                                                 <input name="token_rank_up" value="1" type="hidden"> 

                                                                                 <center><button class="uk-button uk-button-primary" type="submit">/\</button></center>

                                                                                 <br>

                                                                                 </form>

                                                                                 <?php

                                                                                 echo "<center><h3>".$row['voti']."</h3></center>";

                                                                                 ?>

                                                                                  <?php

                                                                                 if($row['voti']==1){

                                                                                 echo "<center>Voto</center>";

                                                                                 }

                                                                                 else

                                                                                 {

                                                                                 echo "<center>Voti</center>"; 

                                                                                 }

                                                                                 ?>

                                                                                 <form action="dashboard_idea_theme.php" method="post">

                                                                                  <br>

                                                                                 <input name="ID_utente" value="<?php echo filtraOutput($ID_utente);?>" type="hidden"> 

                                                                                 <input name="ID_idea" value="<?php echo  filtraOutput($row['ID_idea']);?>" type="hidden">

                                                                                 <input name="token_rank_down" value="1" type="hidden">  

                                                                                 <center><button class="uk-button uk-button-primary" type="submit">\/</button></center>

                                                                                 </form>

                                                                                 <?php  



                                                                                 }





                                                                                 else





                                                                                 {



                                                                                 ?> 



                                                                                 <form action="dashboard_idea_theme.php" method="post">

                                                                                 <input name="ID_utente" value="<?php echo filtraOutput($ID_utente);?>" type="hidden"> 

                                                                                 <input name="ID_idea" value="<?php echo filtraOutput($row['ID_idea']);?>" type="hidden">

                                                                                 <input name="token_cancella_rank" value="1" type="hidden"> 

                                                                                 <center><button type="submit" <?php if($row_rank['stato']=="up")echo "disabled"; else echo "";?> class="<?php if($row_rank['stato']=="up")echo "uk-button uk-button-primary";

                                                                                 else echo "uk-button uk-button-primary";?>">/\</button></center>

                                                                                 </form>

                                                                                 <br>

                                                                                 <?php

                                                                                 echo "<center><h3>".$row['voti']."</h3></center>";

                                                                                 ?>

                                                                                  <?php

                                                                                 if($row['voti']==1){

                                                                                 echo "<center>Voto</center>";

                                                                                 }

                                                                                 else

                                                                                 {

                                                                                 echo "<center>Voti</center>"; 

                                                                                 }

                                                                                 ?>

                                                                                 <form action="dashboard_idea_theme.php" method="post">

                                                                                  

                                                                                 <input name="ID_utente" value="<?php echo filtraOutput($ID_utente);?>" type="hidden"> 

                                                                                 <input name="ID_idea" value="<?php echo filtraOutput($row['ID_idea']);?>" type="hidden">

                                                                                 <input name="token_cancella_rank" value="1" type="hidden">  

                                                                                 <center><button type="submit" <?php if($row_rank['stato']=="down")echo "disabled"; else echo "";?> class="<?php if($row_rank['stato']=="down")echo "uk-button uk-button-primary";

                                                                                 else echo "uk-button uk-button-primary";?>">\/</button></center>

                                                                                 </form>





                                                                                 <?php









                                                                                 } 



                                                                                 ?>

                                                                            

                                 </div>

                                 <br>

                                 <br>

                                  <div class="uk-width-medium-4-10 uk-width-small-4-10">

                                    <br>

                                      <a href="#">

                                        <?php

                                        $con = connetti("my_canvass");

                                        $sql_estrai_img = "SELECT * FROM associa_foto_idea INNER JOIN foto ON associa_foto_idea.ID_foto=foto.ID_foto WHERE foto.cancellato = 'NO' AND ID_idea = '".$row['ID_idea']."';";

                                        //echo $sql_estrai_img."<br>";

                                        $row_estrai_img = esegui_query_stringa($con,$sql_estrai_img);

                                       // echo $row_estrai_img['percorso'];

                                        ?>

                                          <img class="uk-position-relative" src="<?php if($row_estrai_img['percorso']!='') echo "../".filtraOutput($row_estrai_img['percorso']); else echo "http://placehold.it/1200x720";?>" alt="" width="660" height="400">

                                      </a>

                                  </div>

                                

                                  <div class="uk-width-medium-3-10 uk-width-small-3-10">

                                      <h3><?php echo "<h2>".filtraOutput($row['titolo'])."</h2>"; ?></h3>

                                      <h4><?php echo "<i class='uk-icon-user'></i> <b>Autore:</b><br> ".filtraOutput($row['nome'])." ".filtraOutput($row['cognome']);?></h4>

                                      <p><?php 

                                      $dataDaConvertire = strtotime($row['data']);

                                      $data_corretta = date("d-m-Y H:i:s",$dataDaConvertire);

                                      echo "<i class = 'uk-icon-calendar'></i> <b>Data di pubblicazione:</b><br> ".$data_corretta;

                                      ?></p> 

                                      <h5>

                                              <?php 

                                              

                                              $sql_tema = "SELECT * FROM tema WHERE cancellato = 'NO' AND ID_tema = '".$row['ID_tema']."';";

                                              $row_tema = esegui_query_stringa($con,$sql_tema);

                                              echo "<i class='uk-icon-key'></i><b>Tema:</b> ".filtraOutput($row_tema['nome']); 

                                              

                                              ?>

                                      </h5> 

                                       <form action="visualizza_idea.php" method="post">  

                                            <input type="hidden" name="ID_idea" value="<?php echo filtraOutput($row['ID_idea'])?>">   

                                            <button class="uk-button uk-button-primary" type="submit">Vedi Dettagli &raquo;</button>

                                      </form>

                                  </div>

                                  <div class="uk-width-medium-1-10 uk-width-small-1-10"></div>

                              </div>

                              <!-- /.row -->

                              

                              <hr>





                      <?php

                        }//close while

                         

                      }//close if $num_row!=0       



                      ?>

        </div><!--chiudi div = 2 MOBILITA' SOSTENIBILE-->              

         <div id="3">

          <?php



                    //         ---------------------------------->>>>>>>>>>>>>>>>>              CATEGORIA ECONOMIA VIRTUOSA   <<<<<----------------------------

                      $sql = "SELECT * FROM idee INNER JOIN utente ON idee.ID_utente=utente.ID_utente WHERE utente.cancellato='NO' AND idee.cancellato='NO' AND idee.ID_tema = '3' ORDER BY idee.voti DESC;";



                       $con = connetti("my_canvass") ;

                                             

                      $result=esegui_query($con,$sql);



                      /*conta numero righe*/

                      $num_row=calcola_righe_query($con,$sql);



                      //$i = 0;

                      if($num_row!=0){

                            while( $row=mysqli_fetch_assoc($result))

                            {



                                    



                                  ?>



                               <!-- Project One -->

                              <div class="uk-grid post_block_2">

                                <div class="uk-width-medium-1-10 uk-width-small-1-10"></div>

                                 <div class="uk-width-medium-1-10 uk-width-small-1-10">

                                  <br>

                                  <br>

                                                                  

                                                                                  <?php      



                                                                                 $con = connetti("my_canvass");

                                                                                 $sql2 = "SELECT COUNT(*) as conta FROM voto_idea WHERE ID_idea = '".filtraInput($con,$row['ID_idea'])."' AND ID_utente ='$ID_utente';";

                                                                                 $row2 = esegui_query_stringa($con,$sql2);

                                                                                 $sql_rank = "SELECT * FROM voto_idea WHERE ID_idea = '".filtraInput($con,$row['ID_idea'])."' AND ID_utente = '$ID_utente';";

                                                                                 $row_rank = esegui_query_stringa($con,$sql_rank);

                                                                                 $conta = 0;

                                                                                 $conta = $row2['conta'];



                                                                                

                                                                                 if($conta == 0)

                                                                                 {

                                                                                ?>

                                                                               

                                                                                 <form action="dashboard_idea_theme.php" method="post">

                                                                                 <input name="ID_utente" value="<?php echo filtraOutput($ID_utente);?>" type="hidden"> 

                                                                                 <input name="ID_idea" value="<?php echo filtraOutput($row['ID_idea']);?>" type="hidden">

                                                                                 <input name="token_rank_up" value="1" type="hidden"> 

                                                                                 <center><button class="uk-button uk-button-primary" type="submit">/\</button></center>

                                                                                 <br>

                                                                                 </form>

                                                                                 <?php

                                                                                 echo "<center><h3>".$row['voti']."</h3></center>";

                                                                                 ?>

                                                                                  <?php

                                                                                 if($row['voti']==1){

                                                                                 echo "<center>Voto</center>";

                                                                                 }

                                                                                 else

                                                                                 {

                                                                                 echo "<center>Voti</center>"; 

                                                                                 }

                                                                                 ?>

                                                                                 <form action="dashboard_idea_theme.php" method="post">

                                                                                  <br>

                                                                                 <input name="ID_utente" value="<?php echo filtraOutput($ID_utente);?>" type="hidden"> 

                                                                                 <input name="ID_idea" value="<?php echo  filtraOutput($row['ID_idea']);?>" type="hidden">

                                                                                 <input name="token_rank_down" value="1" type="hidden">  

                                                                                 <center><button class="uk-button uk-button-primary" type="submit">\/</button></center>

                                                                                 </form>

                                                                                 <?php  



                                                                                 }





                                                                                 else





                                                                                 {



                                                                                 ?> 



                                                                                 <form action="dashboard_idea_theme.php" method="post">

                                                                                 <input name="ID_utente" value="<?php echo filtraOutput($ID_utente);?>" type="hidden"> 

                                                                                 <input name="ID_idea" value="<?php echo filtraOutput($row['ID_idea']);?>" type="hidden">

                                                                                 <input name="token_cancella_rank" value="1" type="hidden"> 

                                                                                 <center><button type="submit" <?php if($row_rank['stato']=="up")echo "disabled"; else echo "";?> class="<?php if($row_rank['stato']=="up")echo "uk-button uk-button-primary";

                                                                                 else echo "uk-button uk-button-primary";?>">/\</button></center>

                                                                                 </form>

                                                                                 <br>

                                                                                 <?php

                                                                                 echo "<center><h3>".$row['voti']."</h3></center>";

                                                                                 ?>

                                                                                  <?php

                                                                                 if($row['voti']==1){

                                                                                 echo "<center>Voto</center>";

                                                                                 }

                                                                                 else

                                                                                 {

                                                                                 echo "<center>Voti</center>"; 

                                                                                 }

                                                                                 ?>

                                                                                 <form action="dashboard_idea_theme.php" method="post">

                                                                                  

                                                                                 <input name="ID_utente" value="<?php echo filtraOutput($ID_utente);?>" type="hidden"> 

                                                                                 <input name="ID_idea" value="<?php echo filtraOutput($row['ID_idea']);?>" type="hidden">

                                                                                 <input name="token_cancella_rank" value="1" type="hidden">  

                                                                                 <center><button type="submit" <?php if($row_rank['stato']=="down")echo "disabled"; else echo "";?> class="<?php if($row_rank['stato']=="down")echo "uk-button uk-button-primary";

                                                                                 else echo "uk-button uk-button-primary";?>">\/</button></center>

                                                                                 </form>





                                                                                 <?php









                                                                                 } 



                                                                                 ?>

                                                                            

                                 </div>

                                 <br>

                                 <br>

                                  <div class="uk-width-medium-4-10 uk-width-small-4-10">

                                    <br>

                                      <a href="#">

                                        <?php

                                        $con = connetti("my_canvass");

                                        $sql_estrai_img = "SELECT * FROM associa_foto_idea INNER JOIN foto ON associa_foto_idea.ID_foto=foto.ID_foto WHERE foto.cancellato = 'NO' AND ID_idea = '".$row['ID_idea']."';";

                                        //echo $sql_estrai_img."<br>";

                                        $row_estrai_img = esegui_query_stringa($con,$sql_estrai_img);

                                       // echo $row_estrai_img['percorso'];

                                        ?>

                                          <img class="uk-position-relative" src="<?php if($row_estrai_img['percorso']!='') echo "../".filtraOutput($row_estrai_img['percorso']); else echo "http://placehold.it/1200x720";?>" alt="" width="660" height="400">

                                      </a>

                                  </div>

                                

                                  <div class="uk-width-medium-3-10 uk-width-small-3-10">

                                      <h3><?php echo "<h2>".filtraOutput($row['titolo'])."</h2>"; ?></h3>

                                      <h4><?php echo "<i class='uk-icon-user'></i> <b>Autore:</b><br> ".filtraOutput($row['nome'])." ".filtraOutput($row['cognome']);?></h4>

                                      <p><?php 

                                      $dataDaConvertire = strtotime($row['data']);

                                      $data_corretta = date("d-m-Y H:i:s",$dataDaConvertire);

                                      echo "<i class = 'uk-icon-calendar'></i> <b>Data di pubblicazione:</b><br> ".$data_corretta;

                                      ?></p> 

                                      <h5>

                                              <?php 

                                              

                                              $sql_tema = "SELECT * FROM tema WHERE cancellato = 'NO' AND ID_tema = '".$row['ID_tema']."';";

                                              $row_tema = esegui_query_stringa($con,$sql_tema);

                                              echo "<i class='uk-icon-key'></i><b>Tema:</b> ".filtraOutput($row_tema['nome']); 

                                              

                                              ?>

                                      </h5> 

                                       <form action="visualizza_idea.php" method="post">  

                                            <input type="hidden" name="ID_idea" value="<?php echo filtraOutput($row['ID_idea'])?>">   

                                            <button class="uk-button uk-button-primary" type="submit">Vedi Dettagli &raquo;</button>

                                      </form>

                                  </div>

                                  <div class="uk-width-medium-1-10 uk-width-small-1-10"></div>

                              </div>

                              <!-- /.row -->

                              

                              <hr>





                      <?php

                        }//close while

                         

                      }//close if $num_row!=0       



                      ?>

        </div><!--chiudi div = 3 CITTADINANZA ATTIVA-->    



         <div id="4">

          <?php



                    //         ---------------------------------->>>>>>>>>>>>>>>>>              CATEGORIA ECONOMIA VIRTUOSA   <<<<<----------------------------

                      $sql = "SELECT * FROM idee INNER JOIN utente ON idee.ID_utente=utente.ID_utente WHERE utente.cancellato='NO' AND idee.cancellato='NO' AND idee.ID_tema = '4' ORDER BY idee.voti DESC;";



                       $con = connetti("my_canvass") ;

                                             

                      $result=esegui_query($con,$sql);



                      /*conta numero righe*/

                      $num_row=calcola_righe_query($con,$sql);



                      //$i = 0;

                      if($num_row!=0){

                            while( $row=mysqli_fetch_assoc($result))

                            {



                                    



                                  ?>



                               <!-- Project One -->

                              <div class="uk-grid post_block_2">

                                <div class="uk-width-medium-1-10 uk-width-small-1-10"></div>

                                 <div class="uk-width-medium-1-10 uk-width-small-1-10">

                                  <br>

                                  <br>

                                                                  

                                                                                  <?php      



                                                                                 $con = connetti("my_canvass");

                                                                                 $sql2 = "SELECT COUNT(*) as conta FROM voto_idea WHERE ID_idea = '".filtraInput($con,$row['ID_idea'])."' AND ID_utente ='$ID_utente';";

                                                                                 $row2 = esegui_query_stringa($con,$sql2);

                                                                                 $sql_rank = "SELECT * FROM voto_idea WHERE ID_idea = '".filtraInput($con,$row['ID_idea'])."' AND ID_utente = '$ID_utente';";

                                                                                 $row_rank = esegui_query_stringa($con,$sql_rank);

                                                                                 $conta = 0;

                                                                                 $conta = $row2['conta'];



                                                                                

                                                                                 if($conta == 0)

                                                                                 {

                                                                                ?>

                                                                               

                                                                                 <form action="dashboard_idea_theme.php" method="post">

                                                                                 <input name="ID_utente" value="<?php echo filtraOutput($ID_utente);?>" type="hidden"> 

                                                                                 <input name="ID_idea" value="<?php echo filtraOutput($row['ID_idea']);?>" type="hidden">

                                                                                 <input name="token_rank_up" value="1" type="hidden"> 

                                                                                 <center><button class="uk-button uk-button-primary" type="submit">/\</button></center>

                                                                                 <br>

                                                                                 </form>

                                                                                 <?php

                                                                                 echo "<center><h3>".$row['voti']."</h3></center>";

                                                                                 ?>

                                                                                  <?php

                                                                                 if($row['voti']==1){

                                                                                 echo "<center>Voto</center>";

                                                                                 }

                                                                                 else

                                                                                 {

                                                                                 echo "<center>Voti</center>"; 

                                                                                 }

                                                                                 ?>

                                                                                 <form action="dashboard_idea_theme.php" method="post">

                                                                                  <br>

                                                                                 <input name="ID_utente" value="<?php echo filtraOutput($ID_utente);?>" type="hidden"> 

                                                                                 <input name="ID_idea" value="<?php echo  filtraOutput($row['ID_idea']);?>" type="hidden">

                                                                                 <input name="token_rank_down" value="1" type="hidden">  

                                                                                 <center><button class="uk-button uk-button-primary" type="submit">\/</button></center>

                                                                                 </form>

                                                                                 <?php  



                                                                                 }





                                                                                 else





                                                                                 {



                                                                                 ?> 



                                                                                 <form action="dashboard_idea_theme.php" method="post">

                                                                                 <input name="ID_utente" value="<?php echo filtraOutput($ID_utente);?>" type="hidden"> 

                                                                                 <input name="ID_idea" value="<?php echo filtraOutput($row['ID_idea']);?>" type="hidden">

                                                                                 <input name="token_cancella_rank" value="1" type="hidden"> 

                                                                                 <center><button type="submit" <?php if($row_rank['stato']=="up")echo "disabled"; else echo "";?> class="<?php if($row_rank['stato']=="up")echo "uk-button uk-button-primary";

                                                                                 else echo "uk-button uk-button-primary";?>">/\</button></center>

                                                                                 </form>

                                                                                 <br>

                                                                                 <?php

                                                                                 echo "<center><h3>".$row['voti']."</h3></center>";

                                                                                 ?>

                                                                                  <?php

                                                                                 if($row['voti']==1){

                                                                                 echo "<center>Voto</center>";

                                                                                 }

                                                                                 else

                                                                                 {

                                                                                 echo "<center>Voti</center>"; 

                                                                                 }

                                                                                 ?>

                                                                                 <form action="dashboard_idea_theme.php" method="post">

                                                                                  

                                                                                 <input name="ID_utente" value="<?php echo filtraOutput($ID_utente);?>" type="hidden"> 

                                                                                 <input name="ID_idea" value="<?php echo filtraOutput($row['ID_idea']);?>" type="hidden">

                                                                                 <input name="token_cancella_rank" value="1" type="hidden">  

                                                                                 <center><button type="submit" <?php if($row_rank['stato']=="down")echo "disabled"; else echo "";?> class="<?php if($row_rank['stato']=="down")echo "uk-button uk-button-primary";

                                                                                 else echo "uk-button uk-button-primary";?>">\/</button></center>

                                                                                 </form>





                                                                                 <?php









                                                                                 } 



                                                                                 ?>

                                                                            

                                 </div>

                                 <br>

                                 <br>

                                  <div class="uk-width-medium-4-10 uk-width-small-4-10">

                                    <br>

                                      <a href="#">

                                        <?php

                                        $con = connetti("my_canvass");

                                        $sql_estrai_img = "SELECT * FROM associa_foto_idea INNER JOIN foto ON associa_foto_idea.ID_foto=foto.ID_foto WHERE foto.cancellato = 'NO' AND ID_idea = '".$row['ID_idea']."';";

                                        //echo $sql_estrai_img."<br>";

                                        $row_estrai_img = esegui_query_stringa($con,$sql_estrai_img);

                                       // echo $row_estrai_img['percorso'];

                                        ?>

                                          <img class="uk-position-relative" src="<?php if($row_estrai_img['percorso']!='') echo "../".filtraOutput($row_estrai_img['percorso']); else echo "http://placehold.it/1200x720";?>" alt="" width="660" height="400">

                                      </a>

                                  </div>

                                

                                  <div class="uk-width-medium-3-10 uk-width-small-3-10">

                                      <h3><?php echo "<h2>".filtraOutput($row['titolo'])."</h2>"; ?></h3>

                                      <h4><?php echo "<i class='uk-icon-user'></i> <b>Autore:</b><br> ".filtraOutput($row['nome'])." ".filtraOutput($row['cognome']);?></h4>

                                      <p><?php 

                                      $dataDaConvertire = strtotime($row['data']);

                                      $data_corretta = date("d-m-Y H:i:s",$dataDaConvertire);

                                      echo "<i class = 'uk-icon-calendar'></i> <b>Data di pubblicazione:</b><br> ".$data_corretta;

                                      ?></p> 

                                      <h5>

                                              <?php 

                                              

                                              $sql_tema = "SELECT * FROM tema WHERE cancellato = 'NO' AND ID_tema = '".$row['ID_tema']."';";

                                              $row_tema = esegui_query_stringa($con,$sql_tema);

                                              echo "<i class='uk-icon-key'></i><b>Tema:</b> ".filtraOutput($row_tema['nome']); 

                                              

                                              ?>

                                      </h5> 

                                       <form action="visualizza_idea.php" method="post">  

                                            <input type="hidden" name="ID_idea" value="<?php echo filtraOutput($row['ID_idea'])?>">   

                                            <button class="uk-button uk-button-primary" type="submit">Vedi Dettagli &raquo;</button>

                                      </form>

                                  </div>

                                  <div class="uk-width-medium-1-10 uk-width-small-1-10"></div>

                              </div>

                              <!-- /.row -->

                              

                              <hr>





                      <?php

                        }//close while

                         

                      }//close if $num_row!=0       



                      ?>

        </div><!--chiudi div = 4 ISTRUZIONE E RICERCA-->  

         <div id="5">

          <?php



                    //         ---------------------------------->>>>>>>>>>>>>>>>>              CATEGORIA ECONOMIA VIRTUOSA   <<<<<----------------------------

                      $sql = "SELECT * FROM idee INNER JOIN utente ON idee.ID_utente=utente.ID_utente WHERE utente.cancellato='NO' AND idee.cancellato='NO' AND idee.ID_tema = '5' ORDER BY idee.voti DESC;";



                       $con = connetti("my_canvass") ;

                                             

                      $result=esegui_query($con,$sql);



                      /*conta numero righe*/

                      $num_row=calcola_righe_query($con,$sql);



                      //$i = 0;

                      if($num_row!=0){

                            while( $row=mysqli_fetch_assoc($result))

                            {



                                    



                                  ?>



                               <!-- Project One -->

                              <div class="uk-grid post_block_2">

                                <div class="uk-width-medium-1-10 uk-width-small-1-10"></div>

                                 <div class="uk-width-medium-1-10 uk-width-small-1-10">

                                  <br>

                                  <br>

                                                                  

                                                                                  <?php      



                                                                                 $con = connetti("my_canvass");

                                                                                 $sql2 = "SELECT COUNT(*) as conta FROM voto_idea WHERE ID_idea = '".filtraInput($con,$row['ID_idea'])."' AND ID_utente ='$ID_utente';";

                                                                                 $row2 = esegui_query_stringa($con,$sql2);

                                                                                 $sql_rank = "SELECT * FROM voto_idea WHERE ID_idea = '".filtraInput($con,$row['ID_idea'])."' AND ID_utente = '$ID_utente';";

                                                                                 $row_rank = esegui_query_stringa($con,$sql_rank);

                                                                                 $conta = 0;

                                                                                 $conta = $row2['conta'];



                                                                                

                                                                                 if($conta == 0)

                                                                                 {

                                                                                ?>

                                                                               

                                                                                 <form action="dashboard_idea_theme.php" method="post">

                                                                                 <input name="ID_utente" value="<?php echo filtraOutput($ID_utente);?>" type="hidden"> 

                                                                                 <input name="ID_idea" value="<?php echo filtraOutput($row['ID_idea']);?>" type="hidden">

                                                                                 <input name="token_rank_up" value="1" type="hidden"> 

                                                                                 <center><button class="uk-button uk-button-primary" type="submit">/\</button></center>

                                                                                 <br>

                                                                                 </form>

                                                                                 <?php

                                                                                 echo "<center><h3>".$row['voti']."</h3></center>";

                                                                                 ?>

                                                                                  <?php

                                                                                 if($row['voti']==1){

                                                                                 echo "<center>Voto</center>";

                                                                                 }

                                                                                 else

                                                                                 {

                                                                                 echo "<center>Voti</center>"; 

                                                                                 }

                                                                                 ?>

                                                                                 <form action="dashboard_idea_theme.php" method="post">

                                                                                  <br>

                                                                                 <input name="ID_utente" value="<?php echo filtraOutput($ID_utente);?>" type="hidden"> 

                                                                                 <input name="ID_idea" value="<?php echo  filtraOutput($row['ID_idea']);?>" type="hidden">

                                                                                 <input name="token_rank_down" value="1" type="hidden">  

                                                                                 <center><button class="uk-button uk-button-primary" type="submit">\/</button></center>

                                                                                 </form>

                                                                                 <?php  



                                                                                 }





                                                                                 else





                                                                                 {



                                                                                 ?> 



                                                                                 <form action="dashboard_idea_theme.php" method="post">

                                                                                 <input name="ID_utente" value="<?php echo filtraOutput($ID_utente);?>" type="hidden"> 

                                                                                 <input name="ID_idea" value="<?php echo filtraOutput($row['ID_idea']);?>" type="hidden">

                                                                                 <input name="token_cancella_rank" value="1" type="hidden"> 

                                                                                 <center><button type="submit" <?php if($row_rank['stato']=="up")echo "disabled"; else echo "";?> class="<?php if($row_rank['stato']=="up")echo "uk-button uk-button-primary";

                                                                                 else echo "uk-button uk-button-primary";?>">/\</button></center>

                                                                                 </form>

                                                                                 <br>

                                                                                 <?php

                                                                                 echo "<center><h3>".$row['voti']."</h3></center>";

                                                                                 ?>

                                                                                  <?php

                                                                                 if($row['voti']==1){

                                                                                 echo "<center>Voto</center>";

                                                                                 }

                                                                                 else

                                                                                 {

                                                                                 echo "<center>Voti</center>"; 

                                                                                 }

                                                                                 ?>

                                                                                 <form action="dashboard_idea_theme.php" method="post">

                                                                                  

                                                                                 <input name="ID_utente" value="<?php echo filtraOutput($ID_utente);?>" type="hidden"> 

                                                                                 <input name="ID_idea" value="<?php echo filtraOutput($row['ID_idea']);?>" type="hidden">

                                                                                 <input name="token_cancella_rank" value="1" type="hidden">  

                                                                                 <center><button type="submit" <?php if($row_rank['stato']=="down")echo "disabled"; else echo "";?> class="<?php if($row_rank['stato']=="down")echo "uk-button uk-button-primary";

                                                                                 else echo "uk-button uk-button-primary";?>">\/</button></center>

                                                                                 </form>





                                                                                 <?php









                                                                                 } 



                                                                                 ?>

                                                                            

                                 </div>

                                 <br>

                                 <br>

                                  <div class="uk-width-medium-4-10 uk-width-small-4-10">

                                    <br>

                                      <a href="#">

                                        <?php

                                        $con = connetti("my_canvass");

                                        $sql_estrai_img = "SELECT * FROM associa_foto_idea INNER JOIN foto ON associa_foto_idea.ID_foto=foto.ID_foto WHERE foto.cancellato = 'NO' AND ID_idea = '".$row['ID_idea']."';";

                                        //echo $sql_estrai_img."<br>";

                                        $row_estrai_img = esegui_query_stringa($con,$sql_estrai_img);

                                       // echo $row_estrai_img['percorso'];

                                        ?>

                                          <img class="uk-position-relative" src="<?php if($row_estrai_img['percorso']!='') echo "../".filtraOutput($row_estrai_img['percorso']); else echo "http://placehold.it/1200x720";?>" alt="" width="660" height="400">

                                      </a>

                                  </div>

                                

                                  <div class="uk-width-medium-3-10 uk-width-small-3-10">

                                      <h3><?php echo "<h2>".filtraOutput($row['titolo'])."</h2>"; ?></h3>

                                      <h4><?php echo "<i class='uk-icon-user'></i> <b>Autore:</b><br> ".filtraOutput($row['nome'])." ".filtraOutput($row['cognome']);?></h4>

                                      <p><?php 

                                      $dataDaConvertire = strtotime($row['data']);

                                      $data_corretta = date("d-m-Y H:i:s",$dataDaConvertire);

                                      echo "<i class = 'uk-icon-calendar'></i> <b>Data di pubblicazione:</b><br> ".$data_corretta;

                                      ?></p> 

                                      <h5>

                                              <?php 

                                              

                                              $sql_tema = "SELECT * FROM tema WHERE cancellato = 'NO' AND ID_tema = '".$row['ID_tema']."';";

                                              $row_tema = esegui_query_stringa($con,$sql_tema);

                                              echo "<i class='uk-icon-key'></i><b>Tema:</b> ".filtraOutput($row_tema['nome']); 

                                              

                                              ?>

                                      </h5> 

                                       <form action="visualizza_idea.php" method="post">  

                                            <input type="hidden" name="ID_idea" value="<?php echo filtraOutput($row['ID_idea'])?>">   

                                            <button class="uk-button uk-button-primary" type="submit">Vedi Dettagli &raquo;</button>

                                      </form>

                                  </div>

                                  <div class="uk-width-medium-1-10 uk-width-small-1-10"></div>

                              </div>

                              <!-- /.row -->

                              

                              <hr>





                      <?php

                        }//close while

                         

                      }//close if $num_row!=0       



                      ?>

        </div><!--chiudi div = 5 AMBIENTE ED ECOSOSTENIBILITA'-->                                    

         <div id="6">

          <?php



                    //         ---------------------------------->>>>>>>>>>>>>>>>>              CATEGORIA ECONOMIA VIRTUOSA   <<<<<----------------------------

                      $sql = "SELECT * FROM idee INNER JOIN utente ON idee.ID_utente=utente.ID_utente WHERE utente.cancellato='NO' AND idee.cancellato='NO' AND idee.ID_tema = '6' ORDER BY idee.voti DESC;";



                       $con = connetti("my_canvass") ;

                                             

                      $result=esegui_query($con,$sql);



                      /*conta numero righe*/

                      $num_row=calcola_righe_query($con,$sql);



                      //$i = 0;

                      if($num_row!=0){

                            while( $row=mysqli_fetch_assoc($result))

                            {



                                    



                                  ?>



                               <!-- Project One -->

                              <div class="uk-grid post_block_2">

                                <div class="uk-width-medium-1-10 uk-width-small-1-10"></div>

                                 <div class="uk-width-medium-1-10 uk-width-small-1-10">

                                  <br>

                                  <br>

                                                                  

                                                                                  <?php      



                                                                                 $con = connetti("my_canvass");

                                                                                 $sql2 = "SELECT COUNT(*) as conta FROM voto_idea WHERE ID_idea = '".filtraInput($con,$row['ID_idea'])."' AND ID_utente ='$ID_utente';";

                                                                                 $row2 = esegui_query_stringa($con,$sql2);

                                                                                 $sql_rank = "SELECT * FROM voto_idea WHERE ID_idea = '".filtraInput($con,$row['ID_idea'])."' AND ID_utente = '$ID_utente';";

                                                                                 $row_rank = esegui_query_stringa($con,$sql_rank);

                                                                                 $conta = 0;

                                                                                 $conta = $row2['conta'];



                                                                                

                                                                                 if($conta == 0)

                                                                                 {

                                                                                ?>

                                                                               

                                                                                 <form action="dashboard_idea_theme.php" method="post">

                                                                                 <input name="ID_utente" value="<?php echo filtraOutput($ID_utente);?>" type="hidden"> 

                                                                                 <input name="ID_idea" value="<?php echo filtraOutput($row['ID_idea']);?>" type="hidden">

                                                                                 <input name="token_rank_up" value="1" type="hidden"> 

                                                                                 <center><button class="uk-button uk-button-primary" type="submit">/\</button></center>

                                                                                 <br>

                                                                                 </form>

                                                                                 <?php

                                                                                 echo "<center><h3>".$row['voti']."</h3></center>";

                                                                                 ?>

                                                                                  <?php

                                                                                 if($row['voti']==1){

                                                                                 echo "<center>Voto</center>";

                                                                                 }

                                                                                 else

                                                                                 {

                                                                                 echo "<center>Voti</center>"; 

                                                                                 }

                                                                                 ?>

                                                                                 <form action="dashboard_idea_theme.php" method="post">

                                                                                  <br>

                                                                                 <input name="ID_utente" value="<?php echo filtraOutput($ID_utente);?>" type="hidden"> 

                                                                                 <input name="ID_idea" value="<?php echo  filtraOutput($row['ID_idea']);?>" type="hidden">

                                                                                 <input name="token_rank_down" value="1" type="hidden">  

                                                                                 <center><button class="uk-button uk-button-primary" type="submit">\/</button></center>

                                                                                 </form>

                                                                                 <?php  



                                                                                 }





                                                                                 else





                                                                                 {



                                                                                 ?> 



                                                                                 <form action="dashboard_idea_theme.php" method="post">

                                                                                 <input name="ID_utente" value="<?php echo filtraOutput($ID_utente);?>" type="hidden"> 

                                                                                 <input name="ID_idea" value="<?php echo filtraOutput($row['ID_idea']);?>" type="hidden">

                                                                                 <input name="token_cancella_rank" value="1" type="hidden"> 

                                                                                 <center><button type="submit" <?php if($row_rank['stato']=="up")echo "disabled"; else echo "";?> class="<?php if($row_rank['stato']=="up")echo "uk-button uk-button-primary";

                                                                                 else echo "uk-button uk-button-primary";?>">/\</button></center>

                                                                                 </form>

                                                                                 <br>

                                                                                 <?php

                                                                                 echo "<center><h3>".$row['voti']."</h3></center>";

                                                                                 ?>

                                                                                  <?php

                                                                                 if($row['voti']==1){

                                                                                 echo "<center>Voto</center>";

                                                                                 }

                                                                                 else

                                                                                 {

                                                                                 echo "<center>Voti</center>"; 

                                                                                 }

                                                                                 ?>

                                                                                 <form action="dashboard_idea_theme.php" method="post">

                                                                                  

                                                                                 <input name="ID_utente" value="<?php echo filtraOutput($ID_utente);?>" type="hidden"> 

                                                                                 <input name="ID_idea" value="<?php echo filtraOutput($row['ID_idea']);?>" type="hidden">

                                                                                 <input name="token_cancella_rank" value="1" type="hidden">  

                                                                                 <center><button type="submit" <?php if($row_rank['stato']=="down")echo "disabled"; else echo "";?> class="<?php if($row_rank['stato']=="down")echo "uk-button uk-button-primary";

                                                                                 else echo "uk-button uk-button-primary";?>">\/</button></center>

                                                                                 </form>





                                                                                 <?php









                                                                                 } 



                                                                                 ?>

                                                                            

                                 </div>

                                 <br>

                                 <br>

                                  <div class="uk-width-medium-4-10 uk-width-small-4-10">

                                    <br>

                                      <a href="#">

                                        <?php

                                        $con = connetti("my_canvass");

                                        $sql_estrai_img = "SELECT * FROM associa_foto_idea INNER JOIN foto ON associa_foto_idea.ID_foto=foto.ID_foto WHERE foto.cancellato = 'NO' AND ID_idea = '".$row['ID_idea']."';";

                                        //echo $sql_estrai_img."<br>";

                                        $row_estrai_img = esegui_query_stringa($con,$sql_estrai_img);

                                       // echo $row_estrai_img['percorso'];

                                        ?>

                                          <img class="uk-position-relative" src="<?php if($row_estrai_img['percorso']!='') echo "../".filtraOutput($row_estrai_img['percorso']); else echo "http://placehold.it/1200x720";?>" alt="" width="660" height="400">

                                      </a>

                                  </div>

                                

                                  <div class="uk-width-medium-3-10 uk-width-small-3-10">

                                      <h3><?php echo "<h2>".filtraOutput($row['titolo'])."</h2>"; ?></h3>

                                      <h4><?php echo "<i class='uk-icon-user'></i> <b>Autore:</b><br> ".filtraOutput($row['nome'])." ".filtraOutput($row['cognome']);?></h4>

                                      <p><?php 

                                      $dataDaConvertire = strtotime($row['data']);

                                      $data_corretta = date("d-m-Y H:i:s",$dataDaConvertire);

                                      echo "<i class = 'uk-icon-calendar'></i> <b>Data di pubblicazione:</b><br> ".$data_corretta;

                                      ?></p> 

                                      <h5>

                                              <?php 

                                              

                                              $sql_tema = "SELECT * FROM tema WHERE cancellato = 'NO' AND ID_tema = '".$row['ID_tema']."';";

                                              $row_tema = esegui_query_stringa($con,$sql_tema);

                                              echo "<i class='uk-icon-key'></i><b>Tema:</b> ".filtraOutput($row_tema['nome']); 

                                              

                                              ?>

                                      </h5> 

                                       <form action="visualizza_idea.php" method="post">  

                                            <input type="hidden" name="ID_idea" value="<?php echo filtraOutput($row['ID_idea'])?>">   

                                            <button class="uk-button uk-button-primary" type="submit">Vedi Dettagli &raquo;</button>

                                      </form>

                                  </div>

                                  <div class="uk-width-medium-1-10 uk-width-small-1-10"></div>

                              </div>

                              <!-- /.row -->

                              

                              <hr>





                      <?php

                        }//close while

                         

                      }//close if $num_row!=0       



                      ?>

        </div><!--chiudi div = 6 INNOVAZIONE--> 

         <div id="7">

          <?php



                    //         ---------------------------------->>>>>>>>>>>>>>>>>              CATEGORIA ALTRO   <<<<<----------------------------

                      $sql = "SELECT * FROM idee INNER JOIN utente ON idee.ID_utente=utente.ID_utente WHERE utente.cancellato='NO' AND idee.cancellato='NO' AND idee.ID_tema = '7' ORDER BY idee.voti DESC,idee.data DESC;";



                       $con = connetti("my_canvass") ;

                                             

                      $result=esegui_query($con,$sql);



                      /*conta numero righe*/

                      $num_row=calcola_righe_query($con,$sql);



                      //$i = 0;

                      if($num_row!=0){

                            while( $row=mysqli_fetch_assoc($result))

                            {



                                    



                                  ?>



                               <!-- Project One -->

                              <div class="uk-grid post_block_2">

                                <div class="uk-width-medium-1-10 uk-width-small-1-10"></div>

                                 <div class="uk-width-medium-1-10 uk-width-small-1-10">

                                  <br>

                                  <br>

                                                                  

                                                                                  <?php      



                                                                                 $con = connetti("my_canvass");

                                                                                 $sql2 = "SELECT COUNT(*) as conta FROM voto_idea WHERE ID_idea = '".filtraInput($con,$row['ID_idea'])."' AND ID_utente ='$ID_utente';";

                                                                                 $row2 = esegui_query_stringa($con,$sql2);

                                                                                 $sql_rank = "SELECT * FROM voto_idea WHERE ID_idea = '".filtraInput($con,$row['ID_idea'])."' AND ID_utente = '$ID_utente';";

                                                                                 $row_rank = esegui_query_stringa($con,$sql_rank);

                                                                                 $conta = 0;

                                                                                 $conta = $row2['conta'];



                                                                                

                                                                                 if($conta == 0)

                                                                                 {

                                                                                ?>

                                                                               

                                                                                 <form action="dashboard_idea_theme.php" method="post">

                                                                                 <input name="ID_utente" value="<?php echo filtraOutput($ID_utente);?>" type="hidden"> 

                                                                                 <input name="ID_idea" value="<?php echo filtraOutput($row['ID_idea']);?>" type="hidden">

                                                                                 <input name="token_rank_up" value="1" type="hidden"> 

                                                                                 <center><button class="uk-button uk-button-primary" type="submit">/\</button></center>

                                                                                 <br>

                                                                                 </form>

                                                                                 <?php

                                                                                 echo "<center><h3>".$row['voti']."</h3></center>";

                                                                                 ?>

                                                                                  <?php

                                                                                 if($row['voti']==1){

                                                                                 echo "<center>Voto</center>";

                                                                                 }

                                                                                 else

                                                                                 {

                                                                                 echo "<center>Voti</center>"; 

                                                                                 }

                                                                                 ?>

                                                                                 <form action="dashboard_idea_theme.php" method="post">

                                                                                  <br>

                                                                                 <input name="ID_utente" value="<?php echo filtraOutput($ID_utente);?>" type="hidden"> 

                                                                                 <input name="ID_idea" value="<?php echo  filtraOutput($row['ID_idea']);?>" type="hidden">

                                                                                 <input name="token_rank_down" value="1" type="hidden">  

                                                                                 <center><button class="uk-button uk-button-primary" type="submit">\/</button></center>

                                                                                 </form>

                                                                                 <?php  



                                                                                 }





                                                                                 else





                                                                                 {



                                                                                 ?> 



                                                                                 <form action="dashboard_idea_theme.php" method="post">

                                                                                 <input name="ID_utente" value="<?php echo filtraOutput($ID_utente);?>" type="hidden"> 

                                                                                 <input name="ID_idea" value="<?php echo filtraOutput($row['ID_idea']);?>" type="hidden">

                                                                                 <input name="token_cancella_rank" value="1" type="hidden"> 

                                                                                 <center><button type="submit" <?php if($row_rank['stato']=="up")echo "disabled"; else echo "";?> class="<?php if($row_rank['stato']=="up")echo "uk-button uk-button-primary";

                                                                                 else echo "uk-button uk-button-primary";?>">/\</button></center>

                                                                                 </form>

                                                                                 <br>

                                                                                 <?php

                                                                                 echo "<center><h3>".$row['voti']."</h3></center>";

                                                                                 ?>

                                                                                  <?php

                                                                                 if($row['voti']==1){

                                                                                 echo "<center>Voto</center>";

                                                                                 }

                                                                                 else

                                                                                 {

                                                                                 echo "<center>Voti</center>"; 

                                                                                 }

                                                                                 ?>

                                                                                 <form action="dashboard_idea_theme.php" method="post">

                                                                                  

                                                                                 <input name="ID_utente" value="<?php echo filtraOutput($ID_utente);?>" type="hidden"> 

                                                                                 <input name="ID_idea" value="<?php echo filtraOutput($row['ID_idea']);?>" type="hidden">

                                                                                 <input name="token_cancella_rank" value="1" type="hidden">  

                                                                                 <center><button type="submit" <?php if($row_rank['stato']=="down")echo "disabled"; else echo "";?> class="<?php if($row_rank['stato']=="down")echo "uk-button uk-button-primary";

                                                                                 else echo "uk-button uk-button-primary";?>">\/</button></center>

                                                                                 </form>





                                                                                 <?php









                                                                                 } 



                                                                                 ?>

                                                                            

                                 </div>

                                 <br>

                                 <br>

                                  <div class="uk-width-medium-4-10 uk-width-small-4-10">

                                    <br>

                                      <a href="#">

                                        <?php

                                        $con = connetti("my_canvass");

                                        $sql_estrai_img = "SELECT * FROM associa_foto_idea INNER JOIN foto ON associa_foto_idea.ID_foto=foto.ID_foto WHERE foto.cancellato = 'NO' AND ID_idea = '".$row['ID_idea']."';";

                                        //echo $sql_estrai_img."<br>";

                                        $row_estrai_img = esegui_query_stringa($con,$sql_estrai_img);

                                       // echo $row_estrai_img['percorso'];

                                        ?>

                                          <img class="uk-position-relative" src="<?php if($row_estrai_img['percorso']!='') echo "../".filtraOutput($row_estrai_img['percorso']); else echo "http://placehold.it/1200x720";?>" alt="" width="660" height="400">

                                      </a>

                                  </div>

                                

                                  <div class="uk-width-medium-3-10 uk-width-small-3-10">

                                      <h3><?php echo "<h2>".filtraOutput($row['titolo'])."</h2>"; ?></h3>

                                      <h4><?php echo "<i class='uk-icon-user'></i> <b>Autore:</b><br> ".filtraOutput($row['nome'])." ".filtraOutput($row['cognome']);?></h4>

                                      <p><?php 

                                      $dataDaConvertire = strtotime($row['data']);

                                      $data_corretta = date("d-m-Y H:i:s",$dataDaConvertire);

                                      echo "<i class = 'uk-icon-calendar'></i> <b>Data di pubblicazione:</b><br> ".$data_corretta;

                                      ?></p> 

                                      <h5>

                                              <?php 

                                              

                                              $sql_tema = "SELECT * FROM tema WHERE cancellato = 'NO' AND ID_tema = '".$row['ID_tema']."';";

                                              $row_tema = esegui_query_stringa($con,$sql_tema);

                                              echo "<i class='uk-icon-key'></i><b>Tema:</b> ".filtraOutput($row_tema['nome']); 

                                              

                                              ?>

                                      </h5> 

                                       <form action="visualizza_idea.php" method="post">  

                                            <input type="hidden" name="ID_idea" value="<?php echo filtraOutput($row['ID_idea'])?>">   

                                            <button class="uk-button uk-button-primary" type="submit">Vedi Dettagli &raquo;</button>

                                      </form>

                                  </div>

                                  <div class="uk-width-medium-1-10 uk-width-small-1-10"></div>

                              </div>

                              <!-- /.row -->

                              

                              <hr>





                      <?php

                        }//close while

                         

                      }//close if $num_row!=0       



                      ?>

        </div><!--chiudi div = 1 ECONOMIA VIRTUOSA-->                           



        </div><!-- chiudi div uk-width-4-6 -->

        <div class="uk-width-medium-1-6 uk-width-small-1-6"></div> 

        </div><!-- chiudi la griglia  della tabella--> 

        



        </div><!-- chiudi il container della pagina html--> 



        <script type="text/javascript">



$(document).ready(function(){





        $("#1").show();

        $("#2").hide();

        $("#3").hide();

        $("#4").hide();

        $("#5").hide();

        $("#6").hide();

        $("#7").hide();









    $("#economy").click(function(){

        $("#1").show();

        $("#2").hide();

        $("#3").hide();

        $("#4").hide();

        $("#5").hide();

        $("#6").hide();

        $("#7").hide();



    });



    $("#mobility").click(function(){

        $("#1").hide();

        $("#2").show();

        $("#3").hide();

        $("#4").hide();

        $("#5").hide();

        $("#6").hide();

        $("#7").hide();

    });



    $("#citizen").click(function(){

       $("#1").hide();

       $("#2").hide();

       $("#3").show();

       $("#4").hide();

       $("#5").hide();

       $("#6").hide();

       $("#7").hide();

    });



    $("#school").click(function(){

       $("#1").hide();

       $("#2").hide();

       $("#3").hide();

       $("#4").show();

       $("#5").hide();

       $("#6").hide();

       $("#7").hide(); 

    });





    $("#ambient").click(function(){

       $("#1").hide();

       $("#2").hide();

       $("#3").hide();

       $("#4").hide();

       $("#5").show();

       $("#6").hide();

       $("#7").hide();

    });



      $("#innovation").click(function(){

       $("#1").hide();

       $("#2").hide();

       $("#3").hide();

       $("#4").hide();

       $("#5").hide();

       $("#6").show();

       $("#7").hide();

    });



      $("#other").click(function(){

       $("#1").hide();

       $("#2").hide();

       $("#3").hide();

       $("#4").hide();

       $("#5").hide();

       $("#6").hide();

       $("#7").show();

    });  



     /* $('li').click(function() {

        $(this).siblings('li').removeClass('active');

        $(this).addClass('active');

    }); */



});





</script>







<?php

}

else

{



//redirect 

// remove all session variables

session_unset(); 



// destroy the session 

session_destroy(); 



header("location:../home.php");



}





?>            

    </body>

</html> 