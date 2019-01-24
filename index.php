
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
	switch ($params[1]) {
		case 'accueil' :
			$site->titre='Accueil';
			$site-> right_sidebar=$site->afficheBlocContact();			
			$site-> left_sidebar="<p>Ce programme vous est proposé par le GRETA.</p>";
			$site->affiche();
			break;
		case 'connexion' :
		      if($controleur->estConnecte() == false)
		          {
			         $site->titre='Connexion';
			         $site-> right_sidebar=$site->afficheBlocContact();
			         $site-> left_sidebar=$controleur->formulaireLogin();
		          }
	           else
	              {
	                 $site-> left_sidebar= "Vous êtes déjà connecté !";
	              }
	        $site->affiche();
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
		      $site-> left_sidebar=$controleur->listeFournisseurs();
		      $site->affiche();
		      break;
		    
		case 'clients':
		    if($controleur->estConnecte()!= false)
		    {
		          if(isset($params[2]))
		          {
		              $site->js = "pageClient";
		              switch($params[2])
		                 {
		                 case 'ajouterclient':
		                     $site->left_sidebar = $controleur->ajouterSocieteCliente();
		                     break;
                         case 'modificationclient':
                             $site->left_sidebar = $controleur->ajouterSocieteCliente();
                             break;
                         case 'modificationcontact':
                             $site->left_sidebar = $controleur->ajouterSocieteCliente();
                             break;
                         case 'supprimercontact':
                             $site->left_sidebar = $controleur->ajouterSocieteCliente();
                             break;
                         case 'ajoutercontact':
                             $site->left_sidebar = $controleur->ajouterContact();
                             break;
		                 default:
		                     $site-> left_sidebar=$controleur->listeContactClients($params[2]);
                             break;
		                 }
		          }
		          else
		          {
		              $site-> left_sidebar=$controleur->listeClients();
		          }
		          $site->affiche();
		    }
             else
		    {
		          $site-> left_sidebar= "Vous n'êtes pas connecté !";
		    }
		    break;
		case 'Achats':
		    break;
		case 'ventes' :
		    if($controleur->estConnecte()!= false)
		    {
    		    $site->left_sidebar = $site->afficheSousMenuVente();		    
    		    if(isset($params[2]))
    		    {
        		    switch($params[2])
        		    {
        		        case 'devis' :
        		            if($controleur->estConnecte() == 1 || $controleur->estConnecte() == 4)
        		            {
        		                if(isset($params[3]))
        		                {
        		                    switch($params[3])
        		                    {
        		                        case 'ajouter':
        		                            $site->js = "pageAjoutDevis";    		                            
                                            $site->left_sidebar = $controleur->afficheAjoutDevis();
        		                            break;
        		                        case 'confirmer':    		                            
        		                            $site->left_sidebar = 'confirmation';
        		                            break;
        		                        default:
        		                            if(isset($params[4]))
        		                            {
        		                                $site->left_sidebar=$controleur->validationDevis();    		                                
        		                            }
        		                            else
        		                            {
        		                                $site->js="pageDetailDevis";        		                               
        		                                $site->left_sidebar =$controleur->detailsDevis($params[3]);
        		                            }
        		                            break;
        		                  }
        		                }
        		                else
        		                {
        		                    $site-> left_sidebar=$controleur->listeDevis();
        		                }
        		            }
        		            break;
        		        case 'commande' :
        		            break;
        		        case 'preparation' :
        		            break;
        		        case 'livraison' :
        		            break;
        		        case 'facturation' :
        		            break;
        		        case 'conflit' :
        		            break;		            		        
    		        }
    		    }
		    }
		    else
		    {
		        $site-> left_sidebar= "Vous n'êtes pas connecté !";
		    }
		    
		    $site->affiche();		    
		    break;		
		case 'articles':
		    $site->left_sidebar = 'Page articles';
		    $site->left_sidebar = ' <select id="blbl"><option value="KEY">VALUE</option></select>';
		    $site->right_sidebar = '<script>console.log(document.getElementById("blbl").options[document.getElementById("blbl").selectedIndex].text)</script>';
		    $site->affiche();
		    break;
		    
	    default: 
			$site->titre='Accueil';			
			$site-> left_sidebar='<p id="p-404">Erreur 404 : page non trouvée.</p>';
			$site->affiche();
			break;
	}		
?>