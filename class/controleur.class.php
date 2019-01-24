<?php
class controleur {

	private $vpdo;
	private $db;
	public function __construct() {
		$this->vpdo = new mypdo ();
		$this->db = $this->vpdo->connexion;
	}
	public function __get($propriete) {
		switch ($propriete) {
			case 'vpdo' :
				{
					return $this->vpdo;
					break;
				}
			case 'db' :
				{
					
					return $this->db;
					break;
				}
		}
    }
	
public function detailsDevis($idVente)
{
	    $v = $this->vpdo->venteParSonId($idVente);
	    $c = $this->vpdo->clientParSonId($v->idClient);
	    $s = $this->vpdo->societeParSonId($c->idSociete);
	    $lesDetails = $this->vpdo->listeDetailsDevisParIdVente($v->idVente);
	    $e = $this->vpdo->employeParSonId($lesDetails->fetch(PDO::FETCH_OBJ)->idEmploye);
	    $retour = '
            <div id="conteneur">
                <div id="details-vente">
                    <row>
                        <p>Responsable Devis : <input type="text" data-employe="'.$e->idEmploye.'" value="'.$e->idEmploye.' - '.$e->prenom.' '.$e->nom.'" readonly></p>
                    </row>                    
                    <row>
                        <p>N° Vente : <input type="text" value="'.$v->idVente.'" readonly></p>
                        <p>N° Client : <input type="text" value="'.$c->idClient.' - '.$c->prenom.' '.$c->nom.'" readonly required></p>
                        <p>Date : <input type="date" value="'.substr($v->dateDevis, 0,10).'" readonly required></p>
                    </row>
                    <row>
                        <p>Entreprise : <input type="text" value="'.$s->idSociete.' - '.$s->nom.'" readonly></p>
                        <p>Adresse : <input type="text" value="'.$s->adresse.'" readonly></p>
                        <p>Coordonnées : <input type="text" value="'.$s->telephone.'" readonly></p>
                    </row>
                </div>

                <div id="details-article">
                    <table>
                        <tr>    <th>Code article</th>   <th>Nom</th>   <th>Prix unitaire</th>   <th>Quantité</th>   <th>Remise %</th>   <th>Remise €</th>   <th>Total HT</th>   <th>TVA</th>   <th>Total TTC</th>   <th>Oservation</th>   </tr>';
	    
	    $lesDetails = $this->vpdo->listeDetailsDevisParIdVente($v->idVente);	    
	    while($d = $lesDetails->fetch(PDO::FETCH_OBJ))
	    {	       
	        $a = $this->vpdo->articleParSonId($d->idArticle);
	        $ht = ($d->prixVente*$d->qteDemandee*(1-$d->txRemise));
	        $retour = $retour.'
                        <tr>
                                <td>'.$d->idArticle.'</td>
                                <td>'.$a->libelle.'</td>
                                <td>'.$a->dernierCMUP.'</td>
                                <td>'.$d->qteDemandee.'</td>
                                <td>'.$d->txRemise.'</td>
                                <td>'.$d->remise.'</td>
                                <td>'.$ht.'</td>
                                <td>'.$a->txTVA.'</td>
                                <td>'.$ht*(1+$a->txTVA).'</td>
                                <td>'.$d->observation.'</td>
                        </tr>';	       
	    }    
	    
	  $retour = $retour.'
                    </table>
                </div>
            </div>
            <a id="btn-ajouterUnDevis" onclick=confirmerDevis()>Confirmer Devis</a>';
	    return $retour;
}	
		
public function formulaireLogin()
{
	    
	    return '    <form action="confirmation" method="post">
    Votre login : <input type="text" name="login">
    <br />
    Votre mot de passe : <input type="password" name="pwd"><br />
    <input type="submit" value="Connexion">
    </form>';
}	
	
public function confirmationLogin($login,$mdp)
{  // verifie si l'identifiant et le mots de passe est valide
	    $mdp=md5($mdp);
	    $result = $this->vpdo->listeComptes($login,$mdp)->fetch(PDO::FETCH_OBJ);
	    if($result != null)
	    {
	        echo 'connecte';
	        session_start ();
	        // on enregistre les parametres du visiteur comme variables de session
	        $_SESSION['id'] = $result->identifiant;// son identifiant
	        $_SESSION['idEmploye'] = $result->idEmploye; //Son id de base de données 
	        $_SESSION['type'] = $result->idType;   //le poste de la personne dans l'entreprise
	        // on redirige notre visiteur vers une page de notre section membre
	        
	        //Le visiteur pourra seulement rejoindre les pages ou son type (son rôle dans l'entreprise) le lui permet
	        // l'envoi sur la page accueil afin qu'il puisse se diriger vers la page qu'il souhaite
	    }
	    else {
	        // L'identifiant et/ou le mots de passe, est incorrect, on laisse un message au visiteur
	        echo '<body onLoad="alert(\'Identifiant ou mots de passe incorrect ! \')">';
	    }
	    // puis on le redirige vers la page d'accueil	    
	    header ('location: Accueil');	    
}
	
public function estConnecte()
{
        if(isset($_SESSION['id']) && isset($_SESSION['type'])) // on verfie que l'utilisateur a bien un id et un type
            return $_SESSION['type'];// Si oui on retourne le type qui lui servira à ce connecter aux pages dont il a accès,
            else return false;// Sinon retourne false et donc a seulement accès au page sans sécurité.
}
	
public function listeDevis()
{
	    
	$return='
            <div id="conteneur">
                <p style="margin-left: 1em">
                    Voici l\'outil de gestion des devis. Ci-dessous la liste des devis existants.<br>
                    Vous pouvez accéder au detail de chaque devis en cliquant sur "Voir Détail".<br>
                    Si vous souhaitez ajouter un devis, cliquez sur le bouton "Ajouter un devis" en bas de la page.
                </p>';//On affiche un message pour que l'utilisateur trouve plus facilement ses marques.
	
	$l = $this->vpdo->listeVentes();
	while($ligneIdVente = $l->fetch(PDO::FETCH_OBJ))//boucle tant que..des données sont présentes dans la requête liste. 
    {    
    $e=$this->vpdo->employeParIdVente($ligneIdVente->idVente)->fetch(PDO::FETCH_OBJ);
    $v=$this->vpdo->venteParSonId($ligneIdVente->idVente);
    $s=$this->vpdo->entrepriseParIdVente($ligneIdVente->idVente)->fetch(PDO::FETCH_OBJ);
    $c=$v->idClient;
    $p=$this->vpdo->prixTotalParIdVente($ligneIdVente->idVente)->fetch(PDO::FETCH_OBJ);
    
    //on prévoit des variables pour nos appels
    // on crée un bloque avec les informations qui seront multipliées pour chaque nouvelle ligne de la requête. 
    //On met aussi le bouton "Voir Detail", avec un lien dynamique pour envoyé l'utilisateur sur un lien différent en fonction du bouton sur lequel il clique
    $return = $return.'<div id="bloc-liste">
   
                <row>
                    <p>Code vente :<input type="text" readonly value='.$ligneIdVente->idVente.'></p>
                    <p>Responsbale devis :<input type="text" readonly value='.$e->idEmploye.' - '.$e->prenom.' '.$e->nom.'></p>
                    <p>Date devis :<input type="text" readonly value='.$v->dateDevis.'></p>
                </row>
                <row>
                    <p>Entreprise :<input type="text" readonly value='.$s->idSociete.' - '.$s->nom.'></p>
                    <p>Code client :<input type="text" readonly value='.$c.'></p>
                    <p>Prix Total :<input type="text" readonly value='.$p->prixTotal.'></p>
                </row>
                <row>
                   <a href="Devis/'.$ligneIdVente->idVente.'/" id="btn-voirDetail">Voir Details</a>
                </row>
    </div>';
	}
    // on rajoute le bouton pour ajouter un Devis
    $return = $return.'</div>
    <a href="Devis/Ajouter" id="btn-ajouter">Ajouter un Devis</a>';
    return $return;   // on retourne la totalité du texte
	}
	
