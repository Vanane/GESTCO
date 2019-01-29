function modificationclient() {
	if (confirm("Pour valider les modifications des données de l'entreprise, cliqué sur 'ok', sinon cliquer sur 'annuler'."))
	{	let idSociete = document.getElementById("idSociete");
		let nomSociete = document.getElementById("nomSociete");
		let siteWebSociete = document.getElementById("siteWebSociete");
		let telSociete = document.getElementById("telSociete");
		let adresse = document.getElementById("adresse");
		let raisonSociete = document.getElementById("raisonSociete");
		let mailSociete = document.getElementById("mailSociete");
		$.ajax({
		type: "POST",
        dataType: "json",
        data:
    	{
        	'action':'modifierClient',
    		'idSociete':idSociete.value,
    		'nomSociete':nomSociete.value,
    		'siteWebSociete':siteWebSociete.value,
    		'telSociete':telSociete.value,
    		'adresseSociete':adresseSociete.value,
    		'faxSociete':faxSociete.value,
    		'raisonSociete':raisonSociete.value,
    		'mailSociete':mailSociete.value
    	},
        url: "../ajax/clientAjax.php",
        success: function(r) {
        	console.log(r['idSociete']);
        	location.reload()
        },
        error: function (xhr, ajaxOptions, thrownError)
        {
        	console.log(idSociete);
            console.log(xhr.status);
            console.log(thrownError);
            console.log(ajaxOptions);
    	}
	});
	}
	}

/* ************************************************************************************************************************************* */
/* *************************************************DEBUT*FONCTION*MODIFIER*CONTACT***************************************************** */
/* ************************************************************************************************************************************* */

function modificationContact(id,type) {
	if (confirm("Pour valider les modifications des données de votre contact, cliqué sur 'ok', sinon cliquer sur 'annuler'.")){
		let idContact=id;
		console.log("id contact égal à" + idContact);
		console.log(type);
		console.log("nom"+id);
		let nom = document.getElementById("nom"+id);
		let prenom = document.getElementById("prenom"+id);
		let telephone = document.getElementById("tel"+id);
		let mail = document.getElementById("mail"+id);
		let societe = document.getElementById("societe"+id);
			if (type="client")
				{$.ajax({
					type: "POST",
			        dataType: "json",
				        data:{		        	
				        	'action':'modifierContactClient',
				    		'id':idContact,
				    		'nom':nom.value,
				    		'prenom':prenom.value,
				    		'telephone':telephone.value,
				    		'mail':mail.value,
				    		'societe':societe.value
				    	},
			        url: "../ajax/clientAjax.php",
			        success: function(r) {location.reload()},
				        error: function (xhr, ajaxOptions, thrownError){
				        	console.log(id);
				            console.log(xhr.status);
				            console.log(thrownError);
				            console.log(ajaxOptions);
				    	}
				});}
			else
				{$.ajax({
					type: "POST",
			        dataType: "json",
				        data:
				    	{			        	
				        	'action':'modifierContactFournisseur',
				    		'id':idContact,
				    		'nom':nom.value,
				    		'prenom':prenom.value,
				    		'telephone':telephone.value,
				    		'mail':mail.value,
				    		'societe':societe.value
				    	},
			        url: "../ajax/clientAjax.php",
			        success: function(r) {location.reload()},
				        error: function (xhr, ajaxOptions, thrownError){
				        	console.log(id);
				            console.log(xhr.status);
				            console.log(thrownError);
				            console.log(ajaxOptions);
				    	}
			});}		
	}
	}

/* ************************************************************************************************************************************* */
/* *******************************************FIN*FONCTION*MODIFIER*CONTACT************************************************************* */
/* ************************************************************************************************************************************* */


function ajoutercontactclient() {
	if (confirm("Pour confirmer la création de ce contact, cliqué sur 'ok', sinon cliquer sur 'annuler'."))
	{
		let idClient = document.getElementById("idClient");
		let nom = document.getElementById("nomClient");
		let prenom = document.getElementById("prenomClient");
		let telephone = document.getElementById("telClient");
		let mail = document.getElementById("mailClient");
		let societe = document.getElementById("societeClient");
		$.ajax({
		type: "POST",
        dataType: "json",
        data:
    	{
        	'action':'ajouterContactClient',
        	'idClient':idClient.value,
    		'nom':nom.value,
    		'prenom':prenom.value,
    		'telephone':telephone.value,
    		'mail':mail.value,
    		'societe':societe.value
    	},
        url: "../../ajax/clientAjax.php",
        success: function(r) {
        	location.reload()
        },
        error: function (xhr, ajaxOptions, thrownError)
        {
        	console.log(idClient);
            console.log(xhr.status);
            console.log(thrownError);
            console.log(ajaxOptions);
    	}
	});
	}
	}


