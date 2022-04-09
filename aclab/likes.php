<?php session_start ();?>
<!doctype html>
<html>
  <head>
  <meta charset="utf-8">
  <title>Page Principale</title>
  <link rel="stylesheet" href="css/css_home/style.css">
</head>
<body>
    <div class="action">
    <div class="profile" onclick="menuToggle();">
    <img src="export.php?">
    </div>
    <?php

    $bdd = new PDO('mysql:host=127.0.0.1;dbname=espace_membre', 'root', '');
    
    if(isset($_SESSION['id']) && $_SESSION['id'] > 0) {
    $getid = intval($_SESSION['id']);

    $requser = $bdd->prepare('SELECT * FROM membres WHERE id = ?');
    $requser->execute(array($getid));
    $userinfo = $requser->fetch();

    $req_match_list = $bdd->prepare('SELECT id_2 FROM match_bd WHERE id_1 = ? AND likes = "1"');
    $req_match_list->execute(array($getid));
    // On prends tous les id que l'utilisateur connecté a aimé et on regarde si l'id l'a aimé aussi.
    $req_match_list2 = $bdd->prepare('SELECT * FROM match_bd WHERE id_1 = ?  AND id_2 = ? AND likes = "1"');

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
  <div align="center">
      <h4> Mes matchs</h4>
    </br>
  <?php
  while(true) {
    $id_2= $req_match_list->fetch();
    $req_match_list2->execute(array($id_2['id_2'],$getid));
    $id_match = $req_match_list2->fetch();
    $info_match = $bdd->prepare('SELECT * FROM membres WHERE id = ?');
    $info_match->execute(array($id_match['id_1']));
    $infos = $info_match->fetch();
    $user_date_nais = $infos['date_nais'];
    $date_actuelle = date("Y-m-d");
    $diff = abs(strtotime($date_actuelle) - strtotime($user_date_nais));
    $years = floor($diff / (365*60*60*24));
    //if ($id_match['id_2'] == $id_2['id_2'])
    if(empty($infos['prenom'])){
        break;
    }
    ?>
    </br>
    <h4><?php echo '<img width="50" height="50" src="data:image/jpeg;base64,'.base64_encode($infos['pdp']).'" />'; ?> <?php echo $infos['prenom'];?> <?php echo $years;?> ans</h5>
    </br>
    </br>
    </div>
    <?php
    }
    ?>

  <script>
    function menuToggle(){
      const toggleMenu = document.querySelector('.menu');
      toggleMenu.classList.toggle('active')
    }
  </script>
</body>
</html>
<?php } ?>