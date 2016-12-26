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

            $ID_idea = pulisci($_POST['ID_idea']);

            //SE REFRESH LA PAGINA E PERDE ID_idea RIAMANDO L'UTENTE AL HOMEPAGE DELLE IDEE
            $stato = 1;
            if($ID_idea!="")
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
                                    <a href="">Spazio Personale</a>

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
                                    <a href="../profiloUtente/home.php"><i class="uk-icon-user"></i> <?php echo filtraOutput($row['nome']);?></a>

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

        <?php   //-------------------------------------------       AREA DEI TOKEN ----------------------------------------------?>

            <?php   
            
            if($_POST['token_rimuovi_link'])
            {
             
             $ID_link = pulisci($_POST['ID_link']);
             $ID_idea = pulisci($_POST['ID_idea']);
         
                
             $con = connetti("my_canvass");
             
             $sql_conta = "SELECT COUNT(*) AS conta FROM associa_link_idea INNER JOIN link "
                     . "ON associa_link_idea.ID_link=link.ID_link "
                     . "WHERE associa_link_idea.ID_idea = '$ID_idea' AND link.ID_link = '$ID_link';";
             $row_conta = esegui_query_stringa($con,$sql_conta);
             //echo $sql_conta."<br>";
             $conta = 0;
             $conta = $row_conta['conta'];
             if($conta!=0)
             {
                 
                 $sql_canc = "DELETE  FROM associa_link_idea "
                         . "WHERE ID_idea = '$ID_idea' AND  ID_link = '$ID_link';";
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
                        <br>Un link aggiuntivo e' stato rimosso a questa idea.<br>
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
                
             $ID_idea = pulisci($_POST['ID_idea']); 
             $nome = pulisci($_POST['nome']);
             
             $con = connetti("my_canvass");
             
             $sql_conta = "SELECT COUNT(*) AS conta FROM associa_link_idea INNER JOIN link "
                     . "ON associa_link_idea.ID_link=link.ID_link "
                     . "WHERE associa_link_idea.ID_idea = '$ID_idea' AND link.nome = '$nome';";
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
                 
                 $sql_ass_link = "INSERT INTO associa_link_idea(ID_link,ID_idea)VALUES('$ultimo','$ID_idea');";
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
                        <br>Un link aggiuntivo e' stato caricato a questa idea.<br>
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
                        <br>Questo link e' gia' presente per questa idea.<br>
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

                if(isset($_POST['token_aggiorna'])){

                $con = connetti("my_canvass");  
              

                $titolo = pulisci($_POST['titolo']);
                $descrizione = pulisci($_POST['descrizione']);
                $descrizioneEstesa = pulisci($_POST['descrizioneEstesa']);
                $ID_tema = pulisci($_POST['ID_tema']);
                $link = pulisci($_POST['link']); 
                $ID_idea = pulisci($_POST['ID_idea']);

                $sql_aggiorna_idea = "UPDATE idee SET titolo ='$titolo', descrizione='$descrizione',descrizioneEstesa = '$descrizioneEstesa' ,ID_tema = '$ID_tema',link = '$link',cancellato='NO' WHERE ID_idea = '$ID_idea';";

             
                $result=esegui_query($con,$sql_aggiorna_idea);

                $ID_foto = caricaImg();
                if($ID_foto!=0)
                {    

                $sql_canc = "DELETE FROM associa_foto_idea WHERE ID_idea = '$ID_idea';";
                $ris_canc = esegui_query($con, $sql_canc);
                $sql_img_idea = "INSERT INTO associa_foto_idea(ID_foto,ID_idea)VALUES('$ID_foto','$ID_idea');";
                $ris_img_idea = esegui_query($con,$sql_img_idea);
                //echo $sql_img_idea."<br>";
                
                }

                if($result){


                ?>
              
                 <br>
                  <br>
                  <center>
                  <div class="uk-grid">
                   <div class="uk-width-medium-1-4 uk-width-small-1-4"></div>
                   <div class="uk-width-medium-2-4 uk-width-small-2-4">
                      <div class="uk-alert uk-alert-success" data-uk-alert>
                        <a href="" class="uk-alert-close uk-close"></a>
                        <strong>Aggiornamento avvenuto con successo!</strong>
                        <br>Questa idea e' stata aggiornata correttamente.<br>
                      </div>
                   </div> 
                   <div class="uk-width-medium-1-4 uk-width-small-1-4"></div>
                   </div> 
                  </center>
                  <br>
                  <br> 

                <?php
                }//close if($result)

                else{
                ?>

                 <br>
                  <br>
                  <center>
                  <div class="uk-grid">
                   <div class="uk-width-medium-1-4 uk-width-small-1-4"></div>
                   <div class="uk-width-medium-2-4 uk-width-small-2-4">
                      <div class="uk-alert uk-alert-danger" data-uk-alert>
                        <a href="" class="uk-alert-close uk-close"></a>
                        <strong>Operazione Fallita!</strong>
                        <br>Non e' stato possibile aggiornare la tua idea.<br>
                      </div>
                   </div> 
                   <div class="uk-width-medium-1-4 uk-width-small-1-4"></div>
                   </div> 
                  </center>
                  <br>
                  <br> 

                <?php
                }





                }//close if(isset['token_aggiorna'])


                ?>
               
                <?php 
                  // ESTRAGGO I DATI DELL'IDEA DA MODIFICARE

                $ID_idea = pulisci($_POST['ID_idea']);


                $con = connetti("my_canvass");
                $sql = "SELECT * FROM idee WHERE cancellato = 'NO' AND ID_idea = '$ID_idea';";
                $row = esegui_query_stringa($con,$sql);

                  //SE REFRESH LA PAGINA E PERDE ID_idea RIAMANDO L'UTENTE AL HOMEPAGE DELLE IDEE
            
        


                ?>


        <div class="uk-grid">
        <div class="uk-width-medium-1-5 uk-width-small-1-5"></div> 
        <div class="uk-width-medium-3-5 uk-width-small-3-5">  
            <center><h2>Modifica Idea</h2></center>
                <br>
                <form action="modifica_idea.php" method="post" enctype="multipart/form-data">
                <label>Titolo</label>
                <div class="uk-form-row">
                    <input class="uk-width-1-1 uk-form-large" type="text" placeholder="Titolo idea" name="titolo" value="<?php echo filtraOutput($row['titolo']);?>">
                </div>  
                <br>
                <label>Introduzione</label>
                <div class="uk-form-row">
                   <textarea class="uk-width-1-1" rows="5" name="descrizione" palceholder="Scrivi Breve Presentazione"><?php echo filtraOutput($row['descrizione']);?></textarea>
                </div>
                <br>
                <label>Descrizione</label>
                <div class="uk-form-row">
                   <textarea class="uk-width-1-1" rows="10" name="descrizioneEstesa" palceholder="Scrivi Descrizione Estesa"><?php echo filtraOutput($row['descrizioneEstesa']);?></textarea>
                </div>
                <br>
                <label>Seleziona Tema</label>
                <div class="uk-form-row">
                      <select class="uk-width-1-1 uk-form-large uk-form-select" name='ID_tema'>
                       
                       <?php

                       $con = connetti("my_canvass");
                       $sql_tema = "SELECT * FROM tema WHERE cancellato = 'NO';";
                       $ris_tema = esegui_query($con,$sql_tema);

                       while($row_tema = mysqli_fetch_assoc($ris_tema))
                       {

                        ?>

                        <option <?php if($row['ID_tema']==$row_tema['ID_tema']) echo "SELECTED=SELECTED";?> value="<?php echo filtraOutput($row_tema['ID_tema']);?>"><?php echo filtraOutput($row_tema['nome']);?></option>

                        <?php

                       }//chiusura ciclo while di estrazione delle voci tematiche da associare all'idea


                       ?>

                      </select>
                  </div> 
                  <br>
                  <label>Link</label>
                  <div class="uk-form-row">
                       <input class="uk-width-1-1 uk-form-large" type="text" placeholder="Inserisci link" name="link" value="<?php echo filtraOutput($row['link']);?>"> 
                  </div>
                  <br>
                  <label>Carica immagine</label>  
                  <div class="uk-form-file">
                     <input type="file" name="image" class="uk-width-1-1 uk-form-large">
                  </div>
                  <br>
                  <input type="hidden" name="token_aggiorna" value="1">
                  <input type="hidden" name="ID_idea" value="<?php echo filtraOutput($_POST['ID_idea']);?>">
                  <button class="uk-button uk-button-success" type="submit"><i class="uk-icon-check"></i> Conferma</button>
             </form>
             <br>
             <form action="dettagli_idea.php" method="post">
                  <input type="hidden" name="ID_idea" value="<?php echo filtraOutput($_POST['ID_idea']);?>">
                  <button class="uk-button uk-button-primary" type="submit"><i class="uk-icon-eye"></i> Visualizza</button>
             </form>     
       
        <br>
        <hr>
        <?php 
           if(!empty($row['link']))
           {

           ?>
           <label>Aggiungi Link</label>
           <form action="modifica_idea.php" method="post">
            <div class="uk-form-row">
              <div class="uk-width-medium-1-1 uk-witdh-small-1-1">   
                   <input class="uk-width-1-1 uk-form-large" type="text" placeholder="Inserisci link" name="nome"> 
                   <input type="hidden" name="ID_idea" value="<?php echo filtraOutput($_POST['ID_idea'])?>">
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
                   $sql_estrai_link = "SELECT * FROM link INNER JOIN associa_link_idea ON link.ID_link=associa_link_idea.ID_link "
                           . "WHERE associa_link_idea.ID_idea = '$ID_idea';";
                   
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
                                   <form action="modifica_idea.php" method="post">
                                          <input name="token_rimuovi_link" type="hidden" value="1">
                                          <input type="hidden" name="ID_idea" value="<?php echo filtraOutput($_POST['ID_idea'])?>">
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
          <div class="uk-width-medium-1-5 uk-width-small-1-5"></div> 
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