<?php
class controleur {
    private $pdf;
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
	

    
public function formulaireLogin()
{
	    
	    return '
        <div class="conteneur">
            <form id="form-connexion" action="confirmation" method="post">
                <span>Identifiant :</span><input type="text" name="login"><br>
                <span>Mot de passe :</span><input type="password" name="pwd"><br/>
                <a class="btn-classique" onclick="$(\'#form-connexion\').submit()"><input type="submit" hidden>Connexion</a>
            </form>';
}	
	

public function confirmationLogin($login,$mdp)
{  // verifie si l'identifiant et le mots de passe est valide
	    $mdp=md5($mdp);
	    $result = $this->vpdo->compteParSesIds($login,$mdp);
	    $retour = '';
	    if($result != null)
	    {
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
	        $retour =  '<script>alert(\'Identifiant ou mots de passe incorrect ! \')</script>';
	    }
	    // puis on le redirige vers la page d'accueil	
	    $retour = $retour."<script>location = 'Accueil'</script>";
	    return $retour;
}
	

public function estConnecte()
{
        if(isset($_SESSION['id']) && isset($_SESSION['type'])) // on verfie que l'utilisateur a bien un id et un type
            return $_SESSION['type'];// Si oui on retourne le type qui lui servira à ce connecter aux pages dont il a accès,
            else return false;// Sinon retourne false et donc a seulement accès au page sans sécurité.
}

public function afficheNonAcces()
{
    $retour = '
    <div class="conteneur">
        <p>Vous n\'avez pas accès à cette page ! </p>
    </div>';
    return $retour;   
}


public function afficheListeDevis()
{
	   
    $retour='
            <div class="conteneur border div-liste div-liste-devis">
                <p style="margin-left: 1em">
                    Voici l\'outil de gestion des devis. Ci-dessous la liste des devis existants.<br>
                    Vous pouvez accéder au detail de chaque devis en cliquant sur "Voir Détail".<br>
                    Si vous souhaitez ajouter un devis, cliquez sur le bouton "Ajouter un devis".
                </p>
                <p style="margin-left: 1em">
                    Vous pouvez choisir quels devis afficher ou non, en changeant la sélection dans "Filtrer".<br>
                    Les devis confirmés en commandes sont affichés lorsque "Validés" est coché.<br>
                </p>'
    ;
    //Div pour les filtres et les tris    
    $retour = $retour.'
            <div class="div-filtres">
                <div id="filtre">
                    <h4>Filtrer :</h4>
                    <p>Validés<input name="radio-filtre" type="radio" value="filtreV" checked></p>
                    <p>A valider<input name="radio-filtre" type="radio" value="filtreNonV"></p>
                </div>
            </div>
    ';
    
    $retour = $retour.'
    <a href="Ajouter" id="btn-ajouter" class="btn-classique">Ajouter un Devis</a>';
    
	$l = $this->vpdo->listeVentesAvecDevis();
	while($v = $l->fetch(PDO::FETCH_OBJ))//boucle tant que..des données sont présentes dans la requête liste. 
    {    
        $details=$this->vpdo->listeDetailsDevisParIdVente($v->idVente)->fetch(PDO::FETCH_OBJ);
        //On récupère la première ligne des détails devis de la vente pour
        //Ensuite récupérer l'employé qui s'est chargé de ce devis.
        $e=$this->vpdo->employeParSonId($details->idEmploye);        
        $s=$this->vpdo->entrepriseParIdVente($v->idVente);
        $c=$v->idClient;
        $p=$this->vpdo->totalDevisparIdVente($v->idVente);
        $date=$this->vpdo->arrondirDate($v->dateDevis);//Récupérer la date sans les heures et les minutes
       // $d = new DateTime()               
        //on prévoit des variables pour nos appels
        // on crée un bloc avec les informations qui seront multipliées pour chaque nouvelle ligne de la requête. 
        //On met aussi le bouton "Voir Detail", avec un lien dynamique pour envoyer l'utilisateur sur un lien différent en fonction du bouton sur lequel il clique
        $retour = $retour.'
                <bloc';
        if($v->dateCommande != null)
            $retour = $retour.' id="valide"';
        $retour = $retour.
        
                '>
                    <row>
                        <p>Code vente :<input type="text" readonly value='.$v->idVente.'></p>
                        <p>Code Employé :<input type="text" readonly value='.$e->idEmploye.' - '.$e->prenom.' '.$e->nom.'></p>
                        <p>Date devis :<input type="text" readonly value="'.$date.'"></p>
                    </row>
                    <row>
                        <p>Entreprise :<input type="text" readonly value='.$s->idSociete.' - '.$s->nom.'></p>
                        <p>Code client :<input type="text" readonly value='.$c.'></p>
                        <p>Prix Total :<input type="text" readonly value="'.$p->prixTotal.' €"></p>
                    </row>
                    <row>
                       <a href="'.$v->idVente.'" id="btn-voirDetail" class="btn-classique">Voir Details</a>
                    </row>
                </bloc>
        ';
	}
	
    // on rajoute le bouton pour ajouter un Devis
	$retour = $retour.'</div>';
	return $retour;   // on retourne la totalité du texte
	}
	
			
	public function afficheListeCommandes()
	{
	    $retour='
            <div class="conteneur border div-liste div-liste-devis">
                <p>
                    Voici l\'outil de gestion des commandes. Ci-dessous se trouve la liste des commandes existantes.
                </p>
                <p>
                    Vous pouvez consulter les détails d\'une commande en cliquant sur le bouton correspondant.<br>
                    Vous pouvez également créer une nouvelle commande sur cette page grâce au bouton "Ajouter une commande".
                </p>
        ';
	    
	    $retour = $retour.'
            <div class="div-filtres">
                <div id="filtre">
                    <h4>Filtrer :</h4>
                    <p>Validés<input name="radio-filtre" type="radio" value="filtreV" checked></p>
                    <p>A valider<input name="radio-filtre" type="radio" value="filtreNonV"></p>
                </div>
            </div>
                <a href="Ajouter" id="btn-ajouter" class="btn-classique">Ajouter une Commande</a>

	    ';
	    $ventes = $this->vpdo->listeVentesAvecCommande();
	    while($v = $ventes->fetch(PDO::FETCH_OBJ))//boucle tant que..des données sont présentes dans la requête liste.
	    {
	        $e=$this->vpdo->listeDetailsCommandeParIdVente($v->idVente)->fetch(PDO::FETCH_OBJ);
	        //On récupère la première ligne des détails commande de la vente pour
	        //Ensuite récupérer l'employé qui s'est chargé de cette commande.	        
	        $e=$this->vpdo->employeParSonId($e->idEmploye);
	        $s=$this->vpdo->entrepriseParIdVente($v->idVente);
	        $p=$this->vpdo->totalCommandeParIdVente($v->idVente);
	        $date=$this->vpdo->arrondirDate($v->dateCommande);//Récupérer la date sans les heures et les minutes
	        
	        $retour = $retour.'
                <bloc';
	        if($this->vpdo->listeDetailsPreparationParIdVente($v->idVente)->fetch(PDO::FETCH_OBJ) == false)
	        //Si la liste des détails preparation pour cette vente est vide, 
	        //Alors on marque la commande comme non complétée, pour le filtre
	            $retour = $retour.' id="non-valide"';
	        else 
	            $retour = $retour.' id="valide"';
	            
	        //on prévoit des variables pour nos appels
	        // on crée un bloc avec les informations qui seront multipliées pour chaque nouvelle ligne de la requête.
	        //On met aussi le bouton "Voir Detail", avec un lien dynamique pour envoyer l'utilisateur sur un lien différent en fonction du bouton sur lequel il clique
	        $retour = $retour.'
                    >
                    <row>
                        <p>Code vente :<input type="text" readonly value='.$v->idVente.'></p>
                        <p>Responsable :<input type="text" readonly value='.$e->idEmploye.' - '.$e->prenom.' '.$e->nom.'></p>
                        <p>Date commande :<input type="text" readonly value="'.$date.'"></p>
                    </row>
                    <row>
                        <p>Entreprise :<input type="text" readonly value='.$s->idSociete.' - '.$s->nom.'></p>
                        <p>Code client :<input type="text" readonly value='.$v->idClient.'></p>
                        <p>Prix Total :<input type="text" readonly value="'.$p->prixTotal.' €"></p>
                    </row>
                    <row>
                       <a href="'.$v->idVente.'" id="btn-voirDetail" class="btn-classique">Voir Details</a>
                    </row>
                </bloc>
    ';
	    }
	    // on rajoute le bouton pour ajouter un Devis
	    $retour = $retour.'</div>';
	    return $retour;   // on retourne la totalité du texte
	}

	
	public function afficheDetailsDevisOuCommande($idVente, $type)
	{
	    //$type permet de déterminer quelle requete utiliser pour les informations.
	    //Etant donné que les pages détails devis et détails commande sont quasiment identiques,
	    //Cette fonction permet de condenser le code en un seul endroit pour optimiser le nombre de lignes.
	    //En fonction du type, on appellera les détails commande ou les détails devis, mais on garde l'ent-tête
	    //Et la structure de la page.
	    $retour = '';
	    $v = $this->vpdo->venteParSonId($idVente);
        if($v!=null)
        {
    	    $c = $this->vpdo->clientParSonId($v->idClient);
    	    $s = $this->vpdo->societeParSonId($c->idSociete);
    	    $lesDetails = $this->vpdo->listeDetailsCommandeParIdVente($v->idVente);
    	    if($type=="devis")
    	        $lesDetails = $this->vpdo->listeDetailsDevisParIdVente($v->idVente);
    	    $e = $this->vpdo->employeParSonId($lesDetails->fetch(PDO::FETCH_OBJ)->idEmploye);
    	    $retour = '
            <div class="conteneur border" '.$type.'>
                <div id="details-vente">
                    <row>
                        <p>Responsable : <input type="text" value="'.$e->idEmploye.' - '.$e->prenom.' '.$e->nom.'" readonly></p>
                    </row>
                    <row>
                        <p>N° Vente : <input id="idVente" type="text" value="'.$v->idVente.'" readonly></p>
                        <p>N° Client : <input type="text" value="'.$c->idClient.' - '.$c->prenom.' '.$c->nom.'" readonly></p>
                        <p>Date : <input type="text" value="'.$v->dateDevis.'" readonly></p>
                    </row>
                    <row>
                        <p>Entreprise : <input type="text" value="'.$s->idSociete.' - '.$s->nom.'" readonly></p>
                        <p>Adresse : <input type="text" value="'.$s->adresse.'" readonly></p>
                        <p>Coordonnées : <input type="text" value="'.$s->telephone.'" readonly></p>
                    </row>
                </div>
                            
                <div id="details-articles-devis">
                    <table>
                        <tr>    <th>Code article</th>   <th>Nom</th>   <th>Prix unitaire</th>   <th>Marge %</th>   <th>Quantité</th>   <th>Total HT</th>    <th>Remise %</th>   <th>Remise €</th>   <th>TVA</th>   <th>Total TTC</th>   <th>Oservation</th>   </tr>';
    	    
    	    $lesDetails = $this->vpdo->listeDetailsDevisParIdVente($v->idVente);
    	    while($d = $lesDetails->fetch(PDO::FETCH_OBJ))
    	    {
    	        $a = $this->vpdo->articleParSonId($d->idArticle);
    	        $ht = round($d->CMUP * (1+$d->marge) * $d->qteDemandee, 2);
    	        $ttc = round($ht * (1+$d->tva) * (1-$d->txRemise), 2);
    	        $retour = $retour.'
                        <tr>
                                <td>'.$d->idArticle.'</td>
                                <td>'.$a->libelle.'</td>
                                <td>'.$d->CMUP.'</td>
                                <td>'. 100 * $d->marge.'</td>
                                <td>'.$d->qteDemandee.'</td>
                                <td>'.$ht.'</td>
                                <td>'. 100 * $d->txRemise.'</td>
                                <td>'.$ht * $d->txRemise.'</td>
                                <td>'. 100 * $d->tva.'</td>
                                <td>'.$ttc.'</td>
                                <td>'.$d->observation.'</td>
                        </tr>';
    	    }
    	    $retour = $retour.'
                    </table>
                </div>
            </div>';
    	    if($type=="devis")
    	    {
    	        if($v->dateCommande != null)//Si le devis a déjà été confirmé en commande, on ne fait pas de bouton JS.
    	            $retour = $retour.'<a class="btn-classique">Devis confirmé</a>';
                else
                    $retour=$retour.'<a id="confirmer" class="btn-classique">Confirmer Devis</a>';
                    return $retour;	                
    	    }
    	    else
    	    {
    	        if($this->vpdo->listeDetailsPreparationParIdVente($v->idVente)->rowCount() != 0)//Si le devis a déjà été confirmé en commande, on ne fait pas de bouton JS.
    	            $retour = $retour.'<a class="btn-classique">Commande envoyée</a>';
                else
                    $retour=$retour.'<a id="confirmer" class="btn-classique">Passer en Préparation</a>';
    	    }
        }
	    else
	        $retour = $retour.'<p>Cette vente n\'existe pas !</p>';                    
        return $retour;	               
	}
	    
	    
	public function afficheAjoutDevisOuCommande()
	{
	    $idVente = $this->vpdo->idDerniereVente()->idVente+1;
	    $emp = $this->vpdo->employeParSonId($_SESSION['idEmploye']);
	    $lesClients = $this->vpdo->listeClients();
	    $lesArticles = $this->vpdo->listeArticles();
	    $return = '
            <div class="conteneur border">
                <div id="details-vente">
                    <row>
                        <p>Responsable : <input id="resp" type="text" value="'.$emp->idEmploye.' - '.$emp->prenom.' '.$emp->nom.'" readonly></input></p>
                    </row>
                    <row>
                        <p>N° Vente : <input id="idVente" type="text" value="'.$idVente.'" readonly></p>
                        <p>N° Client :<span class="tooltip" id="ttIdClient" title="Vous n\'avez pas choisi de client !"></span><select id="idClient">
                                        <option selected hidden disabled></option>';//Ajoute une option vide pour forcer l'utilisateur à choisir.
	    
	    while($e = $lesClients->fetch(PDO::FETCH_OBJ))
	    {
	        $return = $return.'<option value="'.$e->idClient.'">'.$e->idClient.' - '.$e->nom.' '.$e->prenom.'</option>';
	    }
	    $return = $return.'</select></p>
                        <p>Date : <input id="date" type="text" value='.$this->vpdo->laDateAujourdhui().' readonly></p>
                    </row>
                    <row>
                        <p>Société : <input id="idSociete" type="text" readonly></p>
                        <p>Adresse : <input id="adrSociete" type="text" readonly></p>
                        <p>Coordonnées : <input id="coordSociete" type="text" readonly></p>
                    </row>
                </div>
	        
                <div id="details-articles-devis">
                    <table id="table-articles">
                        <tr>    <th text>Code article</th>   <th text>Nom</th>   <th>Prix unitaire</th>   <th>Marge %</th>   <th>Quantité</th>   <th>Remise % TTC</th>   <th>Remise € TTC</th>   <th>Total HT</th>   <th>TVA %</th>   <th>Total TTC</th>   <th text>Oservation</th>   </tr>
                        <tr id="tr1">
                            <td><select id="idArticle">
                                <option value></option>';
	    //Les th avec "text" sont marqués pour le CSS, afin d'agrandir les colonnes
	    while($a = $lesArticles->fetch(PDO::FETCH_OBJ))
	    {
	        $return = $return.'<option value="'.$a->idArticle.'"> '.$a->codeBarre.' | '.$a->idArticle.' | '.$a->libelle.' | '.$a->dernierCMUP.'€</option>';
	    }
	    $return = $return.'</select></td>
                            <td><input id="nomArticle" type="text" readonly></td>
                            <td><input id="CMUPArticle" type="number" readonly></td>
                            <td><input id="margeArticle" type="number" readonly></td>
                            <td><input id="qteArticle" type="number" min=1 value=1></td>
                            <td><input id="txArticle" type="number" min=0 max=100 step=0.5 value=0></td>
                            <td><input id="remise" type="number" value=0 readonly></td>
                            <td><input id="ht" type="number" value=0 readonly></td>
                            <td><input id="tva" type="number" value=0 readonly></td>
                            <td><input id="ttc" type="number" value=0 readonly></td>
                            <td><input id="obsArticle" type="text"></td>
                      </tr>
                    </table>
                    <a id="ajouteLigne" href="#ajouteLigne" class="btn-classique btn-plusLigne">+</a>
                </div>
            </div>
	    <a id="enregistrer" class="btn-classique">Enregistrer</a>';
	    
	    return $return;
	}
	
	
	public function afficheListePreparations()
	{	    
	    $return='
            <div class="conteneur border div-liste div-liste-preparations">
                <h4>Liste des commandes en préparation :</h4>
                <p>
                    Voici l\'outil de gestion des préparations de commandes.
                </p>
                <p>
                    Vous pouvez ici consulter toutes les commandes en attente de préparation. 
                    Pour en sélectionner une, il suffit de cliquer sur "Préparer".                    
                </p>
                <p>
                    Vous serez alors renvoyé vers la page détaillant la commande.
                </p>            

';//On affiche un message pour que l'utilisateur trouve plus facilement ses marques.
	    
	    $l = $this->vpdo->listePreparationsAFaire($_SESSION['idEmploye']);
	    while($ligneIdVente = $l->fetch(PDO::FETCH_OBJ))//boucle tant que..des données sont présentes dans la requête liste.
	    {
	        $v=$this->vpdo->venteParSonId($ligneIdVente->idVente);
	        $s=$this->vpdo->entrepriseParIdVente($ligneIdVente->idVente);
	        $c=$v->idClient;
	        $date = $this->vpdo->arrondirDate($v->dateCommande);
	        
	        
	        //on prévoit des variables pour nos appels
	        // on crée un bloc avec les informations qui seront multipliées pour chaque nouvelle ligne de la requête.
	        //On met aussi le bouton "Voir Detail", avec un lien dynamique pour envoyer l'utilisateur sur un lien différent en fonction du bouton sur lequel il clique
	        $return = $return.'
                <bloc>
                    <row>
                        <p>Code vente :<input id="idV" type="text" readonly value='.$ligneIdVente->idVente.'></p>
                        <p>Date d\'envoi :<input type="text" readonly value="'.$date.'"></p>
                    </row>
                    <row>
                        <p>Entreprise :<input type="text" readonly value='.$s->idSociete.' - '.$s->nom.'></p>
                        <p>Code client :<input type="text" readonly value='.$c.'></p>
                    </row>
                    <row>
                       <a id="btnPrepa'.$v->idVente.'" class="btn-classique" data-idVente="'.$v->idVente.'">Préparer</a>
                    </row>
                </bloc>
    ';
	    }
	    // on rajoute le bouton pour ajouter un Devis
	    $return = $return.'</div>
                       <input hidden id="idE" value="'.$_SESSION['idEmploye'].'">';
    return $return;   // on retourne la totalité du texte
	}
	
	
	
