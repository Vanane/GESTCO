<?php
include_once('../class/autoload.php');
$pdo = new mypdo();
$r = array();//Array renvoyé à la fin
$action = $_POST['action'];//Récupération de la source de l'AJAX

switch ($action)
{ 
    case 'modifierEmploye':
        $idE=$_POST['idEmploye'];// je récupère les variables
        $a=$_POST['adresse'];
        $t=$_POST['telephone'];
        $m=$_POST['mail'];
        $r['result']=$pdo->updateTableUneCondition('employe','telephone',$t,'idEmploye',$idE);
        $r['result']=$r['result'].$pdo->updateTableUneCondition('employe','adresse',$a,'idEmploye',$idE);
        $r['result']=$r['result'].$pdo->updateTableUneCondition('employe','mail',$m,'idEmploye',$idE);
        // je modifie dans la base de donnée les info pour l'idEmploye donné
        break;
        
    case 'ajouterEmploye':
        $idE=$_POST['idEmploye'];// je récupère les variables
        $idT=$_POST['idType'];
        $n=$_POST['nom'];
        $p=$_POST['prenom'];
        $a=$_POST['adresse'];
        $t=$_POST['telephone'];
        $m=$_POST['mail'];
        $mdp=$_POST['mdp'];
        $r['result']=$pdo->insertEmploye($idE,$idT,$n,$p,$a,$t,$m,$mdp);
        // j'insère un nouvelle employé avec ses informations.
        break;
}
die( json_encode($r) );
?>