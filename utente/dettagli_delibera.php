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
if($_SESSION['ID_controllo']==1 AND $row_ruolo['ID_ruolo']<3)
{

?>     
 <?php 
            // ESTRAPOLO NOME UTENTE LOGGATO    
            $con = connetti("my_canvass");
            $sql_nome = "SELECT * FROM utente WHERE cancellato = 'NO' AND ID_utente = '$ID_utente';"; 
            $row = esegui_query_stringa($con, $sql_nome);
            $ID_delibera = pulisci($_POST['ID_delibera']);

            //SE REFRESH LA PAGINA E PERDE ID_idea RIAMANDO L'UTENTE AL HOMEPAGE DELLE IDEE
            $stato = 1;
            if($ID_delibera!="")
              $stato = 0;
            if($stato==1)
            header("location:home.php");
        
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
                                    <a href="home.php">Spazio Personale</a>

                                    <div class="uk-dropdown uk-dropdown-navbar">
                                        <ul class="uk-nav uk-nav-navbar">
                                            <li><a href="propose_idea.php"><i class="uk-icon-edit"></i> Proponi idea</a></li>
                                            <li><a href="home.php"><i class="uk-icon-lightbulb-o"></i> Le tue idee</a></li>
                                            <li><a href="delibere_da_confermare.php"><i class="uk-icon-check"></i> Delibere da confermare</a></li>
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
                                    <li><a href="propose_idea.php"><i class="uk-icon-edit"></i> Proponi idea</a></li>
                                    <li><a href="home.php"><i class="uk-icon-lightbulb-o"></i> Le tue idee</a></li>
                                    <li><a href="delibere_da_confermare.php"><i class="uk-icon-check"></i> Delibere da confermare</a></li>
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
            <?php   //------------------------------------------->>>>>>>>>>>>>>>>>>>   AREA DI LAVORO DEI TOKEN      <<<<<<<<<<<<<<<<<----------------------------?>
     


         <?php

                    if(isset($_POST['token_respingi_delibera2']))
                    {

                      $ID_delibera = pulisci($_POST['ID_delibera']); 
                        
                      $con = connetti("my_canvass");
                      $sql_respingi = "UPDATE delibere SET confermato = 'NO', stato = 'Respinta' WHERE ID_delibera = '$ID_delibera';";
                      $ris_respingi = esegui_query($con,$sql_respingi);

                      $sql_rimuovi = "DELETE FROM associa_delibere_utente WHERE ID_utente = '$ID_utente' AND ID_delibera = '$ID_delibera' ;";
                      $ris_rimuovi = esegui_query($con,$sql_rimuovi);
                     // echo $sql_rimuovi."<br>";
                      
                       $sql_estrai_utente = "SELECT eletto.ID_utente FROM eletto INNER JOIN delibere "
                              . "ON eletto.ID_eletto=delibere.ID_eletto WHERE delibere.ID_delibera = '$ID_delibera';";
                      $row_estrai_utente = esegui_query_stringa($con,$sql_estrai_utente);
                      
                      $sql_notifica_respingi = "INSERT INTO notifica(ID_utente,tipo)VALUES('".$row_estrai_utente['ID_utente']."','Delibera_respinta');";
                      $ris_notifica_respingi = esegui_query($con,$sql_notifica_respingi);
                    //  echo $sql_notifica_conferma."<br>";
                      
                      $ID_notifica_ultima = mysqli_insert_id($con);
                      $sql_setta_delibera_respingi = "INSERT INTO associa_notifica_delibera(ID_notifica,ID_delibera,ID_utente)"
                              . "VALUES('$ID_notifica_ultima','$ID_delibera','$ID_utente');";
                      $ris_setta_delibera_respingi = esegui_query($con,$sql_setta_delibera_respingi);
                     // echo $sql_setta_delibera_conferma."<br>";       
                      //echo $sql_setta_delibera_conferma."<br>";


                      ?>

                      <br>
                      <br>
                      <center>
                      <div class="uk-grid">
                       <div class="uk-width-medium-1-4 uk-width-small-1-4"></div>
                       <div class="uk-width-medium-2-4 uk-width-small-2-4">
                          <div class="uk-alert uk-alert-danger" data-uk-alert>
                            <a href="" class="uk-alert-close uk-close"></a>
                              <strong>Delibera Respinta!</strong>
                              <p align="center">Questa delibera e' stata respinta</p><p> Il membro incaricato non potra' pubblicare la sua delibera!</p>
                              <br>
                            <br> 
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

                    <?php
                    if(isset($_POST['token_respingi_delibera']))
                    {
                    ?>


                     <br>
                      <br>
                      <center>
                      <div class="uk-grid">
                       <div class="uk-width-medium-1-4 uk-width-small-1-4"></div>
                       <div class="uk-width-medium-2-4 uk-width-small-2-4">
                          <div class="uk-alert uk-alert-danger" data-uk-alert>
                            <a href="" class="uk-alert-close uk-close"></a>
                              <strong>Vuoi respingere veramente questa delibera?</strong>
                              <p align="center">Stai per bocciare questa delibera.</p><p>Respingendo la delibera, vieti all'autore la pubblicazione di questo atto, in quanto non rispetta l'idea originaria.</p>
                              <br>
                            <br>
                            <div class="uk-grid">
                              <div class="uk-width-medium-4-10 uk-width-small-4-10"></div>
                              <div class="uk-width-medium-1-10 uk-width-small-1-10">
                                  <form action="dettagli_delibera.php" method="post"> 
                                        <input type='hidden' name='ID_delibera' value="<?php echo filtraOutput($_POST['ID_delibera']);?>">
                                        <input type='hidden' name='token_respingi_delibera2' value="1">
                                     <button class='uk-button uk-button-danger' type='submit'>SI</button>
                                  </form> 
                              </div>
                              <div class="uk-width-medium-1-10 uk-width-small-1-10">
                                  <form action="dettagli_delibera.php" method="post"> 
                                    <input type='hidden' name='ID_delibera' value="<?php echo filtraOutput($_POST['ID_delibera']);?>">
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
                    }//close token
                    ?>    



                    <?php         //----------------->>>>>>>>>>>>>       TOKEN DI CONFERMA DELIBERA   <<<<<<<<<<<<<<<<-----------------------?>

                    <?php

                    if(isset($_POST['token_conferma_delibera2']))
                    {

                      $ID_delibera = pulisci($_POST['ID_delibera']);   
                        
                      $con = connetti("my_canvass");
                      $sql_conferma = "UPDATE delibere SET confermato = 'SI', stato = 'Confermata' WHERE ID_delibera = '".pulisci($_POST['ID_delibera'])."';";
                      $ris_conferma = esegui_query($con,$sql_conferma);
                      
                      $sql_estrai_utente = "SELECT eletto.ID_utente FROM eletto INNER JOIN delibere "
                              . "ON eletto.ID_eletto=delibere.ID_eletto WHERE delibere.ID_delibera = '$ID_delibera';";
                      $row_estrai_utente = esegui_query_stringa($con,$sql_estrai_utente);
                      
                      $sql_notifica_conferma = "INSERT INTO notifica(ID_utente,tipo)VALUES('".$row_estrai_utente['ID_utente']."','Delibera_confermata');";
                      $ris_notifica_conferma = esegui_query($con,$sql_notifica_conferma);
                    //  echo $sql_notifica_conferma."<br>";
                      
                      $ID_notifica_ultima = mysqli_insert_id($con);
                      $sql_setta_delibera_conferma = "INSERT INTO associa_notifica_delibera(ID_notifica,ID_delibera,ID_utente)"
                              . "VALUES('$ID_notifica_ultima','$ID_delibera','$ID_utente');";
                      $ris_setta_delibera_conferma = esegui_query($con,$sql_setta_delibera_conferma);
                     // echo $sql_setta_delibera_conferma."<br>";       
                      //echo $sql_setta_delibera_conferma."<br>";


                      ?>

                      <br>
                      <br>
                      <center>
                      <div class="uk-grid">
                       <div class="uk-width-medium-1-4 uk-width-small-1-4"></div>
                       <div class="uk-width-medium-2-4 uk-width-small-2-4">
                          <div class="uk-alert uk-alert-success" data-uk-alert>
                            <a href="" class="uk-alert-close uk-close"></a>
                              <strong>Delibera confermata e pubblicata!</strong>
                              <p align="center">Complimenti la tua idea e' diventato un progetto.</p><p> Grazie a te e coloro che hanno ti hanno sostenuto!</p>
                              <br>
                            <br>  
                       </div> 
                       <div class="uk-width-medium-1-4 uk-width-small-1-4"></div>
                       </div> 
                      </center>
                      <br>
                      <br> 
                       
                 
                      <?php

                    }


                    ?>

                    <?php
                    if(isset($_POST['token_conferma_delibera']))
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
                              <strong>Vuoi confermare veramente questa delibera?</strong>
                              <p align="center">Stai per confermare questa delibera.</p><p> Confermando acconsenti alla pubblicazione di questo atto, in quanto riconosci l'operato dell'incaricato</p>
                              <br>
                            <br>
                            <div class="uk-grid">
                              <div class="uk-width-medium-4-10 uk-width-small-4-10"></div>
                              <div class="uk-width-medium-1-10 uk-width-small-1-10">
                                  <form action="dettagli_delibera.php" method="post"> 
                                        <input type='hidden' name='ID_delibera' value="<?php echo filtraOutput($_POST['ID_delibera']);?>">
                                        <input type='hidden' name='token_conferma_delibera2' value="1">
                                     <button class='uk-button uk-button-success' type='submit'>SI</button>
                                  </form> 
                              </div>
                              <div class="uk-width-medium-1-10 uk-width-small-1-10">
                                  <form action="dettagli_delibera.php" method="post"> 
                                    <input type='hidden' name='ID_delibera' value="<?php echo filtraOutput($_POST['ID_delibera']);?>">
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
                    }//close token
                    ?>  



            <?php 

              //              ------------------------->>>>>>>>>>>>>>> ESTRAGGO I DAT DELL'IDEA CHE STO VISUALIZZANDO <<<<<<<<<<<<<<<<<<<<<<<<<-------------------------------

            $con = connetti("my_canvass");
            $ID_delibera = pulisci($_POST['ID_delibera']);
           

            $sql = "SELECT * FROM delibere INNER JOIN eletto ON delibere.ID_eletto=eletto.ID_eletto WHERE delibere.cancellato = 'NO' AND delibere.ID_delibera = '$ID_delibera' AND eletto.cancellato = 'NO';";
            $row = esegui_query_stringa($con,$sql);
 
                                
            ?>  



        <div class="uk-grid">
        <div class="uk-width-medium-1-6 uk-width-small-1-6"></div> 
        <div class="uk-width-medium-4-6 uk-width-small-4-6">


              <div class="uk-grid" data-uk-grid-margin>
                <div class="uk-width-medium-3-4">
                  <div class="uk-grid">
                    <form action="home.php" method="post">
                      <button class="uk-button uk-button-primary"><i class="uk-icon-angle-left"></i> Indietro</button>
                    </form>  
                  </div>
                  <br>

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

                                //ESTRAI FOTO AUTORE
                                 /*
                                      $con = connetti("my_canvass");
                                      $sql_estrai_img = "SELECT * FROM associa_foto_delibera INNER JOIN foto ON associa_foto_delibera.ID_foto=foto.ID_foto WHERE foto.cancellato = 'NO' AND associa_foto_delibera.ID_delibera = '".pulisci($_POST['ID_delibera'])."';";
                                     // echo $sql_estrai_img."<br>";
                                      $row_estrai_img = esegui_query_stringa($con,$sql_estrai_img);
                                     // echo $row_estrai_img['percorso'];
                                 */    
                                     
                          ?>  

                        <!--
                        <p><img class="uk-image-preserve" src="<?php if($row_estrai_img['percorso']!=''){echo "../".$row_estrai_img['percorso'];} else echo "data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxNi4wLjQsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+DQo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkViZW5lXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4Ig0KCSB3aWR0aD0iOTAwcHgiIGhlaWdodD0iMzAwcHgiIHZpZXdCb3g9IjAgMCA5MDAgMzAwIiBlbmFibGUtYmFja2dyb3VuZD0ibmV3IDAgMCA5MDAgMzAwIiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxyZWN0IGZpbGw9IiNGNUY1RjUiIHdpZHRoPSI5MDAiIGhlaWdodD0iMzAwIi8+DQo8ZyBvcGFjaXR5PSIwLjciPg0KCTxwYXRoIGZpbGw9IiNEOEQ4RDgiIGQ9Ik0zNzguMTg0LDkzLjV2MTEzaDE0My42MzN2LTExM0gzNzguMTg0eiBNNTEwLjI0NCwxOTQuMjQ3SDM5MC40Mzd2LTg4LjQ5NGgxMTkuODA4TDUxMC4yNDQsMTk0LjI0Nw0KCQlMNTEwLjI0NCwxOTQuMjQ3eiIvPg0KCTxwb2x5Z29uIGZpbGw9IiNEOEQ4RDgiIHBvaW50cz0iMzk2Ljg4MSwxODQuNzE3IDQyMS41NzIsMTU4Ljc2NCA0MzAuODI0LDE2Mi43NjggNDYwLjAxNSwxMzEuNjg4IDQ3MS41MDUsMTQ1LjQzNCANCgkJNDc2LjY4OSwxNDIuMzAzIDUwNC43NDYsMTg0LjcxNyAJIi8+DQoJPGNpcmNsZSBmaWxsPSIjRDhEOEQ4IiBjeD0iNDI1LjQwNSIgY3k9IjEyOC4yNTciIHI9IjEwLjc4NyIvPg0KPC9nPg0KPC9zdmc+DQo=";?>"  width="1200px" height="800px" alt=""></p>
                        -->
                        <h2><i class="uk-icon-align-justify"></i>  Descrizione</h2>
                        <p><?php if(empty($row['descrizione']))echo "Nessuna descrizione"; else echo filtraOutput($row['descrizione']);?></p>

                        <h2><i class="uk-icon-euro"></i>  Budget</h2>
                        <p>
                          <?php 

                          $con = connetti("my_canvass");
                          $sql_budget = "SELECT * FROM budget WHERE cancellato = 'NO' AND ID_budget = '".$row['ID_budget']."';";
                          $row_budget = esegui_query_stringa($con,$sql_budget);
                          echo filtraOutput($row_budget['nome']);
                          ?>
                        </p>

                        <h2><i class="uk-icon-institution"></i>  Livello di istituzione</h2>
                        <p>
                          <?php 

                          $con = connetti("my_canvass");
                          $sql_istituzione = "SELECT * FROM livello_istituzione WHERE cancellato = 'NO' AND ID_livelloIstituzione = '".$row['ID_livelloIstituzione']."';";
                          $row_istituzione = esegui_query_stringa($con,$sql_istituzione);
                          echo filtraOutput($row_istituzione['nome']);
                          ?>
                        </p>

                        <h2><i class="uk-icon-users"></i>  Livello popolazione nel luogo mandato</h2>
                        <p>
                          <?php 

                          $con = connetti("my_canvass");
                          $sql_people = "SELECT * FROM popolazione WHERE cancellato = 'NO' AND ID_livelloPopolazione = '".$row['ID_livelloPopolazione']."';";
                          $row_people = esegui_query_stringa($con,$sql_people);
                          if(empty($row['ID_livelloPopolazione']))echo "Non specificato"; else echo filtraOutput($row_people['nome']);
                          ?>
                        </p>

                       
                        <h2><i class="uk-icon-key"></i>  Tema</h2>

                         <?php 

                              //ESTRAI NOME DEL CATEGORIA DELL'IDEA
                    
                              $sql_tema = "SELECT * FROM tema WHERE cancellato = 'NO' AND ID_tema = '".$row['ID_tema']."';";
                              $row_tema = esegui_query_stringa($con,$sql_tema);
                              echo "<p>".filtraOutput($row_tema['nome'])."</p>";

                          ?>

                        <h2><i class="uk-icon-link"></i>  Link Albo Pretorio</h2>

                        <p><?php if(empty($row['link_alboPretorio']))echo "Nessuna link"; else echo "<a href='".filtraOutput($row['link_alboPretorio'])."' target='_blank'>".filtraOutput($row['link_alboPretorio'])."</a>";?></p>  
                      

                    </article>
        
                </div>


                  <?php

                  //--------------------------------->>>>>>>>>>>>>>>>>>>>>>>>   ESTRAGGO L'IMMAGINE DELL'AUTORE     <<<<<<<<<<<<<<<<--------------------------

                  $con = connetti("my_canvass");
                  
                   $sql_utente = "SELECT * FROM utente INNER JOIN eletto INNER JOIN delibere ON utente.ID_utente=eletto.ID_utente "
                                              . "AND eletto.ID_eletto = delibere.ID_eletto WHERE utente.cancellato = 'NO' AND eletto.cancellato = 'NO' "
                                              . "AND delibere.ID_delibera = '".pulisci($_POST['ID_delibera'])."';";
                   $row_utente = esegui_query_stringa($con, $sql_utente);
                  
                  $sql_estrai_img = "SELECT * FROM associa_foto_profilo INNER JOIN foto ON associa_foto_profilo.ID_foto=foto.ID_foto WHERE foto.cancellato = 'NO' AND associa_foto_profilo.ID_utente = '".$row_utente['ID_utente']."';";
                  //echo $sql_estrai_img."<br>";
                  $row_estrai_img = esegui_query_stringa($con,$sql_estrai_img);
                  //echo $row_estrai_img['percorso'];
                  ?> 


                <div class="uk-width-medium-1-4">
                    <div class="uk-panel uk-panel-box uk-text-center">
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

                     <?php 
                     
                     //ESTRAGGO L'ID CONTATTO DELL'ELETTO DA INVIARE AL CENTRALINO MESSAGGI E POTERLI SCRIVERE UN MESSAGGIO MAIL CON LA MESSSAGGISTICA INTERNA
                         $con = connetti("my_canvass");
                         $sql_eletto = "SELECT * FROM delibere INNER JOIN eletto ON delibere.ID_eletto=eletto.ID_eletto WHERE delibere.cancellato='NO' AND eletto.cancellato='NO' AND ID_delibera = '".pulisci($_POST['ID_delibera'])."';";
                         $row_eletto = esegui_query_stringa($con,$sql_eletto);
                        
                         $sql_incaricato = "SELECT * FROM utente WHERE cancellato = 'NO' AND ID_utente = '".$row_eletto['ID_utente']."';";
                         //echo $sql_incaricato."<br>";
                         $row_incaricato = esegui_query_stringa($con,$sql_incaricato);
                         
                         ?>  

                    <div class="uk-grid">
                        <div class="uk-width-medium-1-1">
                          <br>
                           <form action="../messaggistica/scrivi2.php" method="post">
                              <input type="hidden" name="ID_idea" value="<?php echo filtraOutput($_POST['ID_idea'])?>">
                              <input type="hidden" name="ID_contatto" value="<?php echo filtraOutput($row_incaricato['ID_utente']);?>">  
                              <button class="uk-button uk-button-primary"><i class="uk-icon-edit"></i> Contatta Eletto</button>
                            </form>
                            <br> 
                            <form action="dettagli_delibera.php" method="post">
                              <input type="hidden" name="ID_delibera" value="<?php echo filtraOutput($_POST['ID_delibera'])?>">
                              <input type='hidden' name='token_conferma_delibera' value="1">
                              <button class="uk-button uk-button-success"><i class="uk-icon-thumbs-up"></i> Approva Delibera</button>
                            </form>
                            <br>  
                            <form action="dettagli_delibera.php" method="post">
                              <input type="hidden" name="ID_delibera" value="<?php echo filtraOutput($_POST['ID_delibera'])?>">
                              <input type='hidden' name='token_respingi_delibera' value="1">
                              <button class="uk-button uk-button-danger"><i class="uk-icon-thumbs-down"></i> Respingi Delibera</button>
                            </form>
                            <br>
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