<?php
class mypdo extends PDO{

    private $PARAM_hote='localhost'; // le chemin vers le serveur
    private $PARAM_utilisateur='root'; // nom d'utilisateur pour se connecter
    private $PARAM_mot_passe=''; // mot de passe de l'utilisateur pour se connecter
    private $PARAM_nom_bd='gestco';
    private $connexion;
    public function __construct() {
    	try {
    		
    		$this->connexion = new PDO('mysql:host='.$this->PARAM_hote.';dbname='.$this->PARAM_nom_bd, $this->PARAM_utilisateur, $this->PARAM_mot_passe,array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    		//echo '<script>alert ("ok connex");</script>)';echo $this->PARAM_nom_bd;
    	}
    	catch (PDOException $e)
    	{
    		echo 'hote: '.$this->PARAM_hote.' '.$_SERVER['DOCUMENT_ROOT'].'<br />';
    		echo 'Erreur : '.$e->getMessage().'<br />';
    		echo 'N� : '.$e->getCode();
    		$this->connexion=false;
    		//echo '<script>alert ("pbs acces bdd");</script>)';
    	}
    }
    public function __get($propriete) {
    	switch ($propriete) {
    		case 'connexion' :
    			{
    				return $this->connexion;
    				break;
    			}
    	}
    }
    
    public  function liste_compte($identifiant,$mdp)
    {
    
    	$requete='SELECT * FROM employe WHERE prenom = "'.$identifiant.'" and mdp = "'.$mdp.'";';
        
    	$result=$this->connexion->query($requete);
    	if ($result)    
    	{    
    		return ($result);
    	}
    	return null;
    }
    public  function listeVente()
    {
        
        $requete='SELECT idVente FROM VENTE';
        $result=$this->connexion->query($requete);
        if ($result)
        {
            return ($result);
        }
        return null;
    }
    public  function employeParIdVente($idVente)
    {
        
        $requete='SELECT d.idVente, e.idEmploye ,e.nom, e.prenom FROM detail_devis d, Employe e WHERE d.idEmploye=e.idEmploye AND idVente='.$idVente.'';
        $result=$this->connexion->query($requete);
        if ($result)
        {
            return ($result);
        }
        return null;
    }
    public  function entrepriseParIdVente($idVente)
    {
        
        $requete='SELECT s.nom, v.idVente, v.idClient, v.dateDevis FROM detail_devis d, vente v, contact_client c, societe s WHERE d.idVente=v.idVente AND v.idClient= c.idClient  AND c.idSociete = s.idSociete AND v.idVente='.$idVente.'';
        $result=$this->connexion->query($requete);
        if ($result)
        {
            return ($result);
        }
        return null;
    }
    public  function prixTotalParIdVente($idVente)
    {
        
        $requete='SELECT prixtotal FROM calcul_prix_total WHERE idVente ='.$idVente.'';
        $result=$this->connexion->query($requete);
        if ($result)
        {
            return ($result);
        }
        return null;
    }
 
}
?>
