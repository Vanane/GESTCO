<?php

class page_base {
    
    //Attributs de la classe
	protected $right_sidebar;
	protected $left_sidebar;
	protected $footer;
	protected $titre;
	protected $js=array('jquery-3.3.1.min', 'tooltipster.main.min', 'tooltipster.bundle.min', 'jquery.dataTables.min', 'dataTables.responsive.min');
	protected $css=array('base', 'tooltipster.main.min', 'tooltipster.bundle.min', 'fpdf.css', 'jquery.dataTables.min', 'responsive.dataTables.min');
	protected $page;
	protected $metadescription="Site de gestion de plateforme logistique à destination du GRETA.";
	protected $metakeyword=array('logistique','greta','gestion','commande');
	protected $path="http://192.168.168.187/GESTCO/";
	protected $entreprise;
	protected $user;
	
	public function __construct() {
	//Constructeur
		$numargs = func_num_args();
		$arg_list = func_get_args();
        if ($numargs == 1) {
			$this->titre=$numargs[0];
		}
	}

	public function __set($propriete, $valeur) {
	//Setters. Fonction propre à PHP
		switch ($propriete) {
			case 'css' : {
				$this->css[count($this->css)+1] = $valeur;
				break;
			}
			case 'js' : {
				$this->js[count($this->js)+1] = $valeur;
				break;
			}
			case 'metakeyword' : {
				$this->metakeyword[count($this->metakeyword)+1] = $valeur;
				break;
			}
			case 'titre' : {
				$this->titre = $valeur;
				break;
			}
			case 'metadescription' : {
				$this->metadescription = $valeur;
				break;
			}
			case 'right_sidebar' : {
				$this->right_sidebar = $this->right_sidebar.$valeur;
				break;
			}
			case 'left_sidebar' : {
			    $this->left_sidebar = $this->left_sidebar.$valeur;
			    break;
			}
			case 'footer' : {
			    $this->footer = $this->footer.$valeur;
			    break;
			}
			case 'entreprise' :
			    $this->entreprise = $valeur;
			    break;
			default:
			case 'user':
			    $this->user = $valeur;
			    break;
			{
				$trace = debug_backtrace();
				trigger_error(
            'Propriété non-accessible via __set() : ' . $propriete .
            ' dans ' . $trace[0]['file'] .
            ' à la ligne ' . $trace[0]['line'],
            E_USER_NOTICE);

				break;
			}

		}
	}
	public function __get($propriete) {
    //Setters. Fonction propre à PHP
	    
		switch ($propriete) {
			case 'titre' :
				{
					return $this->titre;
					break;
				}
				default:
			{
				$trace = debug_backtrace();
        trigger_error(
            'Propriété non-accessible via __get() : ' . $propriete .
            ' dans ' . $trace[0]['file'] .
            ' à la ligne ' . $trace[0]['line'],
            E_USER_NOTICE);

				break;
			}
				
		}
	}
	/******************************Gestion des styles **********************************************/
	/* Insertion des feuilles de style */
	private function afficheStyle() {
		foreach ($this->css as $s) {
		   //echo "<link rel='stylesheet'  href='".$this->path."css/".$s.".css' />\n";
		    echo "<link rel='stylesheet'  href='".$this->path."css/".$s.".css'/>\n";
		}

	}
	/******************************Gestion du javascript **********************************************/
	/* Insertion  js */
	private function afficheJavascript() {
		foreach ($this->js as $s) {
			echo "<script src='".$this->path."js/".$s.".js'></script>\n";
		}
	}
	/******************************affichage metakeyword **********************************************/

	private function afficheKeyword() {
		echo '<meta name="keywords" content="';
		foreach ($this->metakeyword as $s) {
			echo utf8_encode($s).',';
		}
		echo '" />';
	}	
	/****************************** Affichage de la partie entete ***************************************/	
	protected function afficheEntete() {
	//Affiche avec echo l'en-tête du site, le logo, le titre.
		echo'
           <header>				
				<a href="'.$this->path.'Accueil"><img  id="img-greta" src="'.$this->path.'image/logo.png" alt="logo"/></a>
				<h1 id="titre-entreprise">
                   <br>
					Application GESTCO
				</h1>
				<h3>
					Bienvenue sur l\'application web de gestion de commandes GESTCO.
				</h3>
             </header>
		';
	}
	
	
	/****************************** Affichage en-tête du menu ***************************************/
	
	
	
