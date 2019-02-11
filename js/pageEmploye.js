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

function ajouteremploye() {// j'affiche un message pour éviter les erreurs de click 
	if (confirm("Pour valider la création de ce nouvelle employé, cliqué sur 'ok', sinon cliquer sur 'annuler'."))
	{
		var erreur=false;
		let idEmploye = document.getElementById("idEmploye");
		let idType = document.getElementById("idType").selectedIndex;
		let nom = document.getElementById("nom");
		let prenom = document.getElementById("prenom");
		let adresse = document.getElementById("adresse");
		let telephone = document.getElementById("telephone");	
		let mail = document.getElementById("mail");
		let mdp = document.getElementById("mdp");
		if (idType == 0 || nom.value == 0 || prenom.value == 0 || mdp.value == 0 ||( adresse.value == 0 && telephone.value == 0 && mail.value == 0))
		{ 
			$('#ttInsertEmploye').tooltipster('open');
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
        	'idType':idType,
        	'nom':nom.value,
    		'prenom':prenom.value,
    		'adresse':adresse.value,
    		'telephone':telephone.value,
    		'mail':mail.value,
    		'mdp':mdp.value
    	},
        url: "/ajax/employeAjax.php",
        success: function(r) {
        	location.href =("http://localhost/GESTCO/Employes");
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

function modifieremploye(id) {// j'affiche un message pour éviter les erreurs de click //Nicolas
	if (confirm("Pour valider la modification de cette employe, cliqué sur 'ok', sinon cliquer sur 'annuler'."))
	{
		var erreur=false;
		var idEmploye=id;
		let adresse = document.getElementById("adresse"+idEmploye);
		let telephone = document.getElementById("telephone"+idEmploye);	
		let mail = document.getElementById("mail"+idEmploye);
		//console.log(idEmploye);
		//console.log(adresse.value);
		//console.log(telephone.value);
		//console.log(mail.value);
		if ( adresse.value ==0 && telephone.value ==0 && mail.value ==0)
		{
			$('#ttUpdateEmploye'+idEmploye).tooltipster('open');
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
        	'idEmploye':idEmploye,
    		'adresse':adresse.value,
    		'telephone':telephone.value,
    		'mail':mail.value,
    	},
        url: "ajax/employeAjax.php",
        success: function(r) {
        	location.reload();
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