<?php
session_start();

include_once('class/autoload.php');
$site = new page_base();
$controleur=new controleur();
$request = strtolower($_SERVER['REQUEST_URI']);
$params = explode('/', trim($request, '/'));
$params = array_filter($params);



if (!isset($params[1]))
{
	$params[1]='accueil';
}
else 
{
    $site->right_sidebar='<a class="btn-classique btn-large" style="float:left" onclick="history.go(-1)">Retour</a>';	    
}	
switch ($params[1]) {
	case 'accueil' :
		$site->titre='Accueil';
		$site-> left_sidebar="<p>Ce programme vous est proposé par le GRETA.</p>";
		
		break;
	case 'connexion' :
	      if($controleur->estConnecte() == false)
	          {
		         $site->titre='Connexion';
		         $site-> left_sidebar=$controleur->formulaireLogin();
	          }
           else
              {
                 $site-> left_sidebar= "Vous êtes déjà connecté !";
              }
        
	    break;			
	case 'confirmation':
	      $site->titre='Confirmation';		   
	      $site->left_sidebar=$controleur->confirmationLogin($_POST['login'],$_POST['pwd']);
	      break;
	case 'deconnexion' :
		  $_SESSION=array();
		  session_destroy();
		  echo '<script>document.location.href="Accueil"; </script>';
		  break;
	case 'fournisseurs':
	    // j'initialise les trois variables dont j'ai besoin pour utiliser mes méthodes
	    $types="fournisseurs";//txt du type au pluriel
	    $type="fournisseur";//txt type au singulier
	    $idType="idFour";//txt de l'id dans la BDD
	    if($controleur->estConnecte()==1 || $controleur->estConnecte() == 4)//je vérifie si l'utilisateur est connecté, si il ne l'est pas j'affiche la méthode afficheNonAcces()
	    {
			$site->js = "pageClient";//j'utilise ma page js
			if(isset($params[2]))//je répartie mes pages, j'appelle les méthodes dont j'ai besoin.
			{	        
		
				switch($params[2])
				{
					case 'ajoutersociete':
						$site->titre = "Ajouter une Société";
						$site->left_sidebar = $controleur->ajouterSociete($type);
						break;
					case 'ajoutercontactsociete':
					    $site->titre = "Ajouter un Contact Fournisseur";
					    $site->left_sidebar = $controleur->ajouterContactSociete($type);
					    break;
					default:
						if(isset($params[3]))
						{
							switch($params[3])
							{
								default:
									$site->titre = "Ajouter un Contact Fournisseur";
									$site->left_sidebar = $controleur->ajouterContact($params[3],$type);//le param[3] qui me permet d'ajouter un contact pour la sociét voulu
									break;
							}
						}
						else
						$site-> left_sidebar=$controleur->listeContact($params[2],$types,$type, $idType);//même idée avec param[2], j'affiche les contacts de la soiciété qui possède l'idSociété=param[2]
						break;
				}
	        }

			else
			{
				$site-> left_sidebar=$controleur->listeSociete($types,$type,$idType);// j'affiche la liste de société, j'envoie les variables de type
			}	    
	    }
	    else
	    {
	        $site-> left_sidebar= $controleur->afficheNonAcces();
	    }
	      break;
	    
	case 'clients': // comme pour les fournisseurs (ci dessus, la seul difference est dans les variables qui ici sont client.
	    $types="clients";
	    $type="client";
	    $idType="idClient";
	    if($controleur->estConnecte()==1 || $controleur->estConnecte() == 4)
	    { $site->js = "pageClient";
	          if(isset($params[2]))
	          { 
	             
	              switch($params[2])
                 {
                 case 'ajoutersociete':
                    $site->titre = "Ajouter une Societe";
                    $site->left_sidebar = $controleur->ajouterSociete($type);
                    break;
                 case 'ajoutercontactsociete':
                     $site->titre = "Ajouter un Contact Client";
                     $site->left_sidebar = $controleur->ajouterContactSociete($type);
                     break;
                    default:
	                     if(isset($params[3]))
	                     {
	                        
	                         switch($params[3])
	                         {       
                                    default://Ajouter contact
                                    $site->titre = "Ajouter un Contact";                                        
                                    $site->left_sidebar = $controleur->ajouterContact($params[3],$type);
                                    break;
                            }
	                     }

	                     else
	                          {  
	                              $site-> left_sidebar=$controleur->listeContact($params[2],$types,$type, $idType);
	                          }
                         break;
	                 }
	          }
	          else
	          {
	              $site-> left_sidebar=$controleur->listeSociete($types,$type,$idType);
	          }
	          
	    }
         else
	    {
            $site->titre = "Erreur";
            $site-> left_sidebar= $controleur->afficheNonAcces();
	    }
	    break;
	case 'achats':
	    if($controleur->estConnecte()== 1|| $controleur->estConnecte() == 4)
	    {
	        $site->js = "pageAchat";
	        
	        if(isset($params[2]))
	        {
	              switch($params[2])
	              {
	                   case 'ajouterachat':
	                   $site->left_sidebar = $controleur->ajoutAchat();
	                   break;
	              }
	        }
	        else 
	        {
	            $site->left_sidebar = $controleur->listeAchats();
	        }
	    }
	    else 
	    {
	        $site->left_sidebar = $controleur->afficheNonAcces();
	    }
	    break;	
		
	case 'ventes' :
	    if($controleur->estConnecte() != false)
	    {
		    if(isset($params[2]))//Si l'adresse est /Ventes/xxx
		    {
    		    switch($params[2])
    		    {
    		        case 'devis' ://Si l'adresse est /Ventes/Devis
    		            if($controleur->estConnecte() == 1 || $controleur->estConnecte() == 4)
    		            {
    		                if(isset($params[3]))
    		                {
    		                    switch($params[3])
    		                    {
    		                        case 'ajouter'://Si l'adresse est /Ventes/Devis/Ajouter
    		                            $site->js = "pageAjoutDevisOuCommande";    		                            
    		                            $site->left_sidebar = $controleur->afficheAjoutDevisOuCommande();
    		                            break;
    		                        default://Si autre
    		                                $site->js="pageDetailDevis"; 
    		                                $site->titre = "Devis n°".$params[3];    		                                
    		                                $site->left_sidebar =$controleur->afficheDetailsDevisOuCommande($params[3],$params[2]);
    		                                break;
    		                  }
    		                }
    		                else
    		                {
    		                    $site->js = "pageListeDevis";
    		                    $site->titre="Liste Devis";
    		                    $site-> left_sidebar=$controleur->afficheListeDevis();
    		                }
    		            }
    		            else
    		            {
                            $controleur->afficheNonAcces();
    		            }
    		            break;
    		        case 'commandes' :
    		            if($controleur->estConnecte() == 1 || $controleur->estConnecte() == 4)
    		            {
        		            if(isset($params[3]))
        		            {
        		                switch($params[3])
        		                {
        		                    case 'ajouter'://Si l'adresse est /Ventes/Devis/Ajouter
        		                        $site->js = "pageAjoutDevisOuCommande";
        		                        $site->left_sidebar = $controleur->afficheAjoutDevisOuCommande();
        		                        break;
        		                    default://Si autre
        		                        $site->js="pageDetailsCommande";
        		                        $site->titre = "Commande n°".$params[3];
        		                        $site->left_sidebar =$controleur->afficheDetailsDevisOuCommande($params[3],$params[2]);
        		                        break;
        		                }
        		            }
        		            else
        		            {
        		                $site->titre="Liste Commandes";
        		                $site->js = "pageListeCommandes";
        		                $site->left_sidebar = $controleur->afficheListeCommandes();
        		            }
    		            }
    		            else
    		            {
    		                $controleur->afficheNonAcces();
    		            }
    		            break;
    		        case 'preparations':
    		            if($controleur->estConnecte() == 2 || $controleur->estConnecte() == 4)
    		            {
    		                if(isset($params[3]))
    		                {
    		                    switch($params[3])
    		                    {
    		                        default:
    		                            $site->js = "pageDetailsPrepa";
    		                            $site->left_sidebar = $controleur->afficheDetailsPreparation($params[3]);
    		                            break;
    		                    }
    		                }
    		                else
    		                {
    		                    $site->js = "pageListePrepa";
    		                    $site->left_sidebar = $controleur->afficheListePreparations();
    		                }
    		            }
    		            else
    		            {
    		                $site->left_sidebar = $controleur->afficheNonAcces();
    		            }
    		            break;
    		            
    		        case 'livraisons' :
    		            if($controleur->estConnecte() == 1 || $controleur->estConnecte() == 3 || $controleur->estConnecte() == 4 )
    		            {
    		                $site->js = "pageLivraisons";
    		                if(isset($params[3]))
    		                {
    		                    if (isset($params[4]))
    		                    {
    		                        switch($params[3])
    		                        {
    		                            default:
    		                                $site->left_sidebar = $controleur->detailLivraisonsEnCours($params[3]);
    		                                break;
    		                        }
    		                    }
    		                    else
    		                    {
    		                        switch($params[3])
    		                        {
    		                            default:
    		                                $site->left_sidebar = $controleur->detailLivraisons($params[3]);
    		                                break;
    		                        }
    		                    }
    		                }
    		                else
    		                {
    		                    $site->left_sidebar = $controleur->listeLivraisons();
    		                }
    		            }
    		            else
    		            {
    		                $site->left_sidebar = $controleur->afficheNonAcces();
    		            }
    		            
    		            break;
    		        case 'facturations' :
    		            if($controleur->estConnecte() == 1 || $controleur->estConnecte() == 4)
    		            {
    		                $site->left_sidebar="Page en construction";
    		            }
    		            else
    		            {
    		                $site->left_sidebar = $controleur->afficheNonAcces();
    		            }
    		            break;
    		        case 'reliquats' :
    		            if(isset($params[3]))
    		            {
    		                switch($params[3])
    		                {
    		                    default:
    		                        $site->js = "pageDetailsReliquat";
    		                        $site->left_sidebar = $controleur->afficheDetailsReliquat($params[3]);
    		                        break;
    		                }
    		            }
    		            else
    		            {
    		                $site->left_sidebar = $controleur->afficheListeReliquats();
    		            }
    		            break;		            		        
		        }
		    }
		    else
		    {
		        $site->titre = "Menu Ventes";
		    }
	    }
	    else
	    {
	        $site->left_sidebar = $controleur->afficheNonAcces();
	    }
	    break;	
	    
	case 'articles':
	    if($controleur->estConnecte()!= false)
	    {    		       
		    if(isset($params[2]))
		    {
		        switch($params[2])
		        {
                    case 'Ajouter':
                        $site->titre = "Ajouter un Article";
                        $site->left_sidebar = "Page Ajout Article";
                        break;
                    default:
                        $site->titre = "Details Article";
                        $site->js = "pageDetailsArticle";
                        $site->left_sidebar = $controleur->afficheDetailsArticle($params[2]);
                        break;
		        }
		    }
		    else
		    {
		        $site->titre = "Articles en stock";
		        $site->left_sidebar = $controleur->afficheListeArticles();
		    }
	    }
		else
			$site-> left_sidebar= "Vous n'êtes pas connecté !";		    
		break;
	case 'employes':
	    if($controleur->estConnecte() == 4 )
	    { 
	        $site->js = "pageEmploye";
	        if(isset($params[2]))
	        {            
	                $site->left_sidebar = $controleur->ajouterEmploye();	 
	        }
	        else
	        {
	            $site->left_sidebar = $controleur->listeEmploye();
	        }          
	    }
	    else
	    {
	        $site-> left_sidebar= "Vous n'êtes pas connecté !";
	    }
    break;
	default:
	    $site->titre='Accueil';
	    $site-> left_sidebar='<p id="p-404">Erreur 404 : page non trouvée.</p>';
	    break;
	    
}

if(isset($_SESSION['id']))
    $site->user = $controleur->employeConnecte($_SESSION['idEmploye']);
$site->entreprise = $controleur->informationsEntreprise();
    
    
$site->affiche();
?>