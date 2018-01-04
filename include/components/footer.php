<?php
	/* print footer */
?>
<div class="footer">
	<div class="uk-container">
		<div class="uk-grid">
				<div class="uk-width-medium-2-5 uk-width-small-5-5">
					<?php if($row_ruolo['ID_ruolo']<3){ ?>
						<h2><a href="../utente/home.php">Canvass</a></h2>
					<?php } else { ?>
						<h2><a href="../membroIstituzione/home.php">Canvass</a></h2>
					<?php } ?>	
					<p>
						Canvass è un aggreggatore di idee, progetti e delibere realizzate con la collaborazione tra cittadini, associazioni e membri delle istituzioni per promuovere politiche virtuose e migliorare la qualità del benessere del territorio in cui vivi.
					</p>
				</div> 
				<div class="uk-width-medium-2-5 uk-width-small-5-5">
					<br/>
					<ul>
						<li>
							<i class="uk-icon-lightbulb-o"></i>
							<a href="../bachecaIdee/dashboard_idea.php">Bacheca Idee</a>
						</li>
		                <li>
		               	    <i class="uk-icon-file-text-o"></i>
							<a href="../bachecaDelibere/dashboard_delibera.php">Bacheca Delibere</a>
		                </li>
		                <li>
		                	<i class="uk-icon-comments-o"></i>
		                	<a href="../messaggistica/home.php">Messaggi</a>
		                </li>
		                <hr>
		                <li>
		                	<i class="uk-icon-phone"></i>
		                	<a href="../assistenza.php" target="_blank">Assistenza</a>
		                </li>
		            </ul>    
				</div>
				 <div class="uk-width-medium-1-5 uk-width-small-5-5">  
				 	<br/>
					<ul>
						<li>
							<button class="uk-button btn_contrast uk-button-primary" onclick="enable_contrast();">
								<i class="uk-icon-sun-o"></i> Abilita contrasto
							</button>
						</li>
					</ul>
				</div>
		</div>	
	</div>				
</div>
<script type="text/javascript">
	function enable_contrast(){
		$("body").toggleClass("CONTRAST");
		$("link").append("<link rel='stylesheet' href='../css/contrast.css'>");
	}
</script>