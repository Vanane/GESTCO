<?php

class page_base {
	protected $right_sidebar;
	protected $left_sidebar;
	protected $titre;
	protected $js=array();
	protected $css=array('perso','base', 'modele');
	protected $page;
	protected $metadescription="Site de gestion de plateforme logistique à destination du GRETA.";
	protected $metakeyword=array('logistique','greta','gestion','commande' );
	protected $path="http://localhost/GESTCO/";
	
	public function __construct() {
		$numargs = func_num_args();
		$arg_list = func_get_args();
        if ($numargs == 1) {
			$this->titre=$numargs[0];
		}
	}

	public function __set($propriete, $valeur) {
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
			default:
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
			echo "<link rel='stylesheet'  href='".$this->path."css/".$s.".css' />\n";
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
	/****************************** Affichage de la partie entÃªte ***************************************/	
	protected function afficheEntete() {
		echo'
           <header>
				
				<a href="'.$this->path.'Accueil"><img  id="img-greta" src="'.$this->path.'image/logo.png" alt="logo"/></a><br>
				<h1 id="titre-gestco">
					GestCo
				</h1>
				<h3>
					<strong>Bienvenue</strong> sur l\'application web de gestion de commandes du GRETA.
				</h3>
             </header>
		';
	}
	/****************************** Affichage du menu ***************************************/	
	
	protected function afficheMenu() {		
	    $lesMenus=array(	        
	        'ventes'=>'
				<ul>
					<li><a href="'.$this->path.'Ventes">Ventes</a></li>
                </ul>',
	        
	        'achats'=>'
				<ul>
					<li><a href="'.$this->path.'Achats">Achats</a></li>
                </ul>',
	        
	        'devis'=>'
				<ul>
					<li><a href="'.$this->path.'Devis">Devis</a></li>
                </ul>',
	        
	        'commandes'=>'
				<ul>
					<li><a  href="'.$this->path.'Commandes">Commandes</a></li>
				</ul>',
	        
	        'preparations'=>'
				<ul>
					<li><a  href="'.$this->path.'Preparations">Préparations</a></li>
				</ul>',
	        
	        'livraisons'=>'
                <ul>
                    <li><a href="'.$this->path.'Livraisons">Livraisons</a></li>
                </ul>',
	        
	        'facturations'=>'
                <ul>
                    <li><a href="'.$this->path.'Facturations">Facturations</a></li>
                </ul>',
	        
		    'conflits'=>'
				<ul>
    				<li><a  href="'.$this->path.'Conflits">Conflits</a></li>
				</ul>',		    
		    
	        'articles'=>'
				<ul>
					<li><a  href="'.$this->path.'Articles">Articles</a></li>
                </ul>',
	        
	        'employes'=>'
				<ul>
					<li><a  href="'.$this->path.'Employes">Employés</a></li>
                </ul>',
	        
	        'clients'=>'
				<ul>
					<li><a  href="'.$this->path.'Clients">Clients</a></li>
                </ul>',
	        
	        'fournisseurs'=>'
				<ul>
					<li><a  href="'.$this->path.'Fournisseurs">Fournisseurs</a></li>
                </ul>',
	        
	        'societes'=>'
				<ul>
					<li><a  href="'.$this->path.'Societes">Sociétés</a></li>
                </ul>'
	    );      	       
	        
		if(!(isset($_SESSION['id']) && isset($_SESSION['type'])))
		{
		    echo'
				<ul id="bou-connexion">
					<li><a href="'.$this->path.'Connexion">Connexion</a></li>
				</ul>';		  
		} 
		else
		{
		    echo '
              <ul id="bou-deconnexion">
		        <li><a href="'.$this->path.'Deconnexion">Déconnexion</a></li>
		      </ul>';		    		   
		    switch($_SESSION['type'])
		    {
		        case 1:   //Cas utilisateur est commercial
		            echo $lesMenus['ventes'].$lesMenus['achats'].$lesMenus['articles'].$lesMenus['clients'].$lesMenus['fournisseurs'];
		            break;
		        case 2:   //Cas utilisateur est préparateur 
		            echo $lesMenus['preparations'].$lesMenus['articles'];
		            break;
		        case 3:   //Cas utilisateur est livreur
		            echo $lesMenus['livraisons'].$lesMenus['clients'].$lesMenus['articles'];
		            break;
		        case 4:   //Cas utilisateur est informaticien
		            foreach($lesMenus as $key => $value)
		            {
		                echo $value;
		            }
		            break;
		    }
		}
	}
	
	
	public function afficheEnteteMenu() {
		echo '
		<div class="menu_horizontal">
				';
						
	}
	public function afficheFooterMenu(){
		echo '
		</div>';

	}

		/****************************************** remplissage affichage colonne ***************************/
	public function afficheBlocContact() {
		return'

			
				<article>
					<h3>GRETA de Loire-Atlantique</h3>
										<p>16 Rue Dufour</br>
										44000 Nantes</br>
										Tel : 02 40 14 56 56</br>
                                        </p>									
										<a  href="Contact" class="button">Contact</a>
                </article>
				';
							
	}
	
	/*************************** retourne sous-menu de la page vente selon le rôle ***************************/
	
	public function afficheSousMenuVente()
	{
	    return '
                <div class="sous-menu menu_horizontal">
                    <p>Choisissez une option</p>
                    <ul>
                        <li><a href="'.$this->path.'Ventes/Devis">Devis</a></li>
                    </ul>
                    <ul>
                        <li><a href="'.$this->path.'Ventes/Commandes">Commandes</a></li>
                    </ul>
                    <ul>
                        <li><a href="'.$this->path.'Ventes/Facturations">Facturations</a></li>
                    </ul>
                    <ul>
                        <li><a href="'.$this->path.'Ventes/Conflits">Reliquats</a></li>
                    </ul>
                </div>';
	}
	/************************************** Affiche la page ajout devis ************************************/
	
	/****************************************** Affichage du pied de la page ***************************/
	private function afficheFooter() {
		echo '
		<!-- Footer -->
			<footer>
				<p></p>
				<p id="copyright">
                GRETA de Loire-Atlantique - 16 Rue Dufour,  44000 Nantes - Tel : 02 40 14 56 56									
				<a href="https://www.greta-paysdelaloire.fr">GRETA Nantes</a> 
				</p>
            </footer>
		';
	}

	
	/********************************************* Fonction permettant l'affichage de la page ****************/

	public function affiche() {
		
		
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
						<?php $this->afficheEnteteMenu(); ?>
						<?php $this->afficheMenu(); ?>
						<?php $this->afficheFooterMenu(); ?>
						
  						<div style="clear:both;">
    						<div class="left_sidebar">
     							<?php echo $this->left_sidebar; ?>
    						</div>
    						<div class="right_sidebar" >
								<?php echo $this->right_sidebar;?>
    						</div>
  						</div>
						<div style="clear:both;">
							<?php $this->afficheFooter(); ?>
						</div>
					</div>
				</body>
			</html>
		<?php
	}

}

?>
