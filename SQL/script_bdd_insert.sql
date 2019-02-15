USE GESTCO;

/* --- SCRIPT POUR VIDER LA BASE SANS LA SUPPRIMER --- */
SET FOREIGN_KEY_CHECKS=0;
DELETE FROM detail_devis;
DELETE FROM detail_commande;
DELETE FROM detail_preparation;
DELETE FROM detail_livraison;
DELETE FROM detail_facture;
DELETE FROM detail_reliquat;
DELETE FROM mouvement_article;
DELETE FROM type_action;
DELETE FROM type_reliquat;
DELETE FROM employe;
DELETE FROM type_employe;
DELETE FROM type_mouvement;
DELETE FROM article;
DELETE FROM emplacement;
DELETE FROM famille_article;
DELETE FROM vente;
DELETE FROM contact_fournisseur;
DELETE FROM contact_client;
DELETE FROM societe;
DELETE FROM informations_societe;
SET FOREIGN_KEY_CHECKS=1;



USE GESTCO;
INSERT INTO INFORMATIONS_SOCIETE (id, nom, adresse, telephone, fax, mail, siteWeb, formeJur, raison, siren, siret, capitalSocial, civiliteContact, nomContact, prenomContact, posteContact, adresseContact, mailContact, logo) VALUES
('1', 'GRETA Loire-Atlantique', '44 Boulevard Jean Moulin', '0240145656', NULL, 'greta.loire-atlantique@ac-nantes.fr', 'https://www.greta-paysdelaloire.fr', 'Public', 'GIP', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, "logo.png");
INSERT INTO SOCIETE (idSociete, nom, adresse, telephone, fax, siteWeb, formeJur, mail) VALUES
(1, 'Mr.Bricolage', '44 rue du Barbier 56000 Vannes', '0245046578  ', 'ContactFournisseur@fax.MrBricolage.fr', 'www.Mr.Bricolage.fr', 'SARL', 'ContactFournisseur@.MrBricolage.fr'),
(2, 'Aide Brico', '23 rue des Barreaux 44000 Nantes', '0240452688', 'AideBrico@fax.gmail.com', 'www.AideBrico.fr', 'SARL', 'AideBrico@gmail.com'),
(3, 'Bricodépot', '15 boulevard Poulain 44000 Nantes', '0240559785', 'fax@bricodepot.fr', 'bricodepot.fr', 'SARL', 'contact@bricodepot.fr'),
(4, 'APC', '78 route de Vannes 44105 Nantes', '0240589696', 'APC@fax.gmail.com', 'www.APC.fr', 'SARL', 'APC@gmail.com'),
(5, 'Ikea', '53 route de Vannes 44105 Nantes', '0240252525',NULL,'www.IKEA.com', 'SAS', 'APC@fax.gmail.com'),
(6, 'Ref Brico', '4 boulevard du marrin 44400 Rezé', '0240333353', NULL, 'www.RefBrico.fr', 'ERL', 'APC@fax.gmail.com');


INSERT INTO CONTACT_CLIENT (idClient, idSociete, nom, prenom, telephone, mail) VALUES
(1, 1, 'Leblanc', 'Jack', '0365846598', 'societe@gmail.com'),
(2, 2, 'Henry', 'Frank', '0605040807', 'frank@henry.fr'),
(3, 3, 'Smith', 'John', '0228784548', 'smithj@gmail.com');

INSERT INTO CONTACT_FOURNISSEUR (idFour, idSociete, nom, prenom, telephone, mail) VALUES
(1, 2, 'Bertrand', 'Paul', '0756238496', 'Paul.Bertrand@MrBricolage.fr'),
(2, 4, 'Dutron  ', 'Pascal', '0240846982', 'DutronPascal17@gmail.fr');

INSERT INTO FAMILLE_ARTICLE (idFam, libelle) VALUES
(1, 'Bricolage');

INSERT INTO EMPLACEMENT (idEmp, libelle, depot) VALUES
('A1C2', 'Palettier A; Travée 1; Niveau C; Emplacement 2  ', 'Principal'),
('A1H5', 'Palettier A; Travée 1 ; Niveau H; Emplacement 5 ', 'Principal'),
('A2B1', 'Palettier A; Travée 2 ; Niveau B; Emplacement 1 ', 'Principal'),
('A2D5', 'Palettier A; Travée 2; Niveau D; Emplacement 5  ', 'Principal');

