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
    
    
    
    public function laDateAujourdhui()
    {
        return $this->connexion->query("SELECT NOW() as now")->fetch(PDO::FETCH_OBJ)->now;
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
     
    public  function listeArticles()
    {
        
        $requete='SELECT * FROM ARTICLE ;';        
        $result=$this->connexion->query($requete);
        if ($result)
            return ($result);
        else
            return null;
    }
    public  function listeContactFournisseurs()
    {
        $requete='SELECT * FROM contact_FOURNISSEUR ;';        
        $result=$this->connexion->query($requete);
        if ($result)
            return ($result);
        else
            return null;
    }
    
    public function listeSocietesFournisseurs()
    {
        $requete='SELECT * FROM societe WHERE idSociete IN(SELECT idSociete FROM contact_fournisseur);';        
        $result=$this->connexion->query($requete);
        if ($result)
            return ($result);
        else
            return null;
    }
    
    
    public function listeTypesMouvements()
    {
        $requete='SELECT * FROM type_mouvement;';        
        $result=$this->connexion->query($requete);
        if ($result)
            return ($result);
        else
            return null;
    }
    
    
    public  function listeContactClientsParID($id)
    {       
        $requete='SELECT c.* FROM Societe s, contact_client c WHERE s.idSociete=c.idSociete AND s.idSociete='.$id.' ;';        
        $result=$this->connexion->query($requete);
        if ($result)
            return ($result);
        else
            return null;
    }
    public  function listeSocieteFournisseurs()
    {
        
        $requete='SELECT s.* FROM Societe s, contact_FOURNISSEUR c WHERE s.idSociete=c.idSociete ;';        
        $result=$this->connexion->query($requete);
        if ($result)
            return ($result);
        else
            return null;
    }
    public  function listeSocieteClients()
    {
        
        $requete='SELECT s.* FROM Societe s, contact_client c WHERE s.idSociete=c.idSociete;';        
        $result=$this->connexion->query($requete);
        if ($result)
            return ($result);
        else
            return null;
    }
    public  function listeSociete()
    {        
        $requete='SELECT * FROM Societe';        
        $result=$this->connexion->query($requete);
        if ($result)
            return ($result);
        else
            return null;
    }
    
    public function familleParSonId($id)
    {
        $q='SELECT * FROM famille_article WHERE idFam = "'.$id.'";';   
        $result=$this->connexion->query($q)->fetch(PDO::FETCH_OBJ);
        if ($result)
            return ($result);
        else
            return null;
    }
    
    
    public function listeMouvementsParArticle($id)
    {
        $q='SELECT * FROM mouvement_article WHERE idArticle = "'.$id.'";';
        $result=$this->connexion->query($q);
        if ($result)
            return ($result);
        else
            return null;
    }
    
    public function listeFamilles()
    {
        $q='SELECT * FROM famille_article;';
        $result=$this->connexion->query($q);
        if ($result)
            return ($result);
            else
                return null;
    }
    
    /*------------------------------------------------------------------------------------------------------------------*/
    /*---------------------------------------------------FIN-LISTE-DEBUT-DELETE-----------------------------------------*/
    /*------------------------------------------------------------------------------------------------------------------*/
/*
    public function deleteContactClient($id)
    {
        $r='DELETE FROM contact_client WHERE idClient="'.$id.'";';
        $result=$this->connexion->query($r);
        if($result)
            return $result;
            else
                return null;
    }
    
    public function deleteContactFournisseur($id)
    {
        $r='DELETE FROM contact_fournisseur WHERE idClient="'.$id.'"';
        $result=$this->connexion->query($r);
        if($result)
            return $result;
            else
                return null;
    }*/ 
            
    // delete impossible car les clients sont clé étrangère aux ventes.
    /*------------------------------------------------------------------------------------------------------------------*/
    /*---------------------------------------------------FIN-DELETE-DEBUT-INSERT----------------------------------------*/
    /*------------------------------------------------------------------------------------------------------------------*/
   
            public function insertMouvement($id, $type, $societe, $article, $date, $prix, $qte, $com)
    {
        $sql='INSERT INTO mouvement_article VALUES("'.$id.'","'.$type.'","'.$societe.'", "'.$article.'","'.$date.'","'.$prix.'", "'.$qte.'", "'.$com.'")';
        $result=$this->connexion->query($sql);
        return $result;
    }
    
    public function insertContactClient($id,$societe, $nom, $prenom, $mail, $telephone)
    {
        $sql='INSERT INTO contact_client VALUES("'.$id.'","'.$societe.'","'.$nom.'", "'.$prenom.'","'.$telephone.'","'.$mail.'")';
        $result=$this->connexion->query($sql);
        return $result;
    }
    
    public function insertSociete($id, $nom, $adresse, $telephone,$fax,$siteWeb, $raison, $mail)
    {
        $sql='INSERT INTO Societe VALUES("'.$id.'","'.$nom.'","'.$adresse.'", "'.$telephone.'","'.$fax.'","'.$siteWeb.'","'.$raison.'","'.$mail.'")';
        $result=$this->connexion->query($sql);
        return $result;
    }
   
    public function insertVente($idVente, $idClient, $idEmploye, $date)
    {
        $q = 'INSERT INTO vente VALUES ('.$idVente.',"'.$idClient.'", "'.$date.'", null, null, null, null);';
        $result = $this->connexion->query($q);
        return $result;
    }
    
    public function insertDetailDevis($idV, $idA, $idEm, $qte, $tx, $remise, $ttc, $obs)
    {
        $q = 'INSERT INTO detail_devis VALUES ('.$idV.',"'.$idA.'", "'.$idEm.'", "'.$qte.'", "'.$tx.'", "'.$remise.'", "'.$ttc.'", "'.$obs.'");';
        $result = $this->connexion->query($q);
        return $result;
    }

    
    public function insertDetailCommande($idV, $idA, $idEm, $qte, $tx, $remise, $ttc, $obs)
    {
        $q = 'INSERT INTO detail_commande VALUES ('.$idV.',"'.$idA.'", "'.$idEm.'", "'.$qte.'", "'.$tx.'", "'.$remise.'", "'.$ttc.'", "'.$obs.'");';
        $result = $this->connexion->query($q);
        return $result;
    }
    
  /* **************************************************************************************************************** */    
 /*--------------------------------------------------------------------------------------------------------------------*/
/*-------------------------------------------------------FIN-INSERT-----------------------------------------------------*/
 /*--------------------------------------------------------------------------------------------------------------------*/
  /* **************************************************************************************************************** */    
 /*--------------------------------------------------------------------------------------------------------------------*/
/*------------------------------------------------------DEBUT UPDATE----------------------------------------------------*/
 /*--------------------------------------------------------------------------------------------------------------------*/
  /* **************************************************************************************************************** */   

    
    public function updateDetailDevis($attr, $value)
    {
        $r='UPDATE detail_devis SET '.$attr.' = "'.$value.'";';
        $result=$this->connexion->query($r);
        if($result)
            return $result;
        else
            return null;
    }
    
    public function updateArticle($attr, $value)
    {
        $r='UPDATE article SET '.$attr.' = "'.$value.'";';
        $result=$this->connexion->query($r);
        if($result)
            return $result;
        else
            return null;                
    }

    /*public function updateClient($nom, $adresse,$telephone,$fax, $siteweb,$raison,$mail,$idSociete)
    {
 
        $r='UPDATE societe SET nom = "'.$nom.'",adresse="'.$adresse.'",telephone="'.$telephone.'", fax="'.$fax.'", siteweb="'.$siteweb.'" , raison="'.$raison.'", mail="'.$mail.'" WHERE idSociete="'.$idSociete.'";';
        $result=$this->connexion->query($r);
        if($result)
            return $result;
            else
                return null;
    } // méthode différentre pour un update*/
    
    public function TupdateClient($attr, $value,$lacondition,$estremplie)
    {
        $r='UPDATE societe SET '.$attr.' = "'.$value.'"WHERE '.$lacondition.' = "'.$estremplie.'";';
        $result=$this->connexion->query($r);
        if($result)
            return $result;
        else
            return null;
    }
    public function updateContactClient($attr, $value,$lacondition,$estremplie)
    {
        $r='UPDATE contact_client SET '.$attr.' = "'.$value.'"WHERE '.$lacondition.' = "'.$estremplie.'";';
        $result=$this->connexion->query($r);
        if($result)
            return $result;
            else
                return null;
    }
    

    public function updateVente($attr, $value)
    {
        $r='UPDATE vente SET '.$attr.' = "'.$value.'";';
        $result=$this->connexion->query($r);
        if($result)
            return $result;
            else
                return null;                
    }

/*------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------FIN UPDATE------------------------------------------------*/
/*------------------------------------------------------------------------------------------------------------------*/
    
    public function emplacementParSonId($id)
    {
        $r='SELECT * from emplacement WHERE idEmp = "'.$id.'";';
        $result=$this->connexion->query($r)->fetch(PDO::FETCH_OBJ);
        if($result)
            return $result;
            else
                return null;
    }
    
    
    public function articleParSonId($id)
    {
        $r='SELECT * from ARTICLE WHERE idArticle = "'.$id.'";';
        $result=$this->connexion->query($r)->fetch(PDO::FETCH_OBJ);
        if($result)
            return $result;
            else
                return null;
    }
    
    
    public function devisParSonId($idV, $idA)
    {
        $r='SELECT * from detail_devis WHERE idArticle = "'.$idA.'" AND idVente = "'.$idV.'";';
        $result=$this->connexion->query($r);
        if($result)
        {
            $result=$result->fetch(PDO::FETCH_OBJ);
            return $result;
        }
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
        $r='SELECT * from SOCIETE WHERE idSociete = '.$id.'';
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

    public function idDerniereVente()
    {
        $r="SELECT idVente FROM VENTE ORDER BY idVente DESC";
        $result=$this->connexion->query($r)->fetch(PDO::FETCH_OBJ);
        if($result)
            return $result;
            else
                return null;
    }
    
    public function idDernierContactClient()
    {
        $r="SELECT  idClient FROM contact_client ORDER BY idClient DESC";//MAX(idClient) si besoin 
        $result=$this->connexion->query($r)->fetch(PDO::FETCH_OBJ);
        if($result)
            return $result;
            else
                return null;
    }
    
    public function idDernierSociete()
    {
        $r="SELECT  idSociete FROM societe WHERE idSociete ORDER BY idSociete DESC";//MAX(idClient) si besoin
        $result=$this->connexion->query($r)->fetch(PDO::FETCH_OBJ);
        if($result)
            return $result;
            else
                return null;
    }
    
    public function idDernierMouvement()
    {
        $r="SELECT  idMouv FROM mouvement_article ORDER BY idMouv DESC";
        $result=$this->connexion->query($r)->fetch(PDO::FETCH_OBJ);
        if($result)
            return $result;
        else
            return null;
    }
    
    public function listeEmployes()
    {
        $r = "SELECT * FROM EMPLOYE";
        $result=$this->connexion->query($r);
        if($result)
            return $result;
            else
                return null;
    }
    
    public function listeEmployesParType($type)
    {
        $r = 'SELECT * FROM EMPLOYE WHERE idType='.$type.'';
        $result=$this->connexion->query($r);
        if($result)
            return $result;
        else
            return null;
    }
    
    public function listeEmplacements()
    {
        $r = 'SELECT * FROM emplacement;';
        $result=$this->connexion->query($r);
        if($result)
            return $result;
        else
            return null;                
    }
    
    public function listeClients()
    {
        $r = 'SELECT * FROM CONTACT_CLIENT';
        $result=$this->connexion->query($r);
        if($result)
            return $result;
            else
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
        $requete='SELECT s.* FROM detail_devis d, vente v, contact_client c, societe s WHERE d.idVente=v.idVente AND v.idClient= c.idClient  AND c.idSociete = s.idSociete AND v.idVente='.$idVente.'';
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
    
    public function qteTotaleArticleParSonId($id)
    {
        $requete='SELECT * FROM lesstocksdechaqueproduit WHERE idArticle = "'.$id.'";';
        $result=$this->connexion->query($requete)->fetch(PDO::FETCH_OBJ);
        if ($result)
            return ($result);
        return null;       
    }
 
}
?>
