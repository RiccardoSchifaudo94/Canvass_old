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

if($_SESSION['ID_controllo']==1 AND $row_ruolo['ID_ruolo']<3)

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

                                    <a href="../profiloUtente/home.php"><i class="uk-icon-user"></i> <?php echo filtraOutput($row['nome']);?></a>



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



                if(isset($_POST['token_inserisci'])){

                 

                $con = connetti("my_canvass");







                $ID_tema = pulisci($_POST['ID_tema']);

                $Titolo = pulisci($_POST['titolo']);

                $Descrizione = pulisci($_POST['descrizione']);

                $descrizioneEstesa = pulisci($_POST['descrizioneEstesa']);

                //$Tema = pulisci($_POST['tema']);

                $Link = pulisci($_POST['link']);



                $sql_verifica = "SELECT COUNT(*) AS conta FROM idee WHERE titolo = '$Titolo' AND descrizione = '$Descrizione' AND ID_tema = '$ID_tema';";

                $row_verifica = esegui_query_stringa($con,$sql_verifica);

                $conta = 0;

                $conta = $row_verifica['conta'];



                if($conta==0)

                {



                $sql2 ="INSERT INTO idee(ID_utente,titolo,descrizione,descrizioneEstesa,ID_tema,link,cancellato) VALUES ('$ID_utente','$Titolo','$Descrizione','$descrizioneEstesa','$ID_tema','$Link','NO');";

                $rs = esegui_query($con,$sql2);

                //echo $sql2."<br>";

                $ultimo = mysqli_insert_id($con);

                $ID_foto = caricaImg();



                $sql_img_idea = "INSERT INTO associa_foto_idea(ID_foto,ID_idea)VALUES('$ID_foto','$ultimo');";

                $ris_img_idea = esegui_query($con,$sql_img_idea);

                //echo $sql_img_idea."<br>";



                if ($rs) {

                   

                    ?>

 <br>

                  <br>

                  <center>

                  <div class="uk-grid">

                   <div class="uk-width-medium-1-4 uk-width-small-1-4"></div>

                   <div class="uk-width-medium-2-4 uk-width-small-2-4">

                      <div class="uk-alert uk-alert-success" data-uk-alert>

                        <a href="" class="uk-alert-close uk-close"></a>

                        <strong>Idea Pubblicata</strong>

                        <br>Complimenti la tua idea e' stata pubblicata con successo nella Bacheca delle Idee.<br>

                      </div>

                   </div> 

                   <div class="uk-width-medium-1-4 uk-width-small-1-4"></div>

                   </div> 

                  </center>

                  <br>

                  <br> 

                



                <?php



                  



                } 

                else {

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

                        <br>Non e' stato possibile pubblicare la tua idea per incompletezza delle informazioni<br> o per il formato dell'immagine caricata<br>

                      </div>

                   </div> 

                   <div class="uk-width-medium-1-4 uk-width-small-1-4"></div>

                   </div> 

                  </center>

                  <br>

                  <br> 



                   <?php

                    }//close else



                }//close if di controllo per verificare che l'idea non sia stata ancora inserita



                }//close if di isset($_POST['token_inserisci'])



                ?>    







        <div class="uk-grid">

        <div class="uk-width-medium-1-5 uk-width-small-1-5"></div> 

        <div class="uk-width-medium-3-5 uk-width-small-3-5">  

            <center><h2>Proponi Idea</h2></center>

                <br>

                <form action="propose_idea.php" method="post" enctype="multipart/form-data">

                <label>Titolo</label>

                <div class="uk-form-row">

                    <input class="uk-width-1-1 uk-form-large" type="text" placeholder="Titolo idea" name="titolo">

                </div>  

                <br>

                <label>Introduzione</label>

                <div class="uk-form-row">

                   <textarea class="uk-width-1-1" rows="5" name="descrizione" palceholder="Scrivi Breve Presentazione"></textarea>

                </div>

                <br>

                <label>Descrizione</label>

                <div class="uk-form-row">

                   <textarea class="uk-width-1-1" rows="10" name="descrizioneEstesa" palceholder="Scrivi Descrizione Estesa"></textarea>

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



                        <option value="<?php echo filtraOutput($row_tema['ID_tema']);?>"><?php echo filtraOutput($row_tema['nome']);?></option>



                        <?php



                       }//chiusura ciclo while di estrazione delle voci tematiche da associare all'idea





                       ?>



                      </select>

                  </div> 

                  <br>

                  <label>Link</label>

                  <div class="uk-form-row">

                       <input class="uk-width-1-1 uk-form-large" type="text" placeholder="Inserisci link" name="link"> 

                  </div>

                  <br>

                  <label>Carica immagine</label>  

                  <div class="uk-form-file">

                     <input type="file" name="image" class="uk-width-1-1 uk-form-large">

                  </div>

                  <br>

                  <input type="hidden" name="token_inserisci" value="1">

                  <button class="uk-button uk-button-success" type="submit"><i class="uk-icon-check"></i> Conferma</button>

             </form>     

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