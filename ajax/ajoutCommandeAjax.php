<?php
include_once('../class/autoload.php');
$pdo = new mypdo();
$r = array();
$idVente = $_POST['idVente'];
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
            $pdo->updateVente("dateCommande", $dateCommande);//On ajoute la date de transfert commande.
            
            $r['resultCommande'] = "";
            while($l = $lesDetails->fetch(PDO::FETCH_OBJ))
            //Pour chaque ligne devis retournée selon idVente,
            //On fait une insertion dans commande.
            {
                $pdo->insertDetailCommande(
                    $l->idVente, $l->idArticle,
                    $l->idEmploye, $l->qteDemandee,
                    $l->txRemise, $l->remise,
                    $l->prixVente, $l->observation
                );
            }
        }
        break;
}

die( json_encode($r) );

?>
