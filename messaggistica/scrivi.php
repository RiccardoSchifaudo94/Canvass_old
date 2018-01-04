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





                   



        <div class="uk-grid">

        <div class="uk-width-medium-1-6 uk-width-small-1-6"></div> 

        <div class="uk-width-medium-4-6 uk-width-small-4-6">

       

        <center><h2>Messaggistica - Cerchia Contatti</h2></center>

        <br>

        <div class="uk-form">

            <button class="uk-button uk-button-primary" data-uk-offcanvas="{target:'#menu_msg'}"><i class="uk-icon-bars"></i> Menu' messaggi</button>

        </div>  

        <br>

         <div class="uk-overflow-container">    

     



       <?php

        

        $con = connetti("my_canvass");

        $sql_verifica_ruolo = "SELECT ID_ruolo FROM utente WHERE cancellato = 'NO' AND ID_utente = '$ID_utente';";

        $row_verifica_ruolo = esegui_query_stringa($con,$sql_verifica_ruolo);

        if($row_verifica_ruolo['ID_ruolo']<=2)

        { 

          ?>  





          <h3>Cerchia Membri Istituzione interessati alle tue Idee</h3>

        

            <table class="uk-table">

                        <thead>

                          <tr>

                            <th>Ruolo</th>

                            <th>Nome</th> 

                            <th>Cognome</th>

                            <th>Titolo Idea</th>

                            <th>Tema</th>

                            <th>Contatta</th> 

                          </tr>

                        </thead>

                        <tbody>

                <?php



                $con = connetti("my_canvass");  

                $sql = "SELECT * FROM idee WHERE cancellato = 'NO' AND ID_utente = '$ID_utente';";

                $ris = esegui_query($con,$sql);

                while($row = mysqli_fetch_assoc($ris))

                {



                  $sql2 = "SELECT ruolo.nome AS nomeRuolo , 

                  utente.nome AS nomeEletto, 

                  utente.cognome AS cognomeEletto, 

                  utente.ID_utente AS ID_contatto,

                  idee.titolo AS titoloIdea,

                  tema.nome AS nomeTema  

                  FROM associa_idee_eletto 

                  INNER JOIN eletto 

                  INNER JOIN utente 

                  INNER JOIN ruolo 

                  INNER JOIN idee

                  INNER JOIN tema 

                  ON associa_idee_eletto.ID_eletto=eletto.ID_eletto 

                  AND eletto.ID_utente=utente.ID_utente 

                  AND eletto.ID_ruolo=ruolo.ID_ruolo 

                  AND associa_idee_eletto.ID_idea=idee.ID_idea 

                  AND idee.ID_tema=tema.ID_tema  

                  WHERE associa_idee_eletto.ID_idea = '".$row['ID_idea']."';";

                  $ris2 = esegui_query($con,$sql2);



                  while($row2 = mysqli_fetch_assoc($ris2))

                  {



                  echo "<tr><td>".$row2['nomeRuolo']."</td><td>".$row2['nomeEletto']."</td><td>".$row2['cognomeEletto']."</td><td>".$row2['titoloIdea']."</td><td>".$row2['nomeTema']."</td>";

                  ?>

                  <td align="center">

                    <form action="scrivi2.php" method="post">

                    <input type="hidden" name="ID_contatto" value="<?php echo $row2['ID_contatto'];?>"> 

                    <button class="uk-button uk-button-success" type="submit"><i class="uk-icon-edit"></i></button>

                    </form>

                  </td> 

                </tr>

                  <?php



                   }//chiudi cicloe estrazione delgi eltti interessati alle tue idee

                }//chiudi ciclo estrazione ID_idee







                ?>

          



              </tbody>

            </table>

        



            <?php



                  }//close if di row_verifica_utente['ID_ruolo']<=2

                  else

                  {

            ?>



          <h3>Cerchia dei cittadini/membri di associazioni coinvolti nei tuoi progetti di delibera</h3>

        



            <table class="uk-table">

                        <thead>

                          <tr>

                            <th>Ruolo</th>

                            <th>Nome</th> 

                            <th>Cognome</th>

                            <th>Titolo Delibera</th>

                            <th>Stato Delibere</th>

                            <th>Contatta</th> 

                          </tr>

                        </thead>

                        <tbody>

                <?php



                $con = connetti("my_canvass");  

                $sql = "SELECT * FROM delibere INNER JOIN eletto ON delibere.ID_eletto=eletto.ID_eletto WHERE delibere.cancellato = 'NO' AND delibere.stato = 'In Attesa di Conferma' OR delibere.stato = 'Da Riformulare' AND eletto.ID_utente = '$ID_utente';";

                $ris = esegui_query($con,$sql);

                while($row = mysqli_fetch_assoc($ris))

                {



                   $sql2 = "SELECT utente.nome AS nomeCittadino, 

                   utente.cognome AS cognomeCittadino, 

                   ruolo.nome AS ruoloCittadino,

                   utente.ID_utente AS ID_contatto, 

                   delibere.stato AS statoDelibera,

                   delibere.titolo AS titoloDelibera 

                   FROM associa_delibere_utente  

                   INNER JOIN utente  

                   INNER JOIN ruolo  

                   INNER JOIN delibere

                   ON associa_delibere_utente.ID_utente = utente.ID_utente 

                   AND utente.ID_ruolo = ruolo.ID_ruolo 

                   AND delibere.ID_delibera=associa_delibere_utente.ID_delibera

                   WHERE utente.cancellato = 'NO'   

                   AND associa_delibere_utente.ID_delibera = '".pulisci($row['ID_delibera'])."';";

                  



                  $row2 = esegui_query_stringa($con,$sql2);



                  echo "<tr><td>".$row2['ruoloCittadino']."</td><td>".$row2['nomeCittadino']."</td><td>".$row2['cognomeCittadino']."</td><td>".$row2['titoloDelibera']."</td><td>".$row2['statoDelibera']."</td>";

                  ?>

                  <td align="center">

                    <form action="scrivi2.php" method="post">

                    <input type="hidden" name="ID_contatto" value="<?php echo $row2['ID_contatto'];?>"> 

                    <button class="uk-button uk-button-success" type="submit"><i class="uk-icon-edit"></i></button>

                    </form>

                  </td> 

                </tr>

                  <?php

                }//chiudi il ciclo while di estrazione dei contatti







                ?>

          



              </tbody>

            </table>

            <?php



               }//close else di verifica ruolo utente per determinale a quale cerchia di utenti va mostrata in base al ruolo di chi si è loggato

               //chi è cittadino o membro di associazione con ruolo 1 e 2 contatterà solo la cerchia degli eltti associati alle proprie idee

               //viceversa gli eletti potranno contattare gli utenti associati alle proprie delibere



            ?>

   



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