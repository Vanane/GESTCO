

$('document').ready(function(){

	$('.tooltip').tooltipster //code pour utiliser tooltipster
	({
		trigger:'custom',
		triggerClose:
		{
			click:true
		}
	});	
});

function modificationSociete() {// j'affiche un message pour éviter les erreurs de click
	if (confirm("Pour valider les modifications des données de l'entreprise, cliqué sur 'ok', sinon cliquer sur 'annuler'."))
	{	var erreur=false; //je déclare erreur pour géré les problèmes de case vide
		let id = document.getElementById("idSociete");//je récupère les informations dans les éléments à partir de leur id
		let nom = document.getElementById("nomSociete");
		let siteWeb = document.getElementById("siteWebSociete");
		let tel = document.getElementById("telSociete");
		let adresse = document.getElementById("adresseSociete");
		let fax = document.getElementById("faxSociete");
		let formeJur = document.getElementById("formeJurSociete");
		let mail = document.getElementById("mailSociete");
		if (nom.value ==0 || adresse.value ==0 || formeJur.value ==0)//je vérifie si ses 3 éléments ne sont pas vides
		{
			$('#ttModifSocieteInfo').tooltipster('open');// si oui j'affiche le pop-up
			erreur = true;//et je change la valeur de la viriable erreur.
		}
		if(!erreur)	//je vérifie si erreur est true ou pas. si oui, le code js est fini sinon je lance la reqête ajax.
		{
		$.ajax({
		type: "POST",//je déclare et type et le dataType.
        dataType: "json",
        data:
    	{
        	'action':'modifierSociete',//je précise quelle action ajax j'appelle.
    		'id':id.value,// j'envoie tous les paramètres.
    		'nom':nom.value,
    		'siteWeb':siteWeb.value,
    		'tel':tel.value,
    		'adresse':adresse.value,
    		'fax':fax.value,
    		'formeJur':formeJur.value,
    		'mail':mail.value
    	},
        url: "../ajax/clientAjax.php",//je précise quel chemin il doit suivre pour trouver la page Ajax.
        success: function(r) {
        	location.reload()// en cas de succès je reload la page.
        },
        error: function (xhr, ajaxOptions, thrownError)
        {
        	console.log(idSociete); //sinon j'affiche dans la console quelques informations sur l'erreur.
            console.log(xhr.status);
            console.log(thrownError);
            console.log(ajaxOptions);
    	}
	});
	}}
	}

/* ************************************************************************************************************************************* */
/* *************************************************DEBUT*FONCTION*MODIFIER*CONTACT***************************************************** */
/* ************************************************************************************************************************************* */

