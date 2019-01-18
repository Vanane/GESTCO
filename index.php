
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
			$site->titre='Connexion';
			$site-> right_sidebar=$site->afficheBlocContact();
			$site-> left_sidebar=$controleur->formulaireLogin();
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
			
		case 'ventes' :
		    $site->js = "ajoutLigneDevis";
		    $site->left_sidebar = $site->afficheSousMenuVente();		    
		    if(isset($params[2]))
		    {
    		    switch($params[2])
    		    {
    		        case 'devis' :
    		            if($controleur->estConnecte() == 1 || $controleur->estConnecte() == 4 || $controleur->estConnecte() != false)
    		            {
    		                if(isset($params[3]))
    		                {
    		                    if($params[3] == "ajouter")
    		                        $site->left_sidebar = $site->afficheAjoutDevis();
    		                    else 
                                        $site->left_sidebar =$controleur->detailsDevis($params[3]);
    		                }
    		                else
    		                {
    		                    $site-> left_sidebar=$controleur->listeDevis();
    		                }
    		            }
    		            else
    		            {
    		                $site-> left_sidebar= "Vous n'êtes pas connecté !";    		                    		               
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
		    $site->affiche();
		    break;		    
		case 'articles':
		    $site->left_sidebar = 'Page articles';
		    $site->left_sidebar = ' <select id="blbl">
                                         <option value="KEY">VALUE</option>
                                    </select>';
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