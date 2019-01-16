
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
			$site-> right_sidebar=$site->rempli_right_sidebar();
			$site-> left_sidebar="<p>Ce programme vous est proposé par le GRETA.</p>";
			$site->affiche();

			break;
		case 'connexion' :
			$site->titre='Connexion';
			$site->js='jquery.validate.min';
			$site->js='messages_fr';
			$site->js='jquery.tooltipster.min';
			$site->css='tooltipster';
			$site-> right_sidebar=$site->rempli_right_sidebar();
			$site-> left_sidebar=$controleur->retourne_formulaire_login();
			$site->affiche();
			break;			
		case 'confirmation':
		    $site->titre='Confirmation';
		    $site->left_sidebar=$controleur->confirmation_login($_POST['login'],$_POST['pwd']);
		    break;
		case 'deconnexion' :
			$_SESSION=array();
			session_destroy();
			echo '<script>document.location.href="Accueil"; </script>';
			break;
		case 'devis':
		    $site-> left_sidebar=$controleur->listeDevis();

		    $site->affiche();    
		    break;
		case 'commandes':
		    $site->left_sidebar = 'Page commandes';
		    $site->affiche();
		    break;
		case 'preparations':
		    $site->left_sidebar = 'Page préparations';
		    $site->affiche();
		    break;
		case 'livraisons':
		    $site->left_sidebar = 'Page livraisons';
		    $site->affiche();
		    break;
		case 'facturations':
		    $site->left_sidebar = 'Page facturations';
		    $site->affiche();
		    break;
		case 'conflits':
		    $site->left_sidebar = 'Page conflits';
		    $site->affiche();
		    break;
		    
		case 'articles':
		    $site->left_sidebar = 'Page articles';
		    $site->affiche();
		    break;
		    
		default: 
			$site->titre='Accueil';
			$site-> right_sidebar=$site->rempli_right_sidebar();
			$site-> left_sidebar='<p id="p-404">Erreur 404 : page non trouvée.</p>';
			$site->affiche();
			break;
	}	
	
?>