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
      <title>Mes likes</title>
      <meta charset="utf-8">
   </head>
   <body>
      <div class="action">
         <div class="buttons">
         <img src="images/home.png"><a href="page_principale.php">Home</a>
         </div>
      </div>
   </body>
</html>
<?php
}
?>