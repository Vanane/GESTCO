<?php
include_once('../class/autoload.php');
$pdo = new mypdo();
$r = array();//Array renvoyé à la fin
$action = $_POST['action'];//Récupération de la source de l'AJAX

switch ($action)
{
    case 'infoEntreprise'://Sur changement du client
        $idSociete = $pdo->clientParSonId($_POST['idClient'])->idSociete;
        $q = $pdo->societeParSonId($idSociete);
        
        $r['idE'] = $q->idSociete;
        $r['adrE'] = $q->adresse;
        $r['nomE'] = $q->nom;
        $r['telE'] = $q->telephone;        
        break;
        
    case 'infoArticle'://Sur changement de l'article
        $idA = $_POST['idArticle'];
        $q= $pdo->articleParSonId($idA);        
        $r['libelle'] = $q->libelle;
        $r['cmup'] = $q->dernierCMUP;
        $r['tva'] = 100*($q->txTVA);
        break;
    case 'ajoutDevis'://Sur clic bouton Ajouter
        $laVente = $_POST['dVente'];//On récupère les informations de la vente
        $lesArticles =$_POST['dArticles'];//Et la liste des articles
        
        //On insère la vente dans la base pour les clés étrangères
        $r['resultVente'] = $pdo->insertVente($laVente['idVente'], $laVente['idClient'], $laVente['idEmploye'], $laVente['dateDevis']);
        
        //Pour chaque ligne allant de 1 à lesArticles.Count()
        for($i=1;$i<=$laVente['nbArticles'];$i++)
        {
            $devis = $pdo->devisParSonId($laVente['idVente'], $lesArticles['idArticle'.$i]);
            $r['test'.$i] = $devis;
            if($devis != null)
            //Si la ligne devis pour ce produit existe déjà.
            //Permet d'éviter les erreurs de doublons, et cumule alors les deux
            //Quantités des deux lignes en conflit
                $r['resultDevis'.$i] = $pdo->updateDetailDevis('qteDemandee', ($lesArticles['qteArticle'.$i]+$devis->qteDemandee));               
            else
            {
                //On récupère la TVA, calcule le TTC et insère.
                $TVA =$pdo->articleParSonId($lesArticles['idArticle'.$i])->txTVA;
                $TTC = $lesArticles['CMUPArticle'.$i]*$lesArticles['qteArticle'.$i]*$TVA;
                
                $r['resultDevis'.$i] = $pdo->insertDetailDevis(
                    $laVente['idVente'], $lesArticles['idArticle'.$i],
                    $laVente['idEmploye'], $lesArticles['qteArticle'.$i],
                    $lesArticles['txRemise'.$i], $TTC*$lesArticles['txRemise'.$i],
                    $lesArticles['CMUPArticle'.$i], $lesArticles['obsArticle'.$i]
                );
            }
        }
        break;
    case 'deleteClient':
        $idC = $_POST['idClient'];
        $q= $pdo->deleteContactClient($idC);
        break;
}
die( json_encode($r) );
?>
