<?php
   session_start();
   $servername = "database";
   $username = "root";
   $password = "123";
   $dbname = "espace_membre";
   $port = "3306";

   $bdd = new PDO("mysql:host=$servername;port=$port;dbname=$dbname",$username,$password);
   $req = $pdo->prepare("select pdp from membres where id=? limit 1");
   $req->setFetchMode(PDO::FETCH_ASSOC);
   $req->execute(array($_SESSION["id"]));
   $tab =$req-> fetchAll();  
   echo $tab[0]["pdp"];
  ?>
