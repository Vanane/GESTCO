<?php
include_once('../class/autoload.php');
$pdo = new mypdo();//Nouvelle connexion à la BDD
$r = array();//Array renvoyé à la fin

if(isset($_POST['action']))
{
    $action = $_POST['action'];//Récupération de la source de l'AJAX
    switch ($action)
    //Selon la valeur de POST 'action' envoyée par le JS dans la requête AJAX,
    //On fait une action différente, cela permet de ne pas dupliquer les fichiers PHP
    //Et d'avoir tout au même endroit, pour une page spécifique.
    //Etant donné que les pages Ajout Devis et Ajout Commande sont très similaires,
    //J'ai choisi d'utiliser le même switch pour les deux en fusionnant le code.
    {
        case 'infoEntreprise'://Sur changement du client, on demande les infos de l'entreprise reliée.
            $idSociete = $pdo->clientParSonId($_POST['idClient'])->idSociete;
            $q = $pdo->societeParSonId($idSociete);

            //On remplit le tableau $r des valeurs de la société du client.            
            $r['idE'] = $q->idSociete;
            $r['adrE'] = $q->adresse;
            $r['nomE'] = $q->nom;
            $r['telE'] = $q->telephone;
            break;
            
        case 'infoArticle'://Sur changement de l'article, on demande les infos de l'article
            $idA = $_POST['idArticle'];
            $q= $pdo->articleParSonId($idA);

            //On remplit le tableau $r des infos.
            $r['libelle'] = $q->libelle;
            $r['cmup'] = $q->dernierCMUP;
            $r['tva'] = $q->txTVA;
            $r['marge'] = $q->txMarge;
            break;
            
        case 'ajoutDevis'://Sur clic bouton Ajouter, on ajoute dans la BDD le devis.
            $laVente = $_POST['dVente'];//On récupère les informations de la vente
            $lesArticles =$_POST['dArticles'];//Et la liste des articles
            
            //On insère la vente dans la base pour les clés étrangères
            $pdo->insertVente($laVente['idVente'], $laVente['idClient']);
            //Et on change la date de devis à aujourd'hui.
            $pdo->updateTableUneCondition("vente", "dateDevis", $pdo->laDateAujourdhui(), "idVente", $laVente['idVente']);          
            
            //Pour chaque ligne allant de 1 à lesArticles.Count()
            for($i=1;$i<=$laVente['nbArticles'];$i++)
            {
                //On remplit le tableau $r avec les résultats des insert, qui sont en fait
                //La requête exécutée, puisqu'un insert ne retourne normalement rien.                
                $pdo->insertDetailDevis(
                    $laVente['idVente'], $lesArticles['idArticle'.$i],
                    $laVente['idEmploye'], $lesArticles['qteArticle'.$i],
                    $lesArticles['txRemise'.$i], $lesArticles['CMUPArticle'.$i],
                    $lesArticles['margeArticle'.$i], $lesArticles['tva'.$i],
                    $lesArticles['obsArticle'.$i]
                    );
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
                //On remplit le tableau $r avec les résultats des insert, qui sont en fait
                //La requête exécutée, puisqu'un insert ne retourne normalement rien.                
                $r['result'] = $pdo->insertDetailCommande(
                    $laVente['idVente'], $lesArticles['idArticle'.$i],
                    $laVente['idEmploye'], $lesArticles['qteArticle'.$i],
                    $lesArticles['txRemise'.$i], $lesArticles['CMUPArticle'.$i],
                    $lesArticles['margeArticle'.$i], $lesArticles['tva'.$i], 
                    $lesArticles['obsArticle'.$i]
                    );
            }            
            break;           
    }
}

//Enfin, on retourne le tableau $r, converti en objet JSON.

die( json_encode($r) );
?>