	public function afficheDetailsPreparation($idVente)
	{
	    $retour ='';
        $v = $this->vpdo->venteParSonId($idVente);
        if($v!=null)
        {
    	    $c = $this->vpdo->clientParSonId($v->idClient);
    	    $s = $this->vpdo->societeParSonId($c->idSociete);
    	    $lesDetails = $this->vpdo->listeDetailsPreparationParIdVente($v->idVente);
    	    $e = $this->vpdo->employeParSonId($_SESSION['idEmploye']);
    	    if($v->datePrepa != null || $lesDetails == null)
    	    //Si la préparation n'a pas de détails, ou si la vente a déjà une date Prépa
    	    {
    	        $retour = "<div class='conteneur border'>Préparation invalide !</div>";
    	    }
    	    else
    	    {
        	    $retour = '
                <div class="conteneur border">
                    <a id="btnAide" class="btn-classique btn-large">Masquer/Afficher l\'aide</a>
                    <aide>
                        <h4>Préparation de commande :</h4>
                        <p>
                            Voici l\'interface de préparation de commande.
                            Vous retrouverez ici les informations concernant la vente en train d\'être traitée,
                            Ainsi que la liste des articles à préparer et à emmener.
                        </p>
                        <p>
                            Pour voir les informations d\'un article, il suffit de cliquer sur le bouton correspondant.
                            Vous pourrez alors entrer le code-barre ou bien le scanner, dans la zone de texte "Scanner le code". Après vérification, vous pourrez choisir le nombre d\'articles que vous retirerez.
                        </p>            
                        <p>
                            S\'il manque des articles pour compléter la commande, ou bien que le code scanné n\'est pas celui de l\'article, le bouton virera alors au orange, signifiant qu\'il n\'est pas complet.
                            Vous pourrez tout de même valider la préparation, qui donnera alors lieu à une démarche auprès du client, géré par les employés commerciaux.
                        </p>
                    </aide>
        
        
                    <div id="details-vente">
                        <h4>Informations Vente :</h4>
                        <row>
                            <p>Responsable Commande : <input id="idE" type="text" value="'.$e->idEmploye.' - '.$e->prenom.' '.$e->nom.'" data-idE="'.$e->idEmploye.'" readonly></p>
                        </row>                    
                        <row>
                            <p>N° Vente : <input id="idVente" type="text" value="'.$v->idVente.'" readonly></p>
                            <p>N° Client : <input type="text" value="'.$c->idClient.' - '.$c->prenom.' '.$c->nom.'" readonly></p>
                            <p>Date : <input type="text" value="'.$v->dateDevis.'" readonly></p>
                        </row>
                    </div>
                    <h4>Articles à fournir :</h4>
                    <div id="details-preparation-articles">
                ';
        	    
        	    $lesDetails = $this->vpdo->listeDetailsPreparationParIdVente($v->idVente);//On récupère les détaisl de la vente	 
        	    $i=0;//Incrément pour donner un id différent à chaque bloc article
                while($d = $lesDetails->fetch(PDO::FETCH_OBJ))
                {
                    $i++;
                    $a = $this->vpdo->articleParSonId($d->idArticle);
                    $retour = $retour.'
                        <a id="btnArticle'.$i.'"class="btn-classique btn-details-preparation">'.$d->idArticle.'</a>
                        <row id="rowArticle'.$i.'">
                            <p>Code Article : <input id="codeArticle" type="text" value="'.$a->codeBarre.'" readonly></p>
                            <p>Nom :<input type="text" value="'.$a->libelle.'" readonly></p>
                            <p>Emplacement<input type="text" value="'.$a->idEmp.'" readonly></p>
                            <p>Scanner le code : <input id="codeScan" type="number"></p>
                            <p class="p-demi">A fournir :<input id="qteDemandee" type="number" value='.$d->qteDemandee.' min=0 readonly></p>
                            <p class="p-demi right">Fourni :<input id="qteFournie" type="number" value='.$d->qteFournie.' min=0 readonly></p>
                        </row>
                        ';
                }
                $retour = $retour.'
                    </div>';//On ferme details-articles
        	    
        	    
        	    $retour = $retour.'
                    <a id="validePrepa" class="btn-classique btn-large">Valider Préparation</a>
                </div>';//On ferme conteneur
    	    }
        }
        else 
            $retour = '<p>Cette vente n\'existe pas !</p>';
	    return $retour;
	}
	
	
	public function afficheListeFactures()
	{	    
	    $retour='
            <div class="conteneur border div-liste div-liste-factures">
                <p style="margin-left: 1em">
                    Voici l\'outil de gestion des factures. Ci-dessous la liste des factures existantes.<br>
                    Vous pouvez accéder au detail de chaque vente en cliquant sur "Voir Détail".<br>
                    A partir de ces détails, vous pourrez générer une facture imprimable.
                </p>
                <p style="margin-left: 1em">
                    Vous pouvez choisir quelles ventes afficher ou non, en changeant la sélection dans "Filtrer".<br>
                </p>'
	        ;
	        //Div pour les filtres et les tris
	        $retour = $retour.'
            <div class="div-filtres">
                <div id="filtre">
                    <h4>Filtrer :</h4>
                    <p>Validés<input name="radio-filtre" type="radio" value="filtreV" checked></p>
                    <p>A valider<input name="radio-filtre" type="radio" value="filtreNonV"></p>
                </div>
            </div>
    ';
	        $l = $this->vpdo->listeVentesAvecFacture();
	        while($v = $l->fetch(PDO::FETCH_OBJ))//boucle tant que..des données sont présentes dans la requête liste.
	        {
	            $details=$this->vpdo->listeDetailsFactureParIdVente($v->idVente)->fetch(PDO::FETCH_OBJ);
	            //On récupère la première ligne des détails devis de la vente pour
	            //Ensuite récupérer l'employé qui s'est chargé de ce devis.
	            $e=$this->vpdo->employeParSonId($details->idEmploye);
	            $s=$this->vpdo->entrepriseParIdVente($v->idVente);
	            $c=$v->idClient;
	            $p=$this->vpdo->totalFactureparIdVente($v->idVente);
	            $date=$this->vpdo->arrondirDate($v->dateDevis);//Récupérer la date sans les heures et les minutes
	            
	            //on prévoit des variables pour nos appels
	            // on crée un bloc avec les informations qui seront multipliées pour chaque nouvelle ligne de la requête.
	            //On met aussi le bouton "Voir Detail", avec un lien dynamique pour envoyer l'utilisateur sur un lien différent en fonction du bouton sur lequel il clique
	            $retour = $retour.'
                <bloc';
	            if($v->dateFacture != null)
	                $retour = $retour.' id="valide"';
	                $retour = $retour.
	                
	                '>
                    <row>
                        <p>Code vente :<input type="text" readonly value='.$v->idVente.'></p>
                        <p>Code Employé :<input type="text" readonly value='.$e->idEmploye.' - '.$e->prenom.' '.$e->nom.'></p>
                        <p>Date devis :<input type="text" readonly value="'.$date.'"></p>
                    </row>
                    <row>
                        <p>Entreprise :<input type="text" readonly value='.$s->idSociete.' - '.$s->nom.'></p>
                        <p>Code client :<input type="text" readonly value='.$c.'></p>
                        <p>Prix Total :<input type="text" readonly value="'.$p->prixTotal.' €"></p>
                    </row>
                    <row>
                       <a href="'.$v->idVente.'" id="btn-voirDetail" class="btn-classique">Voir Details</a>
                    </row>
                </bloc>
        ';
	        }
	        
	        // on rajoute le bouton pour ajouter un Devis
	        $retour = $retour.'</div>';
	        return $retour;   // on retourne la totalité du texte
	}
	
