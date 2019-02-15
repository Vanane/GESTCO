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
    		echo 'N° : '.$e->getCode();
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
    /* --- Fonctions à part --- */
    
    public function laDateAujourdhui()
    {
        return $this->connexion->query("SELECT NOW() as now")->fetch(PDO::FETCH_OBJ)->now;
    }
    
    public function arrondirDate($d)
    //Procédure qui retourne une date sans le temps.
    {        
        $q = 'CALL arrondirDate("'.$d.'");';
        $result = $this->connexion->query($q);
        if($result)
        return $result->fetch(PDO::FETCH_OBJ)->date;
        else return null;
    }
    
    /*------------------------------------------------------------------------------------------------------------------*/
    /*---------------------------------------------------LES-LISTES-----------------------------------------------------*/
    /*------------------------------------------------------------------------------------------------------------------*/
    
   
    public  function listeLivraisons()
    {
        
        $requete='SELECT * FROM detail_livraison ORDER BY idVente DESC;';
        $result=$this->connexion->query($requete);
if ($result)
return $result->fetch(PDO::FETCH_OBJ);
else
return null;
    }
     
    public  function listeAjoutdeStock()
    {
        
        $requete='SELECT * FROM mouvement_article WHERE idType=1 ORDER BY date DESC;';
        $result=$this->connexion->query($requete);
        if ($result)
        {
            return ($result);
        }
        return null;
    }
    
    public  function listeTypeEmploye()
    {
        
        $requete='SELECT * FROM type_employe;';
        $result=$this->connexion->query($requete);
        if ($result)
        {
            return ($result);
        }
        return null;
    }
    
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
        $requete='SELECT * FROM societe WHERE idSociete IN(SELECT idSociete FROM contact_fournisseur GROUP BY idFour);';        
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
    
    
    public function listeTypesAction()
    {
        $requete='SELECT * FROM type_action;';
        $result=$this->connexion->query($requete);
        if ($result)
            return ($result);
        else
            return null;
    }
    
    
    public  function listeContactFournisseursParID($id){
        $requete='SELECT c.* FROM contact_FOURNISSEUR c, Societe s WHERE s.idSociete=c.idSociete AND s.idSociete='.$id.';';
        $result=$this->connexion->query($requete);
        if ($result)
        {
            return ($result);
        }
        return null;
    }
    public  function listeContactClientsParID($id){
        $requete='SELECT c.* FROM Societe s, contact_client c WHERE s.idSociete=c.idSociete AND s.idSociete='.$id.';';
        $result=$this->connexion->query($requete);
        if ($result)
            return ($result);
        else
            return null;
    }
    public  function listeSocietesClients()
    {
        
        $requete='SELECT s.* FROM Societe s, contact_client c WHERE s.idSociete=c.idSociete GROUP BY idSociete;';        
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
    
    
    
    public function listeVentes()
    {
        $r="SELECT * from vente";
        $result=$this->connexion->query($r);
        if($result)
            return $result;
            else
                return null;
    }
    
    public function listeVentesAvecDevis()
    {
        $r="SELECT * from vente WHERE idVente IN(SELECT DISTINCT idVente FROM detail_devis);";
        $result=$this->connexion->query($r);
        if($result)
            return $result;
            else
                return null;
    }
    
    public function listeVentesAvecCommande()
    {
        $r="SELECT * from vente WHERE idVente IN(SELECT DISTINCT idVente FROM detail_commande);";
        $result=$this->connexion->query($r);
        if($result)
            return $result;
            else
                return null;
    }
    
    public function listeVentesAvecFacture()
    {
        $r="SELECT * from vente WHERE idVente IN(SELECT DISTINCT idVente FROM detail_facture);";
        $result=$this->connexion->query($r);
        if($result)
            return $result;
            else
                return null;
    }
    
    
    public function listeVentesAvecReliquatResolus()
    {
        $r="SELECT DISTINCT v.* from vente v JOIN detail_reliquat d ON v.idVente = d.idVente WHERE d.typeAction IS NOT NULL;";
        $result=$this->connexion->query($r);
        if($result)
            return $result;
            else
                return null;
    }
    
    public function listeVentesAvecReliquatNonResolus()
    {
        $r="SELECT DISTINCT v.* from vente v JOIN detail_reliquat d ON v.idVente = d.idVente WHERE d.typeAction IS NULL;";
        $result=$this->connexion->query($r);
        if($result)
            return $result;
            else
                return null;
    }
    
    
    public function listePreparationsAFaire($idEmp)
    {
        $r="SELECT * FROM vente WHERE idVente IN (SELECT idVente FROM detail_preparation WHERE idEmploye IS NULL OR idEmploye = '".$idEmp."') AND datePrepa IS NULL;";
        $result=$this->connexion->query($r);
        if($result)
            return $result;
        else
            return null;
    }    
    
    public function listeLivraisonsAFaire()
    {
        $r="SELECT * FROM detail_livraison WHERE idEmploye is null GROUP BY idVente ;";
        $result=$this->connexion->query($r);
        if($result)
            return $result;
            else
                return null;
    }
    
    public function listeLivraisonsFaite()
    {
        $r="SELECT * FROM detail_livraison WHERE idEmploye is not null GROUP BY idVente ;";
        $result=$this->connexion->query($r);
        if($result)
            return $result;
            else
                return null;
    }
    
    public  function listeDetailsDevisParIdVente($idV)
    {
        $requete='SELECT * FROM detail_devis WHERE idVente="'.$idV.'";';
        
        $result=$this->connexion->query($requete);
        if ($result)
            return ($result);
        else
            return null;
    }

    
    public function listeDetailsDevisParArticle($idA)
    {
        $requete='SELECT * FROM detail_devis WHERE idArticle="'.$idA.'";';
        
        $result=$this->connexion->query($requete);
        if ($result)
            return ($result);
        else
            return null;
    }
    
    
    public function listeDetailsCommandeParIdVente($idV)
    {
        $requete='SELECT d.* FROM detail_commande d JOIN article a ON a.idArticle = d.idArticle WHERE d.idVente = "'.$idV.'" ORDER BY a.idEmp ASC;';
        
        $result=$this->connexion->query($requete);
        if ($result)
        {
            return ($result);
        }
        return null;
    }
    
    
    public function listeDetailsPreparationParIdVente($idV)
    {
        $requete='SELECT d.* FROM detail_preparation d JOIN article a ON a.idArticle = d.idArticle WHERE d.idVente = "'.$idV.'" ORDER BY a.idEmp ASC;';
        
        $result=$this->connexion->query($requete);
        if ($result)
            return ($result);
        else
            return null;
    }
    
    
    public function listeDetailsPreparationParArticle($id)
    {
        $requete='SELECT * FROM detail_preparation WHERE idArticle = "'.$id.'";';
        $result=$this->connexion->query($requete);
        if ($result)
            return ($result);
            else
                return null;
    }
        
    
    public function listeDetailsLivraisonParIdVente($idV)
    {
        $requete='SELECT * FROM detail_livraison WHERE idVente = "'.$idV.'";';
        $result=$this->connexion->query($requete);
        if ($result)
        {
            return ($result);
        }
        return null;
    }
    
    
    public function listeDetailsFactureParIdVente($idV)
    {
        $requete='SELECT * FROM detail_facture WHERE idVente = "'.$idV.'";';
        $result=$this->connexion->query($requete);
        if ($result)
        {
            return ($result);
        }
        return null;
    }
    
    
    public function listeDetailsReliquatParIdVente($idV)
    {
        $requete='SELECT * FROM detail_reliquat WHERE idVente = "'.$idV.'";';
        $result=$this->connexion->query($requete);
        if ($result)
        {
            return ($result);
        }
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
    public function insertContactFournisseur($id,$societe, $nom, $prenom, $mail, $telephone)
    {
        $sql='INSERT INTO contact_fournisseur VALUES("'.$id.'","'.$societe.'","'.$nom.'", "'.$prenom.'","'.$telephone.'","'.$mail.'")';
        $result=$this->connexion->query($sql);
        return $result;
    }
    
    public function insertSociete($id, $nom, $adresse, $telephone,$fax,$siteWeb, $raison, $mail)
    {
        $sql='INSERT INTO Societe VALUES("'.$id.'","'.$nom.'","'.$adresse.'", "'.$telephone.'","'.$fax.'","'.$siteWeb.'","'.$raison.'","'.$mail.'")';
        $result=$this->connexion->query($sql);
        return $result;
    }
   
    public function insertVente($idVente, $idClient)
    {
        $q = 'INSERT INTO vente VALUES ('.$idVente.',"'.$idClient.'", null, null, null, null, null);';
        $result = $this->connexion->query($q);
        return $result;
    }
    
    public function insertEmploye($idEmploye, $idType, $nom, $prenom, $adresse,$telephone, $mail, $mdp)
    {
        $q = 'INSERT INTO employe VALUES ('.$idEmploye.',"'.$idType.'","identifiant","' .$nom.'","' .$prenom.'", "'.$adresse.'","'.$telephone.'","' .$mail.'", "'.$mdp.'");';
        $result = $this->connexion->query($q);
        return $q;
    }
    
    public function insertDetailDevis($idV, $idA, $idEm, $qte, $tx, $cmup, $marge, $tva, $obs)
    {
        $q = 'INSERT INTO detail_devis VALUES ('.$idV.',"'.$idA.'", "'.$idEm.'", "'.$qte.'", "'.$tx.'", "'.$cmup.'", "'.$marge.'", "'.$tva.'", "'.$obs.'");';
        $result = $this->connexion->query($q);
        return $q;
    }
    
    
    public function insertDetailCommande($idV, $idA, $idEm, $qte, $tx, $cmup, $marge, $tva, $obs)
    {
        $q = 'INSERT INTO detail_commande VALUES ('.$idV.',"'.$idA.'", "'.$idEm.'", "'.$qte.'", "'.$tx.'", "'.$cmup.'", "'.$marge.'", "'.$tva.'", "'.$obs.'");';
        $result = $this->connexion->query($q);
        return $q;
    }
    
    public function insertDetailPreparation($idV, $idA, $idEm, $qteD, $qteF, $tx, $cmup, $marge, $tva, $obs)
    {
        $q = 'INSERT INTO detail_preparation VALUES ('.$idV.',"'.$idA.'", '.$idEm.', '.$qteD.', '.$qteF.', '.$tx.', '.$cmup.', '.$marge.', '.$tva.', "'.$obs.'");';
        $result = $this->connexion->query($q);
        return $q;
    }
       public function insertDetailLivraison($idV, $idA, $idEm, $qteD, $qteF, $tx, $cmup, $marge, $tva, $obs)
    {
        $q = 'INSERT INTO detail_livraison VALUES ('.$idV.',"'.$idA.'", '.$idEm.', "'.$qteD.'","'.$qteF.'", "'.$tx.'", "'.$cmup.'", "'.$marge.'", "'.$tva.'", "'.$obs.'");';
        $result = $this->connexion->query($q);
        return $result;
    }

    public function insertDetailFacturation($idV, $idA, $idEm, $qteD, $qteF, $tx, $cmup,$marge,$tva, $obs)
    {
        $q = 'INSERT INTO detail_facture VALUES ("'.$idV.'","'.$idA.'", '.$idEm.', "'.$qteD.'","'.$qteF.'","'.$tx.'","'.$cmup.'","'.$marge.'","'.$tva.'","'.$obs.'");';
        $result = $this->connexion->query($q);
        return $q;
    }

    public function insertDetailReliquat($idV, $idA, $idEm, $typeR, $typeA, $qte, $comp, $obs)
    {
        $q = 'INSERT INTO detail_reliquat VALUES ("'.$idV.'","'.$idA.'", '.$idEm.', '.$typeR.', '.$typeA.', '.$qte.', '.$comp.', "'.$obs.'");';
        $result = $this->connexion->query($q);
        return $q;
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

    
    public function updateTableUneCondition($table, $attr, $value, $col, $id)
    {
        $r='UPDATE '.$table.' SET '.$attr.' = "'.$value.'" WHERE '.$col.' = "'.$id.'";';
        $this->connexion->query($r);

        return $r;
    }
    
    public function updateTableDeuxConditions($table, $attr, $value, $col1, $id1, $col2, $id2)
    {
        $r='UPDATE '.$table.' SET '.$attr.' = "'.$value.'" WHERE '.$col1.' = "'.$id1.'" AND '.$col2.' = "'.$id2.'";';
        $this->connexion->query($r);
        return $r;
    }
    

    public function updateSociete($attr, $value,$lacondition,$estremplie)
    {
        $r='UPDATE societe SET '.$attr.' = "'.$value.'"WHERE '.$lacondition.' = "'.$estremplie.'";';
        $this->connexion->query($r);
        return $r;
    }
    public function updateContactClient($attr, $value,$lacondition,$estremplie)
    {
        $r='UPDATE contact_client SET '.$attr.' = "'.$value.'"WHERE '.$lacondition.' = "'.$estremplie.'";';
        $this->connexion->query($r);
        return $r;
    }
    public function updateContactFournisseur($attr, $value,$lacondition,$estremplie)
    {
        $r='UPDATE contact_fournisseur SET '.$attr.' = "'.$value.'"WHERE '.$lacondition.' = "'.$estremplie.'";';
        $this->connexion->query($r);
        return $r;
    }

/*------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------FIN UPDATE------------------------------------------------*/
/*------------------------------------------------------------------------------------------------------------------*/
    
    public  function compteParSesIds($identifiant,$mdp)
    {
        $requete='SELECT * FROM employe WHERE identifiant = "'.$identifiant.'" and mdp = "'.$mdp.'";';
        $result=$this->connexion->query($requete);
        if($result)
            return $result->fetch(PDO::FETCH_OBJ);
            else
                return null;
    }    
    
    
    public function familleParSonId($id)
    {
        $q='SELECT * FROM famille_article WHERE idFam = "'.$id.'";';
        $result=$this->connexion->query($q);
        if ($result)
            return $result->fetch(PDO::FETCH_OBJ);
            else
                return null;
    }
    
    public function emplacementParSonId($id)
    {
        $r='SELECT * from emplacement WHERE idEmp = "'.$id.'";';
        $result=$this->connexion->query($r);
        if($result)
            return $result->fetch(PDO::FETCH_OBJ);
            else
                return null;
    }

    
    public function articleParSonId($id)
    {
        $r='SELECT * from ARTICLE WHERE idArticle = "'.$id.'";';
        $result=$this->connexion->query($r);
        if($result)
            return $result->fetch(PDO::FETCH_OBJ);
            else
                return null;
    }
    
    
    public function devisParSonId($idV, $idA)
    {
        $r='SELECT * from detail_devis WHERE idVente = "'.$idV.'" AND idArticle = "'.$idA.'";';
        $result=$this->connexion->query($r);
        if ($result)
            return $result->fetch(PDO::FETCH_OBJ);
            else
                return null;
    }
    
    public function commandeParSonId($idV, $idA)
    {
        $r='SELECT * from detail_commande WHERE idVente = "'.$idV.'" AND idArticle = "'.$idA.'";';
        $result=$this->connexion->query($r);
        if ($result)
            return $result->fetch(PDO::FETCH_OBJ);
            else
                return null;
    }
    
    public function preparationParSonId($idV, $idA)
    {
        $r='SELECT * from detail_preparation WHERE idVente = "'.$idV.'" AND idArticle = "'.$idA.'";';
        $result=$this->connexion->query($r);
        if ($result)
            return $result->fetch(PDO::FETCH_OBJ);
            else
                return null;
    }
    
    
    public function venteParSonId($id)
    {
        $r='SELECT * from VENTE WHERE idVente = '.$id;
        $result=$this->connexion->query($r);
        if($result)
            return $result->fetch(PDO::FETCH_OBJ);
        else
            return null;
    }
        
    public function clientParSonId($id)
    {
        $r='SELECT * from CONTACT_CLIENT WHERE idClient = "'.$id.'"';
        $result=$this->connexion->query($r);
        if($result)
            return $result->fetch(PDO::FETCH_OBJ);
            else
                return null;
    }
    
    public function societeParSonId($id)
    {
        $r='SELECT * from SOCIETE WHERE idSociete = '.$id.'';
        $result=$this->connexion->query($r);
        if($result)
            return $result->fetch(PDO::FETCH_OBJ);
            else
                return null;
    }
    
    public function employeParSonId($id)
    {
        $r='SELECT * FROM employe WHERE idEmploye = "'.$id.'";';
        $result=$this->connexion->query($r);
        if ($result)
            return $result->fetch(PDO::FETCH_OBJ);
            else
                return null;
    }

    
    public function typeReliquatParSonId($id)
    {
        $r='SELECT * FROM type_reliquat WHERE idType = "'.$id.'";';
        $result=$this->connexion->query($r);
        if($result)
            return $result->fetch(PDO::FETCH_OBJ);
            else
                return null;
    }
    
    public function typeEmployeParSonId($id)
    {
        $r='SELECT * from type_employe WHERE idType = "'.$id.'";';
        $result=$this->connexion->query($r);
        if($result)
            return $result->fetch(PDO::FETCH_OBJ);
            else
                return null;
    }  
    
    
    public function idDerniereVente()
    {
        $r="SELECT idVente FROM VENTE ORDER BY idVente DESC";
        $result=$this->connexion->query($r);
        if ($result)
            return $result->fetch(PDO::FETCH_OBJ);
            else
                return null;
    }
    
    public function idDernierContactClient()
    {
        $r="SELECT  idClient FROM contact_client ORDER BY idClient DESC";
        $result=$this->connexion->query($r);
        if ($result)
            return $result->fetch(PDO::FETCH_OBJ);
            else
                return null;
    }
    
    public function idDernierContactFournisseur()
    {
        $r="SELECT  idFour FROM contact_fournisseur ORDER BY idFour DESC";
        $result=$this->connexion->query($r);
        if($result)
            return $result->fetch(PDO::FETCH_OBJ);
            else
                return null;
    }
    
    public function idDernierSociete()
    {
        $r="SELECT  idSociete FROM societe WHERE idSociete ORDER BY idSociete DESC";
        $result=$this->connexion->query($r);
        if($result)
            return $result->fetch(PDO::FETCH_OBJ);
            else
                return null;
    }
    
    public function idDernierMouvement()
    {
        $r="SELECT  idMouv FROM mouvement_article ORDER BY idMouv DESC";
        $result=$this->connexion->query($r);
        if ($result)
            return $result->fetch(PDO::FETCH_OBJ);
            else
                return null;
    }
    
    public function idDernierEmploye()
    {
        $r="SELECT idEmploye FROM employe ORDER BY idEmploye DESC";
        $result=$this->connexion->query($r)->fetch(PDO::FETCH_OBJ);
        if($result)
            return $result;
        $r="SELECT  idEmploye FROM employe ORDER BY idEmploye DESC";
        $result=$this->connexion->query($r);
        if ($result)
            return $result->fetch(PDO::FETCH_OBJ);
            else
                return null;
    }
    
    public  function employeParIdVente($idVente)
    {
        $requete='SELECT d.idVente, e.idEmploye ,e.nom, e.prenom FROM detail_devis d, Employe e WHERE d.idEmploye=e.idEmploye AND idVente='.$idVente.'';
        $result=$this->connexion->query($requete);
        if ($result)
            return $result->fetch(PDO::FETCH_OBJ);
            else
                return null;
    }

    public  function entrepriseParIdVente($idV)
    {
        $requete='SELECT s.* FROM societe s JOIN contact_client c ON s.idSociete = c.idSociete JOIN vente v ON v.idClient = c.idClient WHERE idVente = '.$idV;
        $result=$this->connexion->query($requete);
        if ($result)
            return $result->fetch(PDO::FETCH_OBJ);
            else
                return null;
    }

    
    public function infosSociete()
    {
        $requete='SELECT * FROM informations_societe';
        $result=$this->connexion->query($requete);
        if ($result)
            return $result->fetch(PDO::FETCH_OBJ);
            else
                return null;
    }
    
/***********************************************************************/
/************************ AUTRES FONCTIONS *****************************/
/***********************************************************************/
    public function totalDevisParIdVente($idV)
    {
        $requete='SELECT * FROM TTCDevis WHERE idVente ="'.$idV.'";';
        $result=$this->connexion->query($requete);
        if ($result)
            return $result->fetch(PDO::FETCH_OBJ);
            else
                return null;
    }
    
    
    public function totalCommandeParIdVente($idV)
    {
        $requete='SELECT * FROM TTCCommande WHERE idVente ="'.$idV.'";';
        $result=$this->connexion->query($requete);
        if ($result)
            return $result->fetch(PDO::FETCH_OBJ);
            else
                return null;
    }
    
    public function totalFactureParIdVente($idV)
    {
        $requete='SELECT * FROM TTCFacture WHERE idVente ="'.$idV.'";';
        $result=$this->connexion->query($requete);
        if ($result)
            return $result->fetch(PDO::FETCH_OBJ);
            else
                return null;
    }
    
    public function qteReelleArticleParSonId($id)
    {
        $lesMouvements = $this->listeMouvementsParArticle($id);
        $lesPreparations = $this->listeDetailsPreparationParArticle($id);
        $retour = 0;
        while($m = $lesMouvements->fetch(PDO::FETCH_OBJ))//Pour chaque entrée ou dégradation de stockk on calcule le prix moyen
        {
            $retour = $retour + $m->qte;
        }
        
        while($p = $lesPreparations->fetch(PDO::FETCH_OBJ))//Pour chaque vente, on calcule le prix moyen soustrait à l'autre moyenne
        {
            $retour = $retour - $p->qteFournie;
        }
        return $retour;
    }
    
    
    public function qteVirtuelleArticleParSonId($id)
    {
        $lesMouvements = $this->listeMouvementsParArticle($id);
        $lesDevis = $this->listeDetailsDevisParArticle($id);
        $retour = 0;
        while($m = $lesMouvements->fetch(PDO::FETCH_OBJ))//On fait la somme des mouvements, entrées ou dégradations
        {
            $retour = $retour + $m->qte;
        }
        
        while($d = $lesDevis->fetch(PDO::FETCH_OBJ))//Pour chaque devis de la base, on soustrait la quantité à livrer.
        {
            $retour = $retour - $d->qteDemandee;
        }        
        return $retour;
    }    
}
?>
