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

     <div class="uk-container uk-container-center uk-margin-top uk-margin-large-bottom">    

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
                

             <div class="uk-container uk-container-center uk-margin-top uk-margin-large-bottom">    

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


        <?php   //----------------------------->>>>>>>>>>>>>>>>>>>>>    SISTEMA DI RANKING DELLE DELIBERE    <<<<<<<<<<<<<<<<------------------------------?>

       
        <?php

             if(isset($_POST['token_cancella_rank']))
             {

              $con = connetti("my_canvass");
              $ID_delibera = pulisci($_POST['ID_delibera']);
              
              $sql_controllo = "SELECT COUNT(*) AS conta FROM voto_delibera WHERE ID_delibera = '$ID_delibera' AND ID_utente = '$ID_utente';";
              $row_controllo = esegui_query_stringa($con,$sql_controllo);
              $conta = 0;
              $conta = $row_controllo['conta'];

              if($conta!=0)
              {  
              $sql_delete = "DELETE FROM voto_delibera WHERE ID_utente = '$ID_utente' AND ID_delibera ='$ID_delibera';";
              $ris_delete = esegui_query($con,$sql_delete);
             // echo $sql_delete;
              }

              $sql_conta_up = "SELECT COUNT(*) AS max FROM voto_delibera WHERE stato = 'up' AND ID_delibera = '$ID_delibera';";
              $row_conta_up = esegui_query_stringa($con,$sql_conta_up);

              $sql_conta_down = "SELECT COUNT(*) AS min FROM voto_delibera WHERE stato = 'down' AND ID_delibera = '$ID_delibera';";
              $row_conta_down = esegui_query_stringa($con,$sql_conta_down);

              $max = 0;
              $min = 0;
              $diff = 0;

              $max = $row_conta_up['max'];
              $min = $row_conta_down['min'];

              $diff = $max - $min;
              if($diff>0)
              {
                $sql_aggiorna_voti_delibera = "UPDATE delibere SET voti = '$diff' WHERE ID_delibera = '$ID_delibera';";
                $ris_aggiorna_voti_delibera = esegui_query($con,$sql_aggiorna_voti_delibera);
               // echo $sql_aggiorna_voti_idea;
              }  
              else
              {

                $sql_aggiorna_voti_delibera = "UPDATE delibere SET voti = '0' WHERE ID_delibera = '$ID_delibera';";
                $ris_aggiorna_voti_delibera = esegui_query($con,$sql_aggiorna_voti_delibera);
                //echo $sql_aggiorna_voti_idea;
              }//close else



             }//close token


             ?>


             <?php

             if(isset($_POST['token_rank_up']))
             {

              $con = connetti("my_canvass");
              $ID_delibera = pulisci($_POST['ID_delibera']);
              
              $sql_controllo = "SELECT COUNT(*) AS conta FROM voto_delibera WHERE ID_delibera = '$ID_delibera' AND ID_utente = '$ID_utente';";
              $row_controllo = esegui_query_stringa($con,$sql_controllo);
              $conta = 0;
              $conta = $row_controllo['conta'];

              if($conta==0)
              {  
              $sql_up = "INSERT INTO voto_delibera(ID_utente,ID_delibera,stato) VALUES ('$ID_utente','$ID_delibera','up');";
              $ris_up = esegui_query($con,$sql_up);
              }

              $sql_conta_up = "SELECT COUNT(*) AS max FROM voto_delibera WHERE stato = 'up' AND ID_delibera = '$ID_delibera';";
              $row_conta_up = esegui_query_stringa($con,$sql_conta_up);

              $sql_conta_down = "SELECT COUNT(*) AS min FROM voto_delibera WHERE stato = 'down' AND ID_delibera = '$ID_delibera';";
              $row_conta_down = esegui_query_stringa($con,$sql_conta_down);

              $max = 0;
              $min = 0;
              $diff = 0;

              $max = $row_conta_up['max'];
              $min = $row_conta_down['min'];

              $diff = $max - $min;

              if($diff>=0)
              {
                $sql_aggiorna_voti_delibera = "UPDATE delibere SET voti = '$diff' WHERE ID_delibera = '$ID_delibera';";
                $ris_aggiorna_voti_delibera = esegui_query($con,$sql_aggiorna_voti_delibera);
                //echo $sql_aggiorna_voti_idea;
              }  
              else
              {

                $sql_aggiorna_voti_delibera = "UPDATE delibere SET voti = '0' WHERE ID_delibera = '$ID_delibera';";
                $ris_aggiorna_voti_delibera = esegui_query($con,$sql_aggiorna_voti_delibera);
                //echo $sql_aggiorna_voti_idea;
              }//close else


             }//close token



             ?>


             <?php

             if(isset($_POST['token_rank_down']))
             {

              $con = connetti("my_canvass");
              $ID_delibera = pulisci($_POST['ID_delibera']);

              $sql_controllo = "SELECT COUNT(*) AS conta FROM voto_delibera WHERE ID_delibera = '$ID_delibera' AND ID_utente = '$ID_utente';";
              $row_controllo = esegui_query_stringa($con,$sql_controllo);
              $conta = 0;
              $conta = $row_controllo['conta'];

              if($conta==0)
              {  
              $sql_up = "INSERT INTO voto_delibera(ID_utente,ID_delibera,stato) VALUES ('$ID_utente','$ID_delibera','down');";
              $ris_up = esegui_query($con,$sql_up);
              }

              $sql_conta_up = "SELECT COUNT(*) AS max FROM voto_delibera WHERE stato = 'up' AND ID_delibera = '$ID_delibera';";
              $row_conta_up = esegui_query_stringa($con,$sql_conta_up);

              $sql_conta_down = "SELECT COUNT(*) AS min FROM voto_delibera WHERE stato = 'down' AND ID_delibera = '$ID_delibera';";
              $row_conta_down = esegui_query_stringa($con,$sql_conta_down);

              $max = 0;
              $min = 0;
              $diff = 0;

              $max = $row_conta_up['max'];
              $min = $row_conta_down['min'];
              //echo $max;
              //echo $min;

              $diff = $max - $min;
              if($diff>0)
              {
                $sql_aggiorna_voti_delibera = "UPDATE delibere SET voti = '$diff' WHERE ID_delibera = '$ID_delibera';";
                $ris_aggiorna_voti_delibera = esegui_query($con,$sql_aggiorna_voti_delibera);
              }  
              else
              {

                $sql_aggiorna_voti_delibera = "UPDATE delibere SET voti = '0' WHERE ID_delibera = '$ID_delibera';";
                $ris_aggiorna_voti_delibera = esegui_query($con,$sql_aggiorna_voti_delibera);

              }//close else


             }//close token



        ?>


        <?php   //  --------------------------------------- AREA DEI TOKEN DI ESECUZIONE -------------------------------------------?>

        <?php

            if(isset($_POST['token_cerca']))
            {

              $con = connetti("my_canvass");

              $titolo = pulisci($_POST['titolo']);
              $ID_livelloIstituzione = pulisci($_POST['ID_livelloIstituzione']);
              $ID_budget = pulisci($_POST['ID_budget']);

              $stringaLivello = "";

              if($_POST['ID_livelloIstituzione']!="-")
                $stringaLivello = " AND delibere.ID_livelloIstituzione = '$ID_livelloIstituzione' ";

              $stringaCosto = "";
              if($_POST['ID_budget']!="-")
                $stringaCosto = " AND delibere.ID_budget = '$ID_budget' ";

              $sql = "SELECT * FROM delibere INNER JOIN eletto 
               ON delibere.ID_eletto=eletto.ID_eletto  
               WHERE delibere.confermato = 'SI' AND delibere.stato = 'Confermata' AND  
               delibere.titolo LIKE '%$titolo%' AND eletto.cancellato='NO'  
               AND delibere.cancellato='NO' ".$stringaCosto.$stringaLivello." ORDER BY voti DESC,data DESC;";
              // echo $sql."<br>";  
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
                               <strong>Ricerca effettuata</strong>
                               <br>
                               <?php 
                               if($_POST['titolo']!="")
                                echo "<b>Titolo:</b> ".$titolo;
                               if($_POST['ID_budget']!="-")
                               {
                                
                                $sql_costo = "SELECT * FROM budget WHERE cancellato = 'NO' AND ID_budget = '$ID_budget';";
                                $row_costo = esegui_query_stringa($con,$sql_costo);
                                echo "<br><b>Costo : </b>".$row_costo['nome'];

                               }
                               if($_POST['ID_livelloIstituzione']!="-")
                               {

                                $sql_livello = "SELECT * FROM livello_istituzione WHERE cancellato = 'NO' AND ID_livelloIstituzione = '$ID_livelloIstituzione';";
                                $row_livello = esegui_query_stringa($con,$sql_livello);
                                echo "<br><b>Livello Istituzione: </b>".$row_livello['nome'];

                               }


                               ?>
                            </div>
                         </div> 
                         <div class="uk-width-medium-1-4 uk-width-small-1-4"></div>
                         </div> 
                        </center>
                        <br>
                        <br> 



               <?php

            }//token cerca
            else
            {

              $con = connetti("my_canvass");


              $sql = "SELECT * FROM delibere INNER JOIN eletto ON delibere.ID_eletto=eletto.ID_eletto"
                      . " WHERE  delibere.confermato = 'SI' AND delibere.stato = 'Confermata' AND "
                      . "eletto.cancellato='NO' AND delibere.cancellato='NO' ORDER BY voti DESC,data DESC;";
              $ris = esegui_query($con,$sql);

            }


        ?>


        <?php   ///-------------------------------->>>>>>>>>>>>>>>   INIZIO PAGINA WEB <<<<<<<<<<<<<<<<<<<<<<<--------------------------------------------?>


        <div class="uk-grid">
        <div class="uk-width-medium-1-6 uk-width-small-1-6"></div> 
        <div class="uk-width-medium-4-6 uk-width-small-4-6">
        <?php 
         
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
            <center><h2>Bacheca delle Delibere - Popolari</h2></center>
                
                            <ul class="uk-subnav uk-subnav-pill" aling="right">
                                <li><a href="../bachecaDelibere/dashboard_delibera.php"><i class="uk-icon-calendar"></i> Recenti</a></li>
                                <li class="uk-active"><a href="../bachecaDelibere/dashboard_delibera_popular.php"><i class="uk-icon-sort-amount-desc"></i> Popolari</a></li>
                                <li><a href="../bachecaDelibere/dashboard_delibera_theme.php"><i class="uk-icon-key"></i> Tematiche</a></li>
                            </ul>
                        <br>
                      
          <hr>

          <?php  //----------------------------------- TOKEN DI RICERCA DELLE DELIBERE ----------------------------------------?>

          <?php  
          if(isset($_POST['token_form_ricerca']))
          {
            ?>
              <form action="dashboard_delibera_popular.php" method="post">
               <div class="uk-grid">
              
                    <div class="uk-width-small-1-3 uk-width-medium-1-3">
                          <label>Titolo</label>
                          <input type="text" name="titolo" class="uk-form-large uk-width-1-1">
                    </div> 
                  
                    <div class="uk-width-small-1-3 uk-width-medium-1-3">
                        <label>Livello Istituzione</label>
                              <select name="ID_livelloIstituzione" class="uk-form-select uk-form-large uk-width-1-1">
                                <option value="-">Tutti i livelli</option>
                                <?php 
                                $con = connetti("my_canvass");
                                $sql_cerca = "SELECT * FROM livello_istituzione WHERE cancellato = 'NO';";
                                $ris_cerca = esegui_query($con,$sql_cerca);
                                while ($row_cerca = mysqli_fetch_assoc($ris_cerca))
                                 {
                                  ?>

                                  <option value="<?php echo filtraOutput($row_cerca['ID_livelloIstituzione']);?>"><?php echo filtraOutput($row_cerca['nome']); ?></option>

                                  <?php
                                }

                                ?>
                              </select>
                      </div>
                      <div class="uk-width-small-1-3 uk-width-medium-1-3">
                             <label>Costo</label>
                                 <select name="ID_budget" class="uk-form-select uk-form-large uk-width-1-1">
                                  <option value="-">Tutti le categorie</option>
                                  <?php 
                                  $con = connetti("my_canvass");
                                  $sql_cerca = "SELECT * FROM budget WHERE cancellato = 'NO';";
                                  $ris_cerca = esegui_query($con,$sql_cerca);
                                  while ($row_cerca = mysqli_fetch_assoc($ris_cerca))
                                   {
                                    ?>

                                    <option value="<?php echo filtraOutput($row_cerca['ID_budget']);?>"><?php echo filtraOutput($row_cerca['nome']); ?></option>

                                    <?php
                                  }

                                  ?>
                                </select>
                      </div>
            </div>
            <div class="uk-grid">
                 <div class="uk-width-small-1-1 uk-width-medium-1-1" align="right"> 
                     <input type="hidden" name="token_cerca" value="1"> 
                     <button class="uk-button uk-button-success" type="submit">Ricerca</button>
                 </div> 
            </div>  
            </form>
            <hr>  
            <?php
          }
          else
          {
            ?>

              <form action = "dashboard_delibera_popular.php" method="post">
                        <input type="hidden" name="token_form_ricerca" value="1">
                        <button class="uk-button uk-button-primary" type="submit"><i class="uk-icon-search"></i> Cerca Delibera</button>  
              </form>

            <?php
          }//chiudi else di token-form-ricerca in cui viene mostrato il bottone di ricerca se non è settato il token che mostra il forma di ricerca
          ?>





          <?php  //   ----------------------->>>>>>>>>>>>>>>>>>>>  STAMPO DELLE DELIBERE ORDINATE DALLE PIU' RECENTI ALLE MENO RECENTI  <<<<<<<<<<<<<<<<<--------------------------?>

          <?php


                     //   $sql = "SELECT * FROM delibere INNER JOIN eletto ON delibere.ID_eletto=eletto.ID_eletto WHERE eletto.cancellato='NO' AND delibere.cancellato='NO' ORDER BY ID_delibera DESC;";

                      // $con = connetti("my_canvass") ;
                                             
                     // $result=esegui_query($con,$sql);

                      /*conta numero righe*/
                     // $num_row=calcola_righe_query($con,$sql);

                      //$i = 0;
                     // if($num_row!=0){
                            while( $row=mysqli_fetch_assoc($ris))
                            {

                                    

                                  ?>

                               <!-- Project One -->
                              <div class="uk-grid">
                                <div class="uk-width-medium-1-10 uk-width-small-1-10"></div>
                                 <div class="uk-width-medium-1-10 uk-width-small-1-10">
                                  <br>
                                  <br>
                                                                  
                                                                                  <?php      

                                                                                 $con = connetti("my_canvass");
                                                                                 $sql2 = "SELECT COUNT(*) as conta FROM voto_delibera WHERE ID_delibera = '".filtraInput($con,$row['ID_delibera'])."' AND ID_utente ='$ID_utente';";
                                                                                 $row2 = esegui_query_stringa($con,$sql2);
                                                                                 $sql_rank = "SELECT * FROM voto_delibera WHERE ID_delibera = '".filtraInput($con,$row['ID_delibera'])."' AND ID_utente = '$ID_utente';";
                                                                                 $row_rank = esegui_query_stringa($con,$sql_rank);
                                                                                 $conta = 0;
                                                                                 $conta = $row2['conta'];

                                                                                
                                                                                 if($conta == 0)
                                                                                 {
                                                                                ?>
                                                                               
                                                                                 <form action="dashboard_delibera_popular.php" method="post">
                                                                                 <input name="ID_utente" value="<?php echo filtraOutput($ID_utente);?>" type="hidden"> 
                                                                                 <input name="ID_delibera" value="<?php echo filtraOutput($row['ID_delibera']);?>" type="hidden">
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
                                                                                 <form action="dashboard_delibera_popular.php" method="post">
                                                                                  <br>
                                                                                 <input name="ID_utente" value="<?php echo filtraOutput($ID_utente);?>" type="hidden"> 
                                                                                 <input name="ID_delibera" value="<?php echo  filtraOutput($row['ID_delibera']);?>" type="hidden">
                                                                                 <input name="token_rank_down" value="1" type="hidden">  
                                                                                 <center><button class="uk-button uk-button-primary" type="submit">\/</button></center>
                                                                                 </form>
                                                                                 <?php  

                                                                                 }


                                                                                 else


                                                                                 {

                                                                                 ?> 

                                                                                 <form action="dashboard_delibera_popular.php" method="post">
                                                                                 <input name="ID_utente" value="<?php echo filtraOutput($ID_utente);?>" type="hidden"> 
                                                                                 <input name="ID_delibera" value="<?php echo filtraOutput($row['ID_delibera']);?>" type="hidden">
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
                                                                                 <form action="dashboard_delibera_popular.php" method="post">
                                                                                  
                                                                                 <input name="ID_utente" value="<?php echo filtraOutput($ID_utente);?>" type="hidden"> 
                                                                                 <input name="ID_delibera" value="<?php echo filtraOutput($row['ID_delibera']);?>" type="hidden">
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
                                      <h3><?php echo "<h2>".filtraOutput($row['titolo'])."</h2>"; ?></h3>  
                                  </div>
                                  <div class="uk-width-medium-4-10 uk-width-small-4-10">
                                      <h4><?php 
                                      
                                      $sql_utente = "SELECT * FROM utente INNER JOIN eletto ON utente.ID_utente=eletto.ID_utente WHERE eletto.cancellato = 'NO' AND utente.cancellato = 'NO' AND eletto.ID_eletto = '".$row['ID_eletto']."';";
                                      $row_utente = esegui_query_stringa($con,$sql_utente);

                                      echo "<i class='uk-icon-user'></i> <b>Autore:</b><br> ".filtraOutput($row_utente['nome'])." ".filtraOutput($row_utente['cognome']);

                                      ?></h4>
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
                                      <h5>
                                      <?php 
                                      
                                      $sql_ist = "SELECT * FROM livello_istituzione WHERE cancellato = 'NO' AND ID_livelloIstituzione = '".$row['ID_livelloIstituzione']."';";
                                      $row_ist = esegui_query_stringa($con,$sql_ist);
                                      echo "<i class='uk-icon-institution'></i><b>Livello istituzione:</b> ".filtraOutput($row_ist['nome']); 
                                      
                                      ?>
                                      </h5>  
                                       <form action="visualizza_delibera.php" method="post">  
                                            <input type="hidden" name="ID_delibera" value="<?php echo filtraOutput($row['ID_delibera'])?>">   
                                            <button class="uk-button uk-button-primary" type="submit">Vedi Dettagli &raquo;</button>
                                      </form>
                                  </div>
                              </div>
                              <!-- /.row -->
                              
                              <hr>


                      <?php
                        }//close while
                         
                     // }//close if $num_row!=0       

                      ?>





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