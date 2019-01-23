$("document").ready(function(){
	$("#confirmer").click(function(){
		if(confirm("Voulez-vous vraiment FORMULER une commande à partir de ce devis ?"))
		{
			$.ajax({ //AJAX pour transférer de detail_devis à detail_commande
		        type: "POST",
		        dataType: "json",
		        data:
		    	{
		        	'idVente':$("#idVente").value
		    	},
		        url: "../../ajax/ajoutCommandeAjax.php",
		        success: function(r) {
		        	nom.value = r['libelle'];
		        	cmup.value = r['cmup'];
		        	tva.value = r['tva'];
		        },
		        error: function (xhr, ajaxOptions, thrownError)
		        {
		        	console.log(item);
		            console.log(xhr.status);
		            console.log(thrownError);
		            console.log(ajaxOptions);
		    	}
			});

			
		}
	});	
});