INSERT INTO ARTICLE (idArticle, idEmp, idFam, codeBarre, libelle, dernierCMUP, txMarge, txTVA) VALUES
('BB001EA10', 'A1C2', 1, '9782216089840', 'CALES A FERRAILLE BETON', 5.38, 0.2, 0.2),
('BB008EA17', 'A2B1', 1, '9782216030064', 'PNEUMATIQUE CELLULES LOGIQUES', 8.03, 0.2, 0.2),
('BB009EA18', 'A2D5', 1, '9782091790435', 'POINTES SANS TETE 2CM', 4.76, 0.2, 0.2);

INSERT INTO TYPE_MOUVEMENT (idType, libelle) VALUES
(1, 'Ajout de Stock'),
(2, 'Dégradation'),
(3, 'Réajustement');

INSERT INTO MOUVEMENT_ARTICLE (idMouv, idType, idSociete, idArticle, date, prix, qte, commentaire) VALUES
(1, 1, 2, 'BB008EA17', CAST('2019-11-02T00:00:00.000' AS DateTime), 45, 100, 'Rajout article'),
(2, 1, 2, 'BB008EA17', CAST('2019-02-11T00:00:00.000' AS DateTime), 15, 50, 'Achat'),
(3, 1, 2, 'BB001EA10', CAST('2019-02-11T00:00:00.000' AS DateTime), 37.5, 350, 'Achat'),
(4, 1, 2, 'BB009EA18', CAST('2019-11-02T00:00:00.000' AS DateTime), 18, 200, 'Entrée Stock');

INSERT INTO TYPE_EMPLOYE (idType, libelle) VALUES
(1,'Commercial'),
(2,'Préparateur de commande'),
(3,'Livreur'),
(4,'Informaticien');

INSERT INTO EMPLOYE (idEmploye, idType, nom, prenom, adresse, telephone, mail, mdp, identifiant) VALUES
(1, 1, 'Leblanc', 'Jack', '44 Rue Jean Moulin', '0645238759', 'Jack-leblanc@gmail.com', 'test', NULL),
(2, 4, 'Dmin', 'Albert', '44 Rue Jean Moulin', '0604080900', 'admin@admin.com', 'admin', NULL),
(3, 1, 'Jacques', 'Robert', '3 Impasse du moulin', '0609954875', 'robertjacques@gmx.fr', 'robert', NULL),
(4, 2, 'Leclerc', 'Michel', '123 avenue Foch', '0667799118', 'leclerc@live.fr', 'michel', NULL);

INSERT INTO TYPE_RELIQUAT (idType, libelle) VALUES
(1, 'Retour'),
(2, 'Partielle');

INSERT INTO TYPE_ACTION (idType, libelle) VALUES
(1, 'Refus'),
(2, 'Avoirs'),
(3, 'Remboursements');

INSERT INTO VENTE (idVente, idClient, dateDevis, dateCommande, datePrepa, dateLivraison, dateFacture) VALUES
(1, 1, CAST('2019-03-01T00:00:00.000' AS DateTime), CAST('2019-04-01T00:00:00.000' AS DateTime), CAST('2019-04-01T00:00:00.000' AS DateTime), CAST('2019-06-01T00:00:00.000' AS DateTime), CAST('2019-07-01T00:00:00.000' AS DateTime)),
(2, 1, CAST('2019-04-01T00:00:00.000' AS DateTime), CAST('2019-06-01T00:00:00.000' AS DateTime), CAST('2019-06-01T00:00:00.000' AS DateTime), CAST('2019-08-01T00:00:00.000' AS DateTime), CAST('2019-12-01T00:00:00.000' AS DateTime)),
(3, 1, CAST('2019-03-01T00:00:00.000' AS DateTime), CAST('2019-04-01T00:00:00.000' AS DateTime), CAST('2019-07-01T00:00:00.000' AS DateTime), CAST('2019-10-01T00:00:00.000' AS DateTime), CAST('2019-11-01T00:00:00.000' AS DateTime)),
(4, 1, CAST('2019-03-21T00:00:00.000' AS DateTime), CAST('2019-04-02T00:00:00.000' AS DateTime), CAST('2019-04-02T00:00:00.000' AS DateTime), CAST('2019-04-03T00:00:00.000' AS DateTime), CAST('2019-04-03T00:00:00.000' AS DateTime)),
(5, 1, CAST('2019-03-21T00:00:00.000' AS DateTime), CAST('2019-04-02T00:00:00.000' AS DateTime), CAST('2019-04-02T00:00:00.000' AS DateTime), CAST('2019-04-03T00:00:00.000' AS DateTime), CAST('2019-04-03T00:00:00.000' AS DateTime)),
(6, 1, CAST('2019-03-21T00:00:00.000' AS DateTime), CAST('2019-04-02T00:00:00.000' AS DateTime), null, null, null),
(7, 1, CAST('2019-03-21T00:00:00.000' AS DateTime), CAST('2019-04-02T00:00:00.000' AS DateTime), null, null, null),
(8, 1, CAST('2019-03-21T00:00:00.000' AS DateTime), null, null, null, null);

