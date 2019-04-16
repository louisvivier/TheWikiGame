<?php
ini_set("display_errors",0);error_reporting(0);
session_start();
require '..//model.php';
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
		<header>
			<img class="image" src="../images/Wikigame.png" alt="Wikigame"  />
			<h1>Désolé mais tu as perdu...</h1>
			<br>
		</header>
		<div class='saveform'>
			<h2>En effet, tu pensais pouvoir aller de <?php echo $_SESSION['sstartword']?> à <?php echo $_SESSION['sendword']?> en <?php echo $_SESSION['snodes']?> noeuds.
      Mais tu n'as pas réussi... Re-tente ta chance en cliquant sur le bouton !</h2>
      <div class=playsamewords>
        <form name="playsamewords" method="post" action="../index.php">
          <input class="button" type="button" name="lienlooser" value="Recommencer" onclick="self.location.href='..//index.php?s1w=<?php echo $_SESSION['sstartword'] ?>&s2w=<?php echo $_SESSION['sendword'] ?>'"onclick>
        </form>
      </div>
		</div>
	</body>
</html>
