$("document").ready(function(){
	//Sur clic bouton confirmer, on passe le devis en commande.
	$("#confirmer").click(function(){
		if(confirm("Voulez-vous vraiment FORMULER une commande à partir de ce devis ?"))
		{
			$.ajax({ //AJAX pour transférer de detail_devis à detail_commande
		        type: "POST",
		        dataType: "json",
		        data:
		    	{
		        	'action':'ajoutCommande',
		        	'idV':$("#idVente").val()
	        	},
		        url: "../../ajax/detailsDevisAjax.php",
		        success: function(r) {
		        	alert("Commande formulée avec succès !");
		        	history.back();
		        },
		        error: function (xhr, ajaxOptions, thrownError)
		        {
		            console.log(xhr.status);
		            console.log(thrownError);
		            console.log(ajaxOptions);
		    	}
			});			
		}
	});	
});