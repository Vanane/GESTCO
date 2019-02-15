<?php
include_once('../class/autoload.php');
$pdo = new mypdo();
$r = array();
$idVente = $_POST['idV'];
$laVente = $pdo->venteParSonId($idVente);
$lesDetails = $pdo->listeDetailsLivraisonParIdVente($idVente);


switch($_POST['action'])
{
    case 'ajoutLivraison':
        if($laVente->dateLivraison != null)
         break;
        else
        {
            $e=$_POST['idEmploye'];
            $pdo->updateTableUneCondition('Vente', "dateLivraison", $pdo->laDateAujourdhui(), 'idVente', $idVente);//On ajoute la date de transfert livraison.            
            while($l = $lesDetails->fetch(PDO::FETCH_OBJ))
            {
                $pdo->updateTableDeuxConditions('detail_livraison', 'idEmploye', $e,'idVente', $idVente,'idArticle',$l->idArticle);
                $pdo->updateTableDeuxConditions('detail_livraison', 'qteFournie',$l->qteDemandee,'idVente', $idVente,'idArticle',$l->idArticle);
                $pdo->insertDetailFacturation($l->idVente,$l->idArticle,'NULL',$l->qteDemandee,$l->qteDemandee, $l->txRemise,$l->CMUP,$l->marge,$l->tva,$l->observation);

            }
            
        }
    break;
    case 'ajoutReliquat':
        $idA = $_POST['idA'];
        $qte = $_POST['qte'];
        $pdo->insertDetailReliquat($idVente, $idA, 'NULL', "2", 'NULL', $qte, 'NULL', 'NULL');
        break;
}

die( json_encode($r) );

?>
