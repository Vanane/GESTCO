<?php
include_once('../class/autoload.php');
$pdo = new mypdo();
$r = array();//Array renvoyé à la fin
$action = $_POST['action'];//Récupération de la source de l'AJAX

switch ($action)
{
    case 'infoEntreprise'://Sur changement du client
        $idSociete = $pdo->clientParSonId($_POST['idClient'])->idSociete;
        $q = $pdo->societeParSonId($idSociete);
        
        $r['idE'] = $q->idSociete;
        $r['adrE'] = $q->adresse;
        $r['nomE'] = $q->nom;
        $r['telE'] = $q->telephone;        
        break;
        
    case 'infoArticle'://Sur changement de l'article
        $idA = $_POST['idArticle'];
        $q= $pdo->articleParSonId($idA);        
        $r['libelle'] = $q->libelle;
        $r['cmup'] = $q->dernierCMUP;
        break;
    case 'deleteClient':
        $idC = $_POST['idClient'];
        $q= $pdo->deleteContactClient($idC);
        break;
}
die( json_encode($r) );
?>