function ajoutersociete(){
	if (confirm("Si vous souhaietez ajouter une société cliente, cliqué sur 'ok', sinon cliquer sur 'annuler'."))
	{
		let id = document.getElementById("idSociete");
		let nom = document.getElementById("nomSociete");
		let adresse  = document.getElementById("adresseSociete");
		let telephone = document.getElementById("telSociete");
		let fax = document.getElementById("faxSociete");
		let siteWeb = document.getElementById("siteWebSociete");
		let raison = document.getElementById("raisonSociete");
		let mail = document.getElementById("mailSociete");
		$.ajax({
		type: "POST",
        dataType: "json",
        data:
    	{
        	'action':'ajouterSociete',
        	'id':id.value,
    		'nom':nom.value,
    		'adresse':adresse.value,
    		'telephone':telephone.value,
    		'fax':fax.value,
    		'siteWeb':siteWeb.value,
    		'raison':raison.value,
    		'mail':mail.value
    		
    		
    		
    	},
        url: "../ajax/clientAjax.php",
        success: function(r) {
        	location.reload()
        	
        },
        error: function (xhr, ajaxOptions, thrownError)
        {
        	console.log(idSociete);
            console.log(xhr.status);
            console.log(thrownError);
            console.log(ajaxOptions);
    	}
	});
	}
	}

/* ************************************************************************************************************************************* */	
/* *********************************************************DEBUT*GESTION*LISTE*SOCIETE************************************************* */	
/* ************************************************************************************************************************************* */

function afficherListeSociete(){
		var elmt = document.getElementById("btn-AfficherClient");
		elmt.style.display = "none";
		var elmt1 = document.getElementById("btn-AfficherTout");
		elmt1.style.display = "block";
		var elmt2 = document.getElementById("btn-AfficherFournisseur");
		elmt2.style.display = "none";
		var elmt3 = document.getElementById("block-Client");
		elmt3.style.display = "none";
		var elmt4 = document.getElementById("block-Societe");
		elmt4.style.display = "block";
		var elmt5 = document.getElementById("block-Fournisseur");
		elmt5.style.display = "none";
}
function afficherListeClient(){
		var elmt = document.getElementById("btn-AfficherClient");
		elmt.style.display = "none";
		var elmt1 = document.getElementById("btn-AfficherTout");
		elmt1.style.display = "block";
		var elmt2 = document.getElementById("btn-AfficherFournisseur");
		elmt2.style.display = "block";
		var elmt3 = document.getElementById("block-Client");
		elmt3.style.display = "block";
		var elmt4 = document.getElementById("block-Societe");
		elmt4.style.display = "none";
		var elmt5 = document.getElementById("block-Fournisseur");
		elmt5.style.display = "none";
}
function afficherListeFourniseur(){
		var elmt = document.getElementById("btn-AfficherClient");
		elmt.style.display = "block";
		var elmt1 = document.getElementById("btn-AfficherTout");
		elmt1.style.display = "block";
		var elmt2 = document.getElementById("btn-AfficherFournisseur");
		elmt2.style.display = "none";
		var elmt3 = document.getElementById("block-Client");
		elmt3.style.display = "none";
		var elmt4 = document.getElementById("block-Societe");
		elmt4.style.display = "none";
		var elmt5 = document.getElementById("block-Fournisseur");
		elmt5.style.display = "block";
}
function toggleDisplay(elmt){		
		   if(typeof elmt == "string")		
		      elmt = document.getElementById(elmt);		
		   if(elmt.style.display == "none")		
		      elmt.style.display = "block";		
		   else		
		      elmt.style.display = "none";		
}

/* ************************************************************************************************************************************* */	
/* ******************************************************FIN*GESTION*LISTE*SOCIETE****************************************************** */	
/* ************************************************************************************************************************************* */