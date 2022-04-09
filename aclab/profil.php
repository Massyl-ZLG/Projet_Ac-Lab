<?php
session_start();
 
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
?>
<html>
   <head>
      <link rel="stylesheet" type="text/css" href="css/css_profil/style.css">
      <title>Profil</title>
      <meta charset="utf-8">
   </head>
   <body>
      <section>
   <div class="imgBx">
      <img src="images/arriere_plan.jpg">
   </div>
      <div class="contentBx">
         <div class="formBx">
         <h2>Profil de <?php echo $userinfo['prenom']; ?></h2>
         <br> Prenom : <?php echo $userinfo['prenom']; ?>
         <br />
         Mail : <?php echo $userinfo['mail']; ?>
         <br />
         <?php
         if(isset($_SESSION['id']) AND $userinfo['id'] == $_SESSION['id']) {         
         }
         ?>
         <div class="buttons">  
         <img src="images/home.png"><a href="page_principale.php">Home</a> 
         </div>
         <div class="buttons">  
         <img src="images/settings2.png"><a href="editionprofil.php">Modifier</a> 
         </div>
         <div class="buttons">  
         <img src="images/power-off.png"><a href="deconnexion.php">Deconnexion</a> 
         </div>
      </div>
      </div>
      </section>
   </body>
</html>
<?php
}
?>