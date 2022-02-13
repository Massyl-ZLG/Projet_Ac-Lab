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
        if(isset($_SESSION['id']) AND $_GET['id'] > 0) {
          $getid = intval($_GET['id']);
          $requser = $bdd->prepare('SELECT genre FROM membres WHERE id = ?');
          $requser->execute(array($getid));
          $usr_genre = $requser->fetch();
          echo $usr_genre;
       }
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
</body>
</html>