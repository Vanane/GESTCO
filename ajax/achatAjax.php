<?php
include_once('../class/autoload.php');
$pdo = new mypdo();
$r = array();//Array renvoyé à la fin
$action = $_POST['action'];//Récupération de la source de l'AJAX

switch ($action)
{ 
    case 'ajouterAchat':
    $idAc=$_POST['idAchat'];// je récupère les varaibles
    $idAr=$_POST['idArticle'];
    $idF=$_POST['idFour'];
    $p=$_POST['prix'];
    $q=$_POST['qte'];
    $c=$_POST['commentaire'];
    $d=$_POST['date'];
    $t=$_POST['type'];
    $pdo->insertMouvement($idAc,$t,$idF,$idAr,$d,$p,$q,$c);
    // j'insère un nouveau mouvement de type achat dans la base de donnée.
    break; 
}
die( json_encode($r) );
?>