<?php

class page_base {
	protected $right_sidebar;
	protected $left_sidebar;
	protected $titre;
	protected $js=array('jquery-3.3.1.min','bootstrap.min');
	protected $css=array('perso','bootstrap.min','base', 'modele');
	protected $page;
	protected $metadescription="Site de gestion de plateforme logistique à destination du GRETA.";
	protected $metakeyword=array('logistique','greta','gestion','commande' );

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
	private function affiche_style() {
		foreach ($this->css as $s) {
			echo "<link rel='stylesheet'  href='css/".$s.".css' />\n";
		}

	}
	/******************************Gestion du javascript **********************************************/
	/* Insertion  js */
	private function affiche_javascript() {
		foreach ($this->js as $s) {
			echo "<script src='js/".$s.".js'></script>\n";
		}
	}
	/******************************affichage metakeyword **********************************************/

	private function affiche_keyword() {
		echo '<meta name="keywords" content="';
		foreach ($this->metakeyword as $s) {
			echo utf8_encode($s).',';
		}
		echo '" />';
	}	
	/****************************** Affichage de la partie entÃªte ***************************************/	
	protected function affiche_entete() {
		echo'
           <header>
				
				<a href="Accueil"><img  id="img-greta" src="image/logo.png" alt="logo"/></a><br>
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
	
	protected function affiche_menu() {
		echo '
				<ul >
					<li ><a href="Accueil" >Accueil</a></li>
				</ul>';
	}
	protected function affiche_menu_connexion() {
		
		if(!(isset($_SESSION['id']) && isset($_SESSION['type'])))
		{	
			echo '
	           		<ul >
	               		<li><a  href="TestConnexion">Test Connexion</a></li>
		          	</ul>
					<ul id="bou-connexion">
						<li><a href="Connexion">Connexion</a></li>
					</ul>';
		} 
		else
		{
			echo '
					<ul >
						<li><a  href="Ventes">Les Ventes</a></li>
					</ul>
					<ul >
						<li><a  href="Preparation">Les Commandes</a></li>
					</ul>
					<ul >
						<li><a  href="Articles">Les Articles</a></li>
					</ul>
					<ul id="bou-deconnexion">
						<li><a href="Deconnexion">Déconnexion</a></li>
					</ul>';
		}
	}
	public function affiche_entete_menu() {
		echo '
		<div id="menu_horizontal">
				';
						
	}
	public function affiche_footer_menu(){
		echo '
		</div>';

	}

		/****************************************** remplissage affichage colonne ***************************/
	public function rempli_right_sidebar() {
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
	
	/****************************************** Affichage du pied de la page ***************************/
	private function affiche_footer() {
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
					
					<?php $this->affiche_keyword(); ?>
					<?php $this->affiche_javascript(); ?>
					<?php $this->affiche_style(); ?>
				</head>
				<body>
				<div class="global">

						<?php $this->affiche_entete(); ?>
						<?php $this->affiche_entete_menu(); ?>
						<?php $this->affiche_menu(); ?>
						<?php $this->affiche_menu_connexion(); ?>
						<?php $this->affiche_footer_menu(); ?>
						
  						<div style="clear:both;">
    						<div class="left_sidebar">
     							<?php echo $this->left_sidebar; ?>
    						</div>
    						<div class="right_sidebar" >
								<?php echo $this->right_sidebar;?>
    						</div>
  						</div>
						<div style="clear:both;">
							<?php $this->affiche_footer(); ?>
						</div>
					</div>
				</body>
			</html>
		<?php
	}

}

?>