	public function afficheDetailsFacture($idVente)
	{
	    $retour ='';
	    $v = $this->vpdo->venteParSonId($idVente);
	    if($v!=null)
	    {
	        $c = $this->vpdo->clientParSonId($v->idClient);
	        $s = $this->vpdo->societeParSonId($c->idSociete);
	        $lesDetails = $this->vpdo->listeDetailsFactureParIdVente($v->idVente);
	        $e = $this->vpdo->employeParSonId($_SESSION['idEmploye']);
	        if($lesDetails == null)
	        //Si la préparation n'a pas de détails, ou si la vente a déjà une date Prépa
	        {
	            $retour = "<div class='conteneur border'>Cette vente n\'a pas encore été livrée !</div>";
	        }
	        else
	        {
	            $retour = '
        <div class="conteneur border">
            <a id="btnAide" class="btn-classique">Masquer/Afficher l\'aide</a>
            <aide>
                <h4>Préparation de commande :</h4>
                <p>
                    Voici l\'interface de validation de factures. Vous pouvez visualiser ici le récapitulatif d\'une vente, 
                    valider un paiement, et générer une facture à partir de celle-ci.

                </p>
                <p>
                    Les zones grisées ne sont pas modifiables, ces informations sont verrouillées.
                </p>
            </aide>

            <div id="details-vente">
                <h4>Informations Vente :</h4>
                <row>
                    <p>Responsable : <input id="idE" type="text" value="'.$e->idEmploye.' - '.$e->prenom.' '.$e->nom.'" data-idE="'.$e->idEmploye.'" readonly></p>
                    <p>N° Vente : <input id="idVente" type="text" value="'.$v->idVente.'" readonly></p>
                </row>
                <row>
                    <p>N° Client : <input type="text" value="'.$c->idClient.' - '.$c->prenom.' '.$c->nom.'" readonly></p>
                    <p>Date : <input type="text" value="'.$v->dateDevis.'" readonly></p>
                </row>
            </div>
            <h4>Liste des articles achetés : </h4>
                <div id="details-articles-devis">
                    <table>
                        <tr>    <th>Code article</th>   <th>Nom</th>   <th>Prix unitaire</th>   <th>Marge %</th>   <th>Qte Demandée</th>   <th>Qte Fournie</th>   <th>Total HT</th>    <th>Remise %</th>   <th>Remise €</th>   <th>TVA</th>   <th>Total TTC</th>   <th>Oservation</th>   </tr>';
    	    
    	    $lesDetails = $this->vpdo->listeDetailsFactureParIdVente($v->idVente);
    	    while($d = $lesDetails->fetch(PDO::FETCH_OBJ))
    	    {
    	        $a = $this->vpdo->articleParSonId($d->idArticle);
    	        $ht = round($d->CMUP * (1+$d->marge) * $d->qteDemandee, 2);
    	        $ttc = round($ht * (1+$d->tva) * (1-$d->txRemise), 2);
    	        $retour = $retour.'
                        <tr>
                                <td>'.$d->idArticle.'</td>
                                <td>'.$a->libelle.'</td>
                                <td>'.$d->CMUP.'</td>
                                <td>'. 100 * $d->marge.'</td>
                                <td>'.$d->qteDemandee.'</td>
                                <td>'.$d->qteFournie.'</td>
                                <td>'.$ht.'</td>
                                <td>'. 100 * $d->txRemise.'</td>
                                <td>'.$ht * $d->txRemise.'</td>
                                <td>'. 100 * $d->tva.'</td>
                                <td>'.$ttc.'</td>
                                <td>'.$d->observation.'</td>
                        </tr>';
    	    }
    	    $retour = $retour.'
                    </table>
                </div>';
 	            $retour = $retour.'
                    </div>
                <a id="validePaiement" class="btn-classique">Valider le règlement de la vente</a>
                ';//On ferme conteneur
	        }
	    }
	    else
	        $retour = '<p>Cette vente n\'existe pas !</p>';
        return $retour;
	}
	
	
	public function afficheAjoutDevis()
	{
	    $idVente = $this->vpdo->idDerniereVente()->idVente+1;
	    $emp = $this->vpdo->employeParSonId($_SESSION['idEmploye']);	  
	    $lesClients = $this->vpdo->listeClients();
	    $lesArticles = $this->vpdo->listeArticles();
	    $return = '
            <div class="conteneur border">
                <div id="details-vente">
                    <row>
                        <p>Responsable Devis : <input id="resp" type="text" value="'.$emp->idEmploye.' - '.$emp->prenom.' '.$emp->nom.'" readonly></input></p>
                    </row>
                    <row>
                        <p>N° Vente : <input id="idVente" type="text" value="'.$idVente.'" readonly></p>
                        <p>N° Client :<span class="tooltip" id="ttIdClient" title="Vous n\'avez pas choisi de client !"></span><select id="idClient">
                                        <option selected hidden disabled></option>';//Ajoute une option vide pour forcer l'utilisateur à choisir.
        
        while($e = $lesClients->fetch(PDO::FETCH_OBJ))
        {
            $return = $return.'<option value="'.$e->idClient.'">'.$e->idClient.' - '.$e->nom.' '.$e->prenom.'</option>';
        }
        $return = $return.'</select></p>
                        <p>Date : <input id="dateDevis" type="text" readonly></p>
                    </row>
                    <row>
                        <p>Société : <input id="idSociete" type="text" readonly></p>
                        <p>Adresse : <input id="adrSociete" type="text" readonly></p>
                        <p>Coordonnées : <input id="coordSociete" type="text" readonly></p>
                    </row>
                </div>
	        
                <div id="details-articles-devis">
                    <table id="table-articles">
                        <tr>    <th>Code article</th>   <th>Nom</th>   <th>Prix unitaire</th>   <th>Marge %</th>   <th>Quantité</th>   <th>Remise % TTC</th>   <th>Remise € TTC</th>   <th>Total HT</th>   <th>TVA %</th>   <th>Total TTC</th>   <th>Oservation</th>   </tr>
                        <tr>
                            <td><span class="tooltip" id="ttIdArticle" title="Sélectionnez au moins un article !"><select id="idArticle1">
                                <option value></option>';
        while($e = $lesArticles->fetch(PDO::FETCH_OBJ))
        {
            $return = $return.'<option value="'.$e->idArticle.'">'.$e->idArticle.'</option>';
        }        
        $return = $return.'</select></span></td>
                            <td><input id="nomArticle1" type="text" readonly></td>
                            <td><input id="CMUPArticle1" type="number" readonly></td>
                            <td><input id="margeArticle1" type="number" readonly></td>
                            <td><input id="qteArticle1" type="number" min=1 value=1></td>
                            <td><input id="txArticle1" type="number" min=0 max=100 step=0.5 value=0></td>
                            <td><input id="remise1" type="number" value=0 readonly></td>
                            <td><input id="ht1" type="number" value=0 readonly></td>
                            <td><input id="tva1" type="number" value=0 readonly></td>
                            <td><input id="ttc1" type="number" value=0 readonly></td>
                            <td><input id="obsArticle1" type="text"></td>
                      </tr>
                    </table>
                    <a id="ajouteLigne" class="btn-classique btn-plusLigne">+</a>
                </div>
            </div>
	    <a id="enregistrer" class="btn-classique">Enregistrer le devis</a>';
	    return $return;
	}
	
	
	
	
	public function afficheListeReliquats()
	{
	    $retour ='
        <div class="conteneur border div-liste liste-reliquats">';
	    $retour = $retour.'
            <h4>Gestion des reliquats</h4>
            <p style="margin-left:1em">Voici la page de gestion des reliquats.</p>
            <p style="margin-left:1em">Lorsqu\'une vente est incomplète à la préparation, ou des articles sont dégradés la livraison, un reliquat est généré.
                <br>Il permet de prendre en compte les pertes dans une commande, et de générer un avoir ou un remboursement pour le client.</p>
            <p style="margin-left:1em">Pour visualiser les reliquats d\'une vente, cliquez sur "Voir Détails" pour la vente correspondante.</p>
            <a id="filtre" class="btn-classique" data-id="1"></a>
        ';
	    $lesVentes = $this->vpdo->listeVentesAvecReliquatNonResolus();
	    while($v = $lesVentes->fetch(PDO::FETCH_OBJ))
	    {
	        $c= $this->vpdo->clientParSonId($v->idClient);
	        $e=$this->vpdo->societeParSonId($c->idSociete);
	        $lesDetails = $this->vpdo->listeDetailsReliquatParIdVente($v->idVente);
	        $qte = 0;//Nombre d'articles total à gérer.
	        while($d = $lesDetails->fetch(PDO::FETCH_OBJ)) $qte += $d->qte;//Pour chaque reliquat, on fait la somme des quantités
	        
	        $retour = $retour.'
            <bloc tags="non-resolu">
                <row><p>ID Vente : <input value='.$v->idVente.' readonly></p><p>Client : <input value="'.$v->idClient.' - '.$c->prenom.' '.$c->nom.'" readonly></p></row>
                <row><p>Entreprise : <input value="'.$e->idSociete.' - '.$e->nom.'" readonly></p><p>Quantité totale : <input value='.$qte.' readonly></p></row>
                <row><a class="btn-classique" href="'.$v->idVente.'">Voir Détails</a></row>
            </bloc>
            ';
	    }
	    $lesVentes = $this->vpdo->listeVentesAvecReliquatResolus();
	    while($v = $lesVentes->fetch(PDO::FETCH_OBJ))
	    {
	        $c= $this->vpdo->clientParSonId($v->idClient);
	        $e=$this->vpdo->societeParSonId($c->idSociete);
	        $lesDetails = $this->vpdo->listeDetailsReliquatParIdVente($v->idVente);
	        $qte = 0;//Nombre d'articles total à gérer.
	        while($d = $lesDetails->fetch(PDO::FETCH_OBJ)) $qte += $d->qte;//Pour chaque reliquat, on fait la somme des quantités
	        
	        $retour = $retour.'
            <bloc tags="resolu">
                <row><p>ID Vente : <input value='.$v->idVente.' readonly></p><p>Client : <input value="'.$v->idClient.' - '.$c->prenom.' '.$c->nom.'" readonly></p></row>
                <row><p>Entreprise : <input value="'.$e->idSociete.' - '.$e->nom.'" readonly></p><p>Quantité totale : <input value='.$qte.' readonly></p></row>
                <row><a class="btn-classique" href="'.$v->idVente.'">Voir Détails</a></row>
            </bloc>
            ';
	    }
	    $retour = $retour.'</div>';	    	    	   
	    return $retour;
	}
	
	
	
	
	public function afficheDetailsReliquat($idV)
	{
	    $valide = false;
	    //Permet de savoir si les détails sont encore remplir ou non.
	    //"false" si les détails sont tous pleins, 
	    //"true" si au moins un détail n'a pas de typeAction
	    $v = $this->vpdo->venteParSonId($idV);
	    if($v!=null)
	    {
    	    $e = $this->vpdo->employeParIdVente($idV);
    	    $c = $this->vpdo->clientParSonId($v->idClient);	  
    	    $s = $this->vpdo->societeParSonId($c->idSociete);
    	    $lesReliquats = $this->vpdo->listeDetailsReliquatParIdVente($idV);
    	    $retour = '
            <div class="conteneur border">
                <div id="details-vente">
                    <row>
                        <p>Responsable : <input type="text" value="'.$e->idEmploye.' - '.$e->prenom.' '.$e->nom.'" readonly></p>
                    </row>
                    <row>
                        <p>N° Vente : <input id="idVente" type="text" value="'.$v->idVente.'" readonly></p>
                        <p>N° Client : <input type="text" value="'.$c->idClient.' - '.$c->prenom.' '.$c->nom.'" readonly></p>
                        <p>Date : <input type="text" value="'.$this->vpdo->arrondirDate($v->dateDevis).'" readonly></p>
                    </row>
                    <row>
                        <p>Entreprise : <input type="text" value="'.$s->idSociete.' - '.$s->nom.'" readonly></p>
                        <p>Adresse : <input type="text" value="'.$s->adresse.'" readonly></p>
                        <p>Coordonnées : <input type="text" value="'.$this->formeTelephone($s->telephone).'" readonly></p>
                    </row>
                </div>
                <div class="div-liste">
                            
            ';
    	    
    	    while($r = $lesReliquats->fetch(PDO::FETCH_OBJ))
    	    {
    	        if($r->typeAction == null || $r->compensation == null)   
                    $valide = true;
                    
	            $t = $this->vpdo->typeReliquatParSonId($r->typeReliquat);
    	        $retour = $retour.'
                    <bloc>
                        <row>
                            <p>Article : <input id="artReliquat" type="text" value="'.$r->idArticle.'" readonly></p>
                            <p>Quantité : <input type="text" value="'.$r->qte.'" readonly></p>
                            <p>Type de conflit : <input type="text" value="'.$t->libelle.'" readonly></p>
                            <p>Action<select id="actReliquat">
                ';
    	        $lesActions = $this->vpdo->listeTypesAction();
    	        while($a = $lesActions->fetch(PDO::FETCH_OBJ))
    	        {   
    	            $retour = $retour.'
                                <option value="'.$a->idType.'"';
    	            if($a->idType == $r->typeAction)
    	            //Si l'idType de l'action ajoutée = l'idType du reliquat en train d'être défini
    	            //Alors on sélectionne cette option.
    	                $retour = $retour.' selected';
    	            
	                $retour = $retour.'>'.$a->libelle.'</option>';
    	        }
    	        $retour = $retour.'
                            </select></p>
                            <p>Montant € : <input id="montReliquat" type="number" min=0 value="'.$r->compensation.'" step=0.01></p>
                        </row>
                        <row>
                            <p>Observation : <sub id="char"></sub></p><textarea id="obsReliquat" maxlength="64">'.$r->observation.'</textarea>
                        </row>                
                    </bloc>
            ';
    	    }	
    	    $retour = $retour.'
                </div>
            </div>';
            if($valide == true)
        	    $retour = $retour.'<a id="confirmer" class="btn-classique">Valider les reliquats</a>';
            else
                $retour = $retour.'<a class="btn-classique">Reliquats validés</a>';                
	    }
	    else
	    {
	        $retour = '<div class="conteneur">Cette vente n\'existe pas !</div>';
	    }
	    return $retour;
	    
	}
	
/*______________________________________________________________________________________________________________________________________*/
/* ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// */
/* ************************************************************************************************************************************* */	
/* **------------------------------------------DEBUT-GESTION-LISTE-CLIENT-ET-FOURNISSEUR----------------------------------------------** */	
/* ************************************************************************************************************************************* */	
/* ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// */
	
public function listeSociete($types,$type,$idType)
{    // je récupère 3 données, type, types et id idType
     //ils me permettent d'adapter ma page en client ou en fournisseur en fonction de l'appel effectuer dans index
$return='<div class="conteneur div-liste div-liste-entreprises"><p style="margin-left: 1em">
                    <h4>Voici l\'outil de gestion des  '.$types.' </h4><br>
                    Vous pouvez accéder au contact pour chaque societe en cliquant sur <b>"Voir détails"</b>.<br>                  
                    Si vous souhaitez ajouter un contact, vous pouvez cliquez sur <b>"Ajouter un contact"</b>.<br>
                    Vous pouvez aussi cliquez sur <b>"Voir détails"</b> puis sur <b>"Ajouter un contact"</b>.<br>
                    Si vous souhaitez modifier les informations d\'une societe ou d\'un contact, cliquez sur <b>"Voir détails"</b>.<br>
                    Si vous souhaitez ajouter une nouvelle societe, cliquez sur le bouton <b>"Ajouter une Societe"</b>.<br>

                    <a href="ajoutersociete" id="btn-confirmerModifEntreprise" class="btn-classique">Ajouter une société</a>
                    <a href="ajoutercontactsociete" id="btn-confirmerModifEntreprise" class="btn-classique">Ajouter un contact</a>';                    
if ($types=="clients")
{//je vérifie si l'utilisateur a cliquer sur clients ou sur fournisseurs
    $return = $return.'<p><b>Liste des '.$types.'</b></p><div style="display:block"  id="block-'.$type.'">';
    $lscof = $this->vpdo->listeSocietesClients();// en fonction de la réponse je récupère une liste différente
}
else{
    $return = $return.'<p><b>Liste des '.$types.'</b></p><div style="display:block"  id="block-'.$type.'">';
    $lscof = $this->vpdo->listeSocietesFournisseurs();
}
while($ls = $lscof->fetch(PDO::FETCH_OBJ))//j'utilise un while pour parcourir la liste
    {
    $return = $return.'
                <bloc>
                   	<row>    
                        <p>Dénomination : <input type="text" readonly maxlength="24" value='.$ls->nom.'> </p>
                        <p>Code : <input type="text" readonly  value='.$ls->idSociete.'></p>
                        <p>Site web :<input type="text" readonly maxlength="48" value='.$ls->siteWeb.'> </p>  
                    </row>
                    <row>
                        <p>Téléphone :<input type="text" readonly maxlength="12" value='.$ls->telephone.'></p>
                        <p>Adresse :<input type="text" readonly maxlength="64" value="'.$ls->adresse.'"> </p>
                        <p>Raison sociale :<input type="text" readonly maxlength="12" value='.$ls->raison.'></p>
                    </row>
                    <row>
                        <p>Mail :<input type="text" readonly value='.$ls->mail.'></p>
                        <a href="'.$ls->idSociete.'" id="btn-voirDetail" class="btn-classique">Voir détails</a>  
                    </row>
                </bloc>';
    }    
    $return=$return.'</div></div>';// chaque boucle effectuer affiche les données d'une société, 
    //il y a aussi un bouton "Voir détails" qui est créé qui envoie sur le lien de la société souhaité (avec : href="'.$ls->idSociete.'")
    return $return;// je retourne la totalité de l'html
}

/* ************************************************************************************************************************************* */
/* *****************************************************AUTRE*METHODE*MEME*BUT********************************************************** */
/* ************************************************************************************************************************************* */


public function listeContact($idSociete, $types,$type, $idType)
{   // je récupère 4 données, puisque qu'il sagit de la liste des contacts pour une société en particulier, j'ai besoin de l'id de la Société.
    // je récupères aussi types, type et idType, toujours pour m'addapter en fournisseur ou client
    $s = $this->vpdo->societeParSonId($idSociete);//je récupère la société avec l'id passer en paramètre de la page
    if ($types=="clients")//adapte la liste de contact en focntion du types
    {
        $lccof = $this->vpdo->listeContactClientsParId($idSociete);
    }
    else 
    {
        $lccof = $this->vpdo->listeContactFournisseursParId($idSociete);
    }
    $return='   <div class="conteneur border div-liste div-liste-devis">
                    <p style="margin-left: 1em">
                        Voici l\'outil de gestion des contacts. Ci-dessous la liste des contacts existant pour le '.$type.' : <b>'.$s->nom.'</b>.<br>
                        Si vous souhaitez modifier les informations d\'un '.$type.', cliquez sur <b>"Modifier les informations"</b>.<br>                        
                        Si vous souhaitez modifier les informations d\'un contact, cliquez sur <b>"Modifier le contact"</b>.<br>
                        Si vous souhaitez ajouter un nouveau contact, cliquez sur <b>"Ajouter un contact"</b> en bas de la page.
                    </p> ';
   $return=$return.' <p><b>INFORMATION SUR L\'ENTREPRISE</b></p> ';  
   $return = $return.'<div id="details-entreprise">
                        <row>    
                        <p> Code de l\'entreprise : <input type="text" id="idSociete" readonly required value='.$s->idSociete.'></p>
                        <p>  Nom de l\'entreprise : <input type="text" maxlength="24" id="nomSociete" required value='.$s->nom.'> </p>
                        <p>  Raison sociale : <input type="text" id="raisonSociete" maxlength="12" required value='.$s->raison.'> </p>
                     </row>
                     <row>
                        <p>  Site web de l\'entreprise : <input type="text" id="siteWebSociete" maxlength="48" required value='.$s->siteWeb.'> </p>  
                        <p>  Téléphone de l\'entreprise : <input type="text"  id="telSociete" maxlength="12" required value='.$s->telephone.'> </p>
                        <p> Adresse de l\'entreprise : <input type="text" id="adresseSociete" maxlength="64" required value="'.$s->adresse.'"> </p>
                      </row>
                      <row>
                        <p>  fax de l\'entreprise : <input type="text" id="faxSociete" required value='.$s->fax.'> </p>          
                        <p>  Mail de l\'entreprise : <input type="text" id="mailSociete" required value='.$s->mail.'></p>
                        <a onclick="modificationSociete()" class="btn-classique">
                        <span class="tooltip" id="ttUpdateSocieteInfo" title="Vous n\'avez pas rempli toutes les informations !"></span>
                        Modifier les informations</a>
    
                      </row> 
                </div> 
            <h4>Liste des contacts :</h4>';//j'affiche les données de l'entreprise. 
   //Je crée un bouton qui fait appel à une page Js puis à Ajax afin de modifier les informations de l'entreprise. 
   // L'utilisaiton de span class"tooltip" me permet d'afficher un pop-up quand les informations les plus improtantes ne sont pas remplit.

    while($ligneIdContact = $lccof->fetch(PDO::FETCH_OBJ))
    { 
     $return = $return.'<div id="conteneur div-liste div-liste-entreprises">
    <row> 
        <p>Code du contact : <input type="text" id="id'.$ligneIdContact->$idType.'" required readonly value='.$ligneIdContact->$idType.'></p>
        <p>Nom du contact : <input type="text" id="nom'.$ligneIdContact->$idType.'" maxlength="16" required value='.$ligneIdContact->nom.'></p>
        <p>Prenom du contact : <input type="text" id="prenom'.$ligneIdContact->$idType.'" maxlength="16"  required value='.$ligneIdContact->prenom.'></p> 
    </row>
    <row>
        <p>Téléphone du contact : <input type="text" id="tel'.$ligneIdContact->$idType.'" maxlength="10" required value='.$ligneIdContact->telephone.'></p>
        <p>Mail du contact : <input type="text" id="mail'.$ligneIdContact->$idType.'" required maxlength="40" value='.$ligneIdContact->mail.'></p>
        <p>Id société du contact : <input type="text" id="societe'.$ligneIdContact->$idType.'" required value='.$ligneIdContact->idSociete.'></p>
     </row>   
     <row>
        <a onclick=\'modificationContact("'.$ligneIdContact->$idType.'","'.$type.'")\' class="btn-classique">
        <span class="tooltip" id="ttModifContactInfo'.$ligneIdContact->$idType.'" title="Vous n\'avez pas rempli toutes les informations !"></span>
        Modifier le contact</a>
    </row>
</div>';
    }
    $return = $return.'</div><a href="ajoutercontact/'.$idSociete.'" class="btn-classique">Ajouter un contact</a>';
    return $return; //j'affiche les données de toutes les informations des contacts de l'entreprise. 
    //Je crée un bouton qui fait appel à une page Js puis à Ajax afin de modifier les informations du contact.
    // L'utilisaiton de span class"tooltip" me permet d'afficher un pop-up quand les informations ne sont pas remplit.
}


/* ************************************************************************************************************************************* */
/* *****************************************************AUTRE*METHODE*MEME*BUT********************************************************** */
/* ************************************************************************************************************************************* */


public function ajouterContact($idSociete, $type)
{  
    // je récupère 2 données, puisque qu'il sagit du bouton pour créer un contact pour une société en particulier, j'ai besoin de l'id de la Société.
    // je récupere toujours type, pour savoir si il sagit d'un contact client ou fournisseur.
    if ($type=="client")//je m'adapte
    {$idContact = $this->vpdo->idDernierContactClient()->idClient+1;}//je récupère le dernier id utilisé et y ajoute 1
    else                                                                // de cette manière je suis sur que l'id n'est pas utiliser
    {$idContact = $this->vpdo->idDernierContactFournisseur()->idFour+1;}
   
    $infoSociete=$this-> vpdo ->societeParSonId($idSociete)->nom;// je récupère le nom de la société pour afficher le nom dans le texte
    $return='<div class="conteneur border div-liste div-liste-devis">
                    <p style="margin-left: 1em">
                        Voici l\'outil d\'ajout des contacts pour <b>'.$infoSociete.'</b><br>
                        Il vous suffit de remplir les cases ci-dessous et cliquez sur <b>"Valider le contact"</b>.<br>
                        Les cases <b>"Code du contact"</b> et  <b>"id société '.$infoSociete.'"</b> ne peuvent pas être changées.<br>
                        Si vous souhaitez ajouter un contact pour une entreprise déjà existante, veuillez vous diriger vers la liste des entreprises.  
                    </p> ';
    $return = $return.'
    <bloc>
        <row>
            <p>Code du contact : <input type="text" id="id'.$type.'" readonly required value="'.$idContact.'"></p>
            <p>Nom du contact(*) : <input type="text" id="nom'.$type.'" maxlength="16" required value=""></p>
        </row>
        <row>
            <p>Prenom du contact(*) : <input type="text" id="prenom'.$type.'" maxlength="16" required  value=""></p>
            <p>Téléphone du contact(*) : <input type="text" id="tel'.$type.'" maxlength="10" required  value=""></p>
         </row>
         <row>
            <p>Mail du contact(*) : <input type="text" id="mail'.$type.'" maxlength="40" required value=""></p>
            <p>id société '.$infoSociete.' : <input type="text" readonly required id="societe'.$type.'" value="'.$idSociete.'"></p>
         </row>
         <row>
            <a onclick=\'ajoutercontact("'.$type.'")\' class="btn-classique">
            <span class="tooltip" id="ttInsertContactInfo" title="Vous n\'avez pas rempli toutes les informations !"></span>
            Valider le contact</a>  
        </row>
    </bloc>
</div>';
	
	return $return;// J'affiche les cases qui doivent être remplie. Encore une fois js appellé pour effectuer une requete ajax. 
	// toujours l'utilisation de span pour vérifier que les informations sont bien remplies.
}

/* ************************************************************************************************************************************* */
/* *****************************************************AUTRE*METHODE*MEME*BUT********************************************************** */
/* ************************************************************************************************************************************* */


public function ajouterContactSociete($type)// je récupère le type
{   
    if ($type=="client")//je m'adapte
    {$idContact = $this->vpdo->idDernierContactClient()->idClient+1;}
    else
    {$idContact = $this->vpdo->idDernierContactFournisseur()->idFour+1;}
    $lesSocietes = $this->vpdo->listeSociete();//je crée une liste des sociétés
    //$s=$this-> vpdo ->listeSociete();
    $return='<div class="conteneur border div-liste div-liste-devis">
                    <p style="margin-left: 1em">
                        Voici l\'outil d\'ajout des contacts.<br>
                        Il vous suffit de remplir les cases ci-dessous et cliquez sur <b>"Valider le contact"</b>.<br>
                        Les cases <b>"Code du contact"</b> ne peut pas être changées.<br>
                    </p> ';
    $return = $return.'
    <row>
        <p>Code du contact : <input type="text" id="id'.$type.'" readonly required value="'.$idContact.'"></p>
        <p>Id & nom société(*) :<span class="tooltip" id="ttInsertContactIdSociete" title="Vous n\'avez pas choisi de société !"></span>
        <select id="idSocieteContact"><option selected hidden disabled></option>'; 
        while($e = $lesSocietes->fetch(PDO::FETCH_OBJ))
            {
                $return = $return.'<option value="'.$e->idSociete.'">'.$e->idSociete.' - '.$e->nom.'</option>';
            }
        $return=$return.'</select></p><p>Nom du contact(*) :<input type="text" id="nom'.$type.'" maxlength="16" required value=""></p>
    </row>
    <row>
        <p>Prenom du contact(*) :<input type="text" id="prenom'.$type.'" maxlength="16" required  value=""></p>
        <p>Téléphone du contact(*) :<input type="text" id="tel'.$type.'" maxlength="10" required  value=""></p>
        <p>Mail du contact(*) :<input type="text" id="mail'.$type.'" maxlength="40" required value=""></p>    
     </row>
     <row>
            
            <a onclick=\'ajoutercontactsociete("'.$type.'")\'class="btn-classique"> 
            <span class="tooltip" id="ttInsertContactInfo" title="Vous n\'avez pas rempli toutes les informations !"></span>
             Valider le contact</a>
    </row>
</div>';
    return $return;//j'affiche une liste de société, ainsi l'utilisateur peur ajouter un contact à n'importe qu'elle entreprise.
    // même une entreprise qui n'a pas le type.
}


/* ************************************************************************************************************************************* */
/* *****************************************************AUTRE*METHODE*MEME*BUT********************************************************** */
/* ************************************************************************************************************************************* */

public function ajouterSociete($type)//je récupère toujours le type
    {
        $idSociete = $this->vpdo->idDernierSociete()->idSociete+1;
        $return='<div class="conteneur border div-liste div-liste-entreprises">
                    <p style="margin-left: 1em">
                        Voici l\'outil d\'ajout de nouvelle entreprise.</b><br>
                        Il vous suffit de remplir les cases ci-dessous et cliquez sur <b>"Valider"</b>.<br>
                        La case <b>"id de l\'entreprise"</b> ne peut pas être changé.
                    </p> ';
        $return = $return.'
    <row>
        <p> Code de l\'entreprise : <input type="text" id="idSociete" readonly value="'.$idSociete.'"></p>
        <p>  Nom de l\'entreprise : <input type="text" required maxlength="24" id="nomSociete"value=""></p>
        <p>  Raison sociale : <input type="text" id="raisonSociete" required maxlength="12" value=""> </p>
   </row>
    <row>
         <p>  Site web de l\'entreprise : <input type="text" id="siteWebSociete" required maxlength="48" value=""> </p>  
         <p>  Téléphone de l\'entreprise : <input type="text"  id="telSociete" required maxlength="12" value=""> </p>
         <p> Adresse de l\'entreprise : <input type="text" id="adresseSociete" required maxlength="64" value=""> </p>
    </row>
     <row>
         <p>  fax de l\'entreprise : <input type="text" id="faxSociete" required maxlength="48"  value=""> </p>          
         <p>  Mail de l\'entreprise : <input type="text" id="mailSociete" required maxlength="40"  value=""></p>
         <a onclick=\'ajoutersociete("'.$type.'")\' class="btn-classique"> 
         <span class="tooltip" id="ttInsertSocieteInfo" title="Vous n\'avez pas rempli toutes les informations !"></span>
         Valider </a>
    </row>
</div>';
        
        return $return;//Comme ci-dessus, cette fois je récupère juste le type pour pouvoir l'envoyer dans le Js
    }

/* ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// */
/* ************************************************************************************************************************************* */
/* ****************************************FIN*GESTION*LISTE*CLIENT*ET*FOURNISSEUR****************************************************** */
/* ************************************************************************************************************************************* */
/* ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// */

/* ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// */
/* ************************************************************************************************************************************* */
/* ****************************************************DEBUT*LISTE*DES*ACHATS*********************************************************** */
/* ************************************************************************************************************************************* */
/* ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// */
    
