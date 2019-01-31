
$('document').ready(function(){
	var lesBlocs = $('.conteneur bloc');
	
	$.each(lesBlocs, function(index, value){
		$(this).find('.btn-classique').click(function(){
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
		        url: "../ajax/listePrepaAjax.php",
		        success: function(r) {
		        	location+=$(value).find('#idV').val();
		        },
		        error: function (xhr, ajaxOptions, thrownError)
		        {
		        	alert("Erreur inconnue, veuillez réessayer plus tard.");
		            console.log(xhr.status);
		            console.log(thrownError);
		            console.log(ajaxOptions);
		    	}
			});	

		});
	});
});