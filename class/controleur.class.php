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
                        <p>Responsable Devis : <input type="text" value="'.$e->idEmploye.' - '.$e->prenom.' '.$e->nom.'" readonly></p>
                    </row>                    
                    <row>
                        <p>N° Vente : <input type="text" value="'.$v->idVente.'" readonly></p>
                        <p>N° Client : <input type="text" value="'.$c->idClient.' - '.$c->prenom.' '.$c->nom.'" readonly></p>
                        <p>Date : <input type="date" value="'.substr($v->dateDevis, 0,10).'" readonly></p>
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
            </div>';
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
	        $_SESSION['id'] = $result->identifiant;    // son identifiant
	        $_SESSION['type'] = $result->idType;   //le poste de la personne dans l'entreprise
	        
	        //Le visiteur pourra seulement rejoindre les pages ou son type (son rôle dans l'entreprise) le lui permet
	        header ('location: Accueil');
	        // l'envoi sur la page accueil afin qu'il puisse se diriger vers la page qu'il souhaite
	    }
	    else {
	        // L'identifiant et/ou le mots de passe, est incorrect, on laisse un message au visiteur
	        echo '<body onLoad="alert(\'Identifiant ou mots de passe incorrect ! \')">';
	        // puis on le redirige vers la page d'accueil
	        echo '<meta http-equiv="refresh" content="0;URL=Accueil">';
	    }
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
	while($ligneIdVente = $l->fetch(PDO::FETCH_OBJ))//boulce tant que..des données sont présentes dans la requête liste. 
    {    
    $e=$this->vpdo->employeParIdVente($ligneIdVente->idVente)->fetch(PDO::FETCH_OBJ);
    $v=$this->vpdo->venteParSonId($ligneIdVente->idVente);
    $s=$this->vpdo->entrepriseParIdVente($ligneIdVente->idVente)->fetch(PDO::FETCH_OBJ);
    $c=$this->vpdo->entrepriseParIdVente($ligneIdVente->idVente)->fetch(PDO::FETCH_OBJ);
    $p=$this->vpdo->prixTotalParIdVente($ligneIdVente->idVente)->fetch(PDO::FETCH_OBJ);
    //on prévoit des varibles pour nos appels
    // on crée un bloque avec les informations qui seront multipliées pour chaque nouvelle ligne de la requête. 
    //On met aussi le bouton "Voir Detail", avec un lien dynamique pour envoyé l'utilisateur sur un lien différent en fonction du bouton sur lequel il clique
    $return = $return.'<div id="bloc-liste"><p>
   
    Code de la vente : &emsp;&emsp;<input type="text" readonly value='.$ligneIdVente->idVente.'> &emsp; 
    Responsbale devis :&emsp;<input type="text" readonly value='.$e->idEmploye.' - '.$e->prenom.' '.$e->nom.'> &emsp;
    Date devis :&emsp;<input type="text" readonly value='.$v->dateDevis.'> &emsp;

    <br /> <br /> 

    Nom de l\'Entreprise :&emsp;<input type="text" readonly value='.$s->nom.'>&emsp;
    Code du client :&emsp;&emsp;&emsp;<input type="text" readonly value='.$c->idClient.'>&emsp; 
    Prix Total :&emsp;<input type="text" readonly value='.$p->prixTotal.'>&emsp;

    <a href="devis/'.$ligneIdVente->idVente.'" id="btn-voirDetail">Voir Details</a>
    
   </p></div>';
	}
	
    // on rajoute le bouton pour ajouter un Devis
    $return = $return.'</div>
    <a href="Devis/Ajouter" id="btn-ajouter">Ajouter un Devis</a>';
    // on retourne la totalité du texte
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
	        $return = $return.'<br /><div id="bloc-liste"> <p>
    <br /> 	       
    &emsp;Code de l\'entreprise : &emsp;&emsp;&emsp;&nbsp;<input type="text" readonly value='.$ligneIdSociete->idSociete.'> &emsp;
    Nom de l\'entreprise :&emsp;&emsp;&nbsp;<input type="text" readonly value='.$ligneIdSociete->nom.'> &emsp;
    Site web de l\'entreprise :&nbsp;<input type="text" readonly value='.$ligneIdSociete->siteWeb.'> &emsp;  
    <br /> 
    <br /> 
    &emsp;Téléphone de l\'entreprise :&emsp;<input type="text" readonly value='.$ligneIdSociete->telephone.'> &emsp;
    Adresse de l\'entreprise :&emsp;<input type="text" readonly value='.$ligneIdSociete->adresse.'> &emsp;
    Raison sociale :&emsp;&emsp;&emsp;&emsp;&nbsp;<input type="text" readonly value='.$ligneIdSociete->raison.'> &emsp;
    <br /> 
    <br /> 
    &emsp;Mail de l\'entreprise :&emsp;&emsp;&emsp;&emsp;<input type="text" readonly value='.$ligneIdSociete->mail.'> &emsp;
    <a href="fournisseurs/'.$ligneIdSociete->idSociete.'" id="btn-voirDetail">Voir Contact</a>   
    <br />  
    <br />  
    </p></div>';
	}
	   $return = $return.'</div>
       <a href="Fournisseur/Ajouter" id="btn-ajouter">Ajouter un fournisseur </a>';
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
        
        $return = $return.'<br /><div id="bloc-liste"> <p>
     <br /> 	       
    &emsp;Code de l\'entreprise : &emsp;&emsp;&emsp;&nbsp;<input type="text" readonly value='.$ligneIdSociete->idSociete.'> &emsp;
    Nom de l\'entreprise :&emsp;&emsp;&nbsp;<input type="text" readonly value='.$ligneIdSociete->nom.'> &emsp;
    Site web de l\'entreprise :&nbsp;<input type="text" readonly value='.$ligneIdSociete->siteWeb.'> &emsp;  
    <br /> 
    <br /> 
    &emsp;Téléphone de l\'entreprise :&emsp;<input type="text" readonly value='.$ligneIdSociete->telephone.'> &emsp;
    Adresse de l\'entreprise :&emsp;<input type="text" readonly value='.$ligneIdSociete->adresse.'> &emsp;
    Raison sociale :&emsp;&emsp;&emsp;&emsp;&nbsp;<input type="text" readonly value='.$ligneIdSociete->raison.'> &emsp;
    <br /> 
    <br /> 
    &emsp;Mail de l\'entreprise :&emsp;&emsp;&emsp;&emsp;<input type="text" readonly value='.$ligneIdSociete->mail.'> &emsp;
    <a href="fournisseurs/'.$ligneIdSociete->idSociete.'" id="btn-voirDetail">Voir Contact</a>   
    <br />  
    <br />  
    </p></div>';
    }
    $return = $return.'</div>
       <a href="Contact/Ajouter" id="btn-ajouter">Ajouter une société </a>';
    //renvoie vers la page "Ajouter un Contact"
    return $return;
}
public function listeContactClients()
{//récupérer l'id de la page ($id)
    $s = $this->vpdo->societeParSonId($id);
    $return='
                <div id="conteneur">
                    <p style="margin-left: 1em">
                        Voici l\'outil de gestion des contacts. Ci-dessous la liste des contacts existants pour le client sélectionné.<br>
                        Si vous souhaitez ajouter un nouveau contact, cliquez sur le "Ajouter un Contact"<br>
                        Si vous souhaitez modifier les informations d\'un client, cliquez sur "Modifier Client"<br>
                        Si vous souhaitez modifier les informations d\'un contact, cliquez sur "Modifier Contact"
                    </p>
                <div id="bloc-Societe">
                    <p>
                    <br> 	       
                         &emsp;Code de l\'entreprise : &emsp;&emsp;&emsp;&nbsp;<input type="text" readonly value='.$s->idSociete.'> &emsp;
                         Nom de l\'entreprise :&emsp;&emsp;&nbsp;<input type="text" readonly value='.$s->nom.'> &emsp;
                         Site web de l\'entreprise :&nbsp;<input type="text" readonly value='.$s->siteWeb.'> &emsp;  
                    <br> 
                    <br> 
                         &emsp;Téléphone de l\'entreprise :&emsp;<input type="text" readonly value='.$s->telephone.'> &emsp;
                         Adresse de l\'entreprise :&emsp;<input type="text" readonly value='.$s->adresse.'> &emsp;
                         Raison sociale :&emsp;&emsp;&emsp;&emsp;&nbsp;<input type="text" readonly value='.$s->raison.'> &emsp;
                    <br> 
                    <br> 
                         &emsp;Mail de l\'entreprise :&emsp;&emsp;&emsp;&emsp;<input type="text" readonly value='.$s->mail.'> &emsp;
                        <type=input id="btn-voirDetail" value=MODIFIER DONNEE ENTREPRISE>   
                    <br>  

                    <br>  
                    </p>
                </div>';
    
    $lcc = $this->vpdo->listeContactClients();
    while($ligneIdContact = $lcc->fetch(PDO::FETCH_OBJ))
    {
    $return = $return.'<br /><div id="bloc-liste"> <p>
    <br />
    &emsp;Code de l\'entreprise : &emsp;&emsp;&emsp;&nbsp;<input type="text" readonly value='.$ligneIdContact->idClient.'> &emsp;
    Nom de l\'entreprise :&emsp;&emsp;&nbsp;<input type="text" readonly value='.$ligneIdContact->nom.'> &emsp;
    Site web de l\'entreprise :&nbsp;<input type="text" readonly value='.$ligneIdContact->prenom.'> &emsp;
    <br />
    <br />
    &emsp;Téléphone de l\'entreprise :&emsp;<input type="text" readonly value='.$ligneIdContact->telephone.'> &emsp;
    &emsp;Mail de l\'entreprise :&emsp;&emsp;&emsp;&emsp;<input type="text" readonly value='.$ligneIdContact->mail.'> &emsp;
    <br />
    <br />
    <a href="fournisseurs/'.$ligneIdContact->idSociete.'" id="btn-voirDetail">Voir Contact</a>
    <br />
    <br />
    </p></div>';
    }
    $return = $return.'</div>
       <a href="Contact/Ajouter" id="btn-ajouter">Ajouter une société </a>';
    //renvoie vers la page "Ajouter un Contact"
    return $return;
}

public function genererMDP ($longueur = 8){
		// initialiser la variable $mdp
		$mdp = "";
	
		// Définir tout les caractères possibles dans le mot de passe,
		// Il est possible de rajouter des voyelles ou bien des caractères spéciaux
		$possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ&#@$*!";
	
		// obtenir le nombre de caractères dans la chaîne précédente
		// cette valeur sera utilisé plus tard
		$longueurMax = strlen($possible);
	
		if ($longueur > $longueurMax) {
			$longueur = $longueurMax;
		}
	
		// initialiser le compteur
		$i = 0;
	
		// ajouter un caractère aléatoire à $mdp jusqu'à ce que $longueur soit atteint
		while ($i < $longueur) {
			// prendre un caractère aléatoire
			$caractere = substr($possible, mt_rand(0, $longueurMax-1), 1);
	
			// vérifier si le caractère est déjà utilisé dans $mdp
			if (!strstr($mdp, $caractere)) {
				// Si non, ajouter le caractère à $mdp et augmenter le compteur
				$mdp .= $caractere;
				$i++;
			}
		}
	
		// retourner le résultat final
		return $mdp;
}
	
	
}

?>