	/****************************** Affichage du menu ***************************************/	
	
	
	protected function afficheMenuConnexion()
	//Echo le menu de connexion : bouton Connexion si déconnecté, bouton Déconnexion si connecté.
	{
	    $connecte = false;//Retourne si un user est connecté. On considère de base que non.
	    echo '<div id="bandeau_connexion" class="menu_horizontal">';
	    if(isset($_SESSION['id']) && isset($_SESSION['type']))
	    //Si les variables sessions id et type sont existantes
	    {	     
	        echo '
            <ul id="btn-deconnexion">
                <li><a href="'.$this->path.'Deconnexion">Déconnexion</a></li>
            </ul>
            <p id="infoUser" style="float:right">Vous êtes connecté en tant que : '.$this->user->prenom.' '.$this->user->nom.'</p>
            ';
	        $connecte = true;//Connecté = vrai
	    }
	    else
	    //Sinon
	    {	        
	        echo '
				<ul id="btn-connexion">
					<li><a href="'.$this->path.'Connexion">Connexion</a></li>
				</ul>';	        
	    }
	    echo '</div>';
	    return $connecte;
	}
	
	
	
	protected function afficheMenuVentes()
	//Menu de gauche. Selon le rôle de la personne connectée, stockée dans la SESSION 'type', on affiche
	//Ou non certains liens dans le menu.
	{
	    echo '<div class="menu_horizontal">';
	    $lesMenus=array(
	        'titre'=>'
                <h4>Ventes</h4>',
	        'devis'=>'
                <ul>
        	        <li><a href="'.$this->path.'Ventes/Devis/">Devis</a></li>
    	        </ul>',
	        'commandes'=>'
    	        <ul>
    	            <li><a href="'.$this->path.'Ventes/Commandes/">Commandes</a></li>
    	        </ul>',
	        'preparations'=>'
				<ul>
					<li><a  href="'.$this->path.'Ventes/Preparations/">Préparations</a></li>
				</ul>',
	        'livraisons'=>'
                <ul>
                    <li><a href="'.$this->path.'Ventes/Livraisons/">Livraisons</a></li>
                </ul>',
	        'factures'=>'
                <ul>
    	            <li><a href="'.$this->path.'Ventes/Facturations/">Facturations</a></li>
    	        </ul>',
	        'reliquats'=>'
                <ul>
    	            <li><a href="'.$this->path.'Ventes/Reliquats/">Reliquats</a></li>
    	        </ul>'
	    );
	    switch($_SESSION['type'])
	    {
	        case 1:   //Cas utilisateur est commercial
	            echo $lesMenus['titre'].
	            $lesMenus['devis'].
	            $lesMenus['commandes'].
	            $lesMenus['factures'].
	            $lesMenus['reliquats'];
	            break;
	        case 2:   //Cas utilisateur est préparateur
	            echo $lesMenus['titre'].$lesMenus['preparations'];
	            break;
	        case 3:   //Cas utilisateur est livreur
	            echo $lesMenus['titre'].$lesMenus['livraisons'];
	            break;
	        case 4:   //Cas utilisateur est informaticien
	            foreach($lesMenus as $key => $value)
	            {
	                echo $value;
	            }
	            break;
	    }
	    echo '
		</div>';
	}
	
	
	protected function afficheMenuAchats()
	//Menu de droite. Identiquement à celui de gauche, on affiche les liens en fonction
	//Du role du connecté.
	{
	    echo '<div class="menu_horizontal">';
	    $lesMenus=array(
	        'titre'=>'
                <h4>Achats</h4>',	        
	        'achats'=>'
                <ul>
        	        <li><a href="'.$this->path.'Achats/">Achats</a></li>
    	        </ul>'
	    );
	    switch($_SESSION['type'])
	    {
	        case 1:   //Cas utilisateur est commercial
	            echo $lesMenus['titre'].$lesMenus['achats'];
	            break;
	        case 4:
	            foreach($lesMenus as $key => $value)
	            {
	                echo $value;
	            }
	            break;
	    }
	    echo '
		</div>';
	}
	
	
	protected function afficheMenuAutres()
	//Menu du milieu, identique aux deux autres.
	{
	    echo '<div class="menu_horizontal">';
	    $lesMenus=array(
	        'titre'=>'
                <h4>Gestion</h4>',	       
	        'articles'=>'
				<ul>
					<li><a  href="'.$this->path.'Articles/">Articles</a></li>
                </ul>',
	        'clients'=>'
				<ul>
					<li><a  href="'.$this->path.'Clients/">Clients</a></li>
                </ul>',
	        
	        'fournisseurs'=>'
				<ul>
					<li><a  href="'.$this->path.'Fournisseurs/">Fournisseurs</a></li>
                </ul>',
	        
	        'employes'=>'
				<ul>
					<li><a  href="'.$this->path.'Employes/">Employés</a></li>
                </ul>'
	        
	    );
	    
	    switch($_SESSION['type'])
	    {
	        case 1:   //Cas utilisateur est commercial
	            echo $lesMenus['titre'].
	            $lesMenus['articles'].
	            $lesMenus['clients'].
	            $lesMenus['fournisseurs'];
	            break;
	        case 2:   //Cas utilisateur est préparateur
	            echo $lesMenus['titre'].
	            $lesMenus['articles'];
	            break;
	        case 3:   //Cas utilisateur est livreur
	            echo $lesMenus['titre'].
	            $lesMenus['clients'];
	            break;
	        case 4:   //Cas utilisateur est informaticien
	            foreach($lesMenus as $key => $value)
	            {
	                echo $value;
	            }
	            break;
	    }
	    echo '
		</div>';
	}
	
	
	protected function afficheMenu()
	//Permet de regrouper les fonctions de menu en une seule, et de les exécuter si
	//L'utilisateur est connecté.
	{
	    if($this->afficheMenuConnexion() != false)
	    {
	        $this->afficheMenuVentes();
	        echo '<div class="separation_menu"></div>';
	        $this->afficheMenuAutres();
	        echo '<div class="separation_menu"></div>';	        
	        $this->afficheMenuAchats();
	        
	    }
	}

