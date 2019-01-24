  var lesSelectsClients = new Array();
function modificationclient() {
	if (confirm("Pour valider les modifications des données de l'entreprise, cliqué sur 'ok', sinon cliquer sur 'annuler'."))
	{
	location = "modificationclient";
	}
	}

function supprimercontactclient(){
	if (confirm("Pour supprimer les données du contact de l'entreprise, cliqué sur 'ok', sinon cliquer sur 'annuler'."))
	{
	//location = "confirmerSupressionContactClient"
		let item = lesSelectsClients;	
		$.ajax({
		type: "POST",
        dataType: "json",
        data:
    	{
        	'action':'deleteClient',
    		'idClient':item.value
    	},
        url: "../../ajax/ajoutDevisAjax.php",
        success: function(r) {
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
}


function modificationcontact(){
	if (confirm("Pour valider les modifications des données du contact client, cliqué sur 'ok', sinon cliquer sur 'annuler'."))
	{
	location = "modificationcontact";
	}
	}

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
