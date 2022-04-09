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
      <img src="images/contact_fond.jpeg">
   </div>
      <div class="contentBx">
         <div class="formBx">
         <h2>Nous contacter</h2>
         <div class="buttons">  
         <img src="images/email.png">massyl.zelleg@lacatholille.fr 
         </div>
         <div class="buttons">  
         <img src="images/email.png">alexandre.soares@lacatholille.fr
         </div>
      </div>
      </div>
      </section>
   </body>
</html>
<?php
}
?>