    /******************Fonction permettant d'afficher le footer et les informations de l'entreprise******************/
	
	public function afficheFooter()
	//Footer de la page, avec l'adresse, le numero, le site internet de l'entreprise.
	{
	    echo'
				<p id="copyright">
                '.$this->entreprise->nom.' - '.$this->entreprise->adresse.' - Tel : '.$this->entreprise->telephone.'
				 - <a href="'.$this->entreprise->siteWeb.'">Site Internet</a>
				</p>
		';
	}
	
	/********************************************* Fonction permettant l'affichage de la page ****************/

	public function affiche() {
	//Permet d'appeler toutes les méthodes au-dessus pour afficher
	//Chacune à leur tour leur contenu en fonction des données, 
	//Imbriqués dans la page HTML.
		
		?>
			<!DOCTYPE html>
			<html lang='fr'>
				<head>
					<title><?php echo $this->titre; ?></title>
					<meta http-equiv="content-type" content="text/html; charset=utf-8" />
					<meta name="description" content="<?php echo $this->metadescription; ?>" />
					
					<?php $this->afficheKeyword(); ?>
					<?php $this->afficheJavascript(); ?>
					<?php $this->afficheStyle(); ?>
				</head>
				<body>
					<div class="global">
							<?php $this->afficheEntete(); ?>
						<div class="bloc_menus">						
							<?php $this->afficheMenu(); ?>
						</div>
						<script>location.hash="#content"</script>
  						<div style="clear:both;">
    						<div id="content" class="left_sidebar">
     							<?php echo $this->left_sidebar; ?>
    						</div>
    						<div class="right_sidebar" >
								<?php echo $this->right_sidebar;?>
    						</div>
  						</div>
						<div style="clear:both;">
							<div class="footer">
								<?php $this->afficheFooter(); ?>
							</div>
						</div>
					</div>
				</body>
			</html>
		<?php
	}

}

?>
