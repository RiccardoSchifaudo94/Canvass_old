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

        if(isset($_POST['token_cancella2'])){

          $con =connetti("my_canvass");

          $sql3 ="UPDATE delibere SET cancellato='SI' WHERE ID_delibera='".pulisci($_POST['ID_delibera'])."';";
         
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
                  <strong>Delibera cancellata!</strong>
                  <p align="center"> La delibera e' stata cancellata con successo.</p>
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
        
        if(isset($_POST['token_cancella'])){

        ?>

             <br>
              <br>
              <center>
              <div class="uk-grid">
               <div class="uk-width-medium-1-4 uk-width-small-1-4"></div>
               <div class="uk-width-medium-2-4 uk-width-small-2-4">
                  <div class="uk-alert uk-alert-danger" data-uk-alert>
                    <a href="" class="uk-alert-close uk-close"></a>
                     <strong>Vuoi cancellare veramente questa delibera?</strong>
                     <p align="center"> L'operazione sara' irreversibile!</p>
                     <br>
                    <div class="uk-grid">
                      <div class="uk-width-medium-4-10 uk-width-small-4-10"></div>
                      <div class="uk-width-medium-1-10 uk-width-small-1-10">
                          <form action="home.php" method="post">
                              <input type='hidden' name='ID_delibera' value="<?php echo filtraOutput($_POST['ID_delibera']);?>">
                              <input type='hidden' name='token_cancella2' value="1">
                              <button class='uk-button uk-button-danger' type='submit'>SI</button>
                          </form> 
                      </div>
                      <div class="uk-width-medium-1-10 uk-width-small-1-10">
                          <form action="home.php" method="post"> 
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

        }//chiudi token di cancella delibera 1

        ?>
    

        <?php 
          
          if(isset($_POST['token_trasforma_idea_delibera']))
         
          {

           
            $ID_idea = pulisci($_POST['ID_idea']);
            $ID_livelloPopolazione = pulisci($_POST['ID_livelloPopolazione']);
            $titolo = pulisci($_POST['titolo']);
            $descrizione = pulisci($_POST['descrizione']);
            $ID_budget = pulisci($_POST['ID_budget']);
            $link_alboPretorio = pulisci($_POST['link_alboPretorio']);
            //$allegato = pulisci($_POST['allegato']);
            $ID_tema = pulisci($_POST['ID_tema']);
           
            $con = connetti("my_canvass");
            $sql_estrai = "SELECT * FROM eletto WHERE ID_utente = '$ID_utente';";
            //echo $sql_estrai."<br>";
            $row_estrai = esegui_query_stringa($con,$sql_estrai);

            $ID_eletto = $row_estrai['ID_eletto'];
            $ID_livelloIstituzione = $row_estrai['ID_livelloIstituzione'];

            $sql_delibera = "INSERT INTO delibere(ID_eletto,ID_idea,ID_livelloIstituzione,titolo,descrizione,ID_budget,link_alboPretorio,ID_tema,ID_livelloPopolazione) 
            VALUES ('$ID_eletto','$ID_idea','$ID_livelloIstituzione','$titolo','$descrizione','$ID_budget','$link_alboPretorio','$ID_tema','$ID_livelloPopolazione');";
           // echo $sql_delibera."<br>";
            $ris_delibera = esegui_query($con,$sql_delibera);

            ?>

                 <br>
                  <br>
                  <center>
                  <div class="uk-grid">
                   <div class="uk-width-medium-1-4 uk-width-small-1-4"></div>
                   <div class="uk-width-medium-2-4 uk-width-small-2-4">
                      <div class="uk-alert uk-alert-success" data-uk-alert>
                        <a href="" class="uk-alert-close uk-close"></a>
                         <strong>Processo di creazione delibera terminato.</strong>
                         <p align="center"> L'operazione di trasformazione dell'idea in delibera e' avvenuto con successo.</p>
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
        <br>
            <center><h2>Agenda Personale - Le tue Delibere</h2></center>
            <br>
              <ul class="uk-subnav uk-subnav-pill" aling="right">
                  <li><a href="../membroIstituzione/agenda_idee.php"><i class="uk-icon-lightbulb-o"></i> Idee in Agenda</a></li>
                  <li class="uk-active"><a href="../membroIstituzione/home.php"><i class="uk-icon-book"></i> Le tue Delibere</a></li>
              </ul>
            <br>
            <div class="uk-overflow-container">  
              <table class="uk-table">
                    <thead>
                          <tr>
                            <th>TITOLO DELIBERA</th>
                            <th>TEMA</th>
                            <th>STATO DELIBERA</th>
                            <th>APRI</th>
                            <th>CANCELLA</th>
                          </tr>
                    </thead>
                    <tbody>
                      <?php   

                      $con = connetti("my_canvass");
                      $sql_estrai = "SELECT * FROM eletto WHERE ID_utente = '$ID_utente';";
                     // echo $sql_estrai."<br>";
                      $row_estrai = esegui_query_stringa($con,$sql_estrai);

                      $ID_eletto = $row_estrai['ID_eletto'];

                      $sql_estrai_delibere = "SELECT * FROM delibere INNER JOIN eletto ON  delibere.ID_eletto=eletto.ID_eletto WHERE delibere.cancellato = 'NO' AND eletto.ID_eletto='$ID_eletto' ORDER BY ID_delibera DESC;";
                      $ris_estrai_delibere = esegui_query($con,$sql_estrai_delibere);

                      while($row_estrai_delibere = mysqli_fetch_assoc($ris_estrai_delibere))
                      {


                        $sql_estrai_tema = "SELECT * FROM tema WHERE cancellato = 'NO' AND ID_tema = '".pulisci($row_estrai_delibere['ID_tema'])."';";
                        $row_estrai_tema = esegui_query_stringa($con,$sql_estrai_tema);

                        echo "<tr><td>".filtraOutput($row_estrai_delibere['titolo'])."</td><td>".filtraOutput($row_estrai_tema['nome'])."</td>";
                        
                        if($row_estrai_delibere['stato']=="Respinta")
                        echo "<center><td><font color='red'>".filtraOutput($row_estrai_delibere['stato'])."</font></center></td>";

                        if($row_estrai_delibere['stato']=="In Attesa di Conferma")
                        echo "<center><td><font color='orange'>".filtraOutput($row_estrai_delibere['stato'])."</font></center></td>";
                     
                        if($row_estrai_delibere['stato']=="Confermata")
                        echo "<center><td><font color='green'>".filtraOutput($row_estrai_delibere['stato'])."</font></center></td>";

                        if($row_estrai_delibere['stato']=="Da Confermare")
                        echo "<center><td><font color='black'>".filtraOutput($row_estrai_delibere['stato'])."</font></center></td>";

                        if($row_estrai_delibere['stato']=="DA Riformulare")
                        echo "<center><td><font color='blue'>".filtraOutput($row_estrai_delibere['stato'])."</font></center></td>";

                      ?>
                     
                      <td width="1%">
                        <form action="apri_delibera.php" method="post">
                          <input type="hidden" name="ID_delibera" value="<?php echo filtraOutput($row_estrai_delibere['ID_delibera']);?>">
                          <button type="submit" class="uk-button uk-button-success"><i class="uk-icon-search"></i></button>
                        </form>  
                      </td>

                       <td width="1%">
                        <form action="home.php" method="post">
                          <input type="hidden" name="ID_delibera" value="<?php echo filtraOutput($row_estrai_delibere['ID_delibera']);?>">
                          <input type="hidden" name="token_cancella" value="1">
                           <button type="submit" class="uk-button uk-button-danger"><i class="uk-icon-remove"></i></button>
                        </form>  
                      </td>
                      </tr>
                      <?php

                          

                       }//close while 

                      ?>
                   
                   
                    </tbody>      
                  </table>
                 </div><!-- chiudi div container overflow -->



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