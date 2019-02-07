<?php
include_once('../class/autoload.php');
$pdo = new mypdo();
$r = array();//Array renvoyé à la fin
$action = $_POST['action'];//Récupération de la source de l'AJAX

switch ($action)
{ 
    case 'modifierEmploye':
        $idE=$_POST['idEmploye'];
        $a=$_POST['adresse'];
        $t=$_POST['telephone'];
        $m=$_POST['mail'];
        $mdp=$_POST['mdp'];
        $r['result']=$pdo->updateTableUneCondition('employe','telephone',$t,'employe',$idE);
        $r['result']=$pdo->updateTableUneCondition('employe','adresse',$a,'employe',$idE);
        $r['result']=$pdo->updateTableUneCondition('employe','mail',$m,'employe',$idE);
        $r['result']=$pdo->updateTableUneCondition('employe','mdp',$mdp,'employe',$idE);
        break;
        
    case 'ajouterEmploye':
        $idE=$_POST['idEmploye'];
        $idT=$_POST['idType'];
        $n=$_POST['nom'];
        $p=$_POST['prenom'];
        $a=$_POST['adresse'];
        $t=$_POST['telephone'];
        $m=$_POST['mail'];
        $mdp=$_POST['mdp'];
        $r['result']=$pdo->insertEmploye($idE,$idT,$n,$p,$a,$m,$t,$mdp);
        break;
}

die( json_encode($r) );
?>