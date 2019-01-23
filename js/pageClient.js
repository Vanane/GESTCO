  
function confirmerModifEntrepriseClient() {
	if (confirm("Pour valider les modifications des données de l'entreprise, cliqué sur 'ok', sinon cliquer sur 'annuler'."))
	{
	location = "confirmerModificationEntrepriseClient";

	}
	}

function supprimerContactClient(){
	if (confirm("Pour supprimer les données du contact de l'entreprise, cliqué sur 'ok', sinon cliquer sur 'annuler'."))
	{
	//location = "confirmerSupressionContactClient";	
	$.ajax({ //AJAX pour récupérer les infos d'un article.

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


function confirmerModifContactClient(){
	if (confirm("Pour valider les modifications des données du contact client, cliqué sur 'ok', sinon cliquer sur 'annuler'."))
	{
	location = "modificationcontactclient";
	}
	}

function ajouterContact(){
	if (confirm("Si vous souhaietez ajouter un contact, cliqué sur 'ok', sinon cliquer sur 'annuler'."))
	{
	location = "ajouterContact";
	}
	}
function ajouterSocieteCliente(){
	if (confirm("Si vous souhaietez ajouter une société cliente, cliqué sur 'ok', sinon cliquer sur 'annuler'."))
	{
	location = "ajouterSocieteCliente";
	}
	}
