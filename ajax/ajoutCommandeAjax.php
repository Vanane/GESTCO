<?php
include_once('../class/autoload.php');
$pdo = new mypdo();
$r = array();
$idVente = $_POST['idV'];
$dateCommande = $_POST['dateCommande'];
$laVente = $pdo->venteParSonId($idVente);
$lesDetails = $pdo->listeDetailsDevisParIdVente($idVente);

switch($_POST['action'])
{
    case 'ajoutCommande':
        if($laVente->dateCommande != null)
            break;
        else
        {
            $pdo->updateTableUneCondition('Vente', "dateCommande", $dateCommande, 'idVente', $idVente);//On ajoute la date de transfert commande.            
            $r['resultCommande'] = "";
            while($l = $lesDetails->fetch(PDO::FETCH_OBJ))
            //Pour chaque ligne devis retournée selon idVente,
            //On fait une insertion dans commande.
            {
                $pdo->insertDetailCommande(
                    $l->idVente, $l->idArticle,
                    $l->idEmploye, $l->qteDemandee,
                    $l->txRemise, $l->remise,
                    $l->CMUP, $l->observation
                );
            }
        }
        break;
}

die( json_encode($r) );

?>
