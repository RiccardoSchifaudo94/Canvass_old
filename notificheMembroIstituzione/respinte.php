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

if($_SESSION['ID_controllo']==1 AND $row_ruolo['ID_ruolo']>=3)

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



        <?php   //  --------------------------------------- AREA DEI TOKEN DI ESECUZIONE -------------------------------------------?>



        <?php



            if(isset($_POST['token_setta_notifica_delibere_respinte'])){



              $con =connetti("my_canvass");

              $ID_notifica = pulisci($_POST['ID_notifica']);

              $sql3 ="UPDATE notifica SET letto ='SI' WHERE ID_notifica='$ID_notifica' AND tipo = 'Delibera_respinta';";

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

                   <strong>Notifica letta!</strong>

                   <p align="center"> La notifica e' stata settata come letta</p>

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











        <div class="uk-grid">

        <div class="uk-width-medium-1-6 uk-width-small-1-6"></div> 

        <div class="uk-width-medium-4-6 uk-width-small-4-6">

        <br>

            <center><h2>Notifiche - Delibere Respinte</h2></center>

            <div class="uk-container-overflow">

            <table class="uk-table">

                     <thead>

                         <tr><th>Notifica</th><th>Visualizza</th><th>Letto</th></tr>

                     </thead>

                     <tbody>

                      <?php   

                      $con = connetti("my_canvass");

                      $sql = "SELECT * FROM notifica WHERE  ID_utente = '$ID_utente' AND letto = 'NO' AND tipo = 'Delibera_respinta';";

                      $ris = esegui_query($con, $sql);

                      //echo $sql."<br>";

                     

                      while($row = mysqli_fetch_assoc($ris))

                      {

                          

                          $sql_dettaglio = "SELECT associa_notifica_delibera.ID_notifica, "

                                  . "associa_notifica_delibera.ID_utente, "

                                  . "associa_notifica_delibera.ID_delibera "

                                  . "FROM associa_notifica_delibera "

                                  . "INNER JOIN delibere INNER JOIN notifica "

                                  . " ON associa_notifica_delibera.ID_delibera=delibere.ID_delibera "

                                  . " AND associa_notifica_delibera.ID_notifica=notifica.ID_notifica WHERE delibere.cancellato = 'NO' "

                                  . " AND notifica.tipo = 'Delibera_respinta' AND associa_notifica_delibera.ID_notifica = '".$row['ID_notifica']."';";

                          $ris_dettaglio = esegui_query($con,$sql_dettaglio);

                         // echo $sql_dettaglio."<br>";

                          while($row_dettaglio = mysqli_fetch_assoc($ris_dettaglio))

                          {

                              echo "<tr>";

                              $sql_delibera = "SELECT * FROM delibere WHERE cancellato = 'NO' AND ID_delibera = '".$row_dettaglio['ID_delibera']."';";

                              $row_delibera = esegui_query_stringa($con,$sql_delibera);

                           //   echo $sql_delibera."<br>";

                              

                              $sql_utente = "SELECT * FROM utente WHERE ID_utente = '".$row_dettaglio['ID_utente']."' AND cancellato = 'NO';";

                              $row_utente = esegui_query_stringa($con,$sql_utente);

                              

                              echo "<td><b>".$row_utente['nome']." ".$row_utente['cognome']."</b> ha respinto la tua delibera  <b>".$row_delibera['titolo']."</b></td>";

                             ?>

                              <td width='1%' align='center'>

                                  <form action="../membroIstituzione/apri_delibera.php" method="post">

                                     <input type="hidden" name="ID_delibera" value="<?php  echo filtraOutput($row_delibera['ID_delibera'])?>">

                                     <button class="uk-button uk-button-success" type="submit"><i class="uk-icon-eye"></i></button>

                                 </form>

                             </td>    

                             <td width='1%' align='center'>

                                 <form action="respinte.php" method="post">

                                     <input type="hidden" name="token_setta_notifica_delibere_respinte" value="1">

                                     <input type="hidden" name="ID_notifica" value="<?php  echo filtraOutput($row['ID_notifica']);?>">

                                     <button class="uk-button uk-button-primary" type="submit"><i class="uk-icon-eye-slash"></i></button>

                                 </form>

                             </td>    

                             <?php

                              echo "</tr>"; 

                          }//close while $row_dettaglio

                          

                      }//close while    

                      

                      ?>    

                     </tbody>

                     

                 </table>  

            </div> <!-- chiudi div uk-container-overflow -->      



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