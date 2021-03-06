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

                                    <a href="../utente/home.php">Spazio Personale</a>



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



        <?php   //  --------------------------------------- AREA DEI TOKEN DI ESECUZIONE -------------------------------------------?>



        <?php



            if(isset($_POST['token_cancella2'])){



              $con =connetti("my_canvass");



              $sql3 ="UPDATE idee SET cancellato='SI' WHERE ID_idea='".filtraInput($con,$_POST['ID_idea'])."';";

             

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

                  <strong>Idea cancellata!</strong>

                  <br>Questa idea e' stata cancellata con successo<br>

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

                     <strong>Vuoi cancellare veramente questa idea?</strong>

                    <p align="center"> L'operazione sara' irreversibile!</p>

                    <br>

                    <div class="uk-grid">

                      <div class="uk-width-medium-4-10 uk-width-small-4-10"></div>

                      <div class="uk-width-medium-1-10 uk-width-small-1-10">

                          <form action="home.php" method="post"> 

                                <input type='hidden' name='ID_idea' value="<?php echo filtraOutput($_POST['ID_idea']);?>">

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



            }//chiudi il token di cancella idea 1



            ?>











        <div class="uk-grid">

        <div class="uk-width-medium-1-6 uk-width-small-1-6"></div> 

        <div class="uk-width-medium-4-6 uk-width-small-4-6">

        <div class="uk-form">

          <form action="propose_idea.php" method="post">

            <button type="submit" class="uk-button uk-button-primary"><i class="uk-icon-lightbulb-o"></i> Proponi Idea</button>

          </form>  

        </div>  

        <br>

            <center><h2>Le tue Idee</h2></center>

        <div class="uk-overflow-container">    

        <table class="uk-table uk-table-striped uk-table-hover">

      

        <thead>

            <tr>

                <th>Titolo</th><th>Tema</th><th>Interessati</th><th>Dettagli</th><th>Cancella</th>

            </tr>

       </thead>         

        <tbody>

             <?php



                                            $ID_utente = pulisci($_SESSION['ID_utente']);

                                            $con = mysqli_connect("localhost","root","","my_canvass") or die ("connessione non riuscita al db!".$sql.mysqli_error()) ;

                                            $sql = "SELECT * FROM idee WHERE ID_utente='$ID_utente' AND cancellato = 'NO' ORDER BY ID_idea DESC;";

                                            $result=mysqli_query($con,$sql)

                                            or die ("errore nella query: " . $sql. mysql_error());



                                            /*conta numero righe*/

                                            $num_row=mysqli_num_rows($result);



                                            //$i = 0;

                                              if($num_row!=0){

                                                      while( $row=mysqli_fetch_assoc($result)){



                                                        $titolo = limitaStringa25($row['titolo']);

                                                    



                                                        $sql_conta = "SELECT COUNT(*) AS conta FROM associa_idee_eletto WHERE ID_idea = '".pulisci($row['ID_idea'])."';";

                                                        $row_conta = esegui_query_stringa($con,$sql_conta);

                                                        $conta = 0;

                                                        $conta = $row_conta['conta'];





                                                        echo  "<tr>"

                                                              ."<td>".filtraOutput($titolo)."</td>";

                                                           



                                                        $sql_tema = "SELECT * FROM tema WHERE cancellato = 'NO' AND ID_tema = '".pulisci($row['ID_tema'])."';";

                                                        $row_tema = esegui_query_stringa($con,$sql_tema);      



                                                        echo "<td>".filtraOutput($row_tema['nome'])."</td>"

                                                              ."<td>";



                                                                if($conta==0)

                                                                  echo "Nessuno<br>";

                                                                if($conta==1)

                                                                  echo "1 Persona<br>";

                                                                if($conta>1) 

                                                                echo $conta." Persone<br>";



                                                              echo "</td>";



                                                              echo "<form action='dettagli_idea.php' method='post'>";

                                                              ?>

                                                              <input type='hidden' name='ID_idea' value="<?php echo pulisci($row['ID_idea']);?>">

                                                               <input type='hidden' name='token_dettagli' value="1">

                                                              <?php



                                                              echo "<td alingn='center'><button class='uk-button uk-button-primary' type='submit'><i class='uk-icon-search'></i></button></td>"

                                                              ."</form>";





                                                               echo "<form action='home.php' method='post'>";

                                                               ?>



                                                              <input type='hidden' name='ID_idea' value="<?php echo pulisci($row['ID_idea']);?>">

                                                               <input type='hidden' name='token_cancella' value="1">

                                                              <?php



                                                              echo "<td alingn='center'><button class='uk-button uk-button-danger' type='submit'><i class='uk-icon-eraser'></i></button></td>"

                                                              ."</form>"

                                                              ."</tr>";







                                                        //$i = $i+1;

                                                      }//close while

                                                       

                                                  }//close $num_row!=0   



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

                                                                        <strong>Nessuna idea presente!</strong>

                                                                        <br>Non hai caricato nessuna idea.<br>

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