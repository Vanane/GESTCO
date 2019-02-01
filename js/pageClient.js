('document').ready(function(){

	$('.tooltip').tooltipster //code pour utiliser tooltipster
	({
		trigger:'custom',
		triggerClose:
		{
			click:true
		}
	});	
});

function ajouterachat() {// j'affiche un message pour éviter les erreurs de click //Nicolas
	if (confirm("Pour valider la création de ce nouvelle achat, cliqué sur 'ok', sinon cliquer sur 'annuler'."))
	{
		var erreur=false;
		let idAchat = document.getElementById("idAchat");
		let date = document.getElementById("date");
		let idArticle = document.getElementById("idArticle");
		let prix = document.getElementById("prix");
		let qte = document.getElementById("qte");
		let commentaire = document.getElementById("commentaire");
		let idFour = document.getElementById("idFour");
		if (idArticle.value ==0 || prix.value ==0 || qte.value ==0 || fournisseur.value ==0)
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
        	'idArticle':idArticle.value,
        	'idFour':idFour.value,
    		'date':date.value,
    		'prix':prix.value,
    		'qte':qte.value,
    		'commentaire':commentaire.value
    	},
        url: "../ajax/achatAjax.php",
        success: function(r) {
        	//location.href =("http://localhost/GESTCO/Achats");
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
}