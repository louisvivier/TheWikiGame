<?php
ini_set("display_errors",0);error_reporting(0);
session_start();
require '..//model.php';
//Sauvegarde Formulaire
if (!empty($_POST['save'])){
  AddAScore ($_SESSION['sstartword'],$_SESSION['sendword'],$_SESSION['scount'],$_SESSION['X']->h.':'.$_SESSION['X']->i.':'.$_SESSION['X']->s,$_SESSION['spath'],$_POST['username'],$_POST['comment']);
  $lastInsertId = GetLastID();
  echo ("<script type='text/javascript'>document.location.replace('winpage.php?id=".$lastInsertId."');</script>");
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>WikiGame</title>
    <link rel="stylesheet" type="text/css" href="https://geniusgames.fr/wikigame/css/wiki.css">
		<link rel="stylesheet" type="text/css" href="https://geniusgames.fr/wikigame/css/cadre.css">
		<link rel="stylesheet" type="text/css" href="https://geniusgames.fr/wikigame/css/home_page.css">
		<link rel="icon" type="image/png" href="https://geniusgames.fr/wikigame/images/favicon.png" />
	</head>
	<body id="save">
		<header>
			<img class="image" src="../images/Wikigame.png" alt="Wikigame"  />
			<h1>Bravo tu as remporté le WikiGame</h1>
			<br>
		</header>
		<div class='saveform'>
			<h2>Avant de voir ton score, inscris ton pseudo et un commentaire !</h2>
			<form name="savescore" method="post" action="savepage.php">
				Pseudo : <input type="username" name="username" value=""/> <br/>
				Commentaire : <input type="text" name="comment" value=""/><br/>
				<input class="button" type="submit" name="save" value="Sauvegarder"/>
			</form>
		</div>
	</body>
</html>
