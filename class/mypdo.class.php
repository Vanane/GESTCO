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
    
    /*------------------------------------------------------------------------------------------------------------------*/
    /*---------------------------------------------------LES-LISTES-----------------------------------------------------*/
    /*------------------------------------------------------------------------------------------------------------------*/
    
    public  function listeDetailsDevis()
    {
        
        $requete='SELECT * FROM DETAIL_DEVIS;';
        
        $result=$this->connexion->query($requete);
        if ($result)
        {
            return ($result);
        }
        return null;
    }
     
    public  function listeArticle()
    {
        
        $requete='SELECT * FROM ARTICLE ;';
        
        $result=$this->connexion->query($requete);
        if ($result)
        {
            return ($result);
        }
        return null;
    }
    public  function listeContactFournisseurs()
    {
        
        $requete='SELECT * FROM contact_FOURNISSEUR ;';
        
        $result=$this->connexion->query($requete);
        if ($result)
        {
            return ($result);
        }
        return null;
    }
    public  function listeSocieteFournisseurs()
    {
        
        $requete='SELECT s.* FROM Societe s, contact_FOURNISSEUR c WHERE s.idSociete=c.idSociete ;';
        
        $result=$this->connexion->query($requete);
        if ($result)
        {
            return ($result);
        }
        return null;
    }
    public  function listeSocieteClients()
    {
        
        $requete='SELECT s.* FROM Societe s, contact_client c WHERE s.idSociete=c.idSociete ;';
        
        $result=$this->connexion->query($requete);
        if ($result)
        {
            return ($result);
        }
        return null;
    }
    
    /*------------------------------------------------------------------------------------------------------------------*/
    /*---------------------------------------------------FIN-LISTE-----------------------------------------------------*/
    /*------------------------------------------------------------------------------------------------------------------*/
    
    public function articleParSonId($id)
    {
        $r='SELECT * from ARTICLE WHERE idArticle = "'.$id.'";';
        $result=$this->connexion->query($r)->fetch(PDO::FETCH_OBJ);
        if($result)
            return $result;
            else
                return null;
    }
    
    public function venteParSonId($id)
    {
        $r='SELECT * from VENTE WHERE idVente = '.$id;
        $result=$this->connexion->query($r)->fetch(PDO::FETCH_OBJ);
        if($result)
            return $result;
            else
                return null;
    }
        
    public function clientParSonId($id)
    {
        $r='SELECT * from CONTACT_CLIENT WHERE idClient = "'.$id.'"';
        $result=$this->connexion->query($r)->fetch(PDO::FETCH_OBJ);
        if($result)
            return $result;
            else
                return null;
    }
    
    public function societeParSonId($id)
    {
        $r='SELECT * from SOCIETE WHERE idSociete = '.$id;
        $result=$this->connexion->query($r)->fetch(PDO::FETCH_OBJ);
        if($result)
            return $result;
            else
                return null;
    }
    
    public function employeParSonId($id)
    {
        $r='SELECT * from EMPLOYE WHERE idEmploye = '.$id;
        $result=$this->connexion->query($r)->fetch(PDO::FETCH_OBJ);
        if($result)
            return $result;
            else
                return null;
    }
    
    public  function listeComptes($identifiant,$mdp)
    {
        
        $requete='SELECT * FROM employe WHERE identifiant = "'.$identifiant.'" and mdp = "'.$mdp.'";';
        
        $result=$this->connexion->query($requete);
        if ($result)
        {
            return ($result);
        }
        return null;
    }
    
   
    public  function listeDetailsDevisParIdVente($idV)
    {
        
        $requete='SELECT * FROM DETAIL_DEVIS WHERE idVente="'.$idV.'";';
        
        $result=$this->connexion->query($requete);
        if ($result)
        {
            return ($result);
        }
        return null;
    }
    
    public function listeVentes()
    {
        $r="SELECT * from VENTE";
        $result=$this->connexion->query($r);
        if($result)
            return $result;
            else
                
                return null;
    }
    public  function listeVente() // Peut-^^etre supprimer l'une des deux ? ^^
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
        
        $requete='SELECT prixTotal FROM calculPrixTotal WHERE idVente ='.$idVente.'';
        $result=$this->connexion->query($requete);
        if ($result)
        {
            return ($result);
        }
        return null;
    }
 
}
?>
