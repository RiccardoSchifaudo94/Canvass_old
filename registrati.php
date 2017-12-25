
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

        <div class="uk-vertical-align uk-text-center uk-height-1-1">
            <div class="uk-vertical-align-middle">
                <br>
                <br>
                <a href="../index.html"><img class="uk-margin-bottom" width="160" height="120" src="../img/canvass_logo.png" alt=""></a>
                <form class="uk-panel uk-panel-box uk-form" action="home.php" method="post">
                    <h3>Dati Utente</h3>
                    <label>Nome</label>
                    <div class="uk-form-row">
                        <input class="uk-width-1-1 uk-form-large" type="text" placeholder="nome" name="nome">
                    </div>
                    <br>
                    <label>Cognome</label>
                    <div class="uk-form-row">
                        <input class="uk-width-1-1 uk-form-large" type="text" placeholder="cognome" name="cognome">
                    </div>
                    <br>
                    <label>Ruolo</label>
                    <div class="uk-form-row">
                        <select name="ID_ruolo" class="uk-width-1-1 uk-form-large uk-form-select">
            
                            <?php
                           
                             $con = mysqli_connect("localhost","root","","my_canvass") or die ("connessione non riuscita al db!".$sql.mysqli_error());
                             
                             $sql_ruolo = "SELECT * FROM ruolo WHERE cancellato = 'NO';";
                                        $result_ruolo=mysqli_query($con,$sql_ruolo)
                                        or die ("errore nella query: " . $sql_ruolo. mysql_error());

                                        //
                                        $num_row=mysqli_num_rows($result_ruolo);
                                     
                                        //$i = 0;
                                        if($num_row!=0){

                                                  while($row_ruolo=mysqli_fetch_assoc($result_ruolo)){
                                                    
                                                   ?>

                                                   <option value="<?php echo $row_ruolo['ID_ruolo'];?>"><?php echo $row_ruolo['nome'];?></option>

                                                   <?php

                                                  }//close while
                                                     $result_ruolo -> free();
                                                       
                                              }//close $num_row!=0   

                                       
                                    ?>
                            
                            </select>        
                    </div>
                    <br>
                    <hr>
                    <h3>Dati anagrafici</h3>
                    <label>Data nascita</label>
                    <div class="uk-form-row">
                          <input type="text"  class="uk-button uk-form-select uk-width-1-1 uk-form-large uk-button" placeholder="Data nascita" required autofocus name="data_nascita" 
                          data-uk-datepicker="{format:'YYYY-MM-DD'}">
                    </div>
                    <br>
                    <label>Luogo nascita</label>
                    <div class="uk-form-row">
                        <input class="uk-width-1-1 uk-form-large" type="text" placeholder="Luogo di Nascita" name="luogo_nascita">
                    </div>
                    <br>
                     <label>Provincia Nascita</label>
                     <div class="uk-form-row">
                            <select name="provincia_nascita" class="uk-width-1-1 uk-form-large uk-form-select">

                                <option value="AG">AGRIGENTO</option>
                                <option value="AL">ALESSANDRIA</option>
                                <option value="AN">ANCONA</option>
                                <option value="AO">AOSTA</option>
                                <option value="AR">AREZZO</option>
                                <option value="AP">ASCOLI PICENO</option>
                                <option value="AT">ASTI</option>
                                <option value="AV">AVELLINO</option>
                                <option value="BA">BARI</option>
                                <option value="BT">Barletta-Andria-Trani</option>
                                <option value="BL">BELLUNO</option>
                                <option value="BN">BENEVENTO</option>
                                <option value="BG">BERGAMO</option>
                                <option value="BI">BIELLA</option>
                                <option value="BO">BOLOGNA</option>
                                <option value="BZ">BOLZANO</option>
                                <option value="BS">BRESCIA</option>
                                <option value="BR">BRINDISI</option>
                                <option value="CA">CAGLIARI</option>
                                <option value="CL">CALTANISSETTA</option>
                                <option value="CB">CAMPOBASSO</option>
                                <option value="CI">Carbonia-Iglesias</option>
                                <option value="CE">CASERTA</option>
                                <option value="CT">CATANIA</option>
                                <option value="CZ">CATANZARO</option>
                                <option value="CH">CHIETI</option>
                                <option value="CO">COMO</option>
                                <option value="CS">COSENZA</option>
                                <option value="CR">CREMONA</option>
                                <option value="KR">CROTONE</option>
                                <option value="CN">CUNEO</option>
                                <option value="EN">ENNA</option>
                                <option value="FM">FERMO</option>
                                <option value="FE">FERRARA</option>
                                <option value="FI">FIRENZE</option>
                                <option value="FG">FOGGIA</option>
                                <option value="FC">FORLI’-CESENA</option>
                                <option value="FR">FROSINONE</option>
                                <option value="GE">GENOVA</option>
                                <option value="GO">GORIZIA</option>
                                <option value="GR">GROSSETO</option>
                                <option value="IM">IMPERIA</option>
                                <option value="IS">ISERNIA</option>
                                <option value="SP">LA SPEZIA</option>
                                <option value="AQ">L’AQUILA</option>
                                <option value="LT">LATINA</option>
                                <option value="LE">LECCE</option>
                                <option value="LC">LECCO</option>
                                <option value="LI">LIVORNO</option>
                                <option value="LO">LODI</option>
                                <option value="LU">LUCCA</option>
                                <option value="MC">MACERATA</option>
                                <option value="MN">MANTOVA</option>
                                <option value="MS">MASSA-CARRARA</option>
                                <option value="MT">MATERA</option>
                                <option value="VS"> MEDIO CAMPIDANO</option>
                                <option value="ME">MESSINA</option>
                                <option value="MI">MILANO</option>
                                <option value="MO">MODENA</option>
                                <option value="MB">MONZA E DELLA BRIANZA</option>
                                <option value="NA">NAPOLI</option>
                                <option value="NO">NOVARA</option>
                                <option value="NU">NUORO</option>
                                <option value="OG">OGLIASTRA</option>
                                <option value="OT">OLBIA-TEMPIO</option>
                                <option value="OR">ORISTANO</option>
                                <option value="PD">PADOVA</option>
                                <option value="PA">PALERMO</option>
                                <option value="PR">PARMA</option>
                                <option value="PV">PAVIA</option>
                                <option value="PG">PERUGIA</option>
                                <option value="PU">PESARO E URBINO</option>
                                <option value="PE">PESCARA</option>
                                <option value="PC">PIACENZA</option>
                                <option value="PI">PISA</option>
                                <option value="PT">PISTOIA</option>
                                <option value="PN">PORDENONE</option>
                                <option value="PZ">POTENZA</option>
                                <option value="PO">PRATO</option>
                                <option value="RG">RAGUSA</option>
                                <option value="RA">RAVENNA</option>
                                <option value="RC">REGGIO DI CALABRIA</option>
                                <option value="RE">REGGIO NELL’EMILIA</option>
                                <option value="RI">RIETI</option>
                                <option value="RN">RIMINI</option>
                                <option value="RM">ROMA</option>
                                <option value="RO">ROVIGO</option>
                                <option value="SA">SALERNO</option>
                                <option value="SS">SASSARI</option>
                                <option value="SV">SAVONA</option>
                                <option value="SI">SIENA</option>
                                <option value="SR">SIRACUSA</option>
                                <option value="SO">SONDRIO</option>
                                <option value="TA">TARANTO</option>
                                <option value="TE">TERAMO</option>
                                <option value="TR">TERNI</option>
                                <option value="TO">TORINO</option>
                                <option value="TP">TRAPANI</option>
                                <option value="TN">TRENTO</option>
                                <option value="TV">TREVISO</option>
                                <option value="TS">TRIESTE</option>
                                <option value="UD">UDINE</option>
                                <option value="VA">VARESE</option>
                                <option value="VE">VENEZIA</option>
                                <option value="VB">VERBANO-CUSIO-OSSOLA</option>
                                <option value="VC">VERCELLI</option>
                                <option value="VR">VERONA</option>
                                <option value="VV">VIBO VALENTIA</option>
                                <option value="VI">VICENZA</option>
                                <option value="VT">VITERBO</option>




                            </select> 
                    </div> 
                    <br>
                    <label>Luogo residenza</label>
                    <div class="uk-form-row">
                        <input class="uk-width-1-1 uk-form-large" type="text" placeholder="Luogo di Residenza" name="luogo_residenza">
                    </div>
                    <br>
                    <label>Provincia Residenza</label>
                     <div class="uk-form-row">
                            <select name="provincia_residenza" class="uk-width-1-1 uk-form-large uk-form-select">

                                <option value="AG">AGRIGENTO</option>
                                <option value="AL">ALESSANDRIA</option>
                                <option value="AN">ANCONA</option>
                                <option value="AO">AOSTA</option>
                                <option value="AR">AREZZO</option>
                                <option value="AP">ASCOLI PICENO</option>
                                <option value="AT">ASTI</option>
                                <option value="AV">AVELLINO</option>
                                <option value="BA">BARI</option>
                                <option value="BT">Barletta-Andria-Trani</option>
                                <option value="BL">BELLUNO</option>
                                <option value="BN">BENEVENTO</option>
                                <option value="BG">BERGAMO</option>
                                <option value="BI">BIELLA</option>
                                <option value="BO">BOLOGNA</option>
                                <option value="BZ">BOLZANO</option>
                                <option value="BS">BRESCIA</option>
                                <option value="BR">BRINDISI</option>
                                <option value="CA">CAGLIARI</option>
                                <option value="CL">CALTANISSETTA</option>
                                <option value="CB">CAMPOBASSO</option>
                                <option value="CI">Carbonia-Iglesias</option>
                                <option value="CE">CASERTA</option>
                                <option value="CT">CATANIA</option>
                                <option value="CZ">CATANZARO</option>
                                <option value="CH">CHIETI</option>
                                <option value="CO">COMO</option>
                                <option value="CS">COSENZA</option>
                                <option value="CR">CREMONA</option>
                                <option value="KR">CROTONE</option>
                                <option value="CN">CUNEO</option>
                                <option value="EN">ENNA</option>
                                <option value="FM">FERMO</option>
                                <option value="FE">FERRARA</option>
                                <option value="FI">FIRENZE</option>
                                <option value="FG">FOGGIA</option>
                                <option value="FC">FORLI’-CESENA</option>
                                <option value="FR">FROSINONE</option>
                                <option value="GE">GENOVA</option>
                                <option value="GO">GORIZIA</option>
                                <option value="GR">GROSSETO</option>
                                <option value="IM">IMPERIA</option>
                                <option value="IS">ISERNIA</option>
                                <option value="SP">LA SPEZIA</option>
                                <option value="AQ">L’AQUILA</option>
                                <option value="LT">LATINA</option>
                                <option value="LE">LECCE</option>
                                <option value="LC">LECCO</option>
                                <option value="LI">LIVORNO</option>
                                <option value="LO">LODI</option>
                                <option value="LU">LUCCA</option>
                                <option value="MC">MACERATA</option>
                                <option value="MN">MANTOVA</option>
                                <option value="MS">MASSA-CARRARA</option>
                                <option value="MT">MATERA</option>
                                <option value="VS"> MEDIO CAMPIDANO</option>
                                <option value="ME">MESSINA</option>
                                <option value="MI">MILANO</option>
                                <option value="MO">MODENA</option>
                                <option value="MB">MONZA E DELLA BRIANZA</option>
                                <option value="NA">NAPOLI</option>
                                <option value="NO">NOVARA</option>
                                <option value="NU">NUORO</option>
                                <option value="OG">OGLIASTRA</option>
                                <option value="OT">OLBIA-TEMPIO</option>
                                <option value="OR">ORISTANO</option>
                                <option value="PD">PADOVA</option>
                                <option value="PA">PALERMO</option>
                                <option value="PR">PARMA</option>
                                <option value="PV">PAVIA</option>
                                <option value="PG">PERUGIA</option>
                                <option value="PU">PESARO E URBINO</option>
                                <option value="PE">PESCARA</option>
                                <option value="PC">PIACENZA</option>
                                <option value="PI">PISA</option>
                                <option value="PT">PISTOIA</option>
                                <option value="PN">PORDENONE</option>
                                <option value="PZ">POTENZA</option>
                                <option value="PO">PRATO</option>
                                <option value="RG">RAGUSA</option>
                                <option value="RA">RAVENNA</option>
                                <option value="RC">REGGIO DI CALABRIA</option>
                                <option value="RE">REGGIO NELL’EMILIA</option>
                                <option value="RI">RIETI</option>
                                <option value="RN">RIMINI</option>
                                <option value="RM">ROMA</option>
                                <option value="RO">ROVIGO</option>
                                <option value="SA">SALERNO</option>
                                <option value="SS">SASSARI</option>
                                <option value="SV">SAVONA</option>
                                <option value="SI">SIENA</option>
                                <option value="SR">SIRACUSA</option>
                                <option value="SO">SONDRIO</option>
                                <option value="TA">TARANTO</option>
                                <option value="TE">TERAMO</option>
                                <option value="TR">TERNI</option>
                                <option value="TO">TORINO</option>
                                <option value="TP">TRAPANI</option>
                                <option value="TN">TRENTO</option>
                                <option value="TV">TREVISO</option>
                                <option value="TS">TRIESTE</option>
                                <option value="UD">UDINE</option>
                                <option value="VA">VARESE</option>
                                <option value="VE">VENEZIA</option>
                                <option value="VB">VERBANO-CUSIO-OSSOLA</option>
                                <option value="VC">VERCELLI</option>
                                <option value="VR">VERONA</option>
                                <option value="VV">VIBO VALENTIA</option>
                                <option value="VI">VICENZA</option>
                                <option value="VT">VITERBO</option>




                            </select> 
                    </div>        
                    <br>  
                    <hr>
                    <h3>Dati di Accesso</h3>   
                    <label>E-mail</label>
                    <div class="uk-form-row">
                        <input class="uk-width-1-1 uk-form-large" type="text" placeholder="mail" name="mail">
                    </div>
                    <br>
                    <label>Password</label>
                    <div class="uk-form-row">
                        <input class="uk-width-1-1 uk-form-large" type="password" placeholder="password" name="password">
                    </div>
                    <br>
                    <input type="hidden" name="token_registrati" value="1">
                   <button class="uk-width-1-1 uk-button uk-button-success uk-button-large"><i class="uk-icon-check"></i> Registrati</button>
                </form>
                <form class="uk-panel uk-panel-box uk-form" action="home.php" method="post">
                  <div class="uk-form-row">   
                        <button class="uk-width-1-1 uk-button uk-button-primary uk-button-large"><i class="uk-icon-chevron-left"></i> Indietro</button> 
                    </div> 
                  </form>  
                 <div class="uk-form-row uk-text-small">
                        <label class="uk-float-left"><input type="checkbox"> Remember Me</label>
                        <a class="uk-float-right uk-link uk-link-muted" href="#">Forgot Password?</a>
                 </div>
            </div>
        </div>
        <br>
        <br>

    </body>
    </html>