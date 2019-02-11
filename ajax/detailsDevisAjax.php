<?php
include_once('../class/autoload.php');
$pdo = new mypdo();
$r = array();
$idVente = $_POST['idV'];
$laVente = $pdo->venteParSonId($idVente);
$lesDetails = $pdo->listeDetailsDevisParIdVente($idVente);

switch($_POST['action'])
{
    case 'ajoutCommande':
        //Si la commande a déjà une date de commande, qui est ajoutée lorsqu'on la confirme
        //Alors on s'arrête là, puisqu'elle est déjà confirmée.
        if($laVente->dateCommande != null)
            break;
        else
        //Sinon on update pour mettre la date d'aujourd'hui et on l'insère.
        {
            $r['resultCommande'] = $pdo->updateTableUneCondition('Vente', "dateCommande", $pdo->laDateAujourdhui(), 'idVente', $idVente);//On ajoute la date de transfert commande.            
            while($l = $lesDetails->fetch(PDO::FETCH_OBJ))
            //Pour chaque ligne devis retournée selon idVente,
            //On fait une insertion dans commande.
            {
                $r['resultCommande'] = $r['resultCommande'].$pdo->insertDetailCommande(
                    $l->idVente, $l->idArticle,
                    $l->idEmploye, $l->qteDemandee,
                    $l->txRemise, $l->CMUP,
                    $l->marge, $l->tva,
                    $l->observation
                );
            }
        }
        break;
}

die( json_encode($r) );

?>