	public function validationDevis()
	{
	    return "FAIRE insertDevis()";
	    /*$this->vpdo->insertDevis();*/    
	}
	
	public function afficheAjoutDevis()
	{
	    $idVente = $this->vpdo->idDerniereVente()->idVente+1;
	    $emp = $this->vpdo->employeParSonId($_SESSION['idEmploye']);	  
	    $lesClients = $this->vpdo->listeClients();
	    $lesArticles = $this->vpdo->listeArticles();
	    $return = '
            <div id="conteneur">
                <div id="details-vente">
                    <row>
                        <p>Responsable Devis : <input id="respDevis" type="text" value="'.$emp->idEmploye.' - '.$emp->prenom.' '.$emp->nom.'" readonly></input></p>
                    </row>
                    <row>
                        <p>N° Vente : <input id="idVente" type="text" value="'.$idVente.'" readonly></p>
                        <p>N° Client :<span class="tooltip" id="ttIdClient" title="Vous n\'avez pas choisi de client !"><select id="idClient">
                                        <option selected hidden disabled></option>';//Ajoute une option vide pour forcer l'utilisateur à choisir.
        
        while($e = $lesClients->fetch(PDO::FETCH_OBJ))
        {
            $return = $return.'<option value="'.$e->idClient.'">'.$e->idClient.' - '.$e->nom.' '.$e->prenom.'</option>';
        }
        $return = $return.'</select></span></p>
                        <p>Date : <input id="dateDevis" type="date" required></p>
                    </row>
                    <row>
                        <p>Société : <input id="idSociete" type="text" readonly></p>
                        <p>Adresse : <input id="adrSociete" type="text" readonly></p>
                        <p>Coordonnées : <input id="coordSociete" type="text" readonly></p>
                    </row>
                </div>
	        
                <div id="details-article">
                    <table id="table-articles">
                        <tr>    <th>Code article</th>   <th>Nom</th>   <th>Prix unitaire</th>   <th>Quantité</th>   <th>Remise % TTC</th>   <th>Remise € TTC</th>   <th>Total HT</th>   <th>TVA %</th>   <th>Total TTC</th>   <th>Oservation</th>   </tr>
                        <tr>
                            <td><span class="tooltip" id="ttIdArticle" title="Sélectionnez au moins un article !"><select id="idArticle1">
                                <option selected hidden></option>';
        while($e = $lesArticles->fetch(PDO::FETCH_OBJ))
        {
            $return = $return.'<option value="'.$e->idArticle.'">'.$e->idArticle.'</option>';
        }        
        $return = $return.'</select></span></td>
                            <td><input id="nomArticle1" type="text" readonly></td>
                            <td><input id="CMUPArticle1" type="number" readonly></td>
                            <td><input id="qteArticle1" type="number" min=1 value=1></td>
                            <td><input id="txArticle1" type="number" min=0 max=100 step=0.5 value=0></td>
                            <td><input id="remise1" type="number" value=0 readonly></td>
                            <td><input id="ht1" type="number" value=0 readonly></td>
                            <td><input id="tva1" type="number" value=0 readonly></td>
                            <td><input id="ttc1" type="number" value=0 readonly></td>
                            <td><input id="obsArticle1" type="text"></td>
                      </tr>
                    </table>
                    <a id="bou-plusLigne">+</a>
                </div>
            </div>
	    <a id="bou-confirmerDevis">Enregistrer le devis</a>';
	    return $return;
	}
	
	   
public function listeFournisseurs()
{
	    
	    $return='
            <div id="conteneur">
                <p style="margin-left: 1em">
                    Voici l\'outil de gestion des fournisseurs. Ci-dessous la liste des fournisseurs existants.<br>
                    Vous pouvez accéder au contact que vous possèdez pour chaque fournisseur en cliquant sur "Voir Contact".<br>
                    Si vous souhaitez ajouter un nouveau fournisseur, cliquez sur le bouton "Ajouter un fournisseur" en bas de la page.<br>
                    Si vous souhaitez ajouter un nouveau contact, cliquez sur le "Voir Contact"<br>
                    Si vous souhaitez modifier les informations d\'un fournisseur ou d\'un contact, cliquez sur "Voir Contact"

                </p>';
	    
	    $lsf = $this->vpdo->listeSocieteFournisseurs();
	    /*$lcf=$this->vpdo->listeContactFournisseurs();*/
	    while($ligneIdSociete = $lsf->fetch(PDO::FETCH_OBJ))
	    {
	        /*$ligneIdContact = $lcf->fetch(PDO::FETCH_OBJ);*/
	        $return = $return.'
    <div id="bloc-liste"> 
   	<row>    
        <p>Code de l\'entreprise : <input type="text" readonly value='.$ligneIdSociete->idSociete.'></p>
        <p>Nom de l\'entreprise :<input type="text" readonly value='.$ligneIdSociete->nom.'> </p>
        <p>Site web de l\'entreprise :<input type="text" readonly value='.$ligneIdSociete->siteWeb.'> </p>  
    <row>
    <row>
        <p>Téléphone de l\'entreprise :<input type="text" readonly value='.$ligneIdSociete->telephone.'></p>
        <p>Adresse de l\'entreprise :<input type="text" readonly value='.$ligneIdSociete->adresse.'> </p>
        <p>Raison sociale :<input type="text" readonly value='.$ligneIdSociete->raison.'></p>
    <row>
    <row>
        <p>Mail de l\'entreprise :<input type="text" readonly value='.$ligneIdSociete->mail.'></p>
    <a href="fournisseurs/'.$ligneIdSociete->idSociete.'" id="btn-voirDetail">Voir Contact</a>   
    <row> 
   </div>';
	}
	   $return = $return.'</div>
       <a href="Fournisseurs/ajouterContact" id="btn-ajouter">Ajouter un fournisseur </a>';
	   //renvoie vers la page "Ajouter un Fournisseur" 
	   return $return;
}


public function listeClients()
{
    
    $return='
            <div id="conteneur">
                <p style="margin-left: 1em">
                    Voici l\'outil de gestion des clients. Ci-dessous la liste des clients existants.<br>
                    Vous pouvez accéder au contact que vous possèdez pour chaque client en cliquant sur "Voir Contact".<br>
                    Si vous souhaitez ajouter un nouveau client, cliquez sur le bouton "Ajouter un client" en bas de la page.<br>
                    Si vous souhaitez ajouter un nouveau contact, cliquez sur le "Voir Contact"<br>
                    Si vous souhaitez modifier les informations d\'un client ou d\'un contact, cliquez sur "Voir Contact"
        
                </p>';
    
    $lsc = $this->vpdo->listeSocieteClients();
    while($ligneIdSociete = $lsc->fetch(PDO::FETCH_OBJ))
    {
    $return = $return.'<div id="bloc-liste">   
  <row> 
    <p>Code de l\'entreprise : <input type="text" readonly value='.$ligneIdSociete->idSociete.'></p>
    <p>Nom de l\'entreprise : <input type="text" readonly value='.$ligneIdSociete->nom.'></p>
    <p>Site web de l\'entreprise : <input type="text" readonly value='.$ligneIdSociete->siteWeb.'></p> 
  <row>
  <row>
    <p>Téléphone de l\'entreprise : <input type="text" readonly value='.$ligneIdSociete->telephone.'></p>
    <p>Adresse de l\'entreprise : <input type="text" readonly value='.$ligneIdSociete->adresse.'> </p>
    <p>Raison sociale : <input type="text" readonly value='.$ligneIdSociete->raison.'></p>
  <row>
  <row>
    <p>Mail de l\'entreprise : <input type="text" readonly value='.$ligneIdSociete->mail.'></p>
    <a href="clients/'.$ligneIdSociete->idSociete.'" id="btn-voirDetail">Voir Contact</a>   
  <row>
   </div>';
    }
    $return = $return.'<a href="Clients/ajouterclient" id="btn-confirmerModifEntreprise">Ajouter une societe cliente</a></div>';
    //renvoie vers la page "Ajouter un Contact"
    return $return;
}

public function listeContactClients($idSociete)
{
    $s = $this->vpdo->societeParSonId($idSociete);
    $return='   <div id="conteneur">
                    <p style="margin-left: 1em">
                        Voici l\'outil de gestion des contacts. Ci-dessous la liste des contacts existants pour le client numéro '.$idSociete.'.<br>
                        Si vous souhaitez modifier les informations d\'un client, cliquez sur "VALIDER LES MODIFICATIONS DE L\'ENTREPRISE CLIENTE"<br>                        
                        Si vous souhaitez modifier les informations d\'un contact, cliquez sur "VALIDER LES MODIFICATIONS CONTACT"<br>
                        Si vous souhaitez supprimer les informations d\'un contact, cliquez sur "SUPPRIMER CONTACT" <br>
                        Si vous souhaitez ajouter un nouveau contact, cliquez sur "Ajouter un contact"     
                    </p> ';
   $return=$return.' <p><b>INFORMATION SUR L\'ENTREPRISE</b></p> ';  
   $return = $return.'<div id="details-operation">
                        <row>    
                        <p> Code de l\'entreprise : <input type="text" id="idSociete" readonly value='.$s->idSociete.'></p>
                        <p>  Nom de l\'entreprise : <input type="text" id="nomSociete"value='.$s->nom.'> </p>
                        <p>  Raison sociale : <input type="text" id="raisonSociete" value='.$s->raison.'> </p>
                     </row>
                     <row>
                        <p>  Site web de l\'entreprise : <input type="text" id="siteWebSociete" value='.$s->siteWeb.'> </p>  
                        <p>  Téléphone de l\'entreprise : <input type="text"  id="telSociete" value='.$s->telephone.'> </p>
                        <p> Adresse de l\'entreprise : <input type="text" id="adresseSociete" value='.$s->adresse.'> </p>
                      </row>
                      <row>
                        <p>  faxe de l\'entreprise : <input type="text" id="faxSociete"value='.$s->fax.'> </p>          
                        <p>  Mail de l\'entreprise : <input type="text" id="mailSociete" value='.$s->mail.'></p>
                        <a onclick="modificationclient()" class="bou-classique">Modifier les informations</a>
                      </row>
                      <row>
                      </row>
                </div></div>';
   //<a href="ajouterModifEntreprise.php" target="_blank"> <input type="button" id = btn-ajouter value="VALIDER LES MODIFICATIONS DE L\'ENTREPRISE CLIENTE"> </a>
    $lcc = $this->vpdo->listeContactClientsParId($idSociete);
    if(isset($_POST['validerModifEntreprise']))
  {
      //$sql=$this->vpdo->deleteContactClient($idClient);
  }
    while($ligneIdContact = $lcc->fetch(PDO::FETCH_OBJ))
    { 
    $return = $return.'
<div id="bloc-liste">       
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

    <//row>
</div>';
    }
    $return = $return.'<a href="ajoutercontact" class="bou-classique">Ajouter un contact</a>';
    return $return;
}
public function ajouterContact()
{
    $vretour='test';
	
	return $vretour;
}
public function ajouterSocieteCliente()
{
    $vretour='<p>test</p>';
    
    return $vretour;
}
}
?>
