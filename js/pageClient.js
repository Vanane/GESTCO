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
function modificationcontactclient() {
	if (confirm("Pour valider les modifications des données de votre contact, cliqué sur 'ok', sinon cliquer sur 'annuler'."))
	{	let idClient = document.getElementById("idClient");
		let nom = document.getElementById("nomClient");
		let prenom = document.getElementById("prenomClient");
		let telephone = document.getElementById("telClient");
		let mail = document.getElementById("mailClient");
		let idSociete = document.getElementById("societeClient");
		$.ajax({
		type: "POST",
        dataType: "json",
        data:
    	{
        	'action':'modifierContactClient',
    		'idClient':idClient.value,
    		'nom':nom.value,
    		'prenom':prenom.value,
    		'telephone':telephone.value,
    		'mail':mail.value
    		'idSociete':idSociete.value,
    	},
        url: "../ajax/clientAjax.php",
        success: function(r) {
        	console.log(r['idClient']);
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

function ajouterContact(){
	if (confirm("Si vous souhaietez ajouter un contact, cliqué sur 'ok', sinon cliquer sur 'annuler'."))
	{
	location = "ajoutercontact";
	}
	}
function ajouterSocieteCliente(){
	if (confirm("Si vous souhaietez ajouter une société cliente, cliqué sur 'ok', sinon cliquer sur 'annuler'."))
	{
	location = "ajouterSocieteCliente";
	}
	}
