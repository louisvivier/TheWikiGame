<?php
ini_set("display_errors",0);error_reporting(0);
session_start();


try {
  $bdd = new PDO('mysql:host=localhost;dbname=DBNAME;charset=utf8', 'USER', "PASSWORD", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch (Exception $e) {
  die('Erreur : ' . $e->getMessage());
}

//Obtenir le texte d'une class HTML
function GetClassContent ($class, $html){
  $dom = new DOMDocument();
  $res=$dom->loadHTML($html);
  $xpath = new DomXPath($dom);
  $divs = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $class ')]");
  foreach($divs as $div) {
    return $div->nodeValue;
  }
}

//obtenir le texte entre deux balises
function GetNumberDegreesOnPage ($html){
  $pattern = '#([0-9]) degree#';
  $matches = array();
  preg_match_all($pattern, $html,$matches);
  foreach ($matches as $match);
  return $match[0];
}

//Obtenir un article au harsard
function GetRandomArticle (){
  $randomwikilink = 'https://fr.wikipedia.org/wiki/Sp%C3%A9cial:Page_au_hasard/';
  $randomwiki = curl_init();
  curl_setopt($randomwiki, CURLOPT_URL, $randomwikilink);
  curl_setopt($randomwiki,CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($randomwiki,CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($randomwiki, CURLOPT_COOKIESESSION, true);
  curl_setopt($randomwiki, CURLOPT_RETURNTRANSFER, true);
  libxml_use_internal_errors(true);
  $loadedwikiarticle = curl_exec($randomwiki);
  return GetClassContent('firstHeading',$loadedwikiarticle);
  curl_close($randomwiki);
}

//Afficher un article en donnant son nom
function DisplayArticle ($article){
  normalizetextforsystem($article, $charset='utf-8');
  $wikiarticle = 'https://fr.wikipedia.org/wiki/'.$article;
  $wiki = curl_init();
  curl_setopt($wiki, CURLOPT_URL, $wikiarticle);
  curl_setopt($wiki,CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($wiki,CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($wiki, CURLOPT_COOKIESESSION, true);
  curl_setopt($wiki, CURLOPT_RETURNTRANSFER, true);
  libxml_use_internal_errors(true);
  $loadedwikiarticle = curl_exec($wiki);
  $loadedwikiarticle=str_replace('href="/wiki/','href="index.php?articleclick=',$loadedwikiarticle);
  echo $loadedwikiarticle;
  $check = GetClassContent ("firstHeading", $loadedwikiarticle);
  $check = normalizetextforsystem($check, $charset='utf-8');
  $testendword = normalizetextforsystem($_SESSION['sendword'], $charset='utf-8');
  if(($_SESSION['snodes'] == ($_SESSION['scount']))){
    echo "<script type='text/javascript'>document.location.replace('loose/loosepage.php');</script>";
  }
  if($check == $testendword){
    echo "<script type='text/javascript'>document.location.replace('win/savepage.php');</script>";
  }
  curl_close($wiki);
}

//Normalisation du texte pour affichage
function normalizetexttodisplay($str, $charset='utf-8'){
  $str = htmlentities($str, ENT_NOQUOTES, $charset);
  $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
  $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str);
  $str = preg_replace('#&[^;]+;#', '', $str);
  $str = str_replace('_',' ',$str);
  $str = mb_convert_case($str, MB_CASE_TITLE, "UTF-8");
  return $str;
}

//normalisation du texte pour le système
function normalizetextforsystem($str, $charset='utf-8'){
  //$str = htmlentities($str, ENT_NOQUOTES, $charset);
  //$str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
  //$str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str);
  $str = preg_replace('#&[^;]+;#', '', $str);
  $str = str_replace('_',' ',$str);
  $str = strtolower($str);
  return $str;
}

//Normalisation du texte pour la page finale
function normalizetextwinpage($str, $charset='utf-8'){
  $str = htmlentities($str, ENT_NOQUOTES, $charset);
  $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
  $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str);
  $str = preg_replace('#&[^;]+;#', '', $str);
  $str = str_replace('_',' ',$str);
  $str = str_replace(' ','%20',$str);
  $str = mb_convert_case($str, MB_CASE_TITLE, "UTF-8");
  return $str;
}

//Ajout d'un S suivant le nombre
function singularorplural($data){
  if ($data > 1){
    return $plural = 's';
  }
  else {
  }
}

//Obtenir les derniers scores
function GetLastScores ($nbrofscore){
  global $bdd;

  $lastscores = array();

  $req = $bdd->query('SELECT * FROM leaderboard ORDER BY id DESC LIMIT 0, '.$nbrofscore);
  while ($donnees = $req->fetch()) {
    $lastscores[] = array('startword' => $donnees['startword'], 'endword' => $donnees['endword'], 'clics' => $donnees['clics'], 'time' => $donnees['time'], 'username' => $donnees['username'], 'date' => $donnees['date']);

  }
  $req->closeCursor();
  return ($lastscores);
}

//Ajouter un score
function AddAScore ($startword,$endword,$clics,$time,$course,$username,$comment){
  global $bdd;

  $req = $bdd->prepare('INSERT INTO leaderboard(startword,endword,clics,time,course,username,comment,date) VALUES(:startword,:endword,:clics,:time,:course,:username,:comment,NOW())');
  $req->execute(array(
    'startword' => $startword,
    'endword' => $endword,
    'clics' => $clics,
    'time' => $time,
    'course' => $course,
    'username' => $username,
    'comment' => $comment
  ));
}

//Trouver 10 meilleurs scores sur le même parcours
function FindASameScore($startword,$endword,$nbrofscore){
  global $bdd;
  $scores = array();
  $req = $bdd->query('SELECT * FROM leaderboard WHERE startword="'.$startword.'" AND endword="'.$endword.'" ORDER BY clics LIMIT 0,'.$nbrofscore);
  while ($donnees = $req->fetch()) {
    $scores[] = array('startword' => $donnees['startword'], 'endword' => $donnees['endword'], 'clics' => $donnees['clics'], 'time' => $donnees['time'], 'username' => $donnees['username'],'comment' => $donnees['comment'],'date' => $donnees['date']);
  }
  $req->closeCursor();
  return array_reverse($scores);
}

//Recherche suivant pseudo
function FindScoresWithUser($username,$nbrofscore){
  global $bdd;
  $scores = array();
  $req = $bdd->query('SELECT * FROM leaderboard WHERE username="'.$username.'" ORDER BY clics LIMIT 0,'.$nbrofscore);
  while ($donnees = $req->fetch()) {
    $scores[] = array('startword' => $donnees['startword'], 'endword' => $donnees['endword'], 'clics' => $donnees['clics'], 'time' => $donnees['time'], 'username' => $donnees['username'],'comment' => $donnees['comment'],'date' => $donnees['date']);
  }
  $req->closeCursor();
  return array_reverse($scores);
}

//Recuperation du dernier ID
function GetLastID(){
  global $bdd;
  $ids = array();
  $req = $bdd->query('SELECT id FROM leaderboard ORDER BY id DESC LIMIT 1');
  while ($donnees = $req->fetch()) {
    $ids[] = array('id' => $donnees['id']);
  }
  $req->closeCursor();
  foreach($ids as $id)
  return ($id['id']);
}

//Recuperation infos avec id
function FindScoreWithId($id){
  global $bdd;
  $scores = array();
  $req = $bdd->query('SELECT * FROM leaderboard WHERE id="'.$id.'"');
  while ($donnees = $req->fetch()) {
    $scores[] = array('startword' => $donnees['startword'], 'endword' => $donnees['endword'], 'clics' => $donnees['clics'], 'time' => $donnees['time'],'course'=> $donnees['course'], 'username' => $donnees['username'],'comment' => $donnees['comment'],'date' => $donnees['date']);
  }
  $req->closeCursor();
  return array_reverse($scores);
}

//Affichage des scores view
function ViewScoreDisplay($arrays){
  echo '<table align="center" cellpadding="1" width=60%>';
  echo '<tr>';
  echo '<td><h5>Date</h5></td>';
  echo '<td><h5>Joueur</h5></td>';
  echo '<td><h5>Depart</h5></td>';
  echo '<td><h5>Arrivée</h5></td>';
  echo '<td><h5>Clics</h5></td>';
  echo '<td><h5>Temps</h5></td>';
  echo '</tr>';
  foreach($arrays as $array){
  echo '<tr>';
  echo '<td>'.$array["date"].'</td>';
  echo '<td>'.IntegrationLinkUsrShare($array['username']).'</td>';
  echo '<td>'.IntegrationLinkWordsShareStartword($array['startword'],$array['endword']).'</td>';
  echo '<td>'.IntegrationLinkWordsShareEndword($array['startword'],$array['endword']).'</td>';
  echo '<td>'.$array['clics'].'</td>';
  echo '<td>'.$array["time"].'</td>';
  }
  echo '</tr>';
  echo '</table>';
}

function FullScoreDisplay($arrays){
  echo '<table align="center" cellpadding="1" width=60%>';
  echo '<tr>';
  echo '<td><h5>Date</h5></td>';
  echo '<td><h5>Joueur</h5></td>';
  echo '<td><h5>Depart</h5></td>';
  echo '<td><h5>Arrivée</h5></td>';
  echo '<td><h5>Clics</h5></td>';
  echo '<td><h5>Temps</h5></td>';
  echo '<td><h5>Commentaire</h5></td>';
  echo '</tr>';
  foreach($arrays as $array){
  echo '<tr>';
  echo '<td>'.$array["date"].'</td>';
  echo '<td>'.IntegrationLinkUsrShare($array['username']).'</td>';
  echo '<td>'.IntegrationLinkWordsShareStartword($array['startword'],$array['endword']).'</td>';
  echo '<td>'.IntegrationLinkWordsShareEndword($array['startword'],$array['endword']).'</td>';
  echo '<td>'.$array['clics'].'</td>';
  echo '<td>'.$array["time"].'</td>';
  echo '<td>'.$array["comment"].'</td>';
  }
  echo '</tr>';
  echo '</table>';
}

//creation d'un lien de partage username
function LinkUsrShare ($username){
  return 'https://geniusgames.fr/wikigame/usr/'.$username;
}

//creation du lien cliquable sur la page
function IntegrationLinkUsrShare ($username){
  return '<a href="'.LinkUsrShare($username).'">'.$username.'</a>';
}

//creation d'un lien de partage des startword et endword
function LinkWordsShare ($startword,$endword){
  return 'https://geniusgames.fr/wikigame/'.$startword.'/'.$endword;
}

//Creation du lien pour faire la meme partie version endword
function IntegrationLinkWordsShareStartword ($startword,$endword){
  return '<a href="'.LinkWordsShare($startword,$endword).'">'.$startword.'</a>';
}

//Creation du lien pour faire la meme partie version endword
function IntegrationLinkWordsShareEndword ($startword,$endword){
  return '<a href="'.LinkWordsShare($startword,$endword).'">'.$endword.'</a>';
}

//Obtenir le nombre de degres possible
function GetNbrOfDegrees ($startword,$endword){
  $degreeslink = 'http://degreesofwikipedia.com/?a1='.$startword.'&linktype=1&a2='.$endword.'&skips=&submit=1497366838&currentlang=fr';
  $degrees = curl_init();
  curl_setopt($degrees, CURLOPT_URL, $degreeslink);
  curl_setopt($degrees,CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($degrees,CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($degrees, CURLOPT_COOKIESESSION, true);
  curl_setopt($degrees, CURLOPT_RETURNTRANSFER, true);
  libxml_use_internal_errors(true);
  $loadeddegrees = curl_exec($degrees);
  return GetNumberDegreesOnPage($loadeddegrees);
  curl_close($degrees);
}

// Initialisation des variables
$startword = NULL;
$endword = NULL;
$playgame =0;
$articleclick = 0;
