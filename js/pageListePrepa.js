$('document').ready(function(){
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

	//On stocke la collection des éléments "bloc" de la page dans lesBlocs
	var lesBlocs = $('.conteneur bloc');
	
	//Puis pour chaque bloc, on affecte un événement click.
	$.each(lesBlocs, function(index, value){
		$(this).find('.btn-classique').click(function(){
		//Sur click : envoi AJAX avec action "reservePrepa", pour que dans l'AJAX on affecte un id employe à la préparation
		//Afin de ne plus l'afficher dans la liste des préparations à faire.
			$.ajax({
			//AJAX pour, au clic sur une préparation, lui attribue une valeur
			//Pour qu'elle n'apparaisse plus dans la liste.
		        type: "POST",
		        dataType: "json",
		        data:
		    	{
		        	'action':'reservePrepa',
		        	'idV':$(value).find('#idV').val(),
		    		'idE':$("#idE").val()
		    	},
		        url: "../../ajax/listePrepaAjax.php",
		        success: function(r) {
		        	location+=$(value).find('#idV').val();
		        },
		        error: function (xhr, ajaxOptions, thrownError)
		        {
		        	alert("Erreur inconnue, veuillez réessayer plus tard.");
		        	console.log()
		            console.log(xhr.status);
		            console.log(thrownError);
		            console.log(ajaxOptions);
		    	}
			});	

		});
	});
});