function modificationContact(id,type) {// je récupère les varibles id et type, puis j'affiche le message de verificaiton 
	if (confirm("Pour valider les modifications des données de votre contact, cliqué sur 'ok', sinon cliquer sur 'annuler'.")){
		var erreur=false; //déclarations
		let idContact=id;
		let nom = document.getElementById("nom"+id);
		let prenom = document.getElementById("prenom"+id);
		let telephone = document.getElementById("tel"+id);
		let mail = document.getElementById("mail"+id);
		let societe = document.getElementById("societe"+id);
		if (nom.value ==0 || prenom.value ==0 || telephone.value ==0 || mail.value ==0)// verif case vide
		{
			$('#ttModifContactInfo'+id).tooltipster('open');// affiche le pop-up
			erreur = true; //change erreur
		}
		if(!erreur)	//verif si case vide ou non
		{
			if (type=="client")// en fonction du type j'utilise la requte Ajax pour client ou pour fournisseur
				{$.ajax({//requete ajax client
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
				{$.ajax({//requete ajax fournisseur
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
	}}
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
		var erreur=false;
		let id = document.getElementById("id"+type);
		let nom = document.getElementById("nom"+type);
		let prenom = document.getElementById("prenom"+type);
		let telephone = document.getElementById("tel"+type);
		let mail = document.getElementById("mail"+type);
		let societe = document.getElementById("societe"+type);
		if (nom.value ==0 || prenom.value ==0 || telephone.value ==0 && mail.value ==0|| societe.value ==0)
		{
			$('#ttInsertContactInfo').tooltipster('open');
			erreur = true;
		}
		if(!erreur)	
		{
		if(type=="client")
		{
		let redirection = "http://localhost/GESTCO/Clients/"+ societe.value;//je déclare une variable redirection
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
        	alert("Contact ajouté avec succès !");
        	location.href =(redirection);// à la place de reload la page, 
        	//j'envoie l'utilisateur sur la page "Voir Details" de la société pour laquelle elle a effectuer l'ajout du contact 
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
		        	alert("Contact ajouté avec succès !");
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
	}}
	}

/* ************************************************************************************************************************************* */	
/* ************************************************************************************************************************************* */	

function ajoutercontactsociete(type) {// voir ci dessus pour le détail du code
	if (confirm("Pour confirmer la création de ce contact, cliqué sur 'ok', sinon cliquer sur 'annuler'."))
	{
		var erreur = false;
		console.log("type =" + type);
		let id = document.getElementById("id"+type);
		let societe = document.getElementById("idSocieteContact").selectedIndex;
		var nom = document.getElementById("nom"+type);
		var prenom = document.getElementById("prenom"+type);
		var telephone = document.getElementById("tel"+type);
		var mail = document.getElementById("mail"+type);
		if (societe ==0)
		{
			$('#ttInsertContactIdSociete').tooltipster('open');
			erreur = true;
		}
		if (nom.value ==0 || prenom.value ==0 || telephone.value ==0 || mail.value ==0)
		{
			$('#ttInsertContactInfo').tooltipster('open');
			erreur = true;
		}
		if(!erreur)	
		{
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
        	alert("Contact ajouté avec succès !");
        	location.href =(redirection);
        },
        error: function (xhr, ajaxOptions, thrownError)
        {
        	$('#ttIdSociete').tooltipster('open');
            console.log(xhr.status);
            console.log(thrownError);
            console.log(ajaxOptions);
            $('#ttIdSociete').tooltipster('open');
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
		        	alert("Contact ajouté avec succès !");
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
	}}
	}

/* ************************************************************************************************************************************* */	
/* *************************************************************FIN*AJOUT*CONTACT******************************************************* */	
/* ************************************************************************************************************************************* */
/* ************************************************************************************************************************************* */	
/* *************************************************************DEBUT*AJOUT*SOCIETE***************************************************** */	
/* ************************************************************************************************************************************* */

function ajoutersociete(type){// voir ci dessus pour le détail du code
	if (confirm("Si vous souhaietez ajouter une société, cliqué sur 'ok', sinon cliquer sur 'annuler'."))
	{
		var erreur = false;
		let id = document.getElementById("idSociete");
		let nom = document.getElementById("nomSociete");
		let adresse  = document.getElementById("adresseSociete");
		let telephone = document.getElementById("telSociete");
		let fax = document.getElementById("faxSociete");
		let siteWeb = document.getElementById("siteWebSociete");
		let formeJur = document.getElementById("formeJurSociete");
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
		if (nom.value ==0 || adresse.value ==0 || formeJur.value ==0)
		{
			$('#ttInsertSocieteInfo').tooltipster('open');
			erreur = true;
		}
		if(!erreur)	
		{
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
    		'formeJur':formeJur.value,
    		'mail':mail.value
    	},
        url: "../ajax/clientAjax.php",
        success: function(r) {
        	alert("Société ajouté avec succès !");
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
	}}
	}

/* ************************************************************************************************************************************* */	
/* **************************************************************FIN*AJOUT*SOCIETE****************************************************** */	
/* ************************************************************************************************************************************* */

function aide(){
	let elmt = document.getElementById("afficherAide");
	let elmt1 = document.getElementById("masquerAide");
	let elmt2 = document.getElementById("aide");
	toggleDisplay(elmt);
	toggleDisplay(elmt1);
	toggleDisplay(elmt2);
}


function toggleDisplay(elmt){		
		   if(typeof elmt == "string")		
		      elmt = document.getElementById(elmt);		
		   if(elmt.style.display == "none")		
		      elmt.style.display = "block";		
		   else		
		      elmt.style.display = "none";		
}

