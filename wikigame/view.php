<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>WikiGame</title>
		<link rel="stylesheet" type="text/css" href="https://genius-corp.fr/wikigame/css/wiki.css">
		<link rel="stylesheet" type="text/css" href="https://genius-corp.fr/wikigame/css/cadre.css">
		<link rel="stylesheet" type="text/css" href="https://genius-corp.fr/wikigame/css/home_page.css">
		<link rel="icon" type="image/png" href="https://genius-corp.fr/wikigame/images/favicon.png" />
	</head>
	<body>
		<header>
			<img class="image" src="https://genius-corp.fr/wikigame/images/Wikigame.png" alt="Wikigame"  />
		</header>
		<div class="page">
			<div class="content">
				<div class="playmode">
					<div class="pageswiki">
						<div class="playform">
							<form name="playform" method="post" action="index.php">
								Départ &thinsp; : <input type="text" name="startword" value="<?php echo $startword;?>"/> <br/>
								Arrivée : <input type="text" name="endword" value="<?php echo $endword;?>"/><br/>
								<p>
									<label for="nodes">Combien de degrés ?</label><br />
									<select name="nodes" id="noeuds">
										<option value="-1">illimités</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
										<option value="6">6</option>
									</select>
								</p>
								<input class="button" type="submit" name="playgame" value="Go"/>
								<input class="button" type="submit" name="newwords" value="Articles aléatoires"/>
							</form>
						</div>
					</div>
					<div class="reminders">
						<h4>Ton Objectif</h4>
						<?php
							if ($playgame == 1){
							  echo"<br>";
							  echo 'Tu dois aller de ';
							  echo '"'.normalizetexttodisplay($_SESSION['sstartword'], $charset='utf-8').'"';
							  echo ' vers <br>';
							  echo '"'.normalizetexttodisplay($_SESSION['sendword'], $charset='utf-8').'"';
							  echo"<br>";
							  echo"<br>";
							  echo 'Tu as déjà fait '.$_SESSION['scount']." clic".singularorplural($_SESSION['scount']).".";
							  echo "<br>";
							  echo "Tu as choisi de le faire en ".$_SESSION['snodes']." coup".singularorplural($_SESSION['snodes']).".";
							}
							if ($articleclick == 1){
							  echo"<br>";
							  echo 'Tu dois aller de ';
							  echo '"'.normalizetexttodisplay($_SESSION['sstartword'], $charset='utf-8').'"';
							  echo ' vers <br>';
							  echo '"'.normalizetexttodisplay($_SESSION['sendword'], $charset='utf-8').'"';
							  echo"<br>";
							  echo"<br>";
							  echo 'Tu as déjà fait '.$_SESSION['scount']." clic".singularorplural($_SESSION['scount']).".";
							  echo "<br>";
							  echo "Tu as choisi de le faire en ".$_SESSION['snodes']." coup".singularorplural($_SESSION['snodes']).".";
							}
							?>
					</div>
					<div class="path">
						<h4>Ton parcours</h4>
						<?php
							if (!empty($_GET['articleclick'])){
							  echo normalizetexttodisplay($_SESSION['sstartword'], $charset='utf-8');
							  echo $_SESSION['spath'];
							}
							?>
					</div>
					<div class="chrono">
						<h4>Ton temps</h4>
						<img class="image2" src="https://genius-corp.fr/wikigame/images/chrono.gif" alt="chrono"  />
						<?php
							if ($articleclick == 1){
							  echo $_SESSION['X']->h. 'h ';
							  echo $_SESSION['X']->i. 'min ';
							  echo $_SESSION['X']->s. 'sec ';
							  echo"<br>";
							}
							?>
					</div>
					<div class="raz">
						<form name="raz" method="post" action="index.php">
							<input class="button" type="submit" name="raz" value="Abandonner"/>
						</form>
					</div>
				</div>
				<div class="gamedisplay">
					<?php
						// Formulaire de départ
						if ($playgame == 1){
						  DisplayArticle ($_SESSION['sstartword']);
						}
						if ($articleclick == 1){
						  DisplayArticle($article);
						}
						?>
				</div>
				<div class="lastscores">
					<?php
						if($playgame != 1){
						  ViewScoreDisplay($lastscores);}
						  else {}
						    ?>
				</div>
				<br>
				<?php
					if($playgame != 1){
					  echo
					  "<footer>
					  <h3> R&egravegles du jeu: </h3>
					  <li> Pour commencer une partie veuillez inscrire, dans les cases pr&eacutevues &agrave cet effet, le nom de votre article de d&eacutepart ainsi que celui d'arriv&eacutee. Ensuite, cliquez sur 'Go' et c'est parti ! <br>
					  PS: Si vous aimez les d&eacutefis, vous pouvez aussi g&eacuten&eacuterer al&eacuteatoirement des articles en cliquant sur 'Articles al&eacuteatoires'. </li> <br>
					  <li> Pour gagner une partie, c'est tr&egraves simple, il vous suffit de cliquer sur les mots qui m&egravenent vers d'autres pages jusqu'au moment o&ugrave vous arrivez sur la page finale. Attention, si vous voulez &ecirctre le meilleur, n'oubliez pas un d&eacutetail, le but est aussi d'aller le plus vite possible tout en essayant de visiter un minimum de pages. <br>
					  <br>Exemple: Si vous devez aller de 'France' &agrave 'Lille', essayez 'France' puis 'Paris' puis 'Lille'. Evitez de prendre des chemins complexes comme 'Luxembourg' ou 'oc&eacutean Indien'. </li> <br>
					  <li> Pour abandonner, si vous vous sentez faible ou agac&eacute, cliquez simplement sur 'Abandonner' et votre calvaire s'arr&ecirctera.</li> <br> <br>
					  <p> Voil&agrave vous savez tout, bon courage &agrave vous et si vous avez des questions, eh bien gardez les pour vous ! PEACE... </p>
					  </footer>" ;
					}
					else {}
					  ?>
			</div>
		</div>
	</body>
</html>
