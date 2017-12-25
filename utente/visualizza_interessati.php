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





       <?php  



            // SETTAGGIO NOTIFICHE PRESENZA SOGGETTI INTERESSATI A QUESTA IDEA 

            

            $sql_conta = "SELECT * FROM associa_notifica_idea WHERE ID_idea = '$ID_idea';";

            $ris_conta = esegui_query($con, $sql_conta);

            //echo $sql_conta."<br>";

            while($row_conta = mysqli_fetch_assoc($ris_conta))

            {

                

                $sql_setta = "UPDATE notifica SET letto = 'SI' "

                        . "WHERE letto = 'NO' "

                        . "AND ID_utente = '$ID_utente' "

                        . "AND tipo = 'Idea' AND ID_notifica = '".$row_conta['ID_notifica']."';";

                $ris_setta = esegui_query($con, $sql_setta);

                //echo $sql_setta."<br>";

                

            }

            

            

        ?>

      



        <div class="uk-grid">

        <div class="uk-width-medium-1-6 uk-width-small-1-6"></div> 

        <div class="uk-width-medium-4-6 uk-width-small-4-6">

      

        <div class="uk-grid">

                <form action="dettagli_idea.php" method="post">

                  <input type="hidden" name="ID_idea" value="<?php echo filtraOutput($_POST['ID_idea']);?>">

                  <button class="uk-button uk-button-primary"><i class="uk-icon-angle-left"></i> Indietro</button>

                </form> 

                <form action="propose_idea.php" method="post">

                      <button type="submit" class="uk-button uk-button-primary"><i class="uk-icon-lightbulb-o"></i> Proponi Idea</button>

                </form>  

        </div>

          

        

        <br>

            <center><h2>Interessati per questa idea</h2></center>

        <div class="uk-overflow-container">    

        <table class="uk-table">

      

        <thead>

            <tr>

                <th>Nome</th><th>Cognome</th><th>Carica</th><th>Istituzione</th><th>Luogo</th><!--<th>Dettagli</th>--><th>Contatta</th>

            </tr>

        </thead>        

        <tbody>

              <?php

        //con questa query scopro chi sono gli eletti interessati a questa idea

        $sql = "SELECT * FROM associa_idee_eletto INNER JOIN eletto ON associa_idee_eletto.ID_eletto=eletto.ID_eletto WHERE associa_idee_eletto.ID_idea='$ID_idea';";

        $ris = esegui_query($con,$sql);



        $n=0;

        $n = calcola_righe_query($con,$sql);

        if($n!=0)

        {

        

        while ($row=mysqli_fetch_assoc($ris)) 

        {

        

        //con questo query scorro le genralità dell'eletto associata a questa idea

        $sql_eletto = "SELECT * FROM utente INNER JOIN eletto ON utente.ID_utente=eletto.ID_utente WHERE eletto.ID_eletto ='".$row['ID_eletto']."';";

        $row_eletto = esegui_query_stringa($con,$sql_eletto);

       // $ris_eletto = esegui_query($con,$sql_eletto);

          //while($row_eletto=mysqli_fetch_assoc($ris_eletto))

          //{



            echo "<tr>";

            echo "<td>".$row_eletto['nome']."</td>"

                ."<td>".$row_eletto['cognome']."</td>";

            $sql_ruolo = "SELECT * FROM ruolo WHERE ID_ruolo = '".$row_eletto['ID_ruolo']."';";

            $row_ruolo = esegui_query_stringa($con,$sql_ruolo);

            

            echo "<td>".$row_ruolo['nome']."</td>";



            $sql_istituzione = "SELECT * FROM livello_istituzione WHERE ID_livelloIstituzione = '".$row_eletto['ID_livelloIstituzione']."';";

            $row_istituzione = esegui_query_stringa($con,$sql_istituzione);



            echo "<td>".$row_istituzione['nome']."</td>"

                ."<td>".$row_eletto['luogo_mandato']."</td>";

            /*    

            echo "<td>";

            ?>   

            <form action="dettagli_membro_istituzione.php" method="post">

              <input type="hidden" name="ID_eletto" value="<?php echo pulisci($row_eletto['ID_eletto']);?>">

              <input type="hidden" name="ID_idea" value="<?php echo pulisci($_POST['ID_idea']);?>">

              <button type="submit" class="uk-button uk-button-primary"><i class="uk-icon-info"></i> Dettagli</button>

            </form> 

            <?php 

            echo "</td>"; */

            $ID_contatto = filtraOutput($row_eletto['ID_utente']);

             echo "<td>";

            ?>   

            <form action="../messaggistica/scrivi2.php" method="post">

              <input type="hidden" name="ID_contatto" value="<?php echo pulisci($ID_contatto);?>">

              <input type="hidden" name="ID_idea" value="<?php echo pulisci($_POST['ID_idea']);?>">

              <button type="submit" class="uk-button uk-button-success"><i class="uk-icon-edit"></i></button>

            </form> 

            <?php 

            echo "</td>";     

            echo "<tr>";



         // }//chiduo il ciclo while interno che scorre le genralità personali degli eletti il quale interessa questa idea

       



        }//chiudo il while che scorre gli eletti associati a questa idea



         }//close if di !$ris

            else{



                                                ?>

                                                <br>

                                                <br>

                                                    <center>

                                                        <div class="uk-grid">

                                                            <div class="uk-width-medium-1-4 uk-width-small-1-4"></div>

                                                                <div class="uk-width-medium-2-4 uk-width-small-2-4">

                                                                    <div class="uk-alert uk-alert-info" data-uk-alert>

                                                                        <a href="" class="uk-alert-close uk-close"></a>

                                                                        <strong>Nessuno interessato!</strong>

                                                                        <br>Non sono presenti soggetti interessati a questa idea.<br>

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



        </tbody>

        </table>

        </div><!--chiudi containere overflow della tabella-->

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