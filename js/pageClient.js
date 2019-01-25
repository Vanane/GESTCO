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

function modificationContactClient(id) {
	if (confirm("Pour valider les modifications des données de votre contact, cliqué sur 'ok', sinon cliquer sur 'annuler'."))
	{	let idClient = id;
		let nom = document.getElementById("nomClient"+idClient);
		let prenom = document.getElementById("prenomClient"+idClient);
		let telephone = document.getElementById("telClient"+idClient);
		let mail = document.getElementById("mailClient"+idClient);
		let societe = document.getElementById("societeClient"+idClient);
		console.log(idClient);
		$.ajax({
		type: "POST",
        dataType: "json",
        data:
    	{
        	'action':'modifierContactClient',
        	'idClient':idClient,
    		'nom':nom.value,
    		'prenom':prenom.value,
    		'telephone':telephone.value,
    		'mail':mail.value,
    		'societe':societe.value
    	},
        url: "../ajax/clientAjax.php",
        success: function(r) {
        	location.reload()
        	/*document.getElementById("lesBlocs").innerHTML = r['result'];*/ 
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


/*function supprimercontactclient(){
	if (confirm("Pour supprimer les données du contact de l'entreprise, cliqué sur 'ok', sinon cliquer sur 'annuler'."))
	{
	//location = "confirmerSupressionContactClient"
		let item = document.getElementById("codeContact");
		$.ajax({
		type: "POST",
        dataType: "json",
        data:
    	{
        	'action':'deleteClient',
    		'idClient':item.value
    	},
        url: "../ajax/clientAjax.php",
        success: function(r) {
        	console.log(r['idClient']);
        },
        error: function (xhr, ajaxOptions, thrownError)
        {
        	console.log(item);
            console.log(xhr.status);
            console.log(thrownError);
            console.log(ajaxOptions);
    	}
	});
	}
}*/
// pas possible de supprimer car clé étrangère aux ventes 