INSERT INTO DETAIL_DEVIS (idVente, idArticle, idEmploye, qteDemandee, txRemise, CMUP, marge, tva, observation) VALUES
(1, 'BB008EA17', 1, 4, 0, 15, 0.2, 0.2, 'ok pour le client'),
(2, 'BB008EA17', 1, 4, 0, 15, 0.2, 0.2, 'ok pour le client'),
(3, 'BB008EA17', 1, 4, 0, 15, 0.15, 0.2, 'ok pour le client'),
(4, 'BB001EA10', 1, 5, 0, 8, 0.2, 0.2, 'oui'),
(4, 'BB008EA17', 1, 5, 0, 15, 0.2, 0.2, 'ok pour le client'),
(5, 'BB008EA17', 1, 5, 0, 15, 0.2, 0.2, 'ok pour le client'),
(6, 'BB008EA17', 1, 5, 0, 15, 0.2, 0.2, 'ok pour le client'),
(6, 'BB001EA10', 1, 5, 0, 8, 0.2, 0.2, 'ok pour le client'),
(7, 'BB008EA17', 1, 5, 0, 15, 0.2, 0.2, 'ok pour le client'),
(8, 'BB001EA10', 1, 5, 0, 8, 0.2, 0.2, 'ok pour le client');
 
INSERT INTO DETAIL_COMMANDE(idVente, idArticle, idEmploye, qteDemandee, txRemise, CMUP, marge, tva, observation) VALUES
(1, 'BB008EA17', 1, 4, 0, 15, 0.2, 0.2, 'ok pour le client'),
(2, 'BB008EA17', 1, 4, 0, 15, 0.2, 0.2, 'ok pour le client'),
(3, 'BB008EA17', 1, 4, 0, 15, 0.15, 0.2, 'ok pour le client'),
(4, 'BB001EA10', 1, 5, 0, 8, 0.2, 0.2, 'oui'),
(4, 'BB008EA17', 1, 5, 0, 15, 0.2, 0.2, 'ok pour le client'),
(5, 'BB008EA17', 1, 5, 0, 15, 0.2, 0.2, 'ok pour le client'),
(6, 'BB008EA17', 1, 5, 0, 15, 0.2, 0.2, 'ok pour le client'),
(6, 'BB001EA10', 1, 5, 0, 8, 0.2, 0.2, 'ok pour le client'),
(7, 'BB008EA17', 1, 5, 0, 15, 0.2, 0.2, 'ok pour le client');

INSERT INTO DETAIL_PREPARATION (idVente, idArticle, idEmploye, qteDemandee, qteFournie, txRemise, CMUP, marge, tva, observation) VALUES
(1, 'BB008EA17', 1, 4, 4, 0, 15, 0.2, 0.2, 'ok pour le client'),
(2, 'BB008EA17', 1, 4, 4, 0, 15, 0.2, 0.2, 'ok pour le client'),
(6, 'BB008EA17', NULL, 5, 0, 0, 15, 0.2, 0.2, 'ok pour le client'),
(6, 'BB001EA10', NULL, 5, 0, 0, 8, 0.2, 0.2, 'ok pour le client'),
(7, 'BB008EA17', NULL, 5, 0, 0, 15, 0.2, 0.2, 'ok pour le client');

INSERT INTO DETAIL_LIVRAISON (idVente, idArticle, idEmploye, qteDemandee, qteFournie, txRemise, CMUP, marge, tva, observation) VALUES
(1, 'BB008EA17', 1, 4, 4, 0, 15, 0.2, 0.2, 'ok pour le client'),
(2, 'BB008EA17', 1, 4, 4, 0, 15, 0.2, 0.2, 'ok pour le client');

INSERT INTO DETAIL_FACTURE (idVente, idArticle, idEmploye, qteDemandee, qteFournie, txRemise, CMUP, marge, tva, observation) VALUES
(1, 'BB008EA17', 1, 4, 4, 0, 15, 0.2, 0.2, 'ok pour le client');

INSERT INTO DETAIL_RELIQUAT (idVente, idArticle, idEmploye, typeReliquat, typeAction, qte, compensation, observation) VALUES
(1, 'BB008EA17', 1, 2, 3, 2,15, 'ok pour le client');

