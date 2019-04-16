<?php
ini_set("display_errors",0);error_reporting(0);
require '..//model.php';
if (!empty($_GET['usr'])){
  $sharedusername = $_GET['usr'];
}
$linktoshare = LinkUsrShare($sharedusername);
$usrmessagetoshare = 'Regarde les scores de '.$sharedusername.' dans le Wikigame';
$userscores = FindScoresWithUser($sharedusername,30);
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
	<body id="user">
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v2.9";
			fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));
		</script>
		<div class="header">
			<img class="image" src="../images/Wikigame.png" alt="Wikigame"  />
			<h2>Voici les scores de <?php echo $sharedusername;?> </h2>
		</div>
		<div id="utilbody">
			<div class="usrscores">
				<?php
					FullScoreDisplay($userscores);
					?>
			</div>
			<br> <br>
			<div class="sharing">
				<h4>Partage avec tes amis ce tableau des scores</h4>
				<br>
				<div class="Facebook">
					<div class="fb-share-button" data-href="<?php echo $linktoshare ?>" data-layout="button" data-size="large" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=%2F&amp;src=sdkpreparse">Partager</a></div>
				</div>
				<br>
				<div class="Twitter">
					<a href="https://twitter.com/intent/tweet?button_hashtag=&text=<?php echo $usrmessagetoshare ?>" class="twitter-hashtag-button" data-lang="fr" data-size="large" data-url="<?php echo $linktoshare ?>">Tweet #LeWikiGame</a> <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
				</div>
				<br>
				<div class="link">
					<input type="text" name="link" value="<?php echo $linktoshare?>"/>
				</div>
				<br>
				<div class=newgame>
					<form name="newgame" method="post" action="../index.php">
						<input class="newgamebutton" type="submit" name="newgame" value="Nouveau Jeu"/>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>
