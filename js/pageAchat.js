//On stocke le nombre de blocs dans rowCount
	var rowCount = $('.div-liste bloc').length;
	

$('document').ready(function(){//au lancement de la page

	$('.tooltip').tooltipster //code pour utiliser tooltipster
	({
		trigger:'custom',
		triggerClose:
		{
			click:true
		}
	});	
	$('#table').DataTable({
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
	scrollX:true,
	"autoWidth": false
	});	
	let bloc = $('.div-liste bloc');
	let text = $(bloc).find("#commentaire"); // je récupère le commentaire dans le bloc.
	$(bloc).find('sub').html("("+($(text).prop('maxlength') - $(text).val().length)+" signes restants)");
	//j'affiche le nombre de signes restant grâce à maxlength
	$(text).keydown(function(){ // et je le réaffiche à chaque fois qu'un nouveau caractère est entré dedans.
		$(bloc).find('sub').html("("+($(text).prop('maxlength') - $(text).val().length)+" signes restants)");			
	});			
	$(bloc).find

});

function aide(){// fonction pour activer l'aide ou non
	let elmt = document.getElementById("afficherAide");
	let elmt1 = document.getElementById("masquerAide");
	let elmt2 = document.getElementById("aide");
	toggleDisplay(elmt);
	toggleDisplay(elmt1);
	toggleDisplay(elmt2);
}
function toggleDisplay(elmt){	//fonction pour rendre invisible un élément	
	   if(typeof elmt == "string")		
	      elmt = document.getElementById(elmt);		
	   if(elmt.style.display == "none")		
	      elmt.style.display = "block";		
	   else		
	      elmt.style.display = "none";		
}

function ajouterachat() {// j'affiche un message pour éviter les erreurs de click 
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
		if (idArticle==0 || prix.value ==0 || qte.value ==0 || idFour ==0 || date.value ==0)//je test si des varibales obligatoire sont vides
		{
			$('#ttInsertAchatInfo').tooltipster('open');// si oui j'execute les tooltip
			erreur = true;// erreur passe à true
		}
		if(!erreur)	// en fonction de la valeur d'erreur je lance ou bloque la requête Ajax
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
        	alert("Achat ajouté avec succès !");
        	location.href =("http://localhost/GESTCO/Achats");
        	//console.log('ajout...');
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
