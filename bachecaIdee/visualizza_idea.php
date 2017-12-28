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

          $ID_idea = pulisci($_POST['ID_idea']);

            $stato = 1;

            if($ID_idea!="")

              $stato = 0;

            if($stato==1)

            header("location:dashboard_idea.php");

        

          





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

                        <a class="uk-navbar-brand uk-hidden-small" href="home.php">Canvass</a>

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





        <?php   //  --------------------------------------- AREA DEI TOKEN DI ESECUZIONE -------------------------------------------?>



        <?php



          if(isset($_POST['token_rimuovi_in_agenda']))

          {



          $con = connetti("my_canvass");

          //$ID_utente = $_POST['ID_utente'];

          $ID_idea = pulisci($_POST['ID_idea']);

          $ID_eletto = pulisci($_POST['ID_eletto']);



          $sql = "DELETE FROM associa_idee_eletto WHERE ID_idea ='$ID_idea' AND ID_eletto='$ID_eletto';";

          $ris = esegui_query($con,$sql);



          ?>

         

            <br>

              <br>

              <center>

              <div class="uk-grid">

               <div class="uk-width-medium-1-4 uk-width-small-1-4"></div>

               <div class="uk-width-medium-2-4 uk-width-small-2-4">

                  <div class="uk-alert uk-alert-success" data-uk-alert>

                    <a href="" class="uk-alert-close uk-close"></a>

                     <strong>Idea Rimossa!</strong>

                     <p align="center"> Questa idea e' stata rimossa correttamente nella tua agenda personale.</p>

                  </div>

               </div> 

               <div class="uk-width-medium-1-4 uk-width-small-1-4"></div>

               </div> 

              </center>

              <br>

              <br> 





          <?php





          }//close token cancella idea dall'agenda 2



          ?>

 



        <?php



          if(isset($_POST['token_inserisci_in_agenda']))

          {



          $con = connetti("my_canvass");

          $ID_idea = pulisci($_POST['ID_idea']);

          $ID_eletto = pulisci($_POST['ID_eletto']);

          $sql_proponente = "SELECT * FROM idee WHERE cancellato = 'NO' AND ID_idea = '$ID_idea';";

          $row_proponente = esegui_query_stringa($con, $sql_proponente);

          $ID_proponente = $row_proponente['ID_utente'];



          $sql = "INSERT INTO associa_idee_eletto(ID_idea,ID_eletto) VALUES ('$ID_idea','$ID_eletto');";

          $ris = esegui_query($con,$sql);



          $sql_inserisci_notifica_idea = "INSERT INTO notifica(ID_utente,tipo)VALUES('$ID_proponente','Idea');";

          $ris_inserisci_notifica_idea = esegui_query($con,$sql_inserisci_notifica_idea);

          $ID_notifica_ultima = mysqli_insert_id($con);



          $sql_associa_notifica_idea = "INSERT INTO associa_notifica_idea(ID_notifica,ID_idea,ID_utente) VALUES('$ID_notifica_ultima','$ID_idea','$ID_utente');";

          $ris_associa_notifica_idea = esegui_query($con, $sql_associa_notifica_idea);

          ?>



              <br>

              <br>

              <center>

              <div class="uk-grid">

               <div class="uk-width-medium-1-4 uk-width-small-1-4"></div>

               <div class="uk-width-medium-2-4 uk-width-small-2-4">

                  <div class="uk-alert uk-alert-success" data-uk-alert>

                    <a href="" class="uk-alert-close uk-close"></a>

                     <strong>Idea Aggiunta!</strong>

                     <p align="center"> Questa idea e' stata aggiunta correttamente nella tua agenda personale.</p>

                  </div>

               </div> 

               <div class="uk-width-medium-1-4 uk-width-small-1-4"></div>

               </div> 

              </center>

              <br>

              <br> 



          <?php

          }//close token_inserisci idea in agenda membro istituzione





          ?>





        <?php



                if(isset($_POST['token_invia_suggerimento']))

                {



                  $ID_idea = pulisci($_POST['ID_idea']);

                  $testo = pulisci($_POST['testo']);



                  $con = connetti("my_canvass");

                  $sql_proponente = "SELECT * FROM idee WHERE cancellato = 'NO' AND ID_idea = '$ID_idea';";

                  $row_proponente = esegui_query_stringa($con, $sql_proponente);

                  $ID_proponente = pulisci($row_proponente['ID_utente']);

                  

                  $con = connetti("my_canvass");

                  $sql_inserisci_suggerimento = "INSERT INTO suggerimento(ID_utente,testo)VALUES('$ID_utente','$testo');";

                  $ris_inserisci_suggerimento = esegui_query($con,$sql_inserisci_suggerimento);



                  $ID_suggerimento_ultimo = mysqli_insert_id($con);



                  $sql_associa_suggerimento_idea = "INSERT INTO associa_suggerimento_idea(ID_suggerimento,ID_idea) VALUES ('$ID_suggerimento_ultimo','$ID_idea');";

                  $ris_associa_suggerimento_idea = esegui_query($con,$sql_associa_suggerimento_idea);



                  //sistema di creazione notifica in uscita verso il proponente dell'idea affinchè visualizza la notifica dell'utente 

                  //che scriva la notifica su loggato con l'account corrente

                  

                  $sql_notifica_sugg = "INSERT INTO notifica(ID_utente,tipo) VALUES ('$ID_proponente','Suggerimento');";

                                           $ris_notifica_sugg = esegui_query($con, $sql_notifica_sugg);



                                           $ID_notifica_ultima = mysqli_insert_id($con);



                                           $sql_associa_notifica_suggerimento = "INSERT INTO associa_notifica_suggerimento(ID_notifica,ID_suggerimento,ID_utente) VALUES ('$ID_notifica_ultima','$ID_suggerimento_ultimo','$ID_utente');";

                                           $ris_associa_notifica_suggerimento = esegui_query($con, $sql_associa_notifica_suggerimento);





                  ?>

                        <br>

                        <br>

                        <center>

                        <div class="uk-grid">

                         <div class="uk-width-medium-1-4 uk-width-small-1-4"></div>

                         <div class="uk-width-medium-2-4 uk-width-small-2-4">

                            <div class="uk-alert uk-alert-success" data-uk-alert>

                              <a href="" class="uk-alert-close uk-close"></a>

                               <strong>Suggerimento Inviato!</strong>

                               <p align="center"> Il tuo suggerimento e' stata inviato correttamente all'autore dell'idea!.</p>

                            </div>

                         </div> 

                         <div class="uk-width-medium-1-4 uk-width-small-1-4"></div>

                         </div> 

                        </center>

                        <br>

                        <br>  



                  <?php



                }//close 



              ?>











         <?php 



          //              ------------------------->>>>>>>>>>>>>>> ESTRAGGO I DAT DELL'IDEA CHE STO VISUALIZZANDO <<<<<<<<<<<<<<<<<<<<<<<<<-------------------------------



        $con = connetti("my_canvass");

        $ID_idea = pulisci($_POST['ID_idea']);

       



        $sql = "SELECT * FROM idee WHERE cancellato = 'NO' AND ID_idea = '$ID_idea';";

        $row = esegui_query_stringa($con,$sql);



                            

        ?>  







        <div class="uk-grid">

        <div class="uk-width-medium-1-6 uk-width-small-1-6"></div> 

        <div class="uk-width-medium-4-6 uk-width-small-4-6">

       

             <center><h2>Visualizza Idea</h2></center>



              <div class="uk-grid" data-uk-grid-margin>

                <div class="uk-width-medium-3-4">

                  <div class="uk-grid">

                    <form action="dashboard_idea.php" method="post">

                      <button class="uk-button uk-button-primary"><i class="uk-icon-angle-left"></i> Indietro</button>

                    </form>  

                  </div>

                  <br>



                  <?php



                  //QUESTO TOKEN SI ATTIVA QUANDO L'UTENTE ABILITA LA SCRITTURA DEI SUGGERIMENTI TRAMITE BOTTONE DEDICATO



                  if(isset($_POST['token_scrivi_suggerimento']))

                  {



                    ?>

                    <br>

                   <form action="visualizza_idea.php" method="post"> 

                    <div class="uk-grid">

                      <div class="uk-width-medium-1-1 uk-width-small-1-1">

                      <div class="uk-form-row">

                        <textarea class="uk-width-large-1-1" name="testo" placeholder="Scrivi suggerimento privato" rows="5"></textarea>

                       </div>

                       <br>

                       <input type="hidden" name="token_invia_suggerimento" value="1">

                       <input type="hidden" name="ID_idea" value="<?php echo filtraOutput($_POST['ID_idea']);?>">

                       <button class="uk-button uk-button-success"><i class="uk-icon-paper-plane-o"></i> Invia</button> 

                    </div>

                    </div> 

                  </form>

                  <br>

                  <br>

                    <?php

                  }//chiudi il token per la scrittura dei suggerimenti



                  ?>

   

                   <article class="uk-article">



                        <h1 class="uk-article-title"><?php echo filtraOutput($row['titolo']); ?></h1>

                        <?php 

                          

                          // ESTRAI NOME E COGNOME DELL'AUTORE DELL'IDEA

                         

                          $sql_utente = "SELECT * FROM utente WHERE cancellato = 'NO' AND ID_utente = '".pulisci($row['ID_utente'])."';";

                          $row_utente = esegui_query_stringa($con,$sql_utente);



                          //CORREGGI DATA DI PUBBLICAZIONE 

                          $dataDaCorreggere = strtotime(pulisci($row['data']));

                          $data_corretta = date("d-m-Y H:i:s",$dataDaCorreggere);



                        ?>



                        <p class="uk-article-meta">Scritto da <?php echo filtraOutput($row_utente['nome']." ".$row_utente['cognome'])." il ".pulisci($data_corretta); ?></p>



                         <?php



                                //ESTRAI FOTO IDEA

                                 

                                      $con = connetti("my_canvass");

                                      $sql_estrai_img = "SELECT * FROM associa_foto_idea INNER JOIN foto ON associa_foto_idea.ID_foto=foto.ID_foto WHERE foto.cancellato = 'NO' AND associa_foto_idea.ID_idea = '".pulisci($_POST['ID_idea'])."';";

                                     // echo $sql_estrai_img."<br>";

                                      $row_estrai_img = esegui_query_stringa($con,$sql_estrai_img);

                                     // echo $row_estrai_img['percorso'];



                                     

                          ?>  





                        <p><img class="uk-image-preserve" src="<?php if($row_estrai_img['percorso']!=''){echo "../".$row_estrai_img['percorso'];} else echo "data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxNi4wLjQsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+DQo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkViZW5lXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4Ig0KCSB3aWR0aD0iOTAwcHgiIGhlaWdodD0iMzAwcHgiIHZpZXdCb3g9IjAgMCA5MDAgMzAwIiBlbmFibGUtYmFja2dyb3VuZD0ibmV3IDAgMCA5MDAgMzAwIiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxyZWN0IGZpbGw9IiNGNUY1RjUiIHdpZHRoPSI5MDAiIGhlaWdodD0iMzAwIi8+DQo8ZyBvcGFjaXR5PSIwLjciPg0KCTxwYXRoIGZpbGw9IiNEOEQ4RDgiIGQ9Ik0zNzguMTg0LDkzLjV2MTEzaDE0My42MzN2LTExM0gzNzguMTg0eiBNNTEwLjI0NCwxOTQuMjQ3SDM5MC40Mzd2LTg4LjQ5NGgxMTkuODA4TDUxMC4yNDQsMTk0LjI0Nw0KCQlMNTEwLjI0NCwxOTQuMjQ3eiIvPg0KCTxwb2x5Z29uIGZpbGw9IiNEOEQ4RDgiIHBvaW50cz0iMzk2Ljg4MSwxODQuNzE3IDQyMS41NzIsMTU4Ljc2NCA0MzAuODI0LDE2Mi43NjggNDYwLjAxNSwxMzEuNjg4IDQ3MS41MDUsMTQ1LjQzNCANCgkJNDc2LjY4OSwxNDIuMzAzIDUwNC43NDYsMTg0LjcxNyAJIi8+DQoJPGNpcmNsZSBmaWxsPSIjRDhEOEQ4IiBjeD0iNDI1LjQwNSIgY3k9IjEyOC4yNTciIHI9IjEwLjc4NyIvPg0KPC9nPg0KPC9zdmc+DQo=";?>"  width="1200px" height="800px" alt=""></p>



                        <h2><i class="uk-icon-bars"></i> Introduzione</h2>

                        <p><?php if(empty($row['descrizione']))echo "Nessuna introduzione"; else echo filtraOutput($row['descrizione']);?></p>



                        <h2><i class="uk-icon-bars"></i> Descrizione</h2>



                        <p><?php if(empty($row['descrizioneEstesa']))echo "Nessuna descrizione"; else echo filtraOutput($row['descrizioneEstesa']);?></p>



                        <h2><i class="uk-icon-key"></i> Tema</h2>



                         <?php 



                              //ESTRAI NOME DEL CATEGORIA DELL'IDEA

                    

                              $sql_tema = "SELECT * FROM tema WHERE cancellato = 'NO' AND ID_tema = '".$row['ID_tema']."';";

                              $row_tema = esegui_query_stringa($con,$sql_tema);

                              echo "<p>".filtraOutput($row_tema['nome'])."</p>";



                          ?>



                        <h2><i class="uk-icon-link"></i> Link</h2>

                        <div class="uk-container-overflow">

                        <p><?php if(empty($row['link']))echo "Nessun link"; else echo "<a href='".filtraOutput($row['link'])."' target='_blank'>".filtraOutput($row['link'])."</a>";?></p>  

                        </div>

                  

                   <?php 

                   

                   $con = connetti("my_canvass");

                   $sql_estrai_link = "SELECT * FROM link INNER JOIN associa_link_idea ON link.ID_link=associa_link_idea.ID_link "

                           . "WHERE associa_link_idea.ID_idea = '$ID_idea';";

                   

                   $n = calcola_righe_query($con, $sql_estrai_link);

                   if($n!=0)

                   {

                       echo "<br><h2><i class='uk-icon-plus'></i><i class='uk-icon-link'></i> Altri Link</h2>";

                       echo " <div class='uk-form-row'>";

                       echo "<div class='uk-overflow-container'>";

                     

                       echo "<table class='uk-table' width='100%'>"

                       . "<tbody width='100%'>";

                      

                       $ris_estrai_link = esegui_query($con,$sql_estrai_link);

                       while($row_estrai_link = mysqli_fetch_assoc($ris_estrai_link))

                       {

                           ?>

                           <tr>

                           <td>  

                              

                                

                                            <p><?php echo "<a href='".$row_estrai_link['nome']."' target='_blank'>".limitaStringa50(filtraOutput($row_estrai_link['nome']))."</a>"; 

                                           

                                            if(strlen($row_estrai_link['nome'])>50) echo "...";

                                            

                                            ?> 

                                            </p>    

                            

                           </td>    

                         </tr>

                        

                          <?php

                       }  

                       

                       echo "</tbody>";

                       echo "</table>";

                    

                       echo "</div>";  // chiudi container

                       echo "</div>";  // uk-form-row

                       

                   }//chiudi if di $n!=0

                   

                   ?>  

                        



                    </article>

                    <hr>



                    <!--

                    <?php  



                      //CONTA PRESENZA SUGGERIMENTI

                      $con = connetti("my_canvass");

                      $sql_conta = "SELECT COUNT(*) AS conta FROM associa_suggerimento_idea WHERE ID_idea = '$ID_idea';";

                      $conta = 0;

                      $row_conta = esegui_query_stringa($con,$sql_conta);

                      $conta = $row_conta['conta'];

                      if($conta!=0)

                       { 

                    ?>



                    <h3 class="uk-h2">Suggerimenti (<?php echo filtraOutput($conta);?>)</h3>



                    <ul class="uk-comment-list">

                              

                          <?php



                          //  ------------------------->>>>>>>>>>>     ESTRAPOLO I SUGGERIMENTI ASSOCIAT A QUESTA IDEA SE PRESENTI   <<<<<<<<<<<<<------------------------------



                          $con = connetti("my_canvass");

                          $sql_mostra_suggerimenti = "SELECT utente.ID_utente AS autore ,utente.nome AS nomeAutore, 

                          utente.cognome AS cognomeAutore, 

                          suggerimento.dataInvio AS dataCommento, 

                          suggerimento.testo AS testoCommento   

                          FROM associa_suggerimento_idea 

                          INNER JOIN suggerimento 

                          INNER JOIN utente 

                          ON associa_suggerimento_idea.ID_suggerimento=suggerimento.ID_suggerimento 

                          AND suggerimento.ID_utente=utente.ID_utente 

                          WHERE suggerimento.cancellato = 'NO' AND associa_suggerimento_idea.ID_idea = '".pulisci($_POST['ID_idea'])."';";

                          $ris_mostra_suggerimenti = esegui_query($con,$sql_mostra_suggerimenti);

                          //echo $sql_mostra_suggerimenti."<br>";

                          while($row_mostra = mysqli_fetch_assoc($ris_mostra_suggerimenti))

                              {



                                $sql_estrai_img = "SELECT * FROM associa_foto_profilo INNER JOIN foto ON associa_foto_profilo.ID_foto = foto.ID_foto WHERE associa_foto_profilo.ID_utente = '".$row_mostra['autore']."';";

                                //echo $sql_estrai_img;

                                $row_estrai_img = esegui_query_stringa($con,$sql_estrai_img);

                               



                            ?>

                            





                              <li>

                                  <article class="uk-comment">

                                      <header class="uk-comment-header">

                                          <img class="uk-comment-avatar" width="50" height="50" src="<?php if($row_estrai_img['percorso']!='')echo "../".$row_estrai_img['percorso']; else echo "data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxNi4wLjQsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+DQo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkViZW5lXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4Ig0KCSB3aWR0aD0iNTBweCIgaGVpZ2h0PSI1MHB4IiB2aWV3Qm94PSIwIDAgNTAgNTAiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDUwIDUwIiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxyZWN0IGZpbGw9IiNGRkZGRkYiIHdpZHRoPSI1MCIgaGVpZ2h0PSI1MCIvPg0KPGc+DQoJPHBhdGggZmlsbD0iI0UwRTBFMCIgZD0iTTQ1LjQ1LDQxLjM0NWMtMC4yMDktMS4xNjYtMC40NzMtMi4yNDYtMC43OTEtMy4yNDJjLTAuMzE5LTAuOTk2LTAuNzQ3LTEuOTY3LTEuMjg2LTIuOTE0DQoJCWMtMC41MzgtMC45NDYtMS4xNTUtMS43NTMtMS44NTItMi40MmMtMC42OTktMC42NjctMS41NS0xLjItMi41NTYtMS41OThzLTIuMTE3LTAuNTk4LTMuMzMyLTAuNTk4DQoJCWMtMC4xNzksMC0wLjU5NywwLjIxNC0xLjI1NSwwLjY0MmMtMC42NTcsMC40MjktMS4zOTksMC45MDctMi4yMjYsMS40MzRjLTAuODI3LDAuNTI4LTEuOTAzLDEuMDA2LTMuMjI3LDEuNDM0DQoJCWMtMS4zMjUsMC40MjktMi42NTUsMC42NDMtMy45ODksMC42NDNjLTEuMzM0LDAtMi42NjQtMC4yMTQtMy45ODktMC42NDNjLTEuMzI1LTAuNDI4LTIuNDAxLTAuOTA2LTMuMjI3LTEuNDM0DQoJCWMtMC44MjgtMC41MjctMS41NjktMS4wMDUtMi4yMjYtMS40MzRjLTAuNjU4LTAuNDI4LTEuMDc2LTAuNjQyLTEuMjU1LTAuNjQyYy0xLjIxNiwwLTIuMzI2LDAuMTk5LTMuMzMyLDAuNTk4DQoJCWMtMS4wMDYsMC4zOTgtMS44NTgsMC45MzEtMi41NTQsMS41OThjLTAuNjk5LDAuNjY3LTEuMzE1LDEuNDc0LTEuODUzLDIuNDJjLTAuNTM4LDAuOTQ3LTAuOTY3LDEuOTE3LTEuMjg1LDIuOTE0DQoJCXMtMC41ODMsMi4wNzYtMC43OTIsMy4yNDJjLTAuMjA5LDEuMTY1LTAuMzQ5LDIuMjUxLTAuNDE4LDMuMjU2Yy0wLjA3LDEuMDA2LTAuMTA0LDIuMS0wLjEwNCwzLjE1NUMzLjkwMSw0OC41NCwzLjk4Nyw0OSw0LjE0Myw1MA0KCQloNDEuNTg5YzAuMTU2LTEsMC4yNDItMS40NiwwLjI0Mi0yLjI0M2MwLTEuMDU1LTAuMDM1LTIuMTE4LTAuMTA1LTMuMTI0QzQ1Ljc5OSw0My42MjcsNDUuNjYsNDIuNTEsNDUuNDUsNDEuMzQ1eiIvPg0KCTxwYXRoIGZpbGw9IiNFMEUwRTAiIGQ9Ik0yNC45MzgsMzIuNDg1YzMuMTY3LDAsNS44NzEtMS4xMjEsOC4xMTMtMy4zNjFjMi4yNDEtMi4yNDIsMy4zNjEtNC45NDUsMy4zNjEtOC4xMTMNCgkJcy0xLjEyMS01Ljg3Mi0zLjM2MS04LjExMmMtMi4yNDItMi4yNDEtNC45NDYtMy4zNjItOC4xMTMtMy4zNjJzLTUuODcyLDEuMTIxLTguMTEyLDMuMzYyYy0yLjI0MiwyLjI0MS0zLjM2Miw0Ljk0NS0zLjM2Miw4LjExMg0KCQlzMS4xMiw1Ljg3MSwzLjM2Miw4LjExM0MxOS4wNjUsMzEuMzY1LDIxLjc3MSwzMi40ODUsMjQuOTM4LDMyLjQ4NXoiLz4NCjwvZz4NCjwvc3ZnPg0K";?>" alt="">

                                          <h4 class="uk-comment-title"><?php echo filtraOutput($row_mostra['nomeAutore'])." ".filtraOutput($row_mostra['cognomeAutore']);?></h4>

                                          <p class="uk-comment-meta">        

                                            <?php



                                                      //CORREGGO LA DATA DI SCRITTURA DEL SUGGERIMENTO NEL FORMATO CORRETTO   

                                                        $data = strtotime($row_mostra['dataCommento']);

                                                        $dataCorretta = date("d-M-Y H:i.s",$data);

                                                        echo filtraOutput($dataCorretta);

                         



                                            ?>

                                          </p>

                                      </header>

                                      <div class="uk-comment-body"><?php echo filtraOutput($row_mostra['testoCommento']);?></div>

                                  </article>

                              </li>



                            <?php



                              }//chiudi ciclo di estrazione dei commenti/suggerimenti utenti per questa delibera



                             }//chiudi if di $conta!=0 che verifica se sono presenti dei suggerimenti da stampare 

                             else

                             {

                              echo "Nessun suggerimento<br>";

                             }

                            ?>-->



                   </ul>

                </div>





                  <?php



                  //--------------------------------->>>>>>>>>>>>>>>>>>>>>>>>   ESTRAGGO L'IMMAGINE DELL'AUTORE     <<<<<<<<<<<<<<<<--------------------------



                  $con = connetti("my_canvass");

                     $sql_estrai_img = "SELECT * FROM associa_foto_profilo INNER JOIN foto ON associa_foto_profilo.ID_foto=foto.ID_foto WHERE foto.cancellato = 'NO' AND associa_foto_profilo.ID_utente = '".pulisci($row_utente['ID_utente'])."';";

                  //echo $sql_estrai_img."<br>";

                  $row_estrai_img = esegui_query_stringa($con,$sql_estrai_img);

                  //echo $row_estrai_img['percorso'];

                  ?> 





                <div class="uk-width-medium-1-4">

                    <div class="uk-panel uk-panel-box uk-text-center">

                        <h2>Autore</h2>

                        <img class="uk-image-preserve" width="120" height="120" src="<?php if($row_estrai_img['percorso']!='')echo "../".$row_estrai_img['percorso']; else echo "data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxNi4wLjQsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+DQo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkViZW5lXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4Ig0KCSB3aWR0aD0iMTIwcHgiIGhlaWdodD0iMTIwcHgiIHZpZXdCb3g9IjAgMCAxMjAgMTIwIiBlbmFibGUtYmFja2dyb3VuZD0ibmV3IDAgMCAxMjAgMTIwIiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxyZWN0IGZpbGw9IiNGRkZGRkYiIHdpZHRoPSIxMjAiIGhlaWdodD0iMTIwIi8+DQo8Zz4NCgk8cGF0aCBmaWxsPSIjRTBFMEUwIiBkPSJNMTA5LjM1NCw5OS40NzhjLTAuNTAyLTIuODA2LTEuMTM4LTUuNDA0LTEuOTAzLTcuODAxYy0wLjc2Ny0yLjM5Ny0xLjc5Ny00LjczMi0zLjA5My03LjAxMQ0KCQljLTEuMjk0LTIuMjc2LTIuNzc4LTQuMjE3LTQuNDU1LTUuODIzYy0xLjY4MS0xLjYwNC0zLjcyOS0yLjg4Ny02LjE0OC0zLjg0NmMtMi40MjEtMC45NTgtNS4wOTQtMS40MzgtOC4wMTctMS40MzgNCgkJYy0wLjQzMSwwLTEuNDM3LDAuNTE2LTMuMDIsMS41NDVjLTEuNTgxLDEuMDMyLTMuMzY3LDIuMTgyLTUuMzU1LDMuNDVjLTEuOTksMS4yNzEtNC41NzgsMi40MjEtNy43NjUsMy40NTENCgkJQzY2LjQxLDgzLjAzNyw2My4yMSw4My41NTIsNjAsODMuNTUyYy0zLjIxMSwwLTYuNDEtMC41MTUtOS41OTgtMS41NDZjLTMuMTg4LTEuMDMtNS43NzctMi4xODEtNy43NjUtMy40NTENCgkJYy0xLjk5MS0xLjI2OS0zLjc3NC0yLjQxOC01LjM1NS0zLjQ1Yy0xLjU4Mi0xLjAyOS0yLjU4OC0xLjU0NS0zLjAyLTEuNTQ1Yy0yLjkyNiwwLTUuNTk4LDAuNDc5LTguMDE3LDEuNDM4DQoJCWMtMi40MiwwLjk1OS00LjQ3MSwyLjI0MS02LjE0NiwzLjg0NmMtMS42ODEsMS42MDYtMy4xNjQsMy41NDctNC40NTgsNS44MjNjLTEuMjk0LDIuMjc4LTIuMzI2LDQuNjEzLTMuMDkyLDcuMDExDQoJCWMtMC43NjcsMi4zOTYtMS40MDIsNC45OTUtMS45MDYsNy44MDFjLTAuNTAyLDIuODAzLTAuODM5LDUuNDE1LTEuMDA2LDcuODM1Yy0wLjE2OCwyLjQyMS0wLjI1Miw0LjkwMi0wLjI1Miw3LjQ0DQoJCWMwLDEuODg0LDAuMjA3LDMuNjI0LDAuNTgyLDUuMjQ3aDEwMC4wNjNjMC4zNzUtMS42MjMsMC41ODItMy4zNjMsMC41ODItNS4yNDdjMC0yLjUzOC0wLjA4NC01LjAyLTAuMjUzLTcuNDQNCgkJQzExMC4xOTIsMTA0Ljg5MywxMDkuODU3LDEwMi4yOCwxMDkuMzU0LDk5LjQ3OHoiLz4NCgk8cGF0aCBmaWxsPSIjRTBFMEUwIiBkPSJNNjAsNzguMTZjNy42MiwwLDE0LjEyNi0yLjY5NiwxOS41Mi04LjA4OGM1LjM5Mi01LjM5Myw4LjA4OC0xMS44OTgsOC4wODgtMTkuNTE5DQoJCXMtMi42OTYtMTQuMTI2LTguMDg4LTE5LjUxOUM3NC4xMjYsMjUuNjQzLDY3LjYyLDIyLjk0Niw2MCwyMi45NDZzLTE0LjEyOCwyLjY5Ny0xOS41MTksOC4wODkNCgkJYy01LjM5NCw1LjM5Mi04LjA4OSwxMS44OTctOC4wODksMTkuNTE5czIuNjk1LDE0LjEyNiw4LjA4OSwxOS41MTlDNDUuODcyLDc1LjQ2NCw1Mi4zOCw3OC4xNiw2MCw3OC4xNnoiLz4NCjwvZz4NCjwvc3ZnPg0K";?>" alt="">

                        <h3><?php echo filtraOutput($row_utente['nome']." ".$row_utente['cognome']);?></h3>

                        <p>

                        <?php 

                        $sql_ruolo = "SELECT * FROM ruolo WHERE cancellato = 'NO' AND ID_ruolo = '".$row_utente['ID_ruolo']."';";

                        $row_ruolo = esegui_query_stringa($con,$sql_ruolo);

                        echo filtraOutput($row_ruolo['nome']);



                        ?>  

                        </p>

                    </div>

                    <br>

                    <center>

                    <div class="uk-grid">

                        <div class="uk-width-medium-1-1">

                          <br>

                           <form action="visualizza_idea.php" method="post">

                              <input type="hidden" name="ID_idea" value="<?php echo filtraOutput($_POST['ID_idea'])?>">

                              <input type="hidden" name="token_scrivi_suggerimento" value="1">

                              <button class="uk-button uk-button-primary"><i class="uk-icon-paper-plane-o"></i> Suggerimento</button>

                            </form>

                            <br> 

                            <?php

                            //CONTROLLO IL RUOLO DELL'UTENTE E SE RUOLO SUPERIORE A 3 ABILITO IL TASTO AGGIUNGI IDEA IN AGENDA  

                            $con = connetti("my_canvass");

                            $sql_ruolo = "SELECT * FROM utente WHERE ID_utente = '$ID_utente' AND cancellato = 'NO';";

                            $row_ruolo = esegui_query_stringa($con,$sql_ruolo);

                            if($row_ruolo['ID_ruolo']>=3)

                            {



                            ?>



                            <?php      



                             $con = connetti("my_canvass");



                             $sql_estrai = "SELECT ID_eletto FROM eletto WHERE ID_utente='$ID_utente';";

                             $row_estrai = esegui_query_stringa($con,$sql_estrai);

                             $ID_eletto = pulisci($row_estrai['ID_eletto']);

                             $ID_idea = pulisci($_POST['ID_idea']);

                             

                             $sql = "SELECT COUNT(*) as conta FROM associa_idee_eletto WHERE ID_idea ='$ID_idea' AND ID_eletto = '$ID_eletto';";

                             $row = esegui_query_stringa($con,$sql);

                             $conta = 0;

                             $conta = $row['conta'];



                            



                             if($conta==0)

                                {



                             ?>

                            <form action="visualizza_idea.php" method="post">

                              <input type="hidden" name="ID_eletto" value="<?php echo filtraOutput($ID_eletto);?>"> 

                              <input type="hidden" name="ID_idea" value="<?php echo filtraOutput($_POST['ID_idea'])?>">

                              <input type="hidden" name="token_inserisci_in_agenda" value="1">

                              <button class="uk-button uk-button-success"><i class="uk-icon-plus"></i> Inserisci in Agenda</button>

                            </form>

                            <br> 

                              <?php

                              }//close $conta==0

                              else

                              {

                              ?>

                               <form action="visualizza_idea.php" method="post">

                                <input type="hidden" name="ID_eletto" value="<?php echo filtraOutput($ID_eletto);?>"> 

                                <input type="hidden" name="ID_idea" value="<?php echo filtraOutput($_POST['ID_idea'])?>">

                                <input type="hidden" name="token_rimuovi_in_agenda" value="1">

                                <button class="uk-button uk-button-danger"><i class="uk-icon-minus"></i> Elimina da Agenda</button>

                              </form>

                              <br> 



                              <?php



                              } //close else di $conta==0 

                              ?>

                            <?php

                            }//chiudi if di $row_ruolo['ID_ruolo']>=3

                            ?> 

                         

                        </div>  

                    </div>

                    </center>  

                </div>



            </div>



        </div>

        

       

               

        </div><!-- chiudi div uk-width-4-6 -->

        <div class="uk-width-medium-1-6 uk-width-small-1-6"></div> 

        </div><!-- chiudi la griglia  della tabella--> 

        



        </div><!-- chiudi il container della pagina html--> 

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