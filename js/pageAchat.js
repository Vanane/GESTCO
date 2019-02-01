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



function ajouterachat() {// j'affiche un message pour éviter les erreurs de click //Nicolas
	if (confirm("Pour valider la création de ce nouvelle achat, cliqué sur 'ok', sinon cliquer sur 'annuler'."))
	{
		var erreur=false;
		let idAchat = document.getElementById("idAchat");
		let date = document.getElementById("date");
		let listeArticle = document.getElementById("idArticle");
		var idArticle = listeArticle.options[listeArticle.selectedIndex].value;
		let listeFour = document.getElementById("idFour");
		var idFour = listeFour.options[listeFour.selectedIndex].value;
		let prix = document.getElementById("prix");
		let qte = document.getElementById("qte");
		let commentaire = document.getElementById("commentaire");
		let type ='1';
		if (idArticle==0 || prix.value ==0 || qte.value ==0 || idFour ==0)
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
