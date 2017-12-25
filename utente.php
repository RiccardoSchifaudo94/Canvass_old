<?php 
include 'configura.php';
?>
<!DOCTYPE html>
<html lang="en-gb" dir="ltr" class="uk-height-1-1">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Canvass</title>
        <link rel="stylesheet" href="css/uikit.min.css">
        <link rel="stylesheet" href="css/components/datepicker.min.css">
        <script src="js/jquery.js"></script>
        <script src="js/uikit.min.js"></script>
        <script src="js/components/datepicker.min.js"></script>

    </head>

  <body class="uk-height-1-1">
  
<?php

$mail = pulisci($_POST['mail']);
$pass = pulisci($_POST['password']);
$stato = 1;
if($mail!="" AND $pass!="")
    $stato = 0;
if($stato!=0)
    header ("location:home.php");


if(isset($_POST['token_accedi'])){

//entro nella elsa se non ricevo nessuno token di iscrizione e vrifico se l'utente si connette con la giusta mail e password presenti nel db  
$mail = pulisci($_POST['mail']);
$pass = pulisci($_POST['password']);
//mi connetto al db
 $con = mysqli_connect("localhost","root","","my_canvass") or die ("connessione non riuscita al db!".$sql.mysqli_error());
 $sql = "SELECT * FROM utente WHERE mail = '$mail' AND password='$pass' AND cancellato = 'NO';";
 /*esecuzione query*/
$result=mysqli_query($con,$sql)
or die ("errore nella query" . $sql. mysql_error());

/*conta numero righe*/
$row=mysqli_fetch_assoc($result);
//se le sue credenziali sono errate lo riporto alla pagina del login
if($row['mail']!=$mail AND $row['password']!=$pass)
{
  header("Location:home.php");
}
else{ 


      //accesso da cittadino nel portale 
         if($row['ID_ruolo']<="2")
          {
           session_start();
           $_SESSION['ID_utente'] = $row['ID_utente'];
           header("Location:utente/home.php");
         
            }//close if row['ID_ruolo']=="1"
           
            
            //accesso da superAdministrator
            if($row['ID_ruolo']=="10")
                {

                    header("location:superAdmin/home.php");

                }   


       //accesso da consigliere  nel portale 
      if($row['ID_ruolo']>="3" AND $row['ID_ruolo']<="8")
          { 

          

            $con =connetti("my_canvass");
            $sql_presenza_eletto = "SELECT COUNT(*) AS conta FROM eletto WHERE cancellato='NO' AND ID_utente = '".$row['ID_utente']."';";
            $row_eletto = esegui_query_stringa($con,$sql_presenza_eletto);
            $conta = 0;
            $conta = $row_eletto['conta'];
            
      //verifico che l'utente sia registrato da eletto per accedere alla seconda parte del portale se non pressente gli richiedo la cerficazione 
      // che verrà successivamente confermata
      if($conta==0)
        {
          //______________________________________----- RICHIESTA CERTIFICAZIONE DA ELETTO/MEMBRO ISTITUZIONE----- ________________________________//
       
      ?>



                          <div class="uk-vertical-align uk-text-center uk-height-1-1">
                                    <div class="uk-vertical-align-middle" style="width: 250px;">

                                        <h2>Richiedi Certificazione</h2>

                                        <form class="uk-panel uk-panel-box uk-form" action="certifica.php" method="post">
                                            <div class="uk-form-row">
                                              <label>Livello istituzione</label>
                                                <select name="ID_livelloIstituzione" class="uk-width-1-1 uk-form-large uk-form-select">
                              
                                                        <?php
                                                       
                                                         $con = connetti("my_canvass");
                                                         
                                                         $sql_prova = "SELECT * FROM livello_istituzione WHERE cancellato = 'NO';";
                                                                    
                                                         $result_prova= esegui_query($con,$sql_prova);
                                                                    

                                                                    //
                                                                    $num_row=mysqli_num_rows($result_prova);
                                                                 
                                                                    //$i = 0;
                                                                    if($num_row!=0){

                                                                              while($row_prova=mysqli_fetch_assoc($result_prova)){
                                                                                
                                                                               ?>

                                                                               <option value="<?php echo $row_prova['ID_livelloIstituzione'];?>"><?php echo $row_prova['nome'];?></option>

                                                                               <?php

                                                                              }//close while
                                                                                 $result_prova -> free();
                                                                                   
                                                                          }//close $num_row!=0   

                                                                   
                                                                ?>
                                                        
                                                        </select>        

                                            </div>
                                            <div class="uk-form-row">
                                             <label>Data Inizio Nomina/Mandato</label>
                                                  <input type="text"  class="uk-button uk-form-select uk-width-1-1 uk-form-large uk-button" placeholder="Inserisci Data di Nomina/Mandato" required autofocus name="dataInizioMandato" 
                                                  data-uk-datepicker="{format:'YYYY-MM-DD'}">
                                            </div>      
                                                  <br>
                                             <div class="uk-form-row">
                                             <label>Data Fine Nomina/Mandato</label>
                                                  <input type="text"  class="uk-button uk-form-select uk-width-1-1 uk-form-large uk-button" placeholder="Inserisci Data di Fine Mandato" required autofocus name="dataFineMandato" 
                                                  data-uk-datepicker="{format:'YYYY-MM-DD'}">
                                            </div>      
                                            <form class="uk-form">

                                                  <br>      
                                          
                                            <div class="uk-form-row">
                                             <label>Luogo di Mandato</label>
                                                  <input type="text"  class="uk-button uk-form-select uk-width-1-1 uk-form-large uk-button" placeholder="Luogo di Mandato" required autofocus name="luogo_mandato">
                                            </div>      

                                            <br>
                                              <input type="hidden" name="token_certifica" value="1"> 
                                              <input type="hidden" name="ID_utente" value="<?php echo $row['ID_utente'];?>">
                                               <input type="hidden" name="ID_ruolo" value="<?php echo $row['ID_ruolo'];?>"> 
                                              <button class="uk-width-1-1 uk-button uk-button-success uk-button-large">Richiedi Certificazione</button>
                                        </form>
                                       
                                      
                                    </div>
                                </div>

            

         <?php
        }//close if di conta==0

        //se l'untente è gia presente e risulta iscritto da eletto verifico se la sua posizione è stata certificata o se deve attendere
        else
        {

          $con = connetti("my_canvass");  
          $sql_verifica = "SELECT * FROM eletto INNER JOIN ruolo ON eletto.ID_ruolo=ruolo.ID_ruolo WHERE ID_utente='".$row['ID_utente']."' ;";
        
          $row_verifica = esegui_query_stringa($con,$sql_verifica);
          if($row_verifica['certificato']=="SI"){
            
            //______________________________________----- ACCEDO DA ELETTO----- ________________________________//
         
            session_start();
            $_SESSION['ID_utente'] = $row['ID_utente'];
            header("Location:membroIstituzione/agenda_idee.php");


      
            }//close if di $row['certificato']=="SI"
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
                      <strong>In Attesa di Certificazione!</strong>
                      <p align="center">Mi spiace non risulti ancora certificato per poter accervi. Sarai certificato a breve</p>
                  </div>
               </div> 
               <div class="uk-width-medium-1-4 uk-width-small-1-4"></div>
               </div> 
              </center>
              <br>
              <br>   
              
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

              <?php
            }


        }//close else di if di conta==0


    
    }//close else di if $row['ID_ruolo'] 
    
 
  }//close else di $row['mail']!=$mail

}//close if di token       

?>  
        
                                           
                                  
     
</body>
</html>
