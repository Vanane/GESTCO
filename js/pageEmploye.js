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
			// je vérifie si un certain nombre de variable est bien remplis
		{ 
			$('#ttInsertEmploye').tooltipster('open');// si certainne ne sont pas remplis, je déclanche le tooltip
			erreur = true;// et je passe erreur en true
		}
		if(!erreur)	// si il n'y a pas d'erreur il lance la requete Ajax
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
        	alert("Employé ajouté avec succès !");
        	location.href =("http://localhost/GESTCO/Employes");// en cas de réussite je renvoie sur la page employes
        },
        error: function (xhr, ajaxOptions, thrownError)// sinon j'affiche des info. sur l'erreur dans la console
        {
            console.log(xhr.status);
            console.log(thrownError);
            console.log(ajaxOptions);
    	}
	});
    }
  }
}

function modifieremploye(id) {// j'affiche un message pour éviter les erreurs de click 
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
		if ( adresse.value ==0 && telephone.value ==0 && mail.value ==0)//comme au dessus, verif des case vide et tooltip
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
        	location.reload();// j'actualise la page en cas de succès.
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