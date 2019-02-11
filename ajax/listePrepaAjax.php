<?php
include_once('../class/autoload.php');
$pdo = new mypdo();
$r = array();//Array renvoyé à la fin
$r['idV'] = $_POST['idV'];

if(isset($_POST['action']))
{
    $action = $_POST['action'];//Récupération de la source de l'AJAX
    switch($action)
    {
        case 'reservePrepa'://Sur clic bouton détail d'une preparation, on affecte l'user connecté à cette prépa
        //Pour ne plus l'afficher aux autres préparateurs.
            $pdo->updateTableUneCondition('detail_preparation', 'idEmploye', $_POST['idE'], 'idVente', $r['idV']);
            break;
    }
}
die(json_encode($r));
?>