
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

function toggleDisplay(elmt){		
	   if(typeof elmt == "string")		
	      elmt = document.getElementById(elmt);		
	   if(elmt.style.display == "none")		
	      elmt.style.display = "block";		
	   else		
	      elmt.style.display = "none";		
}

function ajouterlivraison() {// voir ci dessus pour le détail du code
	if (confirm("Pour confirmer la livraison de cette Livraison, cliqué sur 'ok', sinon cliquer sur 'annuler'."))
	{
		let idEmploye= document.getElementById("idEmploye");
		let idVente = document.getElementById("idVente");
		console.log(idVente);
		$.ajax({ //AJAX pour transférer de detail_livraison à detail_facturation
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