   public function listeAchats()
    {
        $return='<div class="conteneur div-liste div-liste-entreprises">
                    <p style="margin-left: 1em">
                        Voici l\'outil de gestion des Achats.</b><br>
                        Si vous souhaitez ajouter un nouvelle Achat, cliquez sur <b>" Ajouter achat"</b>.<br>
                        <a href="ajouterachat" id="btn-confirmerModifEntreprise" class="btn-classique">Ajouter achat</a>
                    </p> ';
    $lads = $this->vpdo->listeAjoutdeStock();
while($ls = $lads->fetch(PDO::FETCH_OBJ))//j'utilise un while pour parcourir la liste
   { 
       $lf=$this->vpdo->societeParSonId($ls->idSociete);
       $la=$this->vpdo->articleParSonId($ls->idArticle);
       $return = $return.'
                <bloc>
                   	<row>
                        <p>Date de l\'Achat :<input type="text" readonly maxlength="12" value='.$ls->date.'></p>
                        <p>Quantité :<input type="text" readonly maxlength="12" value='.$ls->qte.'></p>
                        <p>Fournisseur : <input type="text" readonly  value="'.$ls->idSociete.' - '.$lf->nom.'"></p>                       
                    </row>
                    <row>
                        <p>Id Article :<input type="text" readonly maxlength="48" value='.$ls->idArticle.'> </p>
                        <p>Prix Unitaire :<input type="text" readonly maxlength="64" value="'.$ls->prix.'"> </p>
                        <p>Commentaire :<input type="text" readonly value='.$ls->commentaire.'></p>
                    </row>
                    <row>
                        <p>Libelle de l\'Article :<input type="text" readonly maxlength="48" value='.$la->libelle.'> </p>
                        <p>Prix Total Achat :<input type"text" readonly value ='.($ls->prix) *($ls->qte).'></p>
                        <p>Id Achat: <input type="text" readonly maxlength="24" value='.$ls->idMouv.'> </p>                       
                    </row>
                </bloc>';
   }
   $return=$return.'</div></div>';
   return $return;
  }
  
/* ************************************************************************************************************************************* */
/* ************************************************FIN*LISTE*DES*ACHATS****DEBUT*AJOUT*ACHAT******************************************** */
/* ************************************************************************************************************************************* */
  
  
  public function ajoutAchat()
  {
      $lesFournisseurs= $this->vpdo->listeSocietesFournisseurs();
      $lesArticles= $this->vpdo->listeArticles();
      $idMouv = $this->vpdo->idDernierMouvement()->idMouv+1;
      $return='<div class="conteneur border div-liste div-liste-entreprises">
                    <p style="margin-left: 1em">
                        Voici l\'outil de gestion des Achats.</b><br>
                        Vous pouvez sur cette page ajouter un achat, pour ce faire, remplissez les cases ci dessous et cliquez sur <b>" Confirmer achat"</b>.<br>                        
                    </p> ';
      $return = $return.'
                <bloc>
                   	<row>
                        <p>Id Achat: <input type="text" id="idAchat" readonly maxlength="24" required value="'.$idMouv.'"></p>
                        <p>Date de l\'Achat : <input type="date" id="date"  required maxlength="12" value=""></p>
                        <p>Id Article : <select id="idArticle"><option selected hidden disabled></option>';
  while($f = $lesArticles->fetch(PDO::FETCH_OBJ))
  {
      $return = $return.'<option value="'.$f->idArticle.'">'.$f->idArticle.'</option>';
  }
                        
  $return = $return.'</select></p>              
                   </row>
                    <row>
                        <p>Prix Unitaire : <input type="text" id="prix"  maxlength="64" required value=""></p>
                        <p>Quantité : <input type="text" id="qte"  maxlength="12" required value=""></p>
                        <p>id Fournisseur : <select id="idFour"><option selected hidden disabled></option>';
                        while($f = $lesFournisseurs->fetch(PDO::FETCH_OBJ))
                        {
                              $return = $return.'<option value="'.$f->idSociete.'">'.$f->idSociete.' - '.$f->nom.'</option>';
                        }
                         $return = $return.'</select></p></row>        
                        <p>Commentaire : <input type="text" id="commentaire"  required  value=""></p>
                        <a onclick=\'ajouterachat()\' class="btn-classique">
                        <span class="tooltip" id="ttInsertAchatInfo" title="Vous n\'avez pas rempli toutes les informations !"></span>
                        Confirmer achat</a>
                </bloc>';
      
      $return=$return.'</div></div>';
      return $return;
  }
  
/* ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// */
/* ************************************************************************************************************************************* */
/* ***********************************************************FIN*AJOUT*ACHAT*********************************************************** */
/* ************************************************************************************************************************************* */
/* ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// */
    
/* ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// */
/* ************************************************************************************************************************************* */
/* *******************************************************DEBUT*GESTION*LIVRAISON******************************************************* */
/* ************************************************************************************************************************************* */
/* ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// */

