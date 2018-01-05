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

          $ID_delibera = pulisci($_POST['ID_delibera']);

        $stato = 1;

        if($ID_delibera!="")

          $stato = 0;

        if($stato==1)

        header("location:home.php");

          





?>

<?php

if($_SESSION['ID_controllo']==1 AND $row_ruolo['ID_ruolo']<10)

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



        <?php   //  --------------------------------------- AREA DEI TOKEN DI ESECUZIONE -------------------------------------------?>



        <?php



          if(isset($_POST['token_richiedi_conferma']))

          {



            $ID_delibera = pulisci($_POST['ID_delibera']);

        

            $con = connetti("my_canvass");

            $sql_estrai = "SELECT ID_idea FROM delibere WHERE ID_delibera = '$ID_delibera';";

            $row_estrai = esegui_query_stringa($con,$sql_estrai);



            $sql_proponente = "SELECT ID_utente FROM idee WHERE ID_idea = '".pulisci($row_estrai['ID_idea'])."';";

            $row_proponente = esegui_query_stringa($con,$sql_proponente);



            $sql_conta = "SELECT COUNT(*) AS conta FROM associa_delibere_utente WHERE ID_delibera = '$ID_delibera' AND ID_utente = '".$row_proponente['ID_utente']."';";

            $row_conta = esegui_query_stringa($con,$sql_conta);

            $conta = 0;

            $conta = $row_conta['conta'];



            if($conta==0)

            {  

            $sql = "INSERT INTO associa_delibere_utente (ID_delibera,ID_idea,ID_utente) VALUES ('$ID_delibera','".$row_estrai['ID_idea']."','".$row_proponente['ID_utente']."');";

            $ris = esegui_query($con,$sql);



            $sql_stato = "UPDATE delibere SET stato = 'In Attesa di Conferma' WHERE ID_delibera = '$ID_delibera';";

            $ris_stato = esegui_query($con,$sql_stato);



            ?>



                  <br>

                  <br>

                  <center>

                  <div class="uk-grid">

                   <div class="uk-width-medium-1-4 uk-width-small-1-4"></div>

                   <div class="uk-width-medium-2-4 uk-width-small-2-4">

                      <div class="uk-alert uk-alert-success" data-uk-alert>

                        <a href="" class="uk-alert-close uk-close"></a>

                          <strong>Richiesta avvenuta con successo!<br></strong> Congratulazioni la tua richiesta e' stata inoltrata con successo!

                          <p>Attendi la conferma del proponente dell'idea</p>

                      </div>

                   </div> 

                   <div class="uk-width-medium-1-4 uk-width-small-1-4"></div>

                   </div> 

                  </center>

                  <br>

                  <br>



            <?php

            }

            else

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

                        <strong>Richiesta gia' inoltrata!<br></strong>Spiacente ma non puoi richiedere la conferma di pubblicazione!

                        <p>Attendi la conferma del proponente dell'idea</p>

                      </div>

                   </div> 

                   <div class="uk-width-medium-1-4 uk-width-small-1-4"></div>

                   </div> 

                  </center>

                  <br>

                  <br>



     

              <?php

            }//close else 





          }//chiudi il token ri richiesta conferma





        ?>







        <?php 



        // SETTO LA NOTIFICA COME LETTA SE LA DELIBERA IN QUESTIONE E' STATA APPROVATA DAL PROPONENENTE DELL'IDEA



        $ID_delibera = pulisci($_POST['ID_delibera']);



        $con = connetti("my_canvass");

       

        $sql = "SELECT * FROM delibere INNER JOIN eletto ON delibere.ID_eletto=eletto.ID_eletto WHERE delibere.cancellato='NO' AND eletto.cancellato='NO' AND ID_delibera = '".pulisci($_POST['ID_delibera'])."';";

        $row = esegui_query_stringa($con,$sql);



        $sql_controlla = "SELECT * FROM notifica INNER JOIN associa_notifica_delibera "

                . " ON notifica.ID_notifica=associa_notifica_delibera.ID_notifica WHERE notifica.ID_utente = '$ID_utente' AND notifica.letto = 'NO' "

                . " AND associa_notifica_delibera.ID_delibera = '$ID_delibera';";

        $ris_controlla = esegui_query($con,$sql_controlla);

        //echo $sql_controlla."<br>";

        while($row_controlla = mysqli_fetch_assoc($ris_controlla))

        {

            

            $sql_setta_notifica = "UPDATE notifica SET letto = 'SI' WHERE letto = 'NO' AND ID_utente = '$ID_utente' AND ID_notifica = '".$row_controlla['ID_notifica']."';";

            $ris_setta_notifica = esegui_query($con,$sql_setta_notifica);

            //echo $sql_setta_notifica."<br>";

        }//close while $row_controlla

        

        ?>  









        <div class="uk-grid">

        <div class="uk-width-medium-1-6 uk-width-small-1-6"></div> 

        <div class="uk-width-medium-4-6 uk-width-small-4-6">

        <br>

            <center><h2>Agenda Personale - Apri Delibera</h2></center>

            <br>

                       

                      <div class="uk-form-row"> 

                       <center><h1><?php if(empty($row['titolo']))echo "Nessun Titolo"; else echo $row['titolo'];?></h1></center>

                      </div>  

                      <br>

                      <div class="uk-form">

                      <form action="home.php" method="post">  

                      <button  class="uk-button uk-button-primary" type="submit"><i class="uk-icon-angle-left"></i> Indietro</button>  

                      </form>

                      </div> 

                      <br> 

                      <!-- This is the off-canvas sidebar -->

                      <div id="my-id" class="uk-offcanvas">

                          <div class="uk-offcanvas-bar">

                              <div class="uk-panel">

                               <?php 

                               $con = connetti("my_canvass");

                               $sql = "SELECT ID_idea FROM delibere WHERE cancellato = 'NO' AND ID_delibera = '$ID_delibera';";

                               $row_ID = esegui_query_stringa($con,$sql);

                               $sql_idea = "SELECT * FROM idee WHERE cancellato = 'NO' AND ID_idea = '".$row_ID['ID_idea']."';";

                               $row_idea = esegui_query_stringa($con,$sql_idea);

                               ?>

                               <h2><?php if(empty($row_idea['titolo'])) echo "<font color='white'>Nessun Titolo</font>"; else echo "<font color='white'>".filtraOutput($row_idea['titolo'])."</font>";?></h2>                              

                               <br>

                               <h3><font color='white'>Introduzione</font></h3>

                               <p><?php if(empty($row_idea['descrizione'])) echo "<font color='white'>Nessuna Descrizione</font>"; else echo "<font color='white'>".filtraOutput($row_idea['descrizione'])."</font>";?></p>

                                <br>

                               <h3><font color='white'>Descrizione</font></h3>

                               <p><?php if(empty($row_idea['descrizioneEstesa'])) echo "<font color='white'>Nessuna Descrizione</font>"; else echo "<font color='white'>".filtraOutput($row_idea['descrizioneEstesa'])."</font>";?></p>

                               <br>

                               <?php 



                               echo "<h3><font color='white'>Autore</font></h3>";

                               $sql_autore = "SELECT * FROM utente WHERE cancellato = 'NO' AND ID_utente = '".$row_idea['ID_utente']."';";

                               $row_autore = esegui_query_stringa($con,$sql_autore);

                               echo "<p><font color='white'>".$row_autore['nome']." ".$row_autore['cognome']."</font></p>";

                               echo "<h3><font color='white'>Data Pubblicazione Idea</font></h3>";

                               $dataDaCorreggere = strtotime($row_idea['data']);

                               $dataCorretta = date("d-M-Y H:i:s",$dataDaCorreggere);

                               echo "<p><font color='white'>".$dataCorretta."</font></p>";





                               ?>

                              </div>

                          </div>

                      </div>

                      <br>

                      <div class="uk-grid">

                                <div class="uk-width-medium-1-2 uk-width-small-1-2">  

                                

                                    <div class="uk-form-row"> 

                                      <h3><i class="uk-icon-bars"></i> Descrizione</h3>

                                      <p><?php if(empty($row['descrizione']))echo "Nessuna Descrizione<br>"; else echo filtraOutput($row['descrizione']); ?></p> 

                                    </div>  



                                </div><!-- chidi uk-width-1-2 -->

                                <div class="uk-width-medium-1-2 uk-width-small-1-2">  

                             



                                            <div class="uk-form-row"> 

                                                <h3><i class="uk-icon-key"></i> Tema</h3>

                                                <p><?php

                                                 

                                                 $sql_tema = "SELECT * FROM tema WHERE cancellato = 'NO' AND ID_tema = '".pulisci($row['ID_tema'])."';";

                                                 $row_tema = esegui_query_stringa($con,$sql_tema);

                                                 echo filtraOutput($row_tema['nome']); 



                                                 ?></p> 

                                            </div>





                                            <div class="uk-form-row"> 

                                                <h3><i class="uk-icon-eur"></i> Costo per realizzarla</h3>

                                                <p><?php

                                                  

                                                  $sql_budget = "SELECT * FROM budget WHERE ID_budget = '".pulisci($row['ID_budget'])."';";

                                                  $row_budget = esegui_query_stringa($con,$sql_budget);

                                                  echo $row_budget['nome']."<br>";





                                                 ?></p> 

                                            </div> 



                                            <div class="uk-form-row"> 

                                                  <h3><i class="uk-icon-institution"></i> Livello Istituzione </h3>

                                                  <p><?php

                                                    

                                                    $sql_ist = "SELECT * FROM livello_istituzione WHERE ID_livelloIstituzione = '".pulisci($row['ID_livelloIstituzione'])."';";

                                                    $row_ist = esegui_query_stringa($con,$sql_ist);

                                                    if(empty($row['ID_livelloIstituzione']))echo "Non specificato";else echo filtraOutput($row_ist['nome'])."<br>";





                                                   ?></p> 

                                            </div> 



                                            <div class="uk-form-row"> 

                                                  <h3><i class="uk-icon-users"></i> Popolazione </h3>

                                                  <p><?php

                                                    

                                                    $sql_people = "SELECT * FROM popolazione WHERE ID_livelloPopolazione = '".pulisci($row['ID_livelloPopolazione'])."';";

                                                    $row_people = esegui_query_stringa($con,$sql_people);

                                                    if(empty($row['ID_livelloPopolazione']))echo "Non specificato";else echo filtraOutput($row_people['nome'])."<br>";





                                                   ?></p> 

                                            </div>    



                                            <div class="uk-form-row">  

                                                  <h3><i class="uk-icon-pencil"></i> Autore</h3>

                                                  <?php  



                                                  $sql2 = "SELECT * FROM utente INNER JOIN eletto ON utente.ID_utente=eletto.ID_utente WHERE utente.ID_utente = '$ID_utente';";

                                                  $row2 = esegui_query_stringa($con,$sql2);

                                                  //echo $sql2;

                                                  ?>

                                                   <p><?php echo filtraOutput($row2['nome'])." ".filtraOutput($row2['cognome'])."<br>";



                                                        $sql_carica = "SELECT * FROM ruolo WHERE ID_ruolo = '".pulisci($row2['ID_ruolo'])."';";

                                                        $row_carica = esegui_query_stringa($con,$sql_carica);

                                                        echo "Ruolo: ".filtraOutput($row_carica['nome'])."<br>";





                                                    ?></p> 

                                            </div> 



                                          

                                           

                                            <div class="uk-form-row"> 

                                                  <h3><i class="uk-icon-calendar"></i> Data</h3>

                                                  <p><?php

                                                    

                                                  echo  filtraOutput(convertiTimeStampInData($row['data']));



                                                   ?></p> 

                                            </div> 

                        </div><!-- chidi uk-width-1-2 -->



                 </div><!--Chiudi uk-grid in cui sono contenuti i dati della delibera--> 



                 <div class="uk-grid">

                    <div class="uk-form-row"> 

                        <h3><i class="uk-icon-link"></i> Link</h3>

                        <div class="uk-overflow-container">

                        <p>

                          <?php if(empty($row['link_alboPretorio']))echo "Nessun Link";

                              else{

                                ?>



                              <a href="<?php echo pulisci($row['link_alboPretorio']);?>" target="_blank"><?php echo filtraOutput($row['link_alboPretorio']);?></a>  



                          <?php

                            }   

                           ?>

                        </p> 

                        </div>

                     </div>

                  </div><!--Chiudi uk-grid in cui sono contenuti i dati della delibera--> 

                  <br>

           

                   <?php 

                   

                   $con = connetti("my_canvass");

                   $sql_estrai_link = "SELECT * FROM link INNER JOIN associa_link_delibera ON link.ID_link=associa_link_delibera.ID_link "

                           . "WHERE associa_link_delibera.ID_delibera = '$ID_delibera';";

                   

                   $n = calcola_righe_query($con, $sql_estrai_link);

                   if($n!=0)

                   {

                       echo "<br><h3><i class='uk-icon-plus'></i><i class='uk-icon-link'></i> Altri Link</h3>";

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



               <br>

               <hr>



               <?php      



               $con = connetti("my_canvass");



               $sql_estrai = "SELECT ID_eletto FROM eletto WHERE ID_utente='$ID_utente';";

               $row_estrai = esegui_query_stringa($con,$sql_estrai);

               $ID_eletto = pulisci($row_estrai['ID_eletto']);

              // echo $sql_estrai."<br>";

               $sql = "SELECT * FROM delibere WHERE ID_delibera = '".pulisci($_POST['ID_delibera'])."';";

               $row = esegui_query_stringa($con,$sql);

              // echo $sql."<br>";

              // echo $row['confermato'];



          



               if($row['confermato']=="NO")

                  {



               ?>

               

                      <div class="uk-grid">

                      <div class="uk-width-medium-1-4 uk-width-small-1-4">

                      <button class="uk-button uk-button-primary" data-uk-offcanvas="{target:'#my-id'}">Idea originaria</button> 

                      </div>

                      <div class="uk-width-medium-1-4 uk-width-small-1-4">

                      <form action="modifica_delibera.php" method="post">

                      <input type="hidden" name="ID_delibera" value="<?php echo filtraOutput($_POST['ID_delibera']);?>">

                      <input type="hidden" name="ID_eletto" value="<?php echo filtraOutput($ID_eletto);?>">    

                      <input type="hidden" name="token_rimuovi_idea_agenda1" value="1">   

                      <button class="uk-button uk-button-primary">Modifica</button>

                      </form>

                      </div>

              

                      <div class="uk-width-medium-1-4 uk-width-small-1-4">

                      <form action="apri_delibera.php" method="post">

                      <input type="hidden" name="ID_delibera" value="<?php echo filtraOutput($_POST['ID_delibera']);?>">

                      <input type="hidden" name="ID_eletto" value="<?php echo filtraOutput($ID_eletto);?>"> 

                      <input type="hidden" name="token_richiedi_conferma" value="1">    

                      <button class="uk-button uk-button-danger">Richiedi Conferma</button>

                      </form>

                      </div>



                      <div class="uk-width-medium-1-4 uk-width-small-1-4">

                      <form action="../messaggistica/scrivi2.php" method="post">



                        <?php

                        //estrazione dell'id contatto dell'idea per contattarlo tramite messaggio e-mail

                        $con = connetti("my_canvass");

                        $sql_estrai = "SELECT ID_idea FROM delibere WHERE ID_delibera = '".pulisci($_POST['ID_delibera'])."';";

                        $row_estrai = esegui_query_stringa($con,$sql_estrai);



                        $sql_proponente = "SELECT ID_utente FROM idee WHERE ID_idea = '".pulisci($row_estrai['ID_idea'])."';";

                        $row_proponente = esegui_query_stringa($con,$sql_proponente);



                        ?>

                      <input type="hidden" name="ID_contatto" value="<?php echo filtraOutput($row_proponente['ID_utente']);?>">   

                      <input type="hidden" name="ID_delibera" value="<?php echo filtraOutput($_POST['ID_delibera']);?>">

                      <input type="hidden" name="ID_eletto" value="<?php echo filtraOutput($ID_eletto);?>"> 

                      <button class="uk-button uk-button-success">Contatta Proponente</button>

                     </form>

                    </div>

                  </div>  

               

               <hr>

            

              <?php 

               }



               ?>

              







              <?php 



              if($row['confermato']=="SI")

                  {

                    ?>



                    

                    <?php  



                      //CONTA PRESENZA SUGGERIMENTI

                      $con = connetti("my_canvass");

                      $sql_conta = "SELECT COUNT(*) AS conta FROM associa_suggerimento_delibera WHERE ID_delibera = '$ID_delibera';";

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

                            $sql_mostra_suggerimenti = "SELECT utente.ID_utente AS autore, 

                                utente.nome AS nomeAutore, 

                            utente.cognome AS cognomeAutore, 

                            suggerimento.dataInvio AS dataCommento, 

                            suggerimento.testo AS testoCommento   

                            FROM associa_suggerimento_delibera 

                            INNER JOIN suggerimento 

                            INNER JOIN utente 

                            ON associa_suggerimento_delibera.ID_suggerimento=suggerimento.ID_suggerimento 

                            AND suggerimento.ID_utente=utente.ID_utente 

                            WHERE suggerimento.cancellato = 'NO' AND associa_suggerimento_delibera.ID_delibera = '".pulisci($_POST['ID_delibera'])."';";

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

                     



                  

                  

                  }//close if di confermato = SI



                  ?>  

        











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