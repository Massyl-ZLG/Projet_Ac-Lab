<!doctype html>
<html>
  <head>
  <meta charset="utf-8">
  <title>Page Principale</title>
  <link rel="stylesheet" href="css/css_home/style.css">
</head>
<body>
  <div class="fond">
    <img src="images/fond3.jpg">
  </div>
    <div class="action">
    <div class="profile" onclick="menuToggle();">
    <img src="export.php?">
    </div>
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
    if(isset($userinfo['Genre']) && $userinfo['Genre'] == 'M'){
      $genre_recherche = "F";
      }
      else $genre_recherche = "M";

    $req_recherche = $bdd->prepare('SELECT * FROM membres WHERE genre = ?');
    $req_recherche->execute([$genre_recherche]);
    //variable qui contient toutes les personnes du sexe opposé 
    $recherche_info = $req_recherche->fetch();
    //$variale qui contient le premier element du tableau
    $user_id_current = $recherche_info['id'];
    $user_prenom = $recherche_info['prenom'];
    $fin_tab = end($recherche_info);
    $current_tab = current($recherche_info);
    if(isset($_POST['jaime'])){

      $req_like = $bdd->prepare('INSERT INTO match_bd (id_1,likes,id_2) VALUES (?, ?, ?)');
      $req_like->execute(array($getid, 1, $recherche_info['id']));

      $req_check_match = $bdd->prepare('SELECT * FROM match_bd WHERE id_1 = ? AND id_2 = ?');
      $req_check_match->execute(array($recherche_info['id'], $getid));
      $nb_rows = $req_check_match->fetchColumn();
      $recherche_info = $req_recherche->fetch();
      }

    if(isset($_POST['passe'])){
     
      $recherche_info = $req_recherche->fetch();
      }

      $user_date_nais = $recherche_info['date_nais'];
    $date_actuelle = date("Y-m-d");
    $diff = abs(strtotime($date_actuelle) - strtotime($user_date_nais));
    $years = floor($diff / (365*60*60*24));
    
    ?>
    <div class="menu">
      <ul>
        <li><img src="images/coeur.png"><a href="likes.php">Mes likes</a></li>
        <li><img src="images/user.png"><a href="profil.php">My profil</a></li>
        <li><img src="images/editing.png"><a href="editionprofil.php">Modifier profil</a></li>
        <li><img src="images/messages.png"><a href="#">Messages</a></li>
        <li><img src="images/setting.png"><a href="#">Paramétres</a></li>
        <li><img src="images/support.png"><a href="contact.php">Contact</a></li>
        <li><img src="images/power-off.png"><a href="deconnexion.php">Se Deconnecter</a></li>
      </ul>
      </div> 
      <!-- Affichage les profils des personnes proposés -->
      <!-- Afficher toutes les personnes du sexe opposé, un profil au dessus d'un autre-->
      <!-- Recuperer les images des personnes du sexe opposé -->
      <!-- Afficher le prenom de la personne avec son age juste au dessus de la photo -->
      <!-- Boutton Like ou Skip en dessous de la photo -->
      <!--  select * from - where genre = =/= genre -->
      <!--  -->
      </div>
  <script>
    function menuToggle(){
      const toggleMenu = document.querySelector('.menu');
      toggleMenu.classList.toggle('active')
    }
  </script>
        <section>
        <div class="profiles">
        <div class="formP">
         <h4><?php echo $recherche_info['prenom']; ?></h4>
         <br><p>Age = <?php echo $years; ?></p> 
         <br>
          <div class="imageP">
          <?php echo '<img src="data:image/jpeg;base64,'.base64_encode($recherche_info['pdp']).'"/>'; ?>
          </div>
         <form action='' method='POST'>
         <div class="inputP">
         <input type='submit' name='jaime' value ="J'aime" />
         <div class="inputP">
         <input type='submit' name='passe' value = "Passer"/>
         </div>
        </div>
         </form>
         </div>
         </div>
         </section>
</body>
</html>
<?php
}
?>