function ajouteremploye() {// j'affiche un message pour éviter les erreurs de click 
	if (confirm("Pour valider la création de ce nouvelle employé, cliqué sur 'ok', sinon cliquer sur 'annuler'."))
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
        	'action':'ajouterEmploye',
        	'idEmploye':idEmploye.value,
        	'idType':idType.value,
        	'nom':nom.value,
    		'prenom':prenom.value,
    		'adresse':adresse.value,
    		'telephone':telephone.value,
    		'mail':mail.value,
    		'mdp':mdp.value
    	},
        url: "../ajax/achatAjax.php",
        success: function(r) {
        	//location.href =("http://localhost/GESTCO");
        	console.log('ajout employe...');
        },
        error: function (xhr, ajaxOptions, thrownError)
        {
            console.log(xhr.status);
            console.log(thrownError);
            console.log(ajaxOptions);
    	}
	});
    }
  }
}
function modifieremploye() {// j'affiche un message pour éviter les erreurs de click 
	if (confirm("Pour valider la modification de cette employe, cliqué sur 'ok', sinon cliquer sur 'annuler'."))
	{
		var erreur=false;
		let idEmploye = document.getElementById("idEmploye");
		let adresse = document.getElementById("adresse");
		let telephone = document.getElementById("telephone");	
		let mail = document.getElementById("mail");
		let mdp = document.getElementById("mdp");
		if (mdp ==0 || adresse==0 && telephone==0 && mail=00)
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
        	'action':'modifierEmploye',
        	'idEmploye':idEmploye.value,
    		'adresse':adresse.value,
    		'telephone':telephone.value,
    		'mail':mail.value,
    		'mdp':mdp.value
    	},
        url: "/ajax/achatAjax.php",
        success: function(r) {
        	//location.href =("http://localhost/GESTCO");
        	console.log('modif employe...');
        },
        error: function (xhr, ajaxOptions, thrownError)
        {
            console.log(xhr.status);
            console.log(thrownError);
            console.log(ajaxOptions);
    	}
	});
    }
  }
}