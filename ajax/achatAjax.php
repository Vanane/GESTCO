<?php
include_once('../class/autoload.php');
$pdo = new mypdo();
$r = array();//Array renvoyé à la fin
$action = $_POST['action'];//Récupération de la source de l'AJAX

switch ($action)
{ 
    case 'ajouterAchat':
    $idAc=$_POST['idAchat'];
    $idAr=$_POST['idArticle'];
    $idF=$_POST['idFour'];
    $p=$_POST['prix'];
    $q=$_POST['qte'];
    $c=$_POST['commentaire'];
    $d=$_POST['date'];
    $pdo->insertMouvement($idAc,'1',$idF,$idAr,$d,$p,$q,$c);
    break; 
}
die( json_encode($r) );
?>