    public function listeLivraisons()   
    {
        $return='<div class="conteneur border div-liste div-liste-entreprises">
                    <p style="margin-left: 1em">
                        Voici l\'outil de gestion des Livraisons.<br>
                        Voici si dessous la totalité des préparation en attente de livraison<br>
                        Vous pouvez accèdez aux livraisons déjà livré en cliquant sur <b>"Voir Historique"</b><br>
                        <a onclick=\'gestionHistorique()\' class="btn-classique"  id="btn-historique" style="display:block;">Voir Historique</a>
                        <a onclick=\'gestionHistorique()\' class="btn-classique" id="btn-encours" style="display:none;">Voir les livraisons à faire</a>
                        <br><b>Listes des livraisons :</b>
                    </p><div id="encours" style="display:block;"> ';
        $llaf = $this->vpdo->listeLivraisonsAFaire();
        $llf = $this->vpdo->listeLivraisonsFaite();
         
        while($ll = $llaf->fetch(PDO::FETCH_OBJ))
        {
            $vpi = $this->vpdo->venteParSonId($ll->idVente);
            $dateP = $this->vpdo->arrondirDate($vpi->datePrepa);
            $return = $return.'
                    <bloc>
                       	<row>
                            <p>Id de la Vente :<input type="text" readonly maxlength="12" value='.$ll->idVente.'></p>
                            <p>Date de la préparation :<input type="text" readonly maxlength="12" value="'.$dateP.'"></p>
                            <a href="enCours/'.$ll->idVente.'" id="btn-voirDetail" class="btn-classique">Voir détails</a> 
                        </row>
                    </bloc>';
        }   
        $return=$return.' </div><div id="historique"  style="display:none;">';
        while($lf = $llf->fetch(PDO::FETCH_OBJ))
        {
          $vpi = $this->vpdo->venteParSonId($lf->idVente);
          $dateL = $this->vpdo->arrondirDate($vpi->dateLivraison);
          $ei=$this->vpdo->employeParSonId($lf->idEmploye);
          $return = $return.'
                <bloc>
                    <row>
                        <p>Id de la Vente :<input type="text" readonly maxlength="12" value='.$lf->idVente.'></p>
                        <p>Date de la livraison :<input type="text" readonly maxlength="12" value='.$dateL.'></p>
                        <p>Id Employé : <input type="text" readonly  value="'.$ei->idEmploye.' - '.$ei->nom.'"></p>
                        <a href="'.$lf->idVente.'" id="btn-voirDetail" class="btn-classique">Voir détails</a> 
                    </row>
                </bloc>';
        }
        $return=$return.'</div></div>';
        return $return;
    }
  
