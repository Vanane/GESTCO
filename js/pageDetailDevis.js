$("document").ready(function(){
	
	$("#confirmer").click(function(){
		if(confirm("Voulez-vous vraiment FORMULER une commande à partir de ce devis ?"))
		{
			$.ajax({ //AJAX pour transférer de detail_devis à detail_commande
		        type: "POST",
		        dataType: "json",
		        data:
		    	{
		        	'action':'ajoutCommande',
		        	'idV':$("#idVente").val(),
		        	'dateCommande':(new Date().getFullYear()+'-'+
				    				new Date().getMonth()+1+'-'+
				    				new Date().getDate()+' '+
				    				new Date().getHours()+':'+
				    				new Date().getMinutes()+':'+
				    				new Date().getSeconds())
		    	},
		        url: "../../ajax/detailsDevisAjax.php",
		        success: function(r) {
		        	alert("Commande formulée avec succès !");
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