<?php
include_once('../class/autoload.php');
$pdo = new mypdo();
$r = array();//Array renvoyé à la fin
$action = $_POST['action'];//Récupération de la source de l'AJAX

switch ($action)
{ 
   /* case 'deleteClient':
        $idC = $_POST['idClient'];
        $r['idClient'] = $idC; 
        $r['result'] = $pdo->deleteContactClient($idC);
        break;*///annuler car il faut aussi supprimer les ventes
   
    case 'modifierClient':
        $i=$_POST['idSociete'];
        $n=$_POST['nomSociete'];
        $sw=$_POST['siteWebSociete'];
        $t=$_POST['telSociete'];
        $f=$_POST['faxSociete'];
        $a=$_POST['adresseSociete'];
        $ra=$_POST['raisonSociete'];
        $m=$_POST['mailSociete'];
        $r['result']=$pdo->TupdateClient('nomSociete',$n,'idSociete',$i);
        $r['result']=$pdo->TupdateClient('siteWeb',$sw,'idSociete',$i);
        $r['result']=$pdo->TupdateClient('telephone',$t,'idSociete',$i);
        $r['result']=$pdo->TupdateClient('fax',$f,'idSociete',$i);
        $r['result']=$pdo->TupdateClient('adresse',$a,'idSociete',$i);
        $r['result']=$pdo->TupdateClient('raison',$ra,'idSociete',$i);
        $r['result']=$pdo->TupdateClient('mail',$m,'idSociete',$i);
       // $r['result'] = $pdo->updateClient($n,$a,$t,$f,$sw,$ra,$m,$i);//méthode différente.
        break;
        
        
    case 'modifierContactClient':
        $i=$_POST['idClient'];
        $n=$_POST['nom'];
        $p=$_POST['prenom'];
        $t=$_POST['telephone'];
        $m=$_POST['mail'];
        $s=$_POST['idSociete'];
        $r['result']=$pdo->TupdateClient('nom',$n,'idClient',$i);
        $r['result']=$pdo->TupdateClient('prenom',$p,'idClient',$i);
        $r['result']=$pdo->TupdateClient('telephone',$t,'idClient',$i);
        $r['result']=$pdo->TupdateClient('mail',$m,'idClient',$i);
        $r['result']=$pdo->TupdateClient('idSociete',$s,'idClient',$i);
        break;
}
die( json_encode($r) );
?>
    
}