<?php
session_start();
 
$bdd = new PDO('mysql:host=127.0.0.1;dbname=espace_membre', 'root', '');
 
if(isset($_SESSION['id'])) {
   $requser = $bdd->prepare("SELECT * FROM membres WHERE id = ?");
   $requser->execute(array($_SESSION['id']));
   $user = $requser->fetch();
   if(isset($_POST['newprenom']) AND !empty($_POST['newprenom']) AND $_POST['newprenom'] != $user['prenom']) {
      $newprenom = htmlspecialchars($_POST['newprenom']);
      $insertprenom = $bdd->prepare("UPDATE membres SET prenom = ? WHERE id = ?");
      $insertprenom->execute(array($newprenom, $_SESSION['id']));
      header('Location: profil.php?id='.$_SESSION['id']);
   }
   if(isset($_POST['newmail']) AND !empty($_POST['newmail']) AND $_POST['newmail'] != $user['mail']) {
      $newmail = htmlspecialchars($_POST['newmail']);
      $insertmail = $bdd->prepare("UPDATE membres SET mail = ? WHERE id = ?");
      $insertmail->execute(array($newmail, $_SESSION['id']));
      header('Location: profil.php?id='.$_SESSION['id']);
   }
   if(isset($_POST['newmdp1']) AND !empty($_POST['newmdp1']) AND isset($_POST['newmdp2']) AND !empty($_POST['newmdp2'])) {
      $mdp1 = sha1($_POST['newmdp1']);
      $mdp2 = sha1($_POST['newmdp2']);
      if($mdp1 == $mdp2) {
         $insertmdp = $bdd->prepare("UPDATE membres SET motdepasse = ? WHERE id = ?");
         $insertmdp->execute(array($mdp1, $_SESSION['id']));
         header('Location: profil.php?id='.$_SESSION['id']);
      } else {
         $msg = "Vos deux mdp ne correspondent pas !";
      }
   }
?>
<html>
   <head>
      <title>TUTO PHP</title>
      <link rel="stylesheet" href="style.css">
      <meta charset="utf-8">
   </head>
   <body>
   <div class="global">
      <div class="buttons">  
      <img src="images/home.png"><a href="page_principale.php">Home</a>
      </div>
      <div class="edition"> 
         <h2>Edition de mon profil</h2>
         <form method="POST" action="" enctype="multipart/form-data">
	       <label>prenom :</label>
	       <input type="text" name="newprenom" placeholder="prenom" value="<?php echo $user['prenom']; ?>" /><br /><br />
	       <label>Photo de profil :</label>
	       <input type ="file" name = "image"/>
          <br><br> 
               <label>Mail :</label>
               <input type="text" name="newmail" placeholder="Mail" value="<?php echo $user['mail']; ?>" /><br /><br />
               <label>Mot de passe :</label>
               <input type="password" name="newmdp1" placeholder="Mot de passe"/><br /><br />
               <label>Confirmation - mot de passe :</label>
               <input type="password" name="newmdp2" placeholder="Confirmation du mot de passe" /><br /><br />
               <input name="actualiser" type="submit" value="Mettre à jour mon profil !" />
            </form>
<?php
      if(isset($_POST["actualiser"])){
      $newimage = $_POST['image'];
      $insertimage = $bdd->prepare("UPDATE membres SET pdp = ? WHERE id = ?");
      $insertimage->execute(array(file_get_contents($_FILES["image"]["tmp_name"]), $_SESSION['id']));
      header('Location: profil.php?id='.$_SESSION['id']);
   }
?>  
   </div>
   </div>
   </body>
</html>
<?php   
}
else {
   header("Location: connexion.php");
}
?>
