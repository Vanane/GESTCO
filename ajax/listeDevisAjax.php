<?php
include_once('../class/autoload.php');
$pdo = new mypdo();
$r = '';

switch($_POST['action'])
{
    case 'triId':
        $l = $pdo->listeVentesAvecDevis();
        while($ligneIdVente = $l->fetch(PDO::FETCH_OBJ))//boucle tant que..des données sont présentes dans la requête liste.
        {
            $e=$pdo->employeParIdVente($ligneIdVente->idVente)->fetch(PDO::FETCH_OBJ);
            $v=$pdo->venteParSonId($ligneIdVente->idVente);
            $s=$pdo->entrepriseParIdVente($ligneIdVente->idVente)->fetch(PDO::FETCH_OBJ);
            $c=$v->idClient;
            $p=$pdo->prixTotalParIdVente($ligneIdVente->idVente)->fetch(PDO::FETCH_OBJ);
            $r = $r.'
                <bloc>
                    <row>
                        <p>Code vente :<input type="text" readonly value='.$ligneIdVente->idVente.'></p>
                        <p>Code Employé :<input type="text" readonly value='.$e->idEmploye.' - '.$e->prenom.' '.$e->nom.'></p>
                        <p>Date devis :<input type="text" readonly value="'.$v->dateDevis.'"></p>
                    </row>
                    <row>
                        <p>Entreprise :<input type="text" readonly value='.$s->idSociete.' - '.$s->nom.'></p>
                        <p>Code client :<input type="text" readonly value='.$c.'></p>
                        <p>Prix Total :<input type="text" readonly value='.$p->prixTotal.'></p>
                    </row>
                    <row>
                       <a href="'.$ligneIdVente->idVente.'" id="btn-voirDetail" class="btn-classique">Voir Details</a>
                    </row>
                </bloc>
    ';
        }
        
        
        break;
}

die( json_encode($r) );

?>
