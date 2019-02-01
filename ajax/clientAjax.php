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
   
    case 'modifierSociete':
        $i=$_POST['id'];
        $n=$_POST['nom'];
        $sw=$_POST['siteWeb'];
        $t=$_POST['tel'];
        $f=$_POST['fax'];
        $a=$_POST['adresse'];
        $ra=$_POST['raison'];
        $m=$_POST['mail'];
        $r['result']=$pdo->updateSociete('nom',$n,'idSociete',$i);
        $r['result']=$pdo->updateSociete('siteWeb',$sw,'idSociete',$i);
        $r['result']=$pdo->updateSociete('telephone',$t,'idSociete',$i);
        $r['result']=$pdo->updateSociete('fax',$f,'idSociete',$i);
        $r['result']=$pdo->updateSociete('adresse',$a,'idSociete',$i);
        $r['result']=$pdo->updateSociete('raison',$ra,'idSociete',$i);
        $r['result']=$pdo->updateSociete('mail',$m,'idSociete',$i);

        break;
   
    case 'ajouterSociete':
        $i=$_POST['id'];
        $n=$_POST['nom'];
        $a=$_POST['adresse'];
        $t=$_POST['telephone'];
        $f=$_POST['fax'];
        $s=$_POST['siteWeb'];
        $r=$_POST['raison'];
        $m=$_POST['mail'];
        $pdo->insertSociete($i,$n,$a,$t,$f,$s,$r,$m);
        break; 
        
    case 'modifierContactClient':
        $i=$_POST['id'];
        $n=$_POST['nom'];
        $p=$_POST['prenom'];
        $t=$_POST['telephone'];
        $m=$_POST['mail'];
        $s=$_POST['societe'];
        $r['result']=$pdo->updateContactClient('nom',$n,'idClient',$i);
        $r['result']=$pdo->updateContactClient('prenom',$p,'idClient',$i);
        $r['result']=$pdo->updateContactClient('telephone',$t,'idClient',$i);
        $r['result']=$pdo->updateContactClient('mail',$m,'idClient',$i);
        $r['result']=$pdo->updateContactClient('idSociete',$s,'idClient',$i);
        break;
    
    case 'modifierContactFournisseur':
        $i=$_POST['id'];
        $n=$_POST['nom'];
        $p=$_POST['prenom'];
        $t=$_POST['telephone'];
        $m=$_POST['mail'];
        $s=$_POST['societe'];
        $r['result']=$pdo->updateContactFournisseur('nom',$n,'idFour',$i);
        $r['result']=$pdo->updateContactFournisseur('prenom',$p,'idFour',$i);
        $r['result']=$pdo->updateContactFournisseur('telephone',$t,'idFour',$i);
        $r['result']=$pdo->updateContactFournisseur('mail',$m,'idFour',$i);
        $r['result']=$pdo->updateContactFournisseur('idSociete',$s,'idFour',$i);
        break;
        
    case 'ajouterContactClient':
        $i=$_POST['id'];
        $s=$_POST['societe'];
        $n=$_POST['nom'];
        $p=$_POST['prenom'];
        $t=$_POST['telephone'];
        $m=$_POST['mail'];
        $r['result']=$pdo->insertContactClient($i,$s,$n,$p,$m,$t);
        break;
        
    case 'ajouterContactFournisseur':
        $i=$_POST['id'];
        $s=$_POST['societe'];
        $n=$_POST['nom'];
        $p=$_POST['prenom'];
        $t=$_POST['telephone'];
        $m=$_POST['mail'];
        $r['result']=$pdo->insertContactFournisseur($i,$s,$n,$p,$m,$t);
        break;
        
    case 'infoEntreprise':
        $idSociete = $pdo->societeParSonId($_POST['idSociete'])->idSociete;
        $q = $pdo->societeParSonId($idSociete);
        $r['idS'] = $q->idSociete;
        $r['nomS'] = $q->nom;
        break;
}

die( json_encode($r) );

/*$lcc = $pdo->listeContactClientsParId($s);
 while($ligneIdContact = $lcc->fetch(PDO::FETCH_OBJ))
 {
 $r['result']="";
 $r['result'] = $r['result'].'
 <div class="bloc-liste">
 <row>
 <p>Code du contact : <input type="text" id="idClient" readonly value='.$ligneIdContact->idClient.'></p>
 <p>Nom du contact : <input type="text" id="nomClient" value='.$ligneIdContact->nom.'></p>
 <p>Prenom du contact : <input type="text" id="prenomClient"  value='.$ligneIdContact->prenom.'></p>
 </row>
 <row>
 <p>Téléphone du contact : <input type="text" id="telClient"  value='.$ligneIdContact->telephone.'></p>
 <p>Mail du contact : <input type="text" id="mailClient"  value='.$ligneIdContact->mail.'></p>
 <p>Id société du contact : <input type="text" id="societeClient"  value='.$ligneIdContact->idSociete.'></p>
 </row>
 <row>
 <a onclick="modificationcontactclient()" class="bou-classique">Modifier le contact</a>
 
 </row>
 </div>'; }*/
?>
    

      
            
       
      
