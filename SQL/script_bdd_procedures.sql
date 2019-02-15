USE GESTCO;
/*----------------------------------------------------------------------------------------------------------
------------Une vente ne peut être en Préparation que si elle a été formuléen commande.-------------------
-----------------------------------------------------------------------------------------------------------*/
DROP TRIGGER IF EXISTS onInsertPreparation;

DELIMITER |
CREATE TRIGGER onInsertPreparation BEFORE INSERT ON DETAIL_PREPARATION
FOR EACH ROW
BEGIN
       	IF (new.idVente NOT IN (select idVente from DETAIL_COMMANDE)) THEN
		SIGNAL SQLSTATE '45000'
      	SET MESSAGE_TEXT = 'La préparation est impossible car la commande n\'a pas été faite.';
		END IF;
END |
DELIMITER ;

/*---------------------------------------------------------------------------------------------------------------
-------Trigger inversant la quantité si le nouvel enregistrement est une Sortie, afin de faciliter la VUE.-------
---------------------------------------------------------------------------------------------------------------*/

DROP TRIGGER IF EXISTS onInsertMouvementSiSortie;

DELIMITER |
CREATE TRIGGER onInsertMouvementSiSortie BEFORE INSERT ON MOUVEMENT_ARTICLE
FOR EACH ROW
BEGIN
	IF (NEW.qte > -1 AND NEW.idType IN (2,3)) THEN
    	SET NEW.qte = NEW.qte* (-1);
	END IF;
END|
DELIMITER ;

/*INSERT INTO MOUVEMENT_ARTICLE VALUES (5, 2, 1, 'BB001EA10', null, null, 15, 'blbl');*/

/*-------------------------------------------------------------------------------------------------------------
-------------------Le produit peut seulement être envoyé que si il a été livré----------------------------------------
-----------------------------------------------------------------------------------------------------------------*/

DROP TRIGGER IF EXISTS verifRetourSiLivree;

DELIMITER !
CREATE TRIGGER verifRetourSiLivree BEFORE INSERT ON detail_reliquat FOR EACH ROW  
BEGIN
	IF ((NEW.typeReliquat = 1) AND (NEW.idVente NOT IN (SELECT idVente FROM detail_livraison))) THEN
    	SIGNAL SQLSTATE '12345' SET MESSAGE_TEXT = 'Cette commande n\'a pas encore été livrée!';
    END IF;
END!
DELIMITER ;

/*insert into detail_reliquat values (666, 'BB001EA10', null, 1, null, null, null, null, null)*/

/*-------------------------------------------------------------------------------------------------------------
----------------Le commande peut seulement être partielle si le commande a été faite-----------------------------
-----------------------------------------------------------------------------------------------------------------*/

DROP TRIGGER IF EXISTS verifPartielleSiCommande;

DELIMITER |
CREATE TRIGGER verifPartielleSiCommande BEFORE INSERT ON DETAIL_reliquat
FOR EACH ROW
BEGIN
       	IF (new.typeReliquat=2 AND new.idVente NOT IN (select idVente from DETAIL_commande)) THEN
		SIGNAL SQLSTATE '45000'
      	SET MESSAGE_TEXT = 'La commande n\'a pas encore été faite.';
		END IF;
END |
DELIMITER ;

/*----------------------------------------------------------------------------------------------------------
----Une vente ne peut être en livraison que si elle a été formulée en commande et a été préparée.-------------------
-----------------------------------------------------------------------------------------------------------*/

DROP TRIGGER IF EXISTS onInsertLivraison;

DELIMITER |
CREATE TRIGGER onInsertLivraison BEFORE INSERT ON DETAIL_Livraison
FOR EACH ROW
BEGIN
       	IF (new.idVente NOT IN (select idVente from DETAIL_Preparation)) THEN
		SIGNAL SQLSTATE '45000'
      	SET MESSAGE_TEXT = 'La livraison est impossible car la commande n\'a pas été préparée.';
		END IF;
END |
DELIMITER ;

/*----------------------------------------------------------------------------------------------------------
-----------Une vente ne peut être en facturation que si elle a été formulée en commande.-------------------
-----------------------------------------------------------------------------------------------------------*/

DROP TRIGGER IF EXISTS onInsertFacture;

DELIMITER |
CREATE TRIGGER onInsertFacture BEFORE INSERT ON DETAIL_Facture
FOR EACH ROW
BEGIN
       	IF (new.idVente NOT IN (select idVente from DETAIL_COMMANDE)) THEN
		SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'La facture est impossible car la commande n\'a pas été faite.';
		END IF;
END|
DELIMITER ;

/*----------------------------------------------------------------------------------------------------------
-----------------Un employé obtient un identifiant formé de l'initiale de son prenom et son nom--------------------------
-----------------------------------------------------------------------------------------------------------*/
DROP TRIGGER IF EXISTS onInsertEmploye
DELIMITER |
CREATE TRIGGER onInsertEmployeFaitId BEFORE INSERT ON Employe FOR EACH ROW
BEGIN
	SET NEW.identifiant = CONCAT(SUBSTRING(NEW.prenom, 1,1), NEW.nom);
	SET NEW.mdp = MD5(NEW.mdp);
END|
DELIMITER ;

/*----------------------------------------------------------------------------------------------------------
-----------Création des vues qui donnent le TTC d'une vente pour devis, commande et préparation-------------
-----------------------------------------------------------------------------------------------------------*/

DROP VIEW IF EXISTS TTCDevis;

CREATE VIEW TTCDevis AS (SELECT ROUND(SUM(CMUP*qteDemandee*(1+marge)*(1+tva)*(1-txRemise)), 2) AS prixTotal, idVente FROM detail_devis GROUP BY idVente);

DROP VIEW IF EXISTS TTCCommande;

CREATE VIEW TTCCommande AS (SELECT ROUND(SUM(CMUP*qteDemandee*(1+marge)*(1+tva)*(1-txRemise)), 2) AS prixTotal, idVente FROM detail_commande GROUP BY idVente);

DROP VIEW IF EXISTS TTCFacture;

CREATE VIEW TTCFacture AS (SELECT ROUND(SUM(CMUP*qteFournie*(1+marge)*(1+tva)*(1-txRemise)), 2) AS prixTotal, idVente FROM detail_facture GROUP BY idVente);

/*----------------------------------------------------------------------------------------------------------
----------------Procédure qui permet de tronquer une date pour enlever la partie HH:mm:ss-------------------
-----------------------------------------------------------------------------------------------------------*/
DROP PROCEDURE IF EXISTS arrondirDate;

DELIMITER $$
CREATE PROCEDURE arrondirDate(IN d DATE)
SELECT SUBSTR(CONVERT(d, char), 1, 10) AS date$$
DELIMITER ;