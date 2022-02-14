<!doctype html>
<html>
  <head>
  <meta charset="utf-8">
  <title>Page Principale</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="action">
    <div class="profile" onclick="menuToggle();">
    <img src="export.php?">
    </div>
    <?php
    session_start();
    $bdd = new PDO('mysql:host=127.0.0.1;dbname=espace_membre', 'root', '');
    
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
    $recherche_info = $req_recherche->fetch();
    $user_prenom = $recherche_info['prenom'];
    if(isset($_POST['jaime'])){
      $recherche_info = $req_recherche->fetch();
      $req_like = $bdd->prepare('INSERT INTO match_bd (id_1,likes,id_2) VALUES (?, ?, ?)');
      $req_like->execute(array($getid, 1, $recherche_info['id']));
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
        <li><img src="images/support.png"><a href="#">Contact</a></li>
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
  <div align="center">
         <h2>Profil de <?php echo $recherche_info['prenom']; ?></h2>
         <br /><br />
         <?php echo '<img src="data:image/jpeg;base64,'.base64_encode($recherche_info['pdp']).'"/>'; ?>
         <br /><br />
         Age = <?php echo $years; ?>
         <br />
         Genre = <?php echo $genre_recherche; ?>
         <br />
         <br />
         <form action='' method='POST'>
         <input type='submit' name='jaime' value ="J'aime" />
         <input type='submit' name='passe' value = "Passer"/>
          </form>
      </div>
</body>
</html>
<?php
}
?>