DROP DATABASE IF EXISTS GESTCO;
CREATE DATABASE GESTCO;
USE GESTCO;

CREATE TABLE INFORMATIONS_SOCIETE(
id int,
nom char(24),
adresse char(48),
telephone char(10),
fax char(10),
mail char(40),
siteWeb char(48),
formeJur char(12),
raison char(12),
siren char(9),
siret char(14),
capitalSocial char(16),
civiliteContact char(1),
nomContact char(16),
prenomContact char(16),
posteContact char(20),
adresseContact char(96),
mailContact char(40),
logo char(24),
PRIMARY KEY (id)
)ENGINE=InnoDB CHARACTER SET=utf8;

CREATE TABLE SOCIETE(
idSociete int,
nom char(24),
adresse char(64),
telephone char(12),
fax char(48),
siteWeb char(48),
formeJur char(12),
mail char(40),
PRIMARY KEY (idSociete)
)ENGINE=InnoDB CHARACTER SET=utf8;

CREATE TABLE CONTACT_CLIENT(
idClient int,
idSociete int,
nom char(16),
prenom char(16),
telephone char(10),
mail char(40),
PRIMARY KEY (idClient),
FOREIGN KEY(idSociete) REFERENCES SOCIETE(idSociete)
)ENGINE=InnoDB CHARACTER SET=utf8;

CREATE TABLE CONTACT_FOURNISSEUR(
idFour int,
idSociete int,
nom char(16),
prenom char(16),
telephone char(10),
mail char(40),
PRIMARY KEY (idFour),
FOREIGN KEY(idSociete) REFERENCES SOCIETE(idSociete)
)ENGINE=InnoDB CHARACTER SET=utf8;

CREATE TABLE  TYPE_EMPLOYE(
idType int,
libelle char(24),
PRIMARY KEY (idType)
)ENGINE=InnoDB CHARACTER SET=utf8;

CREATE TABLE EMPLOYE(
idEmploye int,
idType int,
identifiant char(36),
nom char(12),
prenom char(12),
adresse char(64),
telephone char(10),
mail char(40),
mdp char(32),
PRIMARY KEY (idEmploye),
FOREIGN KEY(idType) REFERENCES TYPE_EMPLOYE(idType)
)ENGINE=InnoDB CHARACTER SET=utf8;

CREATE TABLE VENTE(
idVente int,
idClient int,
dateDevis datetime,
dateCommande datetime,
datePrepa datetime,
dateLivraison datetime,
dateFacture datetime,
PRIMARY KEY (idVente),
FOREIGN KEY (idClient) REFERENCES CONTACT_CLIENT(idClient)
)ENGINE=InnoDB CHARACTER SET=utf8;

CREATE TABLE  FAMILLE_ARTICLE(
idFam int,
libelle char(24),
PRIMARY KEY (idFam))ENGINE=InnoDB CHARACTER SET=utf8;

CREATE TABLE EMPLACEMENT(
idEmp char(4),
libelle char(48),
depot char(32),
PRIMARY KEY (idEmp))ENGINE=InnoDB CHARACTER SET=utf8;

CREATE TABLE ARTICLE(
idArticle char(12),
idEmp char(4),
idFam int, 
codeBarre char(13),
libelle char(32),
dernierCMUP REAL,
txMarge REAL,
txTVA REAL,
PRIMARY KEY (idArticle),
FOREIGN KEY (idEmp) REFERENCES EMPLACEMENT(idEmp),
FOREIGN KEY (idFam) REFERENCES FAMILLE_ARTICLE(idFam)
)ENGINE=InnoDB CHARACTER SET=utf8;

CREATE TABLE  TYPE_MOUVEMENT(
idType int,
libelle char(24),
PRIMARY KEY (idType)
)ENGINE=InnoDB CHARACTER SET=utf8;

CREATE TABLE  MOUVEMENT_ARTICLE(
idMouv int,
idType int,
idSociete int,
idArticle char(12),
date datetime,
prix real,
qte int,
commentaire char(48),
PRIMARY KEY (idMouv),
FOREIGN KEY (idType) REFERENCES TYPE_MOUVEMENT(idType),
FOREIGN KEY (idSociete) REFERENCES SOCIETE(idSociete),
FOREIGN KEY (idArticle) REFERENCES ARTICLE(idArticle)
)ENGINE=InnoDB CHARACTER SET=utf8;

