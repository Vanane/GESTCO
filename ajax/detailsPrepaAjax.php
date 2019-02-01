<?php
include_once('../class/autoload.php');
$pdo = new mypdo();
$r = array();//Array renvoyé à la fin

if(isset($_POST['action']))
{
    $action = $_POST['action'];//Récupération de la source de l'AJAX
    switch ($action)
    {
        case 'validePrepa'://Sur changement du client
            $r = $_POST['datas'];
            $pdo->updateTableUneCondition('Vente', 'datePrepa', $pdo->laDateAujourdhui(), 'idVente', $r['idV']);
            for($i=1;$i<=$r['nbArticles'];$i++)//Pour i allant de 1 à nb d'articles
            {                
                //UPDATE detail_preparation SET qteFournie = "qte"+i WHERE idVente = x AND idArticle = "idA"+i
                $pdo->updateTableDeuxConditions('detail_preparation', 'qteFournie', $r['qteF'.$i], 'idVente', $r['idV'], 'idArticle', $r['idA'.$i]);                
                if($r['qteF'.$i] != $r['qteD'.$i])
                {
                    $r['reliquat'] = true;
                    $c = $pdo->commandeParSonId($r['idV'], $r['idA'.$i]);                   
                    $r['requete'] = $pdo->insertDetailReliquat(
                        $r['idV'], $r['idA'.$i],
                        $r['idE'], '2',
                        'null', ($r['qteD'.$i] - $r['qteF'.$i]),
                        $c->CMUP, 'null',
                        'Articles manquants à la préparation'
                    );
                }
                
                
            }
            
            break;
   }
}
    die( json_encode($r) );
    ?>