  /* ************************************************************************************************************************************* */
  /* ***************************************FIN*LISTE*DES*LIVRAISONS****DEBUT*DETAIL*LIVRAISON******************************************** */
  /* ************************************************************************************************************************************* */
  
  public function detailLivraisons($idVente)   
  {    
       $vpi = $this->vpdo->venteParSonId($idVente);
       if($vpi != null)
       {
       $return='<div class="conteneur border div-liste div-liste-details-livraison">
                    <p style="margin-left: 1em">
                        Voici l\'outil de détail de la livraison n°'.$idVente.'.<br>
                        Si dessous, voici la liste des articles pour cette Vente.<br>
                    </p><bloc id="encours" style="display:block;"> ';

       $ldval = $this->vpdo->listeDetailsLivraisonParIdVente($idVente);
       $vpi = $this->vpdo->venteParSonId($idVente);
       $ep= $this->vpdo->listeDetailsLivraisonParIdVente($idVente)->fetch(PDO::FETCH_OBJ)->idEmploye;
       $epsi=$this->vpdo->employeParSonId($ep);
       $pttcu=$this->vpdo->totalCommandeParIdVente($idVente);
       $dateL = $this->vpdo->arrondirDate($vpi->dateLivraison);
       $return=$return.'
        <bloc>
            <row>
                <p>Date de la livraison : <input type="text" readonly maxlength="12" value='.$dateL.'> </p>
                <p>Employe responsable : <input type="text" readonly maxlength="12" value="'.$epsi->idEmploye.' - '.$epsi->nom.' '.$epsi->prenom.'"></p>
                <p>Prix total TTC: <input type="text" readonly maxlength="12" value='.$pttcu->prixTotal.'>€</p>
            </row>
        </bloc>
';
       while($ll = $ldval->fetch(PDO::FETCH_OBJ))
          {
              $return = $return.'
        <bloc>
           	<row>
                <p>id Article :<input type="text" readonly maxlength="12" value='.$ll->idArticle.'></p>
                <p>Quantité demandée :<input type="text" readonly maxlength="12" value='.$ll->qteDemandee.'></p>
                <p>Quantité fournie :<input type="text" readonly maxlength="12" value='.$ll->qteFournie.'></p>
            </row>
            <row>                        
                <p>Id de la Vente :<input type="text" readonly maxlength="12" value='.$idVente.'></p>
                <p>Observation :<input type="text" readonly maxlength="12" value='.$ll->observation.'></p>
            </row>
        </bloc>';
          }   

      // signature
          $return=$return.'<p>Signature : <p></div>';
       }
       else
       {
           $return = '<div class="conteneur">Cette vente n\'existe pas !</div>';
       }
      return $return;     
  }
  
