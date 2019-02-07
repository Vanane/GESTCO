function ajouteremploye() {// j'affiche un message pour éviter les erreurs de click 
	if (confirm("Pour valider la création de ce nouvelle achat, cliqué sur 'ok', sinon cliquer sur 'annuler'."))
	{
		var erreur=false;
		let idEmploye = document.getElementById("idEmploye");
		let idType = document.getElementById("idType");
		let nom = document.getElementById("nom");
		let prenom = document.getElementById("prenom");
		let adresse = document.getElementById("adresse");
		let telephone = document.getElementById("telephone");	
		let mail = document.getElementById("mail");
		let mdp = document.getElementById("mdp");
		if (idType==0 || nom.value ==0 || prenom.value ==0 || mdp ==0 || adresse==0 && telephone==0 && mail=00)
		{
			$('#ttInsertAchatInfo').tooltipster('open');
			erreur = true;
		}
		if(!erreur)	
		{
		$.ajax({
		type: "POST",
        dataType: "json",
        data:
    	{
        	'action':'ajouterAchat',
        	'idAchat':idAchat.value,
        	'idArticle':idArticle,
        	'idFour':idFour,
    		'date':date.value,
    		'prix':prix.value,
    		'qte':qte.value,
    		'type':type,
    		'commentaire':commentaire.value
    	},
        url: "../ajax/achatAjax.php",
        success: function(r) {
        	//location.href =("http://localhost/GESTCO/Achats");
        	console.log('ajout...');
        },
        error: function (xhr, ajaxOptions, thrownError)
        {
        	console.log(type);
            console.log(xhr.status);
            console.log(thrownError);
            console.log(ajaxOptions);
    	}
	});
    }
  }
}