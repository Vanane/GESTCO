<?php
include_once('../class/autoload.php');
$pdo = new mypdo();
$r = array();//Array renvoyé à la fin
$action = $_POST['action'];//Récupération de la source de l'AJAX
$idA = $_POST['idArticle'];
switch ($action)
//Selon l'action demandée dans le JS par l'AJAX, on fait une action spécifique.
{
    case 'calculCMUPQte'://Sur demande du nouveau CMUP, on calcule celui-ci avec la BDD et on renvoie le résultat
        $lesMouvements = $pdo->listeMouvementsParArticle($idA);   
        $lesPreparations = $pdo->listeDetailsPreparationParArticle($idA);
        $prixTotal = 0; //On définit les variables à 0.
        $qteReelle = 0; //.
        $qteVirtuelle = 0;
        //Le CMUP est le prix moyen en fonction du stock, du prix d'achat et du prix de vente.
        //Pour le calculer, il faut calculer le prix moyen*le stock de chaque mouvement, avec les entrées en stock positif
        //Et les sorties en stock négatif.
        
        while($m = $lesMouvements->fetch(PDO::FETCH_OBJ))//Pour chaque entrée ou dégradation de stock on calcule le prix moyen
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
        
        //On remplit $r avec le CMUP ainsi que les informations avec lesquelles on a calculé ce CMUP, pour debug potentiel.
        $r['nouveauCMUP'] = $CMUP;
        $r['qteReelle'] = $qteReelle;
        $r['qteVirtuelle'] = $qteVirtuelle;
        $r['prix'] = $prixTotal;
        
        
        
        break;
    case 'modifCMUP'://Sur demande de modification du CMUP, on fait un update sur la table article pour le CMUP envoyé par JS.
        $nouveauCMUP = $_POST['nouveauCMUP'];
        $pdo->updateTableUneCondition('Article', 'dernierCMUP', $nouveauCMUP, 'idArticle', $idA);
        break;
    case 'modifier'://Sur modification de l'article, on update la BDD pour entrer les infos changées.
        $nom = $_POST['nomArticle'];
        $barre = $_POST['codeBarre'];
        $fam = $_POST['famArticle'];
        $emp = $_POST['empArticle'];
        $pdo->updateTableUneCondition('Article', "libelle", $nom, 'idArticle', $idA);
        $pdo->updateTableUneCondition('Article', "codeBarre", $barre, 'idArticle', $idA);
        $pdo->updateTableUneCondition('Article', "idFam", $fam, 'idArticle', $idA);
        $pdo->updateTableUneCondition('Article', "idEmp", $emp, 'idArticle', $idA);
        break;
    case 'ajouter'://Sur ajout d'un mouvement, on insère un mouvement pour l'article correspondant.                  
        $pdo->insertMouvement(  $_POST['idMouv'], $_POST['typeMouv'],
                                $_POST['idFour'], $_POST['idArticle'],
                                $_POST['dateMouv'], $_POST['prixMouv'],
                                $_POST['qteMouv'], $_POST['commentaire']
        );        
        break;
}

//On envoie le tableau $r sous forme de JSON
die( json_encode($r) );
?>