<?php
ini_set("display_errors",0);error_reporting(0);
session_start();
require '..//model.php';

//Recuperation des données de partage
if (!empty($_GET['id'])){
  $id = $_GET['id'];
}

//Bouton Nouveau Jeu
if (!empty($_POST['newgame'])){
  session_destroy();
  echo "<script type='text/javascript'>document.location.replace('../index.php');</script>";
}

$ActualGameScores = FindScoreWithId($id);
foreach($ActualGameScores as $ActualGameScore);


$startword = normalizetextwinpage($ActualGameScore['startword'], $charset='utf-8');
$endword = normalizetextwinpage($ActualGameScore['endword'], $charset='utf-8');
$samescores = FindASameScore($ActualGameScore['startword'],$ActualGameScore['endword'],'10');
$linktoshare = 'https://genius-corp.fr/wikigame/win/'.$id.'';
$winmessagetoshare = 'J\'ai fait '.$ActualGameScore['clics']." clic".singularorplural($ActualGameScore['clics'])." ". 'pour aller de '.$ActualGameScore['startword'].' à '.$ActualGameScore['endword'].' sur le #WikiGame ! Tente de faire mieux que moi.'
?>

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
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v2.9";
			fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));
		</script>
		<header>
			<img class="image" src="../images/Wikigame.png" alt="Wikigame"  />
			<h2>Wow quelle partie !</h2>
		</header>
		<div class="recaptable">
			<div class="winreminders">
				<h4>Ton Objectif</h4>
				<?php
					echo"<br>";
					echo 'Tu devais aller de ';
					echo '"'.normalizetexttodisplay($ActualGameScore['startword'], $charset='utf-8').'"';
					echo ' vers <br>';
					echo '"'.normalizetexttodisplay($ActualGameScore['endword'], $charset='utf-8').'"';
					echo"<br>";
					echo"<br>";
					echo 'Tu l\'as fait en '.$ActualGameScore['clics']." clic".singularorplural($ActualGameScore['clics']).".";
					?>
			</div>
			<div class="winpath">
				<h4>Ton parcours</h4>
				<?php
					echo normalizetexttodisplay($ActualGameScore['startword'], $charset='utf-8');
					echo $ActualGameScore['course'];
					?>
			</div>
			<div class="winchrono">
				<h4>Ton temps</h4>
				<?php
					echo $ActualGameScore['time'];
					echo"<br>";
					?>
			</div>
			<div class="sharing">
				<h4>Partage avec tes amis</h4>
				Invite tes amis à faire mieux que toi !<br><br>
				<div class="Facebook">
					<div class="fb-share-button" data-href="<?php echo $linktoshare ?>" data-layout="button" data-size="large" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=%2F&amp;src=sdkpreparse">Partager</a></div>
				</div>
				<br>
				<div class="Twitter">
					<a href="https://twitter.com/intent/tweet?button_hashtag=&text=<?php echo $winmessagetoshare ?>" class="twitter-hashtag-button" data-lang="fr" data-size="large" data-url="<?php echo $linktoshare ?>">Tweet #LeWikiGame</a> <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
				</div>
				<br>
				<div class="link">
					<input type="text" name="link" value="<?php echo $linktoshare?>"/>
				</div>
				<br>
				<div class=newgame>
					<form name="newgame" method="post" action="../index.php">
						<input class="newgamebutton" type="submit" name="raz" value="Nouveau Jeu"/>
					</form>
				</div>
				<br>
				<div class=playsamewords>
					<form name="playsamewords" method="post" action="../index.php">
						<input class="playsamewordsbutton" type="submit" name="playsamewords" value="Faire la même partie"/>
						<input class="playsamewordvalue" type="hidden" name="playsamewordsvalue" value="<?php echo $id; ?>"/>
					</form>
				</div>
			</div>
		</div>
	</body>
	<br>
	<footer>
		<div class="scores">
			<?php
				FullScoreDisplay($samescores);
				?>
		</div>
	</footer>
</html>