  public function detailLivraisonsEnCours($idVente)//Nicolas, géré reliquat, géré signature
  {   
      $return='<div class="conteneur  div-liste-entreprises">
                    <p style="margin-left: 1em">
                        Voici l\'outil de détail de la livraison n°'.$idVente.'.<br>
                        Si dessous, voici la liste des articles pour cette Vente.<br>
                        Cette livraison n\'a pas encore été livré.
                    </p><bloc id="encours" style="display:block;"> ';
  $ldval = $this->vpdo->listeDetailsLivraisonParIdVente($idVente);
  $se=$_SESSION['idEmploye'];
  $de = $this->vpdo->employeParSonId($se);
  $d=$this->vpdo->laDateAujourdhui();
  $date = $this->vpdo->arrondirDate($d);
  $return=$return.'<p>Date de la livraison : <input type="text" id="dateLivraison" readonly required  maxlength="10" value="'.$date.'">
                   Id de la Vente :<input type="text" readonly id="idVente" maxlength="12" value='.$idVente.'>
                   Id de l\'employe :<input type="text" readonly id="idEmploye" maxlength="12" value='.$se.'>
                   Nom et prenom de l\'employe :<input type="text" readonly id="idVente" maxlength="12" value="'.$de->nom.' '.$de->prenom.'"></p>
                   <br><p><b> Liste des articles de la commande :</b></p>';
  while($ll = $ldval->fetch(PDO::FETCH_OBJ))
  {
      $return = $return.'
                        <bloc>
                           	<row>
                                <p>id Article :<input type="text" id="idArticle" readonly maxlength="12"  value='.$ll->idArticle.'></p>
                                <p>Quantité demandée :<input type="text" id="qteDemandee" readonly maxlength="12" value='.$ll->qteDemandee.'></p>
                                <p>Quantité fournie(*) :<input type="text" id="qteFournie" maxlength="12" required value='.$ll->qteDemandee.'></p>                               
                            </row>
                        </bloc>'; 
  }
  $return=$return.'             </bloc>
                                <a onclick=\'ajouterlivraison()\' class="btn-classique">
                                <span class="tooltip" id="ttInsertAchatInfo" title="Vous n\'avez pas rempli toutes les informations !"></span>
                                Confirmer la livraison</a>
                                </bloc></div>';
  return $return;// <p> block signature (canvas->Trello)</p>
  }
/* ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// */
/* ************************************************************************************************************************************* */
/* *********************************************************FIN*GESTION*LIVRAISON******************************************************* */
/* ************************************************************************************************************************************* */
/* ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// */

/* ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// */
/* ************************************************************************************************************************************* */
/* *********************************************************DEBUT*GESTION*EMPLOYE******************************************************* */
/* ************************************************************************************************************************************* */
/* ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// */
  
