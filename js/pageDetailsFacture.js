$("document").ready(function(){
	$("#validePaiement").click(function(){
		window.open(location.href+'/facture', '_blank');/*
		$.ajax({ //AJAX pour envoyer l'id Vente afin de générer le PDF derrière
	        type: "POST",
	        dataType: "json",
	        data:
	    	{
	        	'action':'ajoutPrepa',
	        	'idV':$("#idVente").val()
	    	},
	        url: "../../ajax/detailsFactureAjax.php",
	        success: function(r) {
    			window.open(pdf, '_blank');
	        },
	        error: function (xhr, ajaxOptions, thrownError)
	        {
	            console.log(xhr.status);
	            console.log(thrownError);
	            console.log(ajaxOptions);
	    	}
		});*/			
	});	
});