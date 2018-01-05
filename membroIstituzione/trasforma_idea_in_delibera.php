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



        <?php   //  --------------------------------------- AREA DEI TOKEN DI ESECUZIONE -------------------------------------------?>



    











        <div class="uk-grid">

        <div class="uk-width-medium-1-6 uk-width-small-1-6"></div> 

        <div class="uk-width-medium-4-6 uk-width-small-4-6">

        <br>

            <center><h2>Agenda Personale - Crea Delibera da Idea</h2></center>

            <br>

            <form action="../membroIstituzione/home.php" method="post">

            

               <label>Titolo Delibera</label>

                <div class="uk-form-row">

                    <input type="text" name="titolo"  autofocus required class="uk-form-large uk-width-1-1">

                </div> 

                <br>

                <label>Descrizione</label>

                <div class="uk-form-row"> 

                  <textarea class="uk-width-1-1" rows="10" name="descrizione"></textarea>

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



                        <option value="<?php echo pulisci($row_budget['ID_budget']); ?>"><?php echo filtraOutput($row_budget['nome']);?></option>



                        <?php



                        }//close ciclo while estrazione valori di budget



                        ?>



                      </select>

                </div> 

                <br>  

                <label>Link Delibera nell'Albo Pretorio</label>       

                <div class="uk-form-row">  

                    <input type="text" name="link_alboPretorio"  autofocus required class="uk-form-large uk-width-1-1">

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



                              <option value="<?php echo filtraOutput($row_tema['ID_tema']);?>"><?php echo filtraOutput($row_tema['nome']);?></option>



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



                        <option value="<?php echo pulisci($row_people['ID_livelloPopolazione']); ?>"><?php echo filtraOutput($row_people['nome']);?></option>



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

                    <input type="hidden" name="ID_idea" value="<?php echo pulisci($_POST['ID_idea']);?>">

                    <input type="hidden" name="token_trasforma_idea_delibera" value="1">

                    <button type="submit" class="uk-button uk-button-success"><i class="uk-icon-check"></i> Conferma</button>

                    </form>

             











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