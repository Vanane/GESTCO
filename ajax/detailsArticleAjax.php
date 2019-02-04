<?php
include_once('../class/autoload.php');
$pdo = new mypdo();
$r = array();//Array renvoyé à la fin
$action = $_POST['action'];//Récupération de la source de l'AJAX
$idA = $_POST['idArticle'];
switch ($action)
{
    case 'calculCMUPQte':
        $lesMouvements = $pdo->listeMouvementsParArticle($idA);   
        $lesPreparations = $pdo->listeDetailsPreparationParArticle($idA);
        $prixTotal = 0; //On définit les variables à 0.
        $qteReelle = 0; //.
        $qteVirtuelle = 0;
        while($m = $lesMouvements->fetch(PDO::FETCH_OBJ))//Pour chaque entrée ou dégradation de stockk on calcule le prix moyen
        {            
            $prixTotal = $prixTotal + $m->prix * $m->qte;
        }                       
        
        while($p = $lesPreparations->fetch(PDO::FETCH_OBJ))//Pour chaque vente, on calcule le prix moyen soustrait à l'autre moyenne
        {
            $prixTotal = $prixTotal - $p->CMUP * $p->qteFournie;
        }
        $qteReelle = $pdo->qteReelleArticleParSonId($idA); 
        $qteVirtuelle = $pdo->qteVirtuelleArticleParSonId($idA);
        $CMUP = $prixTotal/$qteReelle;    
        
        $r['nouveauCMUP'] = $CMUP;
        $r['qteReelle'] = $qteReelle;
        $r['qteVirtuelle'] = $qteVirtuelle;
        $r['prix'] = $prixTotal;
        
        
        
        break;
    case 'modifCMUP':
        $nouveauCMUP = $_POST['nouveauCMUP'];
        $pdo->updateTableUneCondition('Article', 'dernierCMUP', $nouveauCMUP, 'idArticle', $idA);
        break;
    case 'modifier':
        $nom = $_POST['nomArticle'];
        $barre = $_POST['codeBarre'];
        $fam = $_POST['famArticle'];
        $emp = $_POST['empArticle'];
        $pdo->updateTableUneCondition('Article', "libelle", $nom, 'idArticle', $idA);
        $pdo->updateTableUneCondition('Article', "codeBarre", $barre, 'idArticle', $idA);
        $pdo->updateTableUneCondition('Article', "idFam", $fam, 'idArticle', $idA);
        $pdo->updateTableUneCondition('Article', "idEmp", $emp, 'idArticle', $idA);
        break;
    case 'ajouter':                  
        $pdo->insertMouvement(  $_POST['idMouv'], $_POST['typeMouv'],
                                $_POST['idFour'], $_POST['idArticle'],
                                $_POST['dateMouv'], $_POST['prixMouv'],
                                $_POST['qteMouv'], $_POST['commentaire']
        );        
        break;
}

die( json_encode($r) );
?>