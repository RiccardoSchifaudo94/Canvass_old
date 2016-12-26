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
  <body>  
<?php

$stato_registrazione = 0;

?>

<?php

if(isset($_POST['token_certifica'])){

   
 //eseguo la connessione al database e verifico che non esistono utenti con stessa mail e password
 $con = connetti("my_canvass");




  $ID_utente = pulisci($_POST['ID_utente']);
  $ID_ruolo = pulisci($_POST['ID_ruolo']);
  $dataInizioMandato = pulisci($_POST['dataInizioMandato']);
  $dataFineMandato = pulisci($_POST['dataFineMandato']);
  $ID_livelloIstituzione = pulisci($_POST['ID_livelloIstituzione']);
  $luogo_mandato = pulisci($_POST['luogo_mandato']);
  //echo $dataInizioMandato."<br>";
  //echo $dataFineMandato."<br>";


  $dataInizio = strtotime($dataInizioMandato);
  $inizio = date("Y-m-d",$dataInizio);
  $dataFine = strtotime($dataFineMandato);
  $fine = date("Y-m-d",$dataFine);
  //echo $dataInizio."<br>";
  //echo $dataFine."<br>";

  //echo $ID_utente."<br>";
  //echo $ID_ruolo."<br>";
  //echo $dataInizioMandato."<br>";
  //echo $dataFineMandato."<br>";
  //echo $ID_livelloIstituzione."<br>";  

  $sql_controllo = "SELECT COUNT(*) AS conta FROM eletto WHERE cancellato = 'NO' 
  AND ID_utente = '$ID_utente' AND ID_ruolo = '$ID_ruolo' AND dataInizioMandato = '$inizio' AND dataFineMandato = '$fine';";
  $row_controllo = esegui_query_stringa($con,$sql_controllo);
  $conta = 0;
  $conta = $row_controllo['conta'];
  //echo $sql_controllo."<br>";

  $sql = "INSERT INTO eletto (ID_utente,ID_ruolo,dataInizioMandato,dataFineMandato,luogo_mandato,ID_livelloIstituzione) VALUES ('$ID_utente','$ID_ruolo','$inizio','$fine','$luogo_mandato','$ID_livelloIstituzione');"; 
//  echo $sql."<br>";
  if($conta == 0)
  {  
  $sql = "INSERT INTO eletto (ID_utente,ID_ruolo,dataInizioMandato,dataFineMandato,luogo_mandato,ID_livelloIstituzione) VALUES ('$ID_utente','$ID_ruolo','$inizio','$fine','$luogo_mandato','$ID_livelloIstituzione');"; 
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
      <strong>Certificazione Inoltrata!</strong>
      <br>La tua richiesta di certificazione è stata presa in carico.<br> Verrai certificato e potrai loggarti entro 48 ore<br>
    </div>
 </div> 
 <div class="uk-width-medium-1-4 uk-width-small-1-4"></div>
 </div> 
</center>
<br>
<br>  

<?php
  }
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
      <strong>Certificazione In Attesa di Conferma!</strong>
      <br>La tua richiesta di certificazione è stata presa in carico.<br> Verrai certificato e potrai loggarti entro 48 ore<br>
    </div>
 </div> 
 <div class="uk-width-medium-1-4 uk-width-small-1-4"></div>
 </div> 
</center>
<br>
<br>   
<?php
  }//close else alert avviso certificazione avvenuta

}//close if token_certifica

?>



<div class="uk-grid">
<div class="uk-width-1-4">
</div>      
<div class="uk-width-2-4">
<center>
<form action="home.php" method="post"> 
<button class="uk-button uk-button-primary uk-button-middle"><i class="uk-icon-angle-left"></i> Indietro</button>
</form>
</center>
</div>
<div class="uk-width-1-4">
 </div>   
</div>
<br> <br>





  </body>
</html>
