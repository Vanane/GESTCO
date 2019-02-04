<?php
include_once('../class/autoload.php');
$pdo = new mypdo();
$r = array();//Array renvoyé à la fin

if(isset($_POST['action']))
{
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
            $r['tva'] = $q->txTVA;
            $r['marge'] = $q->txMarge;
            break;
            
        case 'ajoutDevis'://Sur clic bouton Ajouter
            $laVente = $_POST['dVente'];//On récupère les informations de la vente
            $lesArticles =$_POST['dArticles'];//Et la liste des articles
            
            //On insère la vente dans la base pour les clés étrangères
            $pdo->insertVente($laVente['idVente'], $laVente['idClient']);
            $pdo->updateTableUneCondition("vente", "dateDevis", $pdo->laDateAujourdhui(), "idVente", $laVente['idVente']);          
            
            //Pour chaque ligne allant de 1 à lesArticles.Count()
            for($i=1;$i<=$laVente['nbArticles'];$i++)
            {
                $devis = $pdo->devisParSonId($laVente['idVente'], $lesArticles['idArticle'.$i]);
                if($devis != null)
                    //Si la ligne devis pour ce produit existe déjà.
                    //Permet d'éviter les erreurs de doublons, et cumule alors les deux
                    //Quantités des deux lignes en conflit
                    $r['resultDevis'.$i] = $pdo->updateTableDeuxConditions('detail_devis', 'qteDemandee', ($lesArticles['qteArticle'.$i]+$devis->qteDemandee), "idVente", $laVente['idVente'], "idArticle", $lesArticles['idArticle'.$i]);
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
            
        case 'ajoutCommandes'://Sur clic bouton Ajouter
            $laVente = $_POST['dVente'];//On récupère les informations de la vente
            $lesArticles =$_POST['dArticles'];//Et la liste des articles
            
            //On insère la vente dans la base pour les clés étrangères
            //Puis on ajoute la date d'ajourdhui comme date de commande.
            $pdo->insertVente($laVente['idVente'], $laVente['idClient']);
            $pdo->updateTableUneCondition("vente", "dateCommande", $pdo->laDateAujourdhui(), "idVente", $laVente['idVente']);
            
            $r['result'] = '';
            //Pour chaque ligne allant de 1 à lesArticles.Count()
            for($i=1;$i<=$laVente['nbArticles'];$i++)
            {
                $devis = $pdo->commandeParSonId($laVente['idVente'], $lesArticles['idArticle'.$i]);
                if($devis != null)
                    $pdo->updateTableDeuxConditions('detail_commande', 'qteDemandee', ($lesArticles['qteArticle'.$i]+$devis->qteDemandee), "idVente", $laVente['idVente'], "idArticle", $lesArticles['idArticle'.$i]);
                    //Si la ligne devis pour ce produit existe déjà.
                    //Permet d'éviter les erreurs de doublons, et cumule alors les deux
                    //Quantités des deux lignes en conflit
                    else
                    {
                        //On récupère la TVA, calcule le TTC et insère.
                        $TVA =$pdo->articleParSonId($lesArticles['idArticle'.$i])->txTVA;
                        $TTC = $lesArticles['CMUPArticle'.$i]*$lesArticles['qteArticle'.$i]*$TVA;
                        
                        $pdo->insertDetailCommande(
                            $laVente['idVente'], $lesArticles['idArticle'.$i],
                            $laVente['idEmploye'], $lesArticles['qteArticle'.$i],
                            $lesArticles['txRemise'.$i], $TTC*$lesArticles['txRemise'.$i],
                            $lesArticles['CMUPArticle'.$i], $lesArticles['obsArticle'.$i]
                            );
                    }
            }
            break;
            
    }
}


die( json_encode($r) );
?>
