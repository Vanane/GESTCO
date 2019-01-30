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
	    $types="fournisseurs";
	    $type="fournisseur";
	    $idType="idFour";
	    if($controleur->estConnecte()!= false)
	    {
			$site->js = "pageClient";
			if(isset($params[2]))
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
									$site->left_sidebar = $controleur->ajouterContact($params[3],$type);
									break;
							}
						}
						else
						$site-> left_sidebar=$controleur->listeContact($params[2],$types,$type, $idType);
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
	        $site-> left_sidebar= $controleur->afficheNonAcces();
	    }
	      break;
	    
	case 'clients':
	    $types="clients";
	    $type="client";
	    $idType="idClient";
	    if($controleur->estConnecte()!= false)
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
	case 'Achats':
	    if($controleur->estConnecte()!= false)
	    {
	    
	    }
	    break;
		
	case 'ventes' :
	    if($controleur->estConnecte() == 1 || $controleur->estConnecte() == 4)
	    {
	        $site->left_sidebar = $site->afficheSousMenuVente();	        
		    if(isset($params[2]))//Si l'adresse est /Ventes/xxx
		    {
    		    switch($params[2])
    		    {
    		        case 'devis' ://Si l'adresse est /Ventes/Devis
    		                if(isset($params[3]))
    		                {
    		                    switch($params[3])
    		                    {
    		                        case 'ajouter'://Si l'adresse est /Ventes/Devis/Ajouter
    		                            $site->js = "pageAjoutDevis";    		                            
                                        $site->left_sidebar = $controleur->afficheAjoutDevis();
    		                            break;
    		                        case 'confirmer': //Si /Confirmer   		                            
    		                            $site->left_sidebar = 'confirmation';
    		                            break;
    		                        default://Si autre
    		                                $site->js="pageDetailDevis";        		                               
    		                                $site->left_sidebar =$controleur->afficheDetailsDevis($params[3]);
    		                                break;
    		                  }
    		                }
    		                else
    		                {
    		                    $site->titre="Liste Devis";
    		                    $site-> left_sidebar=$controleur->afficheListeDevis();
    		                }
    		            break;
    		        case 'commande' :
    		            break;
    		        case 'livraison' :
    		            break;
    		        case 'facturation' :
    		            break;
    		        case 'conflit' :
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
	    
	case 'preparations':
	    if($controleur->estConnecte() == 2 || $controleur->estConnecte() == 4)
	    {
	        if(isset($params[2]))
	        {
	            switch($params[2])
	            {
	                default:
	                    $site->js = "pageDetailsPrepa";
	                    $site->left_sidebar = $controleur->afficheDetailsPreparation($params[2]);
	                    break;
	            }
	        }
	        else
	        {
	            $site->left_sidebar = $controleur->afficheListePreparations();
	        }
	    }
	    else
	    {
	        $site->left_sidebar = $controleur->afficheNonAcces();
	    }
	    break;
	default:
	    $site->titre='Accueil';
	    $site-> left_sidebar='<p id="p-404">Erreur 404 : page non trouvée.</p>';
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
}
$site->affiche();
?>