<?php
$bdd = new PDO('mysql:host=127.0.0.1;dbname=espace_membre', 'root', '');
 
if(isset($_GET['prenom'], $_GET['key']) AND !empty($_GET['prenom']) AND !empty($_GET['key'])) {
   $prenom = htmlspecialchars(urldecode($_GET['prenom']));
   $key = htmlspecialchars($_GET['key']);
   $requser = $bdd->prepare("SELECT * FROM membres WHERE prenom = ? AND confirmkey = ?");
   $requser->execute(array($prenom, $key));
   $userexist = $requser->rowCount();
   if($userexist == 1) {
      $user = $requser->fetch();
      if($user['confirme'] == 0) {
         $updateuser = $bdd->prepare("UPDATE membres SET confirme = 1 WHERE prenom = ? AND confirmkey = ?");
         $updateuser->execute(array($prenom,$key));
         echo "Votre compte a bien été confirmé !";
      } else {
         echo "Votre compte a déjà été confirmé !";
      }
   } else {
      echo "L'utilisateur n'existe pas !";
   }
}
?>