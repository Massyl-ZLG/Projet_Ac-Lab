<?php
session_start();
 
$bdd = new PDO('mysql:host=127.0.0.1;dbname=espace_membre', 'root', '');
 
if(isset($_SESSION['id']) && $_SESSION['id'] > 0) {
   $getid = intval($_SESSION['id']);
   $requser = $bdd->prepare('SELECT * FROM membres WHERE id = ?');
   $requser->execute(array($getid));
   $userinfo = $requser->fetch();
?>
<html>
   <head>
      <title>TUTO PHP</title>
      <meta charset="utf-8">
   </head>
   <body>
      <div align="center">
         <h2>Profil de <?php echo $userinfo['prenom']; ?></h2>
         <br /><br />
         prenom = <?php echo $userinfo['prenom']; ?>
         <br />
         Mail = <?php echo $userinfo['mail']; ?>
         <br />
         <?php
         if(isset($_SESSION['id']) AND $userinfo['id'] == $_SESSION['id']) {         
         }
         ?>
         <a href="editionprofil.php">Editer mon profil</a>
         <a href="page_principale.php">Home</a>
         <a href="deconnexion.php">Se déconnecter</a>
         <br />
      </div>
   </body>
</html>
<?php
}
?>