<?php
   session_start();
   $pdo =  new PDO('mysql:host=127.0.0.1;dbname=espace_membre', 'root', ''); 
   $req = $pdo->prepare("select pdp from membres where id=? limit 1");
   $req->setFetchMode(PDO::FETCH_ASSOC);
   $req->execute(array($_SESSION["id"]));
   $tab =$req-> fetchAll();  
   echo $tab[0]["pdp"];
  ?>