  public function listeEmploye()//Nicolas, modification ne focntionne pas
  {
      $return='<div class="conteneur border div-liste div-liste-devis">
                    <p style="margin-left: 1em">
                        Voici l\'outil de gestion des employés.<br>
                        Voici si dessous la totalité des employés de l\'entreprise.<br>
                        <a href="ajouteremploye" id="btn-confirmerModifEntreprise" class="btn-classique">Ajouter un employé</a>
                        <br><b>Listes des Employés :</b>
                    </p><bloc id="encours" style="display:block;"> ';
      $les = $this->vpdo->listeEmployes();
      
      while($le = $les->fetch(PDO::FETCH_OBJ))
      {
          $te = $this->vpdo->typeEmployeParSonId($le->idType);
          $return = $return.'
                <bloc>
                    <row>
                        <p>Id de l\'employe :<input type="text" readonly maxlength="12" value='.$le->idEmploye.'></p>
                        <p>Identifiant :<input type="text" readonly maxlength="12" value='.$le->identifiant.'></p>
                        <p>Nom :<input type="text" readonly maxlength="12" value='.$le->nom.'></p>
                    </row> 
                    <row>   
                        <p>Prénom: <input type="text" readonly  value="'.$le->prenom.'"></p>
                        <p>Type:<input type="text" readonly maxlength="12" value="'.$le->idType.' - '.$te->libelle.'"></p>
                        <p>Adresse(*) :<input type="text"  id="adresse'.$le->idEmploye.'" maxlength="48" value="'.$le->adresse.'"></p>                       
                    </row>                      
                    <row>   
                        <p>Téléphone(*) : <input type="text" id="telephone'.$le->idEmploye.'" maxlength="10"  value="'.$le->telephone.'"></p>
                        <p>Mail(*) : <input type="text"  id="mail'.$le->idEmploye.'" value="'.$le->mail.'"></p>
                        <a onclick="modifieremploye('.$le->idEmploye.')" class="btn-classique">
                        <span class="tooltip" id="ttUpdateEmploye'.$le->idEmploye.'" title="Vous n\'avez pas rempli toutes les informations !"></span>
                        Confirmer Modification</a>
                    </row>
                </bloc>';
      }
      $return=$return.'</bloc></div>';
      return $return;
  }
 
  
  /* ************************************************************************************************************************************* */
  /* **************************************FIN*LISTE*DES*EMPLOYES****DEBUT*AJOUT*EMPLOYE******************************************** */
  /* ************************************************************************************************************************************* */
  
  
  public function ajouterEmploye()
  {   $lesTypesEmployes= $this->vpdo->listeTypeEmploye();
      $iE=$this->vpdo->idDernierEmploye()->idEmploye+1;
      // select 1/2/3/4 pour le type
      $return='<div class="conteneur border div-liste div-liste-devis">
                    <p style="margin-left: 1em">
                        Vous pouvez sur cette page, ajouter un nouvel employé.<br>
                        Veulliez remplir les champs obligatoire (marqué d\'un " <b>*</b> ").<br>
                        Attention, vous devez obligatoirement rentrer une des informations pour contacter l\'employé. 
                        Soit le mail, l\'adresse ou le téléphone.<br>
                        Si vous avez fini d\'entrer les informations cliquez sur <b>"Ajouter employé"</b>
                    </p><bloc id="encours" style="display:block;"> ';
      $return = $return.'
                <bloc>
                    <row>
                        <p>Id de l\'employe :<input type="text" readonly maxlength="12" id="idEmploye" value='.$iE.'></p>
                        <p>Nom(*)  :<input type="text maxlength="12" id="nom" value=""></p>
                        <p>Prénom(*): <input type="text" id="prenom" value=""></p>
                    </row>
                    <row>
                        <p>Type(*)  :<select id="idType"><option selected hidden disabled></option>';
                        while($tE = $lesTypesEmployes->fetch(PDO::FETCH_OBJ))
                        {
                              $return = $return.'<option value="'.$tE->idType.'">'.$tE->idType.' - '.$tE->libelle.'</option>';
                        }
                         $return = $return.'</select>
                        <p>Téléphone  : <input type="text" maxlength="10" id="telephone" value=""></p>
                        <p>Mail  : <input type="text"  id="mail" value=""></p> 
                    </row>                       
                    <row>  
                        <p>Mots de passe  : <input type="password" id="mdp" value=""></p>    
                        <p>Adresse:<input type="text"  maxlength="48" id="adresse" value=""></p>
                        <a onclick="ajouteremploye()" class="btn-classique">
                        <span class="tooltip" id="ttInsertEmploye" title="Vous n\'avez pas rempli toutes les informations !"></span>
                        Ajouter employé</a>
                    </row>
                </bloc>';
  $return=$return.'</bloc></div>';
  return $return;
  }
 
/* ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// */
/* ************************************************************************************************************************************* */
/* *******************************************************FIN*GESTION*EMPLOYE*********************************************************** */
/* ************************************************************************************************************************************* */
/* ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// */
  
  
    public function afficheListeArticles()
    {
        $lesArticles = $this->vpdo->listeArticles();                
        $retour = '
                <div class="conteneur div-liste div-liste-articles">';
        
        $retour = $retour.'
                    <p>Voici la liste des articles enregistrés dans la base de données.</p>
        ';
        $i = 1;
        while($a = $lesArticles->fetch(PDO::FETCH_OBJ))
        {
            $f = $this->vpdo->familleParSonId($a->idFam);
            $qteR = $this->vpdo->qteReelleArticleParSonId($a->idArticle);
            $retour = $retour.'
    <bloc>
        <row>
            <p>ID Article : <input id="idArticle" value="'.$a->idArticle.'" readonly></p>
            <p>Code-barre : <input id="barreArticle" value="'.$a->codeBarre.'" readonly></p>
        </row>
        <row>
            <p>Dénomination : <input id="libArticle" value="'.$a->libelle.'" readonly></p>
            <p>Famille : <input id="famArticle" value="'.$f->libelle.'" readonly></p>
        </row>
        <row>
            <p>Quantité réelle disponible : <input id="qteArticle" type="number" value='.$qteR.' readonly></p>
        </row>
        <row>
            <a class="btn-classique" href="'.$a->idArticle.'">Détails article</a>
        </row>
    </bloc>
                ';
          $i++;  
        }
        $retour = $retour.'
                </div>';
        return $retour;
    }
    
    public function afficheDetailsArticle($idArticle)
    {
        $retour='';
        $a = $this->vpdo->articleParSonId($idArticle);
        if($a!=null)
        {            
        $em = $this->vpdo->emplacementParSonId($a->idEmp);
        $lesFamilles = $this->vpdo->listeFamilles();        
        $lesMouvements = $this->vpdo->listeMouvementsParArticle($idArticle);
        $lesEmplacements = $this->vpdo->listeEmplacements();
        $lesTypes = $this->vpdo->listeTypesMouvements();
        $lesFournisseurs = $this->vpdo->listeSocietesFournisseurs();
            $retour = '
        <div class="conteneur border">
            <div id="details-infos-article">
                <row>
                    <p>Nom Article : <input id="nomArticle" type="text" value="'.$a->libelle.'"></p>
                    <p>ID Article : <input id="idArticle" type="text" value="'.$a->idArticle.'" readonly></p>
                </row>
                <row>
                    <p>Famille Article : <select id="famArticle">';
                while($lesF = $lesFamilles->fetch(PDO::FETCH_OBJ))
                {
                    $retour = $retour.'
                        <option value="'.$lesF->idFam.'"';
                    
                    //Si l'id de la famille de l'article en détail = l'id de la famille actuellement ajoutée au select
                    if($lesF->idFam == $a->idFam)
                        $retour = $retour." selected"; //On la choisit par défaut.
                    
                    $retour = $retour.'>'.$lesF->idFam.' - '.$lesF->libelle.'</option>';
                }    
                
                $retour = $retour.'</select></p>
                    <p>Code à barres : <input id="codeBarre" type="" value="'.$a->codeBarre.'"></p>
                    <p id="details-info-article-localisation">Localisation : <select id="empArticle">';
                    while($lesE = $lesEmplacements->fetch(PDO::FETCH_OBJ))
                    {
                        $retour = $retour.'
                        <option value="'.$lesE->idEmp.'"';
                        
                        //Si l'id de l'emplacement de l'article en détail = l'id de l'emplacement actuellement ajoutée au select
                        if($lesE->idEmp == $a->idEmp)
                            $retour = $retour." selected"; //On la choisit par défaut.
                            
                            $retour = $retour.'>'.$lesE->idEmp.' - '.$lesE->libelle.'</option>';
                    }
                    
                    $retour = $retour.'</select></p>
                </row>
                <row>
                    <a id="modifierInfos" class="btn-classique">Modifier les informations</a>
                </row>
            </div>
    
            <div id="onglets-details-article">
                <div id="on-cmup" active=true>Calcul CMUP</div>
                <div id="on-mouv">Entrées/Sorties de stock</div>
            </div>
    
            <div id="div-onglets-details-article">
                <div id="div-on-cmup">
                    <row>
                        <p>Marge % minimale : <input type="number" value="'.(100*$a->txMarge).'" readonly></p>
                        <p>Marge supplémentaire : <input id="margeSup" type="number" value=0></p>
                        <p>TVA % : <input type="number" value="'.(100*$a->txTVA).'" readonly></p>
                        <p>Coût Unitaire Moyen Pondéré (CMUP) actuel : <input id="cmupActuel" type="number" value="'.$a->dernierCMUP.'" readonly></p>
                        <p>Prix de Vente TTC : <input type="number" value="'.round($a->dernierCMUP * (1+$a->txMarge) * (1+$a->txTVA),2).'" readonly></p>
                    </row>
                    <row>
                        <p>Nouveau CMUP calculé : <input id="nouveauCMUP" type="number" readonly></p>
                        <a id="modifierArticle" class="btn-classique">Etablir un nouveau CMUP</a>
                    </row>
                </div>
    
                <div id="div-on-mouv">
                    <row>';
                if($lesMouvements->rowCount() == 0)
                {
                    $retour = $retour.'<tr><td>Il n\'y a aucun mouvement à afficher.</td></tr>';
                }
                else
                {
                    $retour = $retour.'           
                        <table>
                            <tr><th>Id Mouvement</th>
                            <th>Type</th>
                            <th>Fournisseur</th>
                            <th>Date</th>
                            <th>Prix unité</th>
                            <th>Quantité</th>
                            <th>Observations</th>
                        </tr>';
                }            
                //Tant qu'il y a des lignes mouvement, on les affiche.
                while($m = $lesMouvements->fetch(PDO::FETCH_OBJ))
                {                
                    $retour = $retour.'
                    <tr>
                        <td>'.$m->idMouv.'</td>
                        <td>'.$m->idType.'</td>
                        <td>'.$m->idSociete.'</td>
                        <td>'.$m->date.'</td>
                        <td>'.$m->prix.'</td>
                        <td>'.$m->qte.'</td>
                        <td>'.$m->commentaire.'</td>
                    </tr>
                ';   
                }
                            
                //On ajoute une ligne vide qui servira de champs d'ajout.
                $retour = $retour. '
                    <tr>
                        <td><input id="idMouv" type="number" value="'.(1+$this->vpdo->idDernierMouvement()->idMouv).'" readonly></td>
                        <td><select id="typeMouv">';
                while($t = $lesTypes->fetch(PDO::FETCH_OBJ))//Select pour les types de mouvements
                {
                    $retour = $retour.'
                                <option value="'.$t->idType.'">'.$t->libelle.'</option>';
                }
                $retour = $retour.'                  
                            </select></td>          
                        <td><select id="idFour">';
                while($f = $lesFournisseurs->fetch(PDO::FETCH_OBJ))//Select pour les entreprises.
                {
    
                    $s = $this->vpdo->societeParSonId($f->idSociete);
                    $retour = $retour.'
                                <option value="'.$f->idSociete.'">'.$f->nom.'</option>';
                }
                $retour = $retour.'                  
                            </select></td>          
                        <td><input id="dateMouv" type="text" value="'.$this->vpdo->laDateAujourdhui().'" readonly></td>
                        <td><input id="prixMouv" type="number" value=0></td>
                        <td><input id="qteMouv" type="number" value=0></td>
                        <td><input id="commentaire" type="text"></td>
                    </tr>';
                
                $retour = $retour.'
                            </table>
                        </row>
                        <row id="div-qtes">
                            <h4>Quantités disponibles</h4>
                            <p><span>Virtuelle :</span><input id="virtuel" type="number" readonly></p>
                            <p><span>Réelle :</span><input id="reel" type="number" readonly></p>
                        </row>
                    <a id="ajouterMouv" class="btn-classique">Ajouter un mouvement</a>
                    </div>
                </div>';               
            $retour = $retour.'
            </div>';
        }
        else
            $retour=$retour.'<p>Cet article n\'existe pas !</p>';
        return $retour;
    }    
        
    public function informationsEntreprise()
    {
        return $this->vpdo->infosSociete();
    }
    public function employeConnecte($id)
    {
        return $this->vpdo->employeParSonId($id);
    }
    public function formeTelephone($tel)
    {
        $retour ='';
        for($i=0;$i<10;$i=$i+2)
        {
            $retour = $retour.substr($tel,$i, 2);
            $retour = $retour.' ';
        }
        return $retour;
    }

    
    
    
    
    
    public function genereFacture($idV)
    {
        $s = $this->informationsEntreprise();
        $v = $this->vpdo->venteParSonId($idV);
        if($v!=null)
        {                
            $s = $this->informationsEntreprise();
            $c = $this->vpdo->clientParSonId($v->idClient);
            $sc = $this->vpdo->societeParSonId($c->idSociete);
            $details = $this->vpdo->listeDetailsFactureParIdVente($idV);
            $pdf = new PDF_Invoice( 'P', 'mm', 'A4' );
            $pdf->AddPage();
            $pdf->addSociete($s->nom,
                $s->adresse, $s->logo);
            $pdf->fact_dev("Vente ", $idV);
            $pdf->temporaire($s->nom);
            $pdf->addDate($this->vpdo->arrondirDate($v->dateFacture));
            $pdf->addClient($v->idClient);
            $pdf->addPageNumber("1");
            $pdf->addClientAdresse($sc->adresse);
            $cols=array( "REFERENCE"    => 25,
                "DESIGNATION"  => 72,
                "QUANTITE"     => 20,
                "P.U. HT"      => 20,
                "MONTANT H.T." => 28,
                "TVA"          => 11,
                "REMISE"       => 14
            );
            $pdf->addCols( $cols);
            $cols=array( "REFERENCE"    => "L",
                "DESIGNATION"  => "L",
                "QUANTITE"     => "C",
                "P.U. HT"      => "R",
                "MONTANT H.T." => "R",
                "TVA"          => "C",
                "REMISE"       => "C" );
            $pdf->addLineFormat($cols);
            $pdf->addLineFormat($cols);
            $tot_prods = array();
            $y = 109;//Position de base de la première ligne, s'incrémente en fonction du nombre de lignes.
            while($d = $details->fetch(PDO::FETCH_OBJ))
            {
                $a = $this->vpdo->articleParSonId($d->idArticle);
                $line = array(
                    "REFERENCE"    => $d->idArticle,
                    "DESIGNATION"  => $a->libelle,
                    "QUANTITE"     => $d->qteDemandee,
                    "P.U. HT"      => $d->CMUP * (1+$d->marge),
                    "MONTANT H.T." => $d->qteDemandee * $d->CMUP * (1+$d->marge),
                    "TVA"          => $d->tva * 100,
                    "REMISE"       => $d->txRemise);
                $size = $pdf->addLine( $y, $line );
                $y   += $size + 2;
                $tab_tva = array( $d->tva * 100 => $d->tva * 100);
                
                //Ajoute dans le tableau tot_prods le prix et la qte et la tva d'un article, pour chaque article.
                array_push($tot_prods, (array("px_unit" => $d->CMUP * (1+$d->marge), "qte" => $d->qteDemandee, "tva" => $d->tva * 100)));
            }
            $pdf->addCadreTVAs();
            
            $params  = array(
                "RemiseGlobale" => 1,
                "remise_tva"     => 1,       // {la remise s'applique sur ce code TVA}
                "remise"         => 20,       // {montant de la remise}
                "remise_percent" => 10,      // {pourcentage de remise sur ce montant de TVA}
                "FraisPort"     => 0,
                "AccompteExige" => 0,
                "accompte"         => 0,     // montant de l'acompte (TTC)
                "accompte_percent" => 15,    // pourcentage d'acompte (TTC)
                "Remarque" => "" );
            
            $pdf->addTVAs( $params, $tab_tva, $tot_prods);
            $pdf->addCadreEurosFrancs();
            $pdf->Output();
        }
    }
}


?>