CREATE TABLE  TYPE_RELIQUAT(
idType int,
libelle char(24),
PRIMARY KEY (idType)
)ENGINE=InnoDB CHARACTER SET=utf8;

CREATE TABLE TYPE_ACTION(
idType int,
libelle char(24),
PRIMARY KEY (idType)
)ENGINE=InnoDB CHARACTER SET=utf8;

CREATE TABLE DETAIL_DEVIS(
idVente int,
idArticle char(12),
idEmploye int,
qteDemandee int,
txRemise real,
CMUP real, marge real, tva real,
observation char(64),
PRIMARY KEY (idVente, idArticle),
FOREIGN KEY (idVente) REFERENCES VENTE(idVente),
FOREIGN KEY (idArticle) REFERENCES ARTICLE(idArticle),
FOREIGN KEY (idEmploye) REFERENCES EMPLOYE(idEmploye)
)ENGINE=InnoDB CHARACTER SET=utf8;

CREATE TABLE  DETAIL_COMMANDE(
idVente int,
idArticle char(12),
idEmploye int,
qteDemandee int,
txRemise real,
CMUP real, marge real, tva real,
observation char(64),
PRIMARY KEY (idVente, idArticle),
FOREIGN KEY (idVente) REFERENCES VENTE(idVente),
FOREIGN KEY (idArticle) REFERENCES ARTICLE(idArticle),
FOREIGN KEY (idEmploye) REFERENCES EMPLOYE(idEmploye)
)ENGINE=InnoDB CHARACTER SET=utf8;

CREATE TABLE DETAIL_PREPARATION(
idVente int,
idArticle char(12),
idEmploye int,
qteDemandee int,
qteFournie int,
txRemise real,
CMUP real, marge real, tva real,
observation char(64),
PRIMARY KEY (idVente, idArticle),
FOREIGN KEY (idVente) REFERENCES VENTE(idVente),
FOREIGN KEY (idArticle) REFERENCES ARTICLE(idArticle),
FOREIGN KEY (idEmploye) REFERENCES EMPLOYE(idEmploye)
)ENGINE=InnoDB CHARACTER SET=utf8;

CREATE TABLE DETAIL_LIVRAISON(
idVente int,
idArticle char(12),
idEmploye int,
qteDemandee int,
qteFournie int,
txRemise real,
CMUP real, marge real, tva real,
observation char(64),
PRIMARY KEY (idVente, idArticle),
FOREIGN KEY (idVente) REFERENCES VENTE(idVente),
FOREIGN KEY (idArticle) REFERENCES ARTICLE(idArticle),
FOREIGN KEY (idEmploye) REFERENCES EMPLOYE(idEmploye)
)ENGINE=InnoDB CHARACTER SET=utf8;

CREATE TABLE  DETAIL_FACTURE(
idVente int,
idArticle char(12),
idEmploye int,
qteDemandee int,
qteFournie int,
txRemise real,
CMUP real, marge real, tva real,
observation char(64),
PRIMARY KEY (idVente, idArticle),
FOREIGN KEY (idVente) REFERENCES VENTE(idVente),
FOREIGN KEY (idArticle) REFERENCES ARTICLE(idArticle),
FOREIGN KEY (idEmploye) REFERENCES EMPLOYE(idEmploye)
)ENGINE=InnoDB CHARACTER SET=utf8;

CREATE TABLE  DETAIL_RELIQUAT(
idVente int,
idArticle char(12),
idEmploye int,
typeReliquat int,
typeAction int,
qte int,
compensation real,
observation char(64),
PRIMARY KEY (idVente, idArticle),
FOREIGN KEY (idVente) REFERENCES VENTE(idVente),
FOREIGN KEY (idArticle) REFERENCES ARTICLE(idArticle),
FOREIGN KEY (idEmploye) REFERENCES EMPLOYE(idEmploye),
FOREIGN KEY (typeReliquat) REFERENCES TYPE_reliquat(idType),
FOREIGN KEY (typeAction) REFERENCES TYPE_ACTION(idType)
)ENGINE=InnoDB CHARACTER SET=utf8;


