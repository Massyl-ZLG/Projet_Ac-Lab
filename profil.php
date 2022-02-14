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
      <link rel="stylesheet" type="text/css" href="style.css">
      <title>Profil</title>
      <meta charset="utf-8">
   </head>
   <body>
      <div class="action">
         <h2>Profil de <?php echo $userinfo['prenom']; ?></h2>
         <br /><br />
         prenom : <?php echo $userinfo['prenom']; ?>
         <br />
         Mail : <?php echo $userinfo['mail']; ?>
         <br />
         <?php
         if(isset($_SESSION['id']) AND $userinfo['id'] == $_SESSION['id']) {         
         }
         ?>
         <div class="buttons"> 
         <a class="stylebutton" href="editionprofil.php">Editer mon profil</a>
         <a class="stylebutton" href="page_principale.php">Home</a>
         <a class="stylebutton" href="deconnexion.php">Se déconnecter</a>
         <br />
         </div>
      </div>
      
   </body>
</html>
<?php
}
?>