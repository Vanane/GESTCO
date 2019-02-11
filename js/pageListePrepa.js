$('document').ready(function(){//Quand la page est prête

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