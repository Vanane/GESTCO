/*$('document').ready(function(){// NC : début gestion reliquat
	let idVente = document.getElementById("idVente");
	let listeIdArticle;
	$.ajax({ 
        type: "POST",
        dataType: "json",
        data:
    	{
        	'action':'boucleArticle,
        	'idV':idVente.value,
    	},
    	url: "../../ajax/detailsLivraisonAjax.php",
        success: function(r) {
        	$(listeidArticle).val(r['result']);
        },
        error: function (xhr, ajaxOptions, thrownError)
        {
            console.log(xhr.status);
            console.log(thrownError);
            console.log(ajaxOptions);
        }
	});
});*/

$('document').ready(function(){
	$('table').each(function(index, value){
		let v = value;
		console.log(v);
		$(v).DataTable({		
			"language": {
				 "sProcessing": "Traitement en cours...",
				 "sSearch": "Rechercher&nbsp;:",
				 "sLengthMenu": "Afficher _MENU_ &eacute;l&eacute;ments",
				 "sInfo": "Affichage de l'&eacute;lement _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
				 "sInfoEmpty": "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
				 "sInfoFiltered": "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
				 "sInfoPostFix": "",
				 "sLoadingRecords": "Chargement en cours...",
				 "sZeroRecords": "Aucun &eacute;l&eacute;ment &agrave; afficher",
				 "sEmptyTable": "Aucune donn&eacute;e disponible dans le tableau",
				 "oPaginate": {
				 "sFirst": "Premier&nbsp;&nbsp;",
				 "sPrevious": "Pr&eacute;c&eacute;dent&nbsp;&nbsp;",
				 "sNext": "Suivant",
				 "sLast": "&nbsp;&nbsp;Dernier"
				 },
				 "oAria": {
				 "sSortAscending": ": activer pour trier la colonne par ordre croissant",
				 "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
				 }
			},
		scrollX:false,
		"autoWidth": false		
		});			
	});
})
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


function gestionHistorique(){
	var elmt = document.getElementById("historique");
	var elmt1 = document.getElementById("encours");
	var elmt2 = document.getElementById("btn-historique");
	var elmt3 = document.getElementById("btn-encours");
	toggleDisplay(elmt);
	toggleDisplay(elmt1);
	toggleDisplay(elmt2);
	toggleDisplay(elmt3);
}


function ajouterlivraison() {
	if (confirm("Pour confirmer la livraison, cliqué sur 'ok', sinon cliquer sur 'annuler'."))
	{
		let idEmploye= document.getElementById("idEmploye");
		let idVente = document.getElementById("idVente");
		var redirection =("http://localhost/GESTCO/Livraisons/");
		
		/*var qteFournie ={}; // NC: début gestion reliquat
		while (isset listeIdArticle)
		boucle des articles de la vente
		if qteFournie == qteDemandee
		ajax
		else
		rien*/
		
		
		$.ajax({ 
	        type: "POST",
	        dataType: "json",
	        data:
	    	{
	        	'action':'ajoutLivraison',
	        	'idV':idVente.value,
	        	'idEmploye':idEmploye.value,
	    	},
	        url: "../../ajax/detailsLivraisonAjax.php",
	        success: function(r) {
	        	alert("Livraison confirmer avec succès !");
	        	location.href =(redirection);
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
