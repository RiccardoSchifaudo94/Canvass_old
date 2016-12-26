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
          $ID_delibera = pulisci($_POST['ID_delibera']);
         // $ID_eletto = pulisci($_POST['ID_eletto']);
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
            
            if($_POST['token_rimuovi_link'])
            {
             
             $ID_link = pulisci($_POST['ID_link']);
             $ID_delibera = pulisci($_POST['ID_delibera']);
         
                
             $con = connetti("my_canvass");
             
             $sql_conta = "SELECT COUNT(*) AS conta FROM associa_link_delibera INNER JOIN link "
                     . "ON associa_link_delibera.ID_link=link.ID_link "
                     . "WHERE associa_link_delibera.ID_delibera = '$ID_delibera' AND link.ID_link = '$ID_link';";
             $row_conta = esegui_query_stringa($con,$sql_conta);
             //echo $sql_conta."<br>";
             $conta = 0;
             $conta = $row_conta['conta'];
             if($conta!=0)
             {
                 
                 $sql_canc = "DELETE  FROM associa_link_delibera "
                         . "WHERE ID_delibera = '$ID_delibera' AND  ID_link = '$ID_link';";
                 $ris_canc = esegui_query($con,$sql_canc);
                 //echo $sql_canc."<br>";
                 $sql_canc2 = "DELETE FROM link WHERE ID_link = '$ID_link';";
                 $ris_canc2 = esegui_query($con,$sql_canc2);
                 //echo $sql_canc2."<br>";
                 ?>  
                 
                  <br>
                  <br>
                  <center>
                  <div class="uk-grid">
                   <div class="uk-width-medium-1-4 uk-width-small-1-4"></div>
                   <div class="uk-width-medium-2-4 uk-width-small-2-4">
                      <div class="uk-alert uk-alert-success" data-uk-alert>
                        <a href="" class="uk-alert-close uk-close"></a>
                        <strong>Link rimosso con successo!</strong>
                        <br>Un link aggiuntivo e' stato rimosso da questa delibera.<br>
                      </div>
                   </div> 
                   <div class="uk-width-medium-1-4 uk-width-small-1-4"></div>
                   </div> 
                  </center>
                  <br>
                  <br> 
         
                 <?php
                 
             }  
                
                
            }//chiudi token rimuovi links
            
            
            ?>
         
         
            <?php  
            
            if(isset($_POST['token_aggiungi_link']))
            {
                
             $ID_delibera = pulisci($_POST['ID_delibera']); 
             $nome = pulisci($_POST['nome']);
             
             $con = connetti("my_canvass");
             
             $sql_conta = "SELECT COUNT(*) AS conta FROM associa_link_delibera INNER JOIN link "
                     . "ON associa_link_delibera.ID_link=link.ID_link "
                     . "WHERE associa_link_delibera.ID_delibera = '$ID_delibera' AND link.nome = '$nome';";
             $row_conta = esegui_query_stringa($con,$sql_conta);
             //echo $sql_conta."<br>";
             $conta = 0;
             $conta = $row_conta['conta'];
             if($conta==0)
             {
                 
                 $sql_add_link = "INSERT INTO link(nome) VALUES('$nome');";
                 $ris_add_link = esegui_query($con, $sql_add_link);
                 $ultimo = mysqli_insert_id($con);
                 //echo $sql_add_link."<br>";
                 
                 $sql_ass_link = "INSERT INTO associa_link_delibera(ID_link,ID_delibera)VALUES('$ultimo','$ID_delibera');";
                 $ris_ass_link = esegui_query($con, $sql_ass_link);
                 //echo $sql_ass_link."<br>";
                 ?>   
                 
                  <br>
                  <br>
                  <center>
                  <div class="uk-grid">
                   <div class="uk-width-medium-1-4 uk-width-small-1-4"></div>
                   <div class="uk-width-medium-2-4 uk-width-small-2-4">
                      <div class="uk-alert uk-alert-success" data-uk-alert>
                        <a href="" class="uk-alert-close uk-close"></a>
                        <strong>Link aggiunto con successo!</strong>
                        <br>Un link aggiuntivo e' stato caricato a questa delibera.<br>
                      </div>
                   </div> 
                   <div class="uk-width-medium-1-4 uk-width-small-1-4"></div>
                   </div> 
                  </center>
                  <br>
                  <br> 
         
                 <?php
             }//chiudi $conta == 0
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
                        <strong>Link analogo presente!</strong>
                        <br>Questo link e' gia' presente per questa delibera.<br>
                      </div>
                   </div> 
                   <div class="uk-width-medium-1-4 uk-width-small-1-4"></div>
                   </div> 
                  </center>
                  <br>
                  <br> 
         
                 <?php
                 }//chiudi else di $conta == 0
                
                
            }
            ?>
            
            
         
         
         
          <?php 


          if(isset($_POST['token_modifica_delibera']))
          {
              
            $titolo = pulisci($_POST['titolo']);
            $descrizione = pulisci($_POST['descrizione']);
            $ID_budget = pulisci($_POST['ID_budget']);
            $link_alboPretorio = pulisci($_POST['link_alboPretorio']);
            $ID_tema = pulisci($_POST['ID_tema']);
            $ID_livelloPopolazione = pulisci($_POST['ID_livelloPopolazione']);
            $ID_delibera = pulisci($_POST['ID_delibera']);
            //$ID_eletto = pulisci($_POST['ID_eletto']);
          

            $con = connetti("my_canvass");
         
            $sql = "UPDATE delibere SET titolo = '$titolo', descrizione = '$descrizione', ID_budget ='$ID_budget', link_alboPretorio = '$link_alboPretorio', ID_livelloPopolazione = '$ID_livelloPopolazione', ID_tema = '$ID_tema' WHERE ID_delibera = '$ID_delibera';";
            $ris = esegui_query($con,$sql);
            //echo $sql."<br>";
          ?>

               <br>
              <br>
              <center>
              <div class="uk-grid">
               <div class="uk-width-medium-1-4 uk-width-small-1-4"></div>
               <div class="uk-width-medium-2-4 uk-width-small-2-4">
                  <div class="uk-alert uk-alert-success" data-uk-alert>
                    <a href="" class="uk-alert-close uk-close"></a>
                      <strong>Modifica avvenuta con successo!<br></strong> 
                      <p align="center">Congratulazioni la tua delibera e' stata aggiornata con successo!</p>
                  </div>
               </div> 
               <div class="uk-width-medium-1-4 uk-width-small-1-4"></div>
               </div> 
              </center>
              <br>
              <br>  

           <?php           
            }//close token modifica delibera

          ?>    



          <?php 


              $con = connetti("my_canvass");
              $sql = "SELECT * FROM delibere WHERE ID_delibera = '".pulisci($_POST['ID_delibera'])."';";
              $row  = esegui_query_stringa($con,$sql);
             // echo $sql;

          ?>





        <div class="uk-grid">
        <div class="uk-width-medium-1-6 uk-width-small-1-6"></div> 
        <div class="uk-width-medium-4-6 uk-width-small-4-6">
        <br>
            <center><h2>Agenda Personale - Modifica Delibera</h2></center>
            <br>
            <form action="../membroIstituzione/modifica_delibera.php" method="post">
            
               <label>Titolo Delibera</label>
                <div class="uk-form-row">
                    <input type="text" name="titolo"  autofocus required class="uk-form-large uk-width-1-1" value="<?php echo filtraOutput($row['titolo']);?>">
                </div> 
                <br>
                <label>Descrizione</label>
                <div class="uk-form-row"> 
                  <textarea class="uk-width-1-1" rows="10" name="descrizione"><?php echo filtraOutput($row['descrizione']);?></textarea>
                </div> 
                <br>
                <label>Costo realizzazione</label>
                <div class="uk-form-row">  
                      <select name="ID_budget" class="uk-form-select uk-form-large uk-width-1-1">

                        <?php 
                        $con = connetti("my_canvass");
                        $sql_budget = "SELECT * FROM budget WHERE cancellato = 'NO';";
                        $ris_budget = esegui_query($con,$sql_budget);

                        while($row_budget = mysqli_fetch_assoc($ris_budget))
                        {


                        ?>

                        <option <?php if($row_budget['ID_budget']==$row['ID_budget']) echo "SELECTED=SELECTED"; ?> value="<?php echo pulisci($row_budget['ID_budget']); ?>"><?php echo filtraOutput($row_budget['nome']);?></option>

                        <?php

                        }//close ciclo while estrazione valori di budget

                        ?>

                      </select>
                </div> 
                <br>  
                <label>Link Delibera nell'Albo Pretorio</label>       
                <div class="uk-form-row">  
                    <input type="text" name="link_alboPretorio"  autofocus required class="uk-form-large uk-width-1-1"  value="<?php echo filtraOutput($row['link_alboPretorio']);?>">
                </div>
                <br>
                <label>Tema</label>
                <div class="uk-form-row">   
                       <select class="uk-form-select uk-form-large uk-width-1-1" name='ID_tema'>
                             
                             <?php

                             $con = connetti("my_canvass");
                             $sql_tema = "SELECT * FROM tema WHERE cancellato = 'NO';";
                             $ris_tema = esegui_query($con,$sql_tema);

                             while($row_tema = mysqli_fetch_assoc($ris_tema))
                             {

                              ?>

                              <option <?php if($row_tema['ID_tema']==$row['ID_tema']) echo "SELECTED=SELECTED"; ?> value="<?php echo filtraOutput($row_tema['ID_tema']);?>"><?php echo filtraOutput($row_tema['nome']);?></option>

                              <?php

                             }//chiusura ciclo while di estrazione delle voci tematiche da associare all'idea


                             ?>
                            </select>
                    </div> 
                    <br>
                    <label>Popolazione nel luogo di mandato</label>
                    <div class="uk-form-row"> 
                      <select name="ID_livelloPopolazione" class="uk-form-select uk-form-large uk-width-1-1">

                        <?php 
                        $con = connetti("my_canvass");
                        $sql_people = "SELECT * FROM popolazione WHERE cancellato = 'NO';";
                        $ris_people = esegui_query($con,$sql_people);

                        while($row_people = mysqli_fetch_assoc($ris_people))
                        {


                        ?>

                        <option <?php if($row_people['ID_livelloPopolazione']==$row['ID_livelloPopolazione']) echo "SELECTED=SELECTED"; ?> value="<?php echo pulisci($row_people['ID_livelloPopolazione']); ?>"><?php echo filtraOutput($row_people['nome']);?></option>

                        <?php

                        }//close ciclo while estrazione valori di budget

                        ?>

                      </select>
                    </div> 
                    <br>
                    <!--<label>Allega Documento di Delibera</label>
                    <div class="uk-form-row"> 
                      <input type="file" class="uk-form-large"  name="allegato">
                    </div> -->
                 
                
             
                    <input type="hidden" name="ID_utente" value="<?php echo pulisci($_POST['ID_utente']);?>"> 
                    <input type="hidden" name="ID_delibera" value="<?php echo pulisci($_POST['ID_delibera']);?>">
                    <input type="hidden" name="token_modifica_delibera" value="1">
                    <button type="submit" class="uk-button uk-button-success"><i class="uk-icon-check"></i> Conferma</button>
                    </form>
                    <br>
                    <form action="apri_delibera.php" method="post">
                    <input type="hidden" name="ID_delibera" value="<?php echo filtraOutput($_POST['ID_delibera']);?>">  
                    <button type="submit" class="uk-button uk-button-primary"><i class="uk-icon-eye"></i> Visualizza</button>
                    </form>
                     <br>
        <hr>
        <?php 
           if(!empty($row['link_alboPretorio']))
           {

           ?>
           <label>Aggiungi Link</label>
           <form action="modifica_delibera.php" method="post">
            <div class="uk-form-row">
              <div class="uk-width-medium-1-1 uk-witdh-small-1-1">   
                   <input class="uk-width-1-1 uk-form-large" type="text" placeholder="Inserisci link" name="nome"> 
                   <input type="hidden" name="ID_delibera" value="<?php echo filtraOutput($_POST['ID_delibera'])?>">
              </div>      
              <br>
              <div class="uk-width-medium-1-1 uk-witdh-small-1-1">  
                 <input type="hidden" name="token_aggiungi_link" value="1"> 
                 <button class="uk-button uk-button-success"><i class="uk-icon-plus"></i> Aggiungi</button>
              </div>
            </div> 
           </form>    
           <?php    
           }//close if di !empty($row['link'])
          ?>
           
            <br>
           
                   <?php 
                   
                   $con = connetti("my_canvass");
                   $sql_estrai_link = "SELECT * FROM link INNER JOIN associa_link_delibera ON link.ID_link=associa_link_delibera.ID_link "
                           . "WHERE associa_link_delibera.ID_delibera = '$ID_delibera';";
                   
                   $n = calcola_righe_query($con, $sql_estrai_link);
                   if($n!=0)
                   {
                       echo "<br><label>Link Aggiuntivo</label>";
                       echo " <div class='uk-form-row'>";
                       echo "<div class='uk-overflow-container'>";
                     
                       echo "<table class='uk-table' width='100%'>"
                       . "<thead><tr><th>Link</th><th>Rimuovi</th></tr></thead>"
                       . "<tbody width='100%'>";
                      
                       $ris_estrai_link = esegui_query($con,$sql_estrai_link);
                       while($row_estrai_link = mysqli_fetch_assoc($ris_estrai_link))
                       {
                           ?>
                           <tr>
                           <td>  
                              
                                
                                            <h4><?php echo "<a href='".$row_estrai_link['nome']."' target='_blank'>".limitaStringa50(filtraOutput($row_estrai_link['nome']))."</a>"; 
                                           
                                            if(strlen($row_estrai_link['nome'])>50) echo "...";
                                            
                                            ?> 
                                            </h4>    
                            
                           </td>    
                           <td width="1%">     
                               <center>
                                   <form action="modifica_delibera.php" method="post">
                                          <input name="token_rimuovi_link" type="hidden" value="1">
                                          <input type="hidden" name="ID_delibera" value="<?php echo filtraOutput($_POST['ID_delibera'])?>">
                                          <input type="hidden" name="ID_link" value="<?php echo filtraOutput($row_estrai_link['ID_link'])?>">
                                          <button class="uk-button uk-button-danger"><i class="uk-icon-minus"></i></button></h3>
                                   </form>
                               </center>
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