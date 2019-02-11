<?php
include_once('../class/autoload.php');
$pdo = new mypdo();
$r = array();//Array renvoyé à la fin
$action = $_POST['action'];//Récupération de la source de l'AJAX
$idV = $_POST['idV'];
switch ($action)
{
    case 'modifReliquats':
        $data = $_POST['dReliquats'];
        $rowCount = $data['rowCount'];
        for($i=0;$i<$rowCount;$i++)
        {          
            $r['updateAct'.$i] = $pdo->updateTableDeuxConditions("detail_reliquat", "typeAction", $data['action'.$i], "idVente", $idV, "idArticle", $data['article'.$i]);
            $r['updateMont'.$i] = $pdo->updateTableDeuxConditions("detail_reliquat", "compensation", $data['montant'.$i], "idVente", $idV, "idArticle", $data['article'.$i]);
            $r['updateObs'.$i] = $pdo->updateTableDeuxConditions("detail_reliquat", "observation", $data['observation'.$i], "idVente", $idV, "idArticle", $data['article'.$i]);
        }
        break;
}


die(json_encode($r));
?>