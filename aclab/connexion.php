<?php
session_start();
 
$bdd = new PDO('mysql:host=127.0.0.1;dbname=espace_membre', 'root', '');
 
if(isset($_POST['formconnexion'])) {
   $mailconnect = htmlspecialchars($_POST['mailconnect']);
   $mdpconnect = sha1($_POST['mdpconnect']);
   if(!empty($mailconnect) AND !empty($mdpconnect)) {
      $requser = $bdd->prepare("SELECT * FROM membres WHERE mail = ? AND motdepasse = ?");
      $requser->execute(array($mailconnect, $mdpconnect));
      $userexist = $requser->rowCount();
      if($userexist == 1) {
         $userinfo = $requser->fetch();
         $_SESSION['id'] = $userinfo['id'];
         $_SESSION['prenom'] = $userinfo['prenom'];
         $_SESSION['mail'] = $userinfo['mail'];
         header("Location: page_principale.php");
      } else {
         $erreur = "Mauvais mail ou mot de passe !";
      }
   } else {
      $erreur = "Tous les champs doivent être complétés !";
   }
}
?>
<html>
   <head>
   <link rel="stylesheet" type="text/css" href="css/css_connexion/style.css">
      <title>Connexion</title>
      <meta charset="utf-8">
   </head>
   <body>
      <section>   
         <div class="imgBx">
            <img src="images/fond.jpg">
         </div>
         <div class="contentBx">
         <div class="formBx">
         <h2>Connexion</h2>
         <form method="POST" action="">
            <div class="inputBx">
               <span>EMail</span>
            <input type="email" name="mailconnect" placeholder="Mail" />
            </div>
            <div class="inputBx">
               <span>Password</span>
            <input type="password" name="mdpconnect" placeholder="Mot de passe" />
            </div>
            <div class="remember">
               <label> <input type="checkbox" name="">Se souvenir de moi</label>
            </div>
            <div class="inputBx">
            <input type="submit" name="formconnexion" value="Se connecter !" />
            </div>
            <div class="inputBx">
            <p>Vous n'êtes pas encore inscrit?<a href="inscription.php"> Rejoignez nous !</a></p>
            </div>
            <?php
         if(isset($erreur)) {
            echo '<font color="red">'.$erreur."</font>";
         }
         ?>
         
	 </form>
    </div>
    </div>   
   </section>
   </body>
</html>
