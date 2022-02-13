<?php
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
                     
                     $message='
                     <html>
                        <body>
                           <div align="center">
                              <a href="http://aclab/confirmation.php?prenom='.urlencode($prenom).'&key='.$key.'">Confirmez votre compte !</a>
                           </div>
                        </body>
                     </html>
                     ';

                     mail($mail, "Confirmation de compte", $message, $header); 
                     
                     $success = "Votre compte a bien été créé ! Veuillez confirmer votre mail";
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
}
?>

<html>
   <head>
      <link rel="stylesheet" type="text/css" href="css/style.css">
      <title>Inscription</title>
      <meta charset="utf-8">
   </head>
   <body>
      <div align="center">
         <h2>Inscription</h2>
         <br /><br />
         <form method="POST" action="">
            <table>
               <tr>
                  <td align="right">
                     <label for="prenom">Prénom :</label>
                  </td>
                  <td>
                     <input type="text" placeholder="Votre prenom" id="prenom" name="prenom" value="<?php if(isset($prenom)) { echo $prenom; } ?>" />
                  </td>
               </tr>
               <tr>
                  <td align="right">
                  <label for="naissance">Date de naissance</label>
                  </td>
                  <td>
                  <input type="date" id="naissance" name = "naissance" value="<?php if(isset($date_nais)) { echo $date_nais; } ?>" min="1922-02-07"/>
                  </td>
               </tr>
               <tr>
                  <td align="right">
                  <label for="M">Homme</label>
                  <input type="radio" id="M" name="genre" value="M"><br>
                  <label for="F">Femme</label>
                  <input type="radio" id="F" name="genre" value="F">
               </tr>
                  <td align="right">
                     <label for="mail">Mail :</label>
                  </td>
                  <td>
                     <input type="email" placeholder="Votre mail" id="mail" name="mail" value="<?php if(isset($mail)) { echo $mail; } ?>" pattern="[\w.%+-]+@lacatholille.fr" />
                  </td>
               </tr>
               <tr>
                  <td align="right">
                     <label for="mail2">Confirmation du mail :</label>
                  </td>
                  <td>
                     <input type="email" placeholder="Confirmez votre mail" id="mail2" name="mail2" value="<?php if(isset($mail2)) { echo $mail2; } ?>" />
                  </td>
               </tr>
               <tr>
                  <td align="right">
                     <label for="mdp">Mot de passe :</label>
                  </td>
                  <td>
                     <input type="password" placeholder="Votre mot de passe" id="mdp" name="mdp" />
                  </td>
               </tr>
               <tr>
                  <td align="right">
                     <label for="mdp2">Confirmation du mot de passe :</label>
                  </td>
                  <td>
                     <input type="password" placeholder="Confirmez votre mdp" id="mdp2" name="mdp2" />
                  </td>
               </tr>
               <tr>
                  <td></td>
                  <td align="center">
                     <br />
                  </td>
               </tr>
            </table>
            <div class = "message">
            <?php
         if(isset($erreur)) {
            echo '<font color="red">'.$erreur."</font>";
         }
         
         if(isset($success)) {
            echo '<font color="blue">'.$success."</font>";
         }

         ?>
         </div>
            <input type="submit" name="forminscription" value="Je m'inscris" />
         </form>
         <p>Vous avez déjà un compte ?<a href="connexion.php"> Connectez vous !</a></p>
      </div>
   </body>
</html>