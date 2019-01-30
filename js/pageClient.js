//$('document').ready(function(){});//Note

function modificationSociete() {
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
        	'action':'modifierSociete',
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
			if (type=="client")
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
			        success: function(r)
			        {location.reload();},
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
				        data:{			        	
				        	'action':'modifierContactFournisseur',
				    		'id':idContact,
				    		'nom':nom.value,
				    		'prenom':prenom.value,
				    		'telephone':telephone.value,
				    		'mail':mail.value,
				    		'societe':societe.value
				    	},
			        url: "../ajax/clientAjax.php",
			        success: function(r) {
			        	location.reload();
			        	},
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
/* *****************************************************FIN*FONCTION*MODIFIER*CONTACT*************************************************** */
/* ************************************************************************************************************************************* */
/* ************************************************************************************************************************************* */	
/* ********************************************************DEBUT*AJOUT*CONTACT********************************************************** */	
/* ************************************************************************************************************************************* */

function ajoutercontact(type) {
	if (confirm("Pour confirmer la création de ce contact, cliqué sur 'ok', sinon cliquer sur 'annuler'."))
	{
		let id = document.getElementById("id"+type);
		let nom = document.getElementById("nom"+type);
		let prenom = document.getElementById("prenom"+type);
		let telephone = document.getElementById("tel"+type);
		let mail = document.getElementById("mail"+type);
		let societe = document.getElementById("societe"+type);
		console.log( societe.value );
		if(type=="client")
		{
		let redirection = "http://localhost/GESTCO/Clients/"+ societe.value;
		$.ajax({
		type: "POST",
        dataType: "json",
        data:
    	{
        	'action':'ajouterContactClient',
        	'id':id.value,
    		'nom':nom.value,
    		'prenom':prenom.value,
    		'telephone':telephone.value,
    		'mail':mail.value,
    		'societe':societe.value
    	},
        url: "../../ajax/clientAjax.php",
        success: function(r) {
        	location.href =(redirection);
        },
        error: function (xhr, ajaxOptions, thrownError)
        {
        	console.log(idClient);
            console.log(xhr.status);
            console.log(thrownError);
            console.log(ajaxOptions);
    	}
	});}
		else
			{
			let redirection = "http://localhost/GESTCO/Fournisseurs/"+ societe.value;
			$.ajax({
				type: "POST",
		        dataType: "json",
		        data:
		    	{
		        	'action':'ajouterContactFournisseur',
		        	'id':id.value,
		    		'nom':nom.value,
		    		'prenom':prenom.value,
		    		'telephone':telephone.value,
		    		'mail':mail.value,
		    		'societe':societe.value
		    	},
		        url: "../../ajax/clientAjax.php",
		        success: function(r) {
		        	location.href =(redirection);
		        },
		        error: function (xhr, ajaxOptions, thrownError)
		        {
		        	console.log(id);
		            console.log(xhr.status);
		            console.log(thrownError);
		            console.log(ajaxOptions);
		    	}
			});
			}
	}
	}

/* ************************************************************************************************************************************* */	
/* ************************************************************************************************************************************* */	

function ajoutercontactsociete(type) {
	if (confirm("Pour confirmer la création de ce contact, cliqué sur 'ok', sinon cliquer sur 'annuler'."))
	{
		console.log("type =" + type);
		let id = document.getElementById("id"+type);
		let nom = document.getElementById("nom"+type);
		let prenom = document.getElementById("prenom"+type);
		let telephone = document.getElementById("tel"+type);
		let mail = document.getElementById("mail"+type);
		let societe = document.getElementById("idSocieteContact").selectedIndex;		
		
		console.log( societe);
		if(type=="client")
		{
		let redirection = "http://localhost/GESTCO/Clients/"+societe;
		console.log( redirection);
		$.ajax({
		type: "POST",
        dataType: "json",
        data:
    	{
        	'action':'ajouterContactClient',
        	'id':id.value,
    		'nom':nom.value,
    		'prenom':prenom.value,
    		'telephone':telephone.value,
    		'mail':mail.value,
    		'societe':societe
    	},
        url: "../ajax/clientAjax.php",
        success: function(r) {
        	location.href =(redirection);
        },
        error: function (xhr, ajaxOptions, thrownError)
        {
            console.log(xhr.status);
            console.log(thrownError);
            console.log(ajaxOptions);
    	}
	});}
		else
			{
			let redirection = "http://localhost/GESTCO/Fournisseurs/"+societe;			
			$.ajax({
				type: "POST",
		        dataType: "json",
		        data:
		    	{
		        	'action':'ajouterContactFournisseur',
		        	'id':id.value,
		    		'nom':nom.value,
		    		'prenom':prenom.value,
		    		'telephone':telephone.value,
		    		'mail':mail.value,
		    		'societe':societe
		    	},
		        url: "../ajax/clientAjax.php",
		        success: function(r) {
		        	location.href =(redirection);
		        },
		        error: function (xhr, ajaxOptions, thrownError)
		        {
		        	console.log(id);
		            console.log(xhr.status);
		            console.log(thrownError);
		            console.log(ajaxOptions);
		    	}
			});
			}
	}
	}

/* ************************************************************************************************************************************* */	
/* *************************************************************FIN*AJOUT*CONTACT******************************************************* */	
/* ************************************************************************************************************************************* */
/* ************************************************************************************************************************************* */	
/* *************************************************************DEBUT*AJOUT*SOCIETE***************************************************** */	
/* ************************************************************************************************************************************* */

// Note ajouter redirection de page
function ajoutersociete(type){
	if (confirm("Si vous souhaietez ajouter une société, cliqué sur 'ok', sinon cliquer sur 'annuler'."))
	{
		let id = document.getElementById("idSociete");
		let nom = document.getElementById("nomSociete");
		let adresse  = document.getElementById("adresseSociete");
		let telephone = document.getElementById("telSociete");
		let fax = document.getElementById("faxSociete");
		let siteWeb = document.getElementById("siteWebSociete");
		let raison = document.getElementById("raisonSociete");
		let mail = document.getElementById("mailSociete");
		if(type=="client")
			{
			var redirection = "http://localhost/GESTCO/Clients/"+id.value;
			
			}
		else
			{
			var redirection = "http://localhost/GESTCO/Fournisseurs/"+id.value;	
			}
		console.log(redirection);
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
        	location.href =(redirection);	
        },
        error: function (xhr, ajaxOptions, thrownError)
        {
        	console.log(id);
            console.log(xhr.status);
            console.log(thrownError);
            console.log(ajaxOptions);
    	}
	});
	}
	}

// save
/*function ajoutersociete(){
	if (confirm("Si vous souhaietez ajouter une société, cliqué sur 'ok', sinon cliquer sur 'annuler'."))
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
	}*/
/* ************************************************************************************************************************************* */	
/* **************************************************************FIN*AJOUT*SOCIETE****************************************************** */	
/* ************************************************************************************************************************************* */
/* ************************************************************************************************************************************* */	
/* *********************************************************DEBUT*GESTION*LISTE*SOCIETE************************************************* */	
/* ************************************************************************************************************************************* */

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
