<?php
include_once('../class/autoload.php');
$pdo = new mypdo();
$r = array();
$idVente = $_POST['idV'];
$dateLivraison = $_POST['dateLivraison'];
$laVente = $pdo->venteParSonId($idVente);
$lesDetails = $pdo->listeDetailsLivraisonParIdVente($idVente);

switch($_POST['action'])
{
    case 'ajoutLivraison'://Nicolas géré observation
        if($laVente->dateLivraison != null)
            break;
        else
        {
            $pdo->updateTableUneCondition('Vente', "dateLivraison", $dateLivraison, 'idVente', $idVente);//On ajoute la date de transfert livraison.            
            $r['resultLivraison'] = "";
            while($l = $lesDetails->fetch(PDO::FETCH_OBJ))
            //Pour chaque ligne livraison retournée selon idVente,
            //On fait une insertion dans Livraison. 
            {
                $pdo->updateTableDeuxConditions('detail_livraison', 'idEmploye', '$l->idEmploye','idVente', '$l->idVente','idArticle','$l->idArticle');
                $pdo->updateTableDeuxConditions('detail_livraison', 'idEmploye', '$l->idEmploye','idVente', '$l->idVente','idArticle','$l->idArticle');
                $pdo->updateTableDeuxConditions('detail_livraison', 'idEmploye', '$l->idEmploye','idVente', '$l->idVente','idArticle','$l->idArticle');
                $pdo->updateTableDeuxConditions('detail_livraison', 'idEmploye', '$l->idEmploye','idVente', '$l->idVente','idArticle','$l->idArticle');
            }
        }
        break;
}

die( json_encode($r) );

?>
