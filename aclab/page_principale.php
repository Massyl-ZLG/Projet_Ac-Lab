<?php session_start ();
if(!isset($_SESSION['counter'])) {
  $_SESSION['counter'] = 1;
}?>
<!doctype html>
<html>
  <head>
  <meta charset="utf-8">
  <title>Page Principale</title>
  <link rel="stylesheet" href="css/css_home/style.css">
</head>
<body>
<div class="fond">
    <img src="images/fond3.jpg">
  </div>
    <div class="action">
    <div class="profile" onclick="menuToggle();">
    <img src="export.php?">
    </div>
    <?php

$servername = "database";
$username = "root";
$password = "123";
$dbname = "espace_membre";
$port = "3306";

$bdd = new PDO("mysql:host=$servername;port=$port;dbname=$dbname",$username,$password);
    
    if(isset($_SESSION['id']) && $_SESSION['id'] > 0) {
    $getid = intval($_SESSION['id']);

    $requser = $bdd->prepare('SELECT * FROM membres WHERE id = ?');
    $requser->execute(array($getid)); 
    
    $userinfo = $requser->fetch();
    if(isset($userinfo['Genre']) && $userinfo['Genre'] == 'M'){
      $genre_recherche = "F";
      }
      else $genre_recherche = "M";

    $req_recherche = $bdd->prepare('SELECT * FROM membres WHERE genre = ?');
    $req_recherche->execute([$genre_recherche]);

    for($i=0;$i<$_SESSION['counter'];$i++){
    $recherche_info = $req_recherche->fetch();
    }

    $user_prenom = $recherche_info['prenom'];
    if(isset($_POST['jaime'])){
      
      $req_like = $bdd->prepare('INSERT INTO match_bd (id_1,likes,id_2) VALUES (?, ?, ?)');
      $req_like->execute(array($getid, 1, $recherche_info['id']));
      $req_check_match = $bdd->prepare('SELECT COUNT(*) FROM match_bd WHERE id_1 = ? AND id_2 = ?');
      $req_check_match->execute(array($recherche_info['id'], $getid));
      $val = $req_check_match->fetch();
      if($val[0]>0) {
        echo '<script>alert("MATCH");</script>'; 
      }
      ++$_SESSION['counter'];
      }

    if(isset($_POST['passe'])){
      ++$_SESSION['counter'];
      }

      $user_date_nais = $recherche_info['date_nais'];
    $date_actuelle = date("Y-m-d");
    $diff = abs(strtotime($date_actuelle) - strtotime($user_date_nais));
    $years = floor($diff / (365*60*60*24));
    ?>
    <div class="menu">
      <ul>
        <li><img src="images/coeur.png"><a href="likes.php">Mes Likes</a></li>
        <li><img src="images/user.png"><a href="profil.php">My profil</a></li>
        <li><img src="images/editing.png"><a href="editionprofil.php">Modifier profil</a></li>
        <li><img src="images/messages.png"><a href="#">Messages</a></li>
        <li><img src="images/setting.png"><a href="#">Paramétres</a></li>
        <li><img src="images/support.png"><a href="contact.php">Contact</a></li>
        <li><img src="images/power-off.png"><a href="deconnexion.php">Se Deconnecter</a></li>
      </ul>
  
    </div>
  </div>
  <script>
    function menuToggle(){
      const toggleMenu = document.querySelector('.menu');
      toggleMenu.classList.toggle('active')
    }
  </script>
  <section>
        <div class="profiles">
        <div class="formP">
  <?php if(!empty($recherche_info['prenom'])){ ?>
         <h4><?php echo $recherche_info['prenom']; ?></h4>
         <div class="imageP">
         <?php echo '<img src="data:image/jpeg;base64,'.base64_encode($recherche_info['pdp']).'"/>'; ?>
        </div>
         <p> <?php echo $years; ?> ans </p>
         <form action='' method='POST'>
         <div class="inputP">
         <input type='submit' name='jaime' value ="J'aime" />
         <div class="inputP">
         <input type='submit' name='passe' value = "Passer"/>
         </div>
        </div>
    </form>
    </div>
         </div>
         </section>
<?php
    }
    else{ ?>
        <h1>Il n'y a plus de profils à vous proposer.
      <?php
    }
}
?>
</body>
</html>