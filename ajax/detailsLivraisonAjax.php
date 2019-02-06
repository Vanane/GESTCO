<?php
include_once('../class/autoload.php');
$pdo = new mypdo();
$r = array();
$idVente = $_POST['idV'];
$laVente = $pdo->venteParSonId($idVente);
$lesDetails = $pdo->listeDetailsLivraisonParIdVente($idVente);

switch($_POST['action'])
{
    case 'ajoutLivraison'://Nicolas
        if($laVente->dateLivraison != null)
            break;
        else
        {
            $e=$_POST['idEmploye'];
            $pdo->updateTableUneCondition('Vente', "dateLivraison", $pdo->laDateAujourdhui(), 'idVente', $idVente);//On ajoute la date de transfert livraison.            
            while($l = $lesDetails->fetch(PDO::FETCH_OBJ))
            //Pour chaque ligne livraison retournée selon idVente,
            //On fait un update dans Livraison. 
            {
                $pdo->updateTableDeuxConditions('detail_livraison', 'idEmploye', $e,'idVente', $idVente,'idArticle',$l->idArticle);
                $pdo->updateTableDeuxConditions('detail_livraison', 'qteFournie',$l->qteDemandee,'idVente', $idVente,'idArticle',$l->idArticle);
                $pdo->insertDetailFacturation($idVente,$l->idArticle,'null',$l->qteDemandee,$l->qteDemandee, $l->txRemise,$l->cmup,$l->marge,$l->tva,$l->observation);
            }
        }
        break;
}

die( json_encode($r) );

?>
