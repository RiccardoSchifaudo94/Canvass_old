<?php
function connetti($db){

$server = "localhost";
$password = "";
$user = "root";


$con = mysqli_connect("$server","$user","$password","$db") 
or die ("connessione non riuscita al db!".$sql.mysqli_error()) ;

return $con;


}

function esegui_query($con,$query){

    $risultato = mysqli_query($con,$query)
    or die ("errore nella query" . $query. mysql_error());

    return $risultato;

}

function esegui_query_stringa($con,$query){

    $risultato = mysqli_query($con,$query)
    or die ("errore nella query" . $query. mysql_error());

    $stringa_riga = mysqli_fetch_assoc($risultato);


    return $stringa_riga;

}

function calcola_righe_query($con,$query){

    $risultato = mysqli_query($con,$query)
    or die ("errore nella query" . $query. mysql_error());

    $numero_righe =mysqli_num_rows($risultato);


    return $numero_righe;

}

function filtraInput($con,$stringa){
  
      //$stringa2 = htmlspecialchars($stringa);
      mysqli_real_escape_string($con,$stringa);
      return $stringa;
}

function filtraOutput($stringa){

      return htmlspecialchars($stringa);
      //return utf8_decode($stringa);

}


function pulisci($str) {
 // if (get_magic_quotes_gpc()) $str = stripslashes($str);
  return htmlentities($str);
}

function convertiTimeStampInData($timeStampDaConvertire){

                $timestamp=strtotime($timeStampDaConvertire);
                $data = date("d-M-Y",$timestamp);
                $orario = date("H:i:s",$timestamp);
                echo $data." ".$orario." ";

}


function limitaStringa50($stringa) {

$stringa2 = substr($stringa,0,50);
return $stringa2;

}

function limitaStringa25($stringa) {

$stringa2 = substr($stringa,0,25);
return $stringa2;

}


function caricaImg()
{

  $ext = 0;
  $ID_foto = 0;

  $msg ="operazione avvenuta con sucesso!";

  //echo "Dimensioni immagine: ".$_FILES['image']['size']."<br>";
  //echo "Percorso temporaneo immagine: ".$_FILES['image']['tmp_name']."<br>";
  //list($width, $height, $type, $attr) = getimagesize($_FILES['image']['tmp_name']);
  //echo "Dimensioni pixel:<br> Larghezza: ".$width." Altezza: ".$height." Tipo file:".$type."<br>";
  //echo "Nome immagine:".$_FILES['image']['name']."<br>";
  $name = time().rand(0,99);
  $name = $name + ".png";
  //echo $name."<br>";
  

  //cambio il nome al file inserendo come nome l'ora di inserimento + numero random da 0 a 100
  $_FILES['image']['name'] = $name;
  //echo "File rinominato:".$_FILES['image']['name'];


    do {
  if (is_uploaded_file($_FILES['image']['tmp_name'])) {
    // Controllo che il file non superi i 18 KB
    if ($_FILES['image']['size'] > 5000000) {
      $msg = "<p>Il file non deve superare i 5 MB!!</p>";
      break;
    }
    // Ottengo le informazioni sull'immagine
    list($width, $height, $type, $attr) = getimagesize($_FILES['image']['tmp_name']);

     //echo "Nome modificato: ".$_FILES['image']['name']."<br>";
    ?>
      <br>
      <br>
      <center>
      <div class="uk-grid">
       <div class="uk-width-medium-1-4 uk-width-small-1-4"></div>
       <div class="uk-width-medium-2-4 uk-width-small-2-4">
          <div class="uk-alert uk-alert-info" data-uk-alert>
            <a href="" class="uk-alert-close uk-close"></a>
            <strong>Immagine caricata correttamente</strong>
            <br>L'immagine rispetta le dimensioni di 5 Mb Max.<br>
          </div>
       </div> 
       <div class="uk-width-medium-1-4 uk-width-small-1-4"></div>
       </div> 
      </center>
      <br>
      <br> 

    <?php
    // Controllo che le dimensioni (in pixel) non superino 160x180
    if (($width > 12000) || ($height > 8000)) {
      ?>
       <br>
      <br>
      <center>
      <div class="uk-grid">
       <div class="uk-width-medium-1-4 uk-width-small-1-4"></div>
       <div class="uk-width-medium-2-4 uk-width-small-2-4">
          <div class="uk-alert uk-alert-danger" data-uk-alert>
            <a href="" class="uk-alert-close uk-close"></a>
            <strong>Dimensioni immagini non corrette</strong>
            <br>L'immagine supera la dimensione massima di 1200x800.<br>
          </div>
       </div> 
       <div class="uk-width-medium-1-4 uk-width-small-1-4"></div>
       </div> 
      </center>
      <br>
      <br> 
      <?php
      break;
    }
    // Controllo che il file sia in uno dei formati GIF, JPG o PNG
    if (($type!=1) && ($type!=2) && ($type!=3)) {
      $msg = "<p>Formato non corretto!!</p>";
      break;
    }
    // Verifico che sul sul server non esista già un file con lo stesso nome
    // In alternativa potrei dare io un nome che sia funzione della data e dell'ora
    if (file_exists('uploader/'.$_FILES['image']['name'])) {
      $msg = "<p>File già esistente sul server. Rinominarlo e riprovare.</p>";
      break;
    }

    $ext = 0;
    //attribuisco l'estenzione all'immagine
    switch ($type) {

       case '1':
        $ext = ".gif";
        break;

      case '2':
        $ext = ".jpeg";
        break;

      case '3':
        $ext = ".png";
        break; 
      
      default:
        $ext =0;
        break;
    }

    // Sposto il file nella cartella da me desiderata
    if (!move_uploaded_file($_FILES['image']['tmp_name'], '../upload/'.$_FILES['image']['name'].$ext)) {
      $msg = "<p>Errore nel caricamento dell'immagine!!</p>";
       $ext = 0;
      break;
    } 
  }
} while (false);
//echo $msg;
$ID_foto = 0;
if($ext!="")
{  
$percorso = "upload/".$_FILES['image']['name'].$ext;

$con = connetti("my_canvass");
$sql = "INSERT INTO foto(percorso)VALUES('$percorso');";
$ris = esegui_query($con,$sql);
$ID_foto = mysqli_insert_id($con);
}
return $ID_foto;


}


function generaHeader(){

?>


<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Knock</title>

    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">



    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="../../assets/js/ie-emulation-modes-warning.js"></script>

  
  </head>


<nav class="navbar navbar-inverse navbar-static-top">
          <div class="container">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand">Knock</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
              <ul class="nav navbar-nav"> 
                <div class="row">
                          <div class="col-md-2">  
                          <form action="../utente/propose_idea.php" method="post">
                          <input type="hidden" name="ID_utente" value="<?php echo $_POST['ID_utente'];?>">  
                          <li><button type="submit" class="btn btn-md btn" style="background-color:#222;color:#9d9d9d; margin-top:10px;">Proponi Idea</button></li>
                          </form>
                          </div>
                          <div class="col-md-2">  
                         <form action="../utente/your_idea.php" method="post">
                          <input type="hidden" name="ID_utente" value="<?php echo $_POST['ID_utente'];?>">  
                          <li><button type="submit" class="btn btn-md btn" style="background-color:#222;color:#9d9d9d; margin-top:10px;">Idee Inserite</button></li>
                          </form>
                          </div>
                          <div class="col-md-2">  
                         <form action="../bachecaIdee/dashboard_idea.php" method="post">
                          <input type="hidden" name="ID_utente" value="<?php echo $_POST['ID_utente'];?>">  
                          <li><button type="submit" class="btn btn-md btn" style="background-color:#222;color:#9d9d9d; margin-top:10px;">Bacheca Idee</button></li>
                          </form>
                          </div>
                          <div class="col-md-3">  
                         <form action="../bachecaDelibere/dashboard_delibere.php" method="post">
                          <input type="hidden" name="ID_utente" value="<?php echo $_POST['ID_utente'];?>">  
                          <li><button type="submit" class="btn btn-md btn" style="background-color:#222;color:#9d9d9d; margin-top:10px;">Bacheca Delibere</button></li>
                          </form>
                          </div>
                           <div class="col-md-2">  
                         <form action="../utente/profilo.php" method="post">
                          <input type="hidden" name="ID_utente" value="<?php echo $_POST['ID_utente'];?>">  
                          <li><button type="submit" class="btn btn-md btn" style="background-color:#222;color:#9d9d9d; margin-top:10px;">Profilo</button></li>
                          </form>
                          </div> 

                </div>
                
              </ul>
            </div>
          </div>
        </nav>


<?php
}


?>
<?php

function generaHeaderEletto()
{
?>


<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Knock</title>

    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">



    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="../../assets/js/ie-emulation-modes-warning.js"></script>

  
  </head>

<nav class="navbar navbar-inverse navbar-static-top">
          <div class="container">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="">Knock</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
              <ul class="nav navbar-nav"> 
                <div class="row">
                          <div class="col-md-2">  
                          <form action="agenda_idee.php" method="post">
                          <input type="hidden" name="ID_utente" value="<?php echo $_POST['ID_utente'];?>">  
                          <li><button type="submit" class="btn btn-md btn" style="background-color:#222;color:#9d9d9d; margin-top:10px;">Agenda Idee</button></li>
                          </form>
                          </div>
                          <div class="col-md-3">  
                         <form action="delibere_idee.php" method="post">
                          <input type="hidden" name="ID_utente" value="<?php echo $_POST['ID_utente'];?>">  
                          <li><button type="submit" class="btn btn-md btn" style="background-color:#222;color:#9d9d9d; margin-top:10px;">Delibere delle idee</button></li>
                          </form>
                          </div>
                          <div class="col-md-2">  
                         <form action="bachecaIdee/dashboard_idea.php" method="post">
                          <input type="hidden" name="ID_utente" value="<?php echo $_POST['ID_utente'];?>">  
                          <li><button type="submit" class="btn btn-md btn" style="background-color:#222;color:#9d9d9d; margin-top:10px;">Bacheca Idee</button></li>
                          </form>
                          </div>
                          <div class="col-md-3">  
                         <form action="dashboard_delibere.php" method="post">
                          <input type="hidden" name="ID_utente" value="<?php echo $_POST['ID_utente'];?>">  
                          <li><button type="submit" class="btn btn-md btn" style="background-color:#222;color:#9d9d9d; margin-top:10px;">Bacheca Delibere</button></li>
                          </form>
                          </div>
                           <div class="col-md-2">  
                         <form action="../membroIstituzione/profilo.php" method="post">
                          <input type="hidden" name="ID_utente" value="<?php echo $_POST['ID_utente'];?>">   
                          <li><button type="submit" class="btn btn-md btn" style="background-color:#222;color:#9d9d9d; margin-top:10px;">Profilo</button></li>
                          </form>
                          </div> 

                </div>
                
              </ul>
            </div>
          </div>
        </nav>
<?php
}
?>