$("document").ready(function(){
	//Sur clic bouton confirmer, AJAX pour valider la commande et la transférer en prépa.
	$("#confirmer").click(function(){
		if(confirm("Voulez-vous vraiment PASSER cette commande en préparation ?"))
		{
			$.ajax({ //AJAX pour transférer de detail_devis à detail_commande
		        type: "POST",
		        dataType: "json",
		        data:
		    	{
		        	'action':'ajoutPrepa',
		        	'idV':$("#idVente").val()
		    	},
		        url: "../../ajax/detailsCommandeAjax.php",
		        success: function(r) {
		        	alert("Préparation envoyée !");
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