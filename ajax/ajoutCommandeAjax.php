<?php
include_once('../class/autoload.php');
$pdo = new mypdo();
$idVente = $_POST['idVente'];

$laVente = $pdo->venteParSonId($idVente);
$lesDetails = $pdo->listeDetailsDevisParIdVente($idVente);
?>
