<?php
include_once('../class/autoload.php');
$pdo = new mypdo();
$r = array();//Array renvoyé à la fin
$action = $_POST['action'];//Récupération de la source de l'AJAX
$idA = $_POST['idArticle'];


switch ($action)
{
    case 'calculCMUP':
        $lesMouvements = $pdo->listeMouvementsParArticle($idA);
        $prixTotal = 0; //On définit les variables à 0.
        $qteTotale = 0; //.
        while($m = $lesMouvements->fetch(PDO::FETCH_OBJ))
        {
            $prixTotal = $prixTotal + $m->prix * $m->qte;
            $qteTotale = $qteTotale + $m->qte;
        }
        $CMUP = $prixTotal/$qteTotale;
        $r['nouveauCMUP'] = $CMUP;
        $r['qte'] = $qteTotale;
        $r['prix'] = $prixTotal;
        break;
    case 'modifCMUP':
        $nouveauCMUP = $_POST['nouveauCMUP'];
        $pdo->updateArticle('dernierCMUP', $nouveauCMUP);
        break;
    
    
    
}

die( json_encode($r) );
?>