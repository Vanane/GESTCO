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

function modificationSociete() {// j'affiche un message pour éviter les erreurs de click //Nicolas
	if (confirm("Pour valider les modifications des données de l'entreprise, cliqué sur 'ok', sinon cliquer sur 'annuler'."))
	{
		var erreur=false;
		let idAchat = document.getElementById("idAchat");
		let date = document.getElementById("date");
		let idArticle = document.getElementById("idArticle);
		let prix = document.getElementById("prix");
		let qte = document.getElementById("qte");
		let commentaire = document.getElementById("commentaire");
		let idFour = document.getElementById("idFour");
		if (nom.value ==0 || prenom.value ==0 || telephone.value ==0 && mail.value ==0|| societe.value ==0)
		{
			$('#ttInsertContactInfo').tooltipster('open');
			erreur = true;
		}
		if(!erreur)	
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
	}
    }
  }
}