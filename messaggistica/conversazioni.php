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





        <?php   // CREO IL MENU LATERALE CON LE OPZIONI PER GESTIRE IL SISTEMA DI MESSAGGISTICA ?>





           <div id="menu_msg" class="uk-offcanvas">

            <div class="uk-offcanvas-bar">

                 <ul class="uk-nav uk-nav-offcanvas uk-nav-parent-icon" data-uk-nav>

                 

                     <li>

                        <a href="../messaggistica/home.php">Home</a>

                     </li>

                     <li>

                        <a href="../messaggistica/scrivi.php">Scrivi</a>

                     </li>

                     <li>

                        <a href="../messaggistica/inviati.php">Inviati</a>

                     </li>

                    <li>

                        <a href="../messaggistica/ricevuti.php">Ricevuti</a>

                    </li>

                    <li>

                        <a href="../messaggistica/conversazioni.php">Conversazioni</a>

                    </li>

                    <li>

                        <a href="../messaggistica/archiviati.php">Archiviati</a>

                    </li>

                     <li>

                        <a href="../messaggistica/cestino.php">Cestino</a>

                    </li>

                </ul>

            </div>

        </div>





        <?php   //  --------------------------------------- AREA DEI TOKEN DI ESECUZIONE -------------------------------------------?>



          <?php

              if(isset($_POST['token_archivia_conversazione2'])){



                $con =connetti("my_canvass");



                $sql3 ="UPDATE messaggio_gruppo SET archiviato='SI' WHERE ID_messaggio_gruppo='".filtraInput($con,$_POST['ID_messaggio_gruppo'])."';";

               //echo $sql3."<br>";

                $rs =esegui_query($con,$sql3);



                if($rs!=0)

                {



              ?>



               <br>

                <br>

                <center>

                <div class="uk-grid">

                 <div class="uk-width-medium-1-4 uk-width-small-1-4"></div>

                 <div class="uk-width-medium-2-4 uk-width-small-2-4">

                    <div class="uk-alert uk-alert-success" data-uk-alert>

                      <a href="" class="uk-alert-close uk-close"></a>

                      <strong>Conversazione Archiviata!</strong>

                     <p align="center"> Questa conversazione e' stata spostata in archivio.</p>

                    <br>

                    </div>

                 </div> 

                 <div class="uk-width-medium-1-4 uk-width-small-1-4"></div>

                 </div> 

                </center>

                <br>

                <br>   

              <?php

                }//close if mysqli_query

              }//close if di token_cancella 2



              ?>





              <?php 



                if(isset($_POST['token_archivia_conversazione']))

                {



                  ?>



                               <br>

                      <br>

                      <center>

                      <div class="uk-grid">

                       <div class="uk-width-medium-1-4 uk-width-small-1-4"></div>

                       <div class="uk-width-medium-2-4 uk-width-small-2-4">

                          <div class="uk-alert uk-alert-info" data-uk-alert>

                            <a href="" class="uk-alert-close uk-close"></a>

                            <strong>Vuoi veramente archiviare questa conversazione?</strong>

                            <p align="center"> L'operazione sara' irreversibile!</p>

                            <br>

                            <br>

                            <div class="uk-grid">

                              <div class="uk-width-medium-4-10 uk-width-small-4-10"></div>

                              <div class="uk-width-medium-1-10 uk-width-small-1-10">

                                   <form action="conversazioni.php" method="post">

                                        <input type="hidden" name="ID_messaggio" value="<?php echo filtraOutput($_POST['ID_messaggio']);?>">  

                                      <input type='hidden' name='ID_messaggio_gruppo' value="<?php echo filtraOutput($_POST['ID_messaggio_gruppo']);?>">

                                      <input type='hidden' name='token_archivia_conversazione2' value="1">

                                     <button class='uk-button uk-button-danger' type='submit'>SI</button>

                                  </form> 

                              </div>

                              <div class="uk-width-medium-1-10 uk-width-small-1-10">

                                  <form action="conversazioni.php" method="post">

                                     <button class='uk-button uk-button-primary' type='submit'>NO</button>

                                    </form> 

                              </div>

                              <div class="uk-width-medium-4-10 uk-width-small-4-10"></div>

                            </div>  

                          </div>

                       </div> 

                       <div class="uk-width-medium-1-4 uk-width-small-1-4"></div>

                       </div> 

                      </center>

                      <br>

                      <br>





                  <?php

                }







              ?>









        <div class="uk-grid">

        <div class="uk-width-medium-1-6 uk-width-small-1-6"></div> 

        <div class="uk-width-medium-4-6 uk-width-small-4-6">

       

        <center><h2>Messaggistica - Conversazioni</h2></center>

        <br>

        <div class="uk-form">

            <button class="uk-button uk-button-primary" data-uk-offcanvas="{target:'#menu_msg'}"><i class="uk-icon-bars"></i> Menu' messaggi</button>

        </div>  

        <br>

         <div class="uk-overflow-container">    

        <table class="uk-table">

      

        <thead>

            <tr>

                <th>Mittente</th><th>Oggetto</th><th>Data Invio</th><th>Apri</th><th>Archivia</th>

            </tr>

       </thead>         

        <tbody>

                 <?php



                    $con = connetti("my_canvass");



                    $sql_estrai = "SELECT * FROM messaggio_gruppo WHERE cancellato = 'NO' AND archiviato = 'NO';";

                    $ris_estrai = esegui_query($con,$sql_estrai);



                    while($row_estrai = mysqli_fetch_assoc($ris_estrai))

                    { 



                    $sql_visualizza = "SELECT * FROM messaggio WHERE destinatario = '$ID_utente' AND ID_messaggio_gruppo = '".$row_estrai['ID_messaggio_gruppo']."' GROUP BY ID_messaggio_gruppo ORDER BY ID_messaggio DESC;";

                    $ris_visualizza = esegui_query($con,$sql_visualizza);

                    //echo $sql_visualizza;



                  ?>  



                  

                  <?php



                  while($row = mysqli_fetch_assoc($ris_visualizza))

                  {



                  $data = strtotime($row['dataInvio']);

                  $data_corretta = date("d-m-Y H:i:s",$data); 

                  $sql_mittente = "SELECT * FROM utente WHERE ID_utente = '".$row['mittente']."';";

                  $row_mittente = esegui_query_stringa($con,$sql_mittente);



                  ?>  

                  <tr><td><?php echo $row_mittente['nome']." ".$row_mittente['cognome'];?></td><td><?php echo $row['oggetto'];?></td><td><?php echo $data_corretta;?></td>

                  <td>

                    

                    <form action="apriConversazione.php" method="post">

                      <input type="hidden" name="ID_messaggio_gruppo" value="<?php echo $row['ID_messaggio_gruppo'];?>">

                      <input type="hidden" name="ID_messaggio" value="<?php echo $row['ID_messaggio'];?>">

                      <input type="hidden" name="ID_utente" value="<?php echo $ID_utente; ?>">

                      <button class="uk-button uk-button-success" type="submit"><i class="uk-icon-search"></i></button>

                    </form>

                  </td>

                  <td>

                  

                    <form action="conversazioni.php" method="post">

                      <input type="hidden" name="ID_messaggio_gruppo" value="<?php echo $row['ID_messaggio_gruppo'];?>">

                      <input type="hidden" name="ID_messaggio" value="<?php echo $row['ID_messaggio'];?>">

                      <input type="hidden" name="token_archivia_conversazione" value="1">

                      <button class="uk-button uk-button-primary" type="submit"><i class="uk-icon-folder-open"></i></button>

                    </form>

                  </td>

                  </tr> 

                      <?php

                    }//close while $ris_visualizza





                  ?>  



                  <?php



                  }//close $ris_estrai  



                  ?>

        </tbody>

        </table>

        </div><!--chiudi containere overflow della tabella-->       

        



        </div><!-- chiudi div uk-width-4-6 -->

        <div class="uk-width-medium-1-6 uk-width-small-1-6"></div> 

        </div><!-- chiudi la griglia  della tabella--> 

        



        </div><!-- chiudi il container della pagina html--> 

<?php
      
       include dirname(__FILE__)."/../include/components/footer.php";
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