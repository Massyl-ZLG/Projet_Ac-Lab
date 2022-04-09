<?php
session_start();
$bdd = new PDO('mysql:host=127.0.0.1;dbname=espace_membre', 'root', '');
 
if(isset($_POST['forminscription'])) {
   $prenom = htmlspecialchars($_POST['prenom']);
   $mail = htmlspecialchars($_POST['mail']);
   $mail2 = htmlspecialchars($_POST['mail2']);
   $mdp = sha1($_POST['mdp']);
   $mdp2 = sha1($_POST['mdp2']);
   $date_nais = $_POST['naissance'];
   date_default_timezone_set("Europe/Paris");
   $date_actuelle = date("Y-m-d");

   if (isset($_POST['genre'])) {
      $genre = htmlspecialchars($_POST['genre']);
  }
   $diff = abs(strtotime($date_actuelle) - strtotime($date_nais));
   $years = floor($diff / (365*60*60*24));

   if(!empty($_POST['prenom']) AND !empty($_POST['mail']) AND !empty($_POST['mail2']) AND !empty($_POST['mdp']) AND !empty($_POST['mdp2']) AND !empty($_POST['naissance']) AND !empty($_POST['genre'])) {
      $prenomlength = strlen($prenom);
      if($prenomlength <= 255) {
         if($mail == $mail2) {
            if(filter_var($mail, FILTER_VALIDATE_EMAIL)) {
               $reqmail = $bdd->prepare("SELECT * FROM membres WHERE mail = ?");
               $reqmail->execute(array($mail));
               $mailexist = $reqmail->rowCount();
               if($mailexist == 0) {
                  if($mdp == $mdp2) {
                     $longueurKey = 15;
                     $key = "";
                     for($i=1;$i<$longueurKey;$i++){
                        $key .=  mt_rand(0,9);
                     }
                     if ($years>=18){
                     $insertmbr = $bdd->prepare("INSERT INTO membres(prenom, Genre, mail, motdepasse, confirmkey, date_nais) VALUES(?, ?, ?, ?, ?, ?)");
                     $insertmbr->execute(array($prenom, $genre, $mail, $mdp, $key, $date_nais));

                     $header="MIME-Version: 1.0\r\n";
                     $header.='From:"Site de rencontre"<confirmation.rencontres@gmail.com>'."\n";
                     $header.='Content-Type:text/html; charset="utf-8"'."\n";
                     $header.='Content-Transfer-Encoding: 8bit';
                     
                     
                     
                     $success = "Votre compte a bien été créé !";
                     
                     } else {
                        $erreur = "Désolé, tu es trop jeune !";
                     }
                  } else {
                     $erreur = "Vos mots de passes ne correspondent pas !";
                  }
               } else {
                  $erreur = "Adresse mail déjà utilisée !";
               }
            } else {
               $erreur = "Votre adresse mail n'est pas valide !";
            }
         } else {
            $erreur = "Vos adresses mail ne correspondent pas !";
         }
      } else {
         $erreur = "Votre prenom ne doit pas dépasser 255 caractères !";
      }
   } else {
      $erreur = "Tous les champs doivent être complétés !";
   }

   if(!empty($mail) AND !empty($mdp)) {
      $requser = $bdd->prepare("SELECT * FROM membres WHERE mail = ? AND motdepasse = ?");
      $requser->execute(array($mail, $mdp));
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
      <link rel="stylesheet" type="text/css" href="css/css_inscription/style.css">
      <title>Inscription</title>
      <meta charset="utf-8">
   </head>
   <body>
      <section>
         <div class="imgBx">
            <img src="images/fond2.jpg">
         </div>
         <div class="contentBx">
         <div class="formBx">
         <h2>Inscription</h2>
         <form method="POST" action="">
         <div class="inputBx">
               <span>Prénom</span>
                  <input type="text" placeholder="Votre prenom" id="prenom" name="prenom" value="<?php if(isset($prenom)) { echo $prenom; } ?>" />
                  </div>
                  <div class="inputBx">
                  <span>Date de naissance</span>
                  <input type="date" id="naissance" name = "naissance" value="<?php if(isset($date_nais)) { echo $date_nais; } ?>" min="1922-02-07"/>
                  </div>  
                  <div class="inputBx">
                  <span>Homme</span>         
                  <input type="radio" id="M" name="genre" value="M"><br>
                  </div>
                  <div class="inputBx">
                  <span>Femme</span>
                  <input type="radio" id="F" name="genre" value="F">
                  </div>
                  <div class="inputBx">
                  <span>Mail</span>
                  <input type="email" placeholder="Votre mail" id="mail" name="mail" value="<?php if(isset($mail)) { echo $mail; } ?>" pattern="[\w.%+-]+@lacatholille.fr" />
                  </div>
                  <div class="inputBx">
                  <span>Confirmation mail</span>
                  <input type="email" placeholder="Confirmez votre mail" id="mail2" name="mail2" value="<?php if(isset($mail2)) { echo $mail2; } ?>" />
                  </div>  
                  <div class="inputBx">
                  <span>Mot de passe</span> 
                  <input type="password" placeholder="Votre mot de passe" id="mdp" name="mdp" />   
                  </div>
                  <div class="inputBx">
                  <span>Confirmation mot de passe</span>
                  <input type="password" placeholder="Confirmez votre mot de passe" id="mdp2" name="mdp2" />
                  </div>
                  <div class="inputBx">
                  <p>Vous avez déjà un compte ?<a href="connexion.php"> Connectez vous !</a></p>
                  </div>
            <?php
         if(isset($erreur)) {
            echo '<font color="red">'.$erreur."</font>";
         }
         
         if(isset($success)) {
            echo '<font color="blue">'.$success."</font>";
         }

         ?>
         <div class="inputBx">
         <input type="submit" name="forminscription" value="Je m'inscris" />
         </div>
         </form>
         </div>
         </div>
      </div>
      </section>
   </body>
</html>
