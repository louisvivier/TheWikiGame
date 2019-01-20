<?php
ini_set("display_errors",0);error_reporting(0);
require 'model.php';
//Chrono
if(!isset($_SESSION['starttime'])){
  $_SESSION['currtime']=new DateTime();
  $_SESSION['X']=0;
  $_SESSION['Y']=0;
}
else{
  $_SESSION['lasttime']=$_SESSION['currtime'];
  $_SESSION['currtime']=new DateTime();
  $_SESSION['X']=$_SESSION['starttime']->diff(new DateTime());
  $_SESSION['Y']=$_SESSION['lasttime']->diff(new DateTime());
}
//Si bouton newwords
if (!empty($_POST['newwords'])){
  $startword = GetRandomArticle();
  $endword = GetRandomArticle();
}

//Si bouton go
if (!empty($_POST['playgame'])){
  $endword=$_POST['endword'];
  $startword=$_POST['startword'];
  $nodes = $_POST['nodes'];
  $getnbrdegrees = GetNbrOfDegrees($startword,$endword);
  if (($nodes >= $getnbrdegrees)|| ($nodes == '-1')){
    $playgame=1;
    $_SESSION['starttime']=new DateTime();
    $_SESSION['sstartword'] = normalizetextforsystem($startword, $charset='utf-8');
    $_SESSION['sendword'] = normalizetextforsystem($endword, $charset='utf-8');
    $_SESSION['snodes'] = $nodes;
    $_SESSION['spath']=NULL;
    $_SESSION['scount']=0;
  }
  else {
    echo '<script type="text/javascript">window.alert("Impossible ! Choisis un nombre de noeuds plus grand !");</script>';
  }
}
//Si clique sur lien
if (!empty($_GET['articleclick'])){
  $article = $_GET['articleclick'];
  $_SESSION['spath'] = $_SESSION['spath'].' > '.normalizetexttodisplay($article, $charset='utf-8');
  $articleclick = 1;
  $_SESSION['scount']++;
}

//Bouton RAZ
if (!empty($_POST['raz'])){
  session_destroy();
  echo "<script type='text/javascript'>document.location.replace('index.php');</script>";
}

//Bouton Même partie
if (!empty($_POST['playsamewords'])){
  session_destroy();
  $id = $_POST['playsamewordsvalue'];
  $idscores = FindScoreWithId($id);
  foreach($idscores as $idscore);
  $startword = $idscore['startword'];
  $endword = $idscore['endword'];
}

//Recuperation des données de partage
if (!empty($_GET['s1w']) && !empty($_GET['s2w']) ){
  session_destroy();
  $startword = $_GET['s1w'];
  $endword = $_GET['s2w'];
}

$lastscores = GetLastScores('10');

require 'view.php';
