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
	
	public function confirmation_login($login,$mdp)
	{
	    $login='Jack';
	    $mdp='098f6bcd4621d373cade4e832627b4f6';
	    if($this-> vpdo ->liste_compte($login,$mdp)!=null)
	    {
	        echo 'connect�';
	        session_start ();
	        // on enregistre les param�tres de notre visiteur comme variables de session ($login et $pwd) (notez bien que l'on utilise pas le $ pour enregistrer ces variables)
	        $_SESSION['login'] = $_POST['login'];
	        $_SESSION['pwd'] = $_POST['pwd'];
	        
	        // on redirige notre visiteur vers une page de notre section membre
	        header ('location: accueil.php');  
	    }
	    else {
	        // Le visiteur n'a pas �t� reconnu comme �tant membre de notre site. On utilise alors un petit javascript lui signalant ce fait
	        echo '<body onLoad="alert(\'Identifiant ou mots de passe incorrect ! \')">';
	        echo ' pas connect�';
	        // puis on le redirige vers la page d'accueil
	        echo '<meta http-equiv="refresh" content="0;URL=index.htm">';
	    }
	}
	   
	   
	public function retourne_formulaire_login()
	{
	    
	   return '    <form action="login.php" method="post">
    Votre login : <input type="text" name="login">
    <br />
    Votre mot de passe : <input type="password" name="pwd"><br />
    <input type="submit" value="Connexion">
    </form>';
	  
	}
	public function retourne_article($title)
	{
		
		$retour='<section>';
		$result = $this->vpdo->liste_article($title);
		if ($result != false) {
			while ( $row = $result->fetch ( PDO::FETCH_OBJ ) )
			// parcourir chaque ligne sélectionnée
			{
		
				$retour = $retour . '<article><h3>'.$row->h3.'</h3><p>'.$row->corps.'</p></article>';
			}
		$retour = $retour .'</section>';
		return $retour;
		}
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
