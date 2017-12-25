<!DOCTYPE html>
<?php

include 'configura.php';

?>
<html lang="en-gb" dir="ltr" class="uk-height-1-1">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Canvass</title>
        <link rel="stylesheet" href="css/uikit.min.css">
        <script src="js/jquery.js"></script>
        <script src="js/uikit.min.js"></script>

    </head>

    <body class="uk-height-1-1" background="../img/survey2.jpg" width="100%" style="background-repeat:no-repeat;background-size:cover;">
     <?php

            if(isset($_POST['token_registrati'])){

                //estrapolo i dati di mail e password per verificare che diversi utenti non si registrino con le stesse mail e password
            $mail = pulisci($_POST['mail']);
            $token = pulisci($_POST['token_registrati']);

             //eseguo la connessione al database e verifico che non esistono utenti con stessa mail e password
            $con = mysqli_connect("localhost","root","","my_canvass") or die ("connessione non riuscita al db!".$sql.mysqli_error());
            $sql = "SELECT * FROM utente WHERE mail = '$mail';";
             /*esecuzione query*/
            $result=mysqli_query($con,$sql)
            or die ("errore nella query" . $sql. mysql_error());

            /*conta numero righe*/
            $row=mysqli_fetch_assoc($result);

            //se risultano già presenti queste credenziali mail e password reindirizzo l'utente alla pagina di registrazione
            if($row['mail']!=$mail){
              $nome = pulisci($_POST['nome']);
              $cognome = pulisci($_POST['cognome']);
              $data_nascita = pulisci($_POST['data_nascita']);
              $luogo_nascita = pulisci($_POST['luogo_nascita']);
              $provincia_nascita = pulisci($_POST['provincia_nascita']);
              $luogo_residenza = pulisci($_POST['luogo_residenza']);
              $provincia_residenza = pulisci($_POST['provincia_residenza']);
              $password = pulisci($_POST['password']);
              $ID_ruolo = pulisci($_POST['ID_ruolo']);  

                
              $sql = "INSERT INTO utente"
              ."(nome,"
              ."cognome,"
              ."data_nascita,"
              ."luogo_nascita,"
              ."provincia_nascita,"
              ."luogo_residenza,"
              ."provincia_residenza,"
              ."mail,"
              ."password,"
              ."ID_ruolo) " 
              ." VALUES ('$nome','$cognome','$data_nascita','$luogo_nascita','$provincia_nascita','$luogo_residenza','$provincia_residenza','$mail','$password','$ID_ruolo');";

              $result=mysqli_query($con,$sql);

            ?>
            <br>
            <br>
            <center>
            <div class="uk-grid">
             <div class="uk-width-medium-1-4 uk-width-small-1-4"></div>
             <div class="uk-width-medium-2-4 uk-width-small-2-4">
                <div class="uk-alert uk-alert-success" data-uk-alert>
                  <a href="" class="uk-alert-close uk-close"></a>
                  <strong>Registrazione Avvenuta!</strong>
                  <br>Complimenti ti sei registrato con successo.<br>
                </div>
             </div> 
             <div class="uk-width-medium-1-4 uk-width-small-1-4"></div>
             </div> 
            </center>
            <br>
            <br> 
            <?php
            }//close if $row['mail']!=$mail

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
                  <strong>Registrazione Fallita!</strong>
                  <br>La Registrazione non e' andata a buon fine. Riprova.<br>
                </div>
             </div> 
             <div class="uk-width-medium-1-4 uk-width-small-1-4"></div>
             </div> 
            </center>
            <br>
            <br>        

            <?php
            }


            }//close if token_iscriviti

            ?>
               

        <div class="uk-vertical-align uk-text-center uk-height-1-1">
            <div class="uk-vertical-align-middle" style="width: 250px;">

                <a href="../index.html"><img class="uk-margin-bottom" width="160" height="120" src="../img/canvass_logo.png" alt=""></a>

                <form class="uk-panel uk-panel-box uk-form" action="utente.php" method="post">
                    <div class="uk-form-row">
                        <input class="uk-width-1-1 uk-form-large" type="text" placeholder="mail" name="mail">
                    </div>
                    <div class="uk-form-row">
                        <input class="uk-width-1-1 uk-form-large" type="password" placeholder="password" name="password">
                    </div>
                    <div class="uk-form-row">
                        <input type="hidden" name="token_accedi" value="1">
                        <button class="uk-width-1-1 uk-button uk-button-primary uk-button-large">Login</button> 
                    </div>
                </form>
                <form class="uk-panel uk-panel-box uk-form" action="registrati.php" method="post">
                    <button class="uk-width-1-1 uk-button uk-button-success uk-button-large">Registrati</button>
                </form> 
            </div>
        </div>


    </body>
    </html>