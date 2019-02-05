<?php
include_once('../class/autoload.php');
$pdo = new mypdo();
$r = array();
$idVente = $_POST['idV'];
$lesDetails = $pdo->listeDetailsCommandeParIdVente($idVente);
$r['result'] = '';

switch($_POST['action'])
{
    case 'ajoutPrepa':
        while($l = $lesDetails->fetch(PDO::FETCH_OBJ))
        //Pour chaque ligne devis retournée selon idVente,
        //On fait une insertion dans commande.
        //On passe simplement les informations des lignes commandes dans préparation.
        {
            $r['result'] = $pdo->insertDetailPreparation(
                $idVente, $l->idArticle,
                "null", $l->qteDemandee,
                0, $l->txRemise,
                $l->CMUP, $l->marge,
                $l->tva, $l->observation
            );
        }        
        break;
}

die( json_encode($r) );

?>
