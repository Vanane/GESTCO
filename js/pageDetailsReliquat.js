$('document').ready(function(){
	var dReliquats = {};//Tableau JSON qui sera renvoyé en AJAX avec les informations de chaque reliquat
	
	//On stocke le nombre de blocs dans rowCount
	var rowCount = $('.div-liste bloc').length;
	
	$('.div-liste bloc').each(function(index, value){
	//Pour chaque élément bloc de la page, on affecte au textarea de ce bloc un compteur de caractères.
		let bloc = $(value);
		let text = $(bloc).find("#obsReliquat");
		$(bloc).find('sub').html("("+($(text).prop('maxlength') - $(text).val().length)+" signes restants)");
		
		$(text).keydown(function(){
			$(bloc).find('sub').html("("+($(text).prop('maxlength') - $(text).val().length)+" signes restants)");			
		});	
		
		$(bloc).find
	});
	
	//Sur clic bouton Confirmer, on regarde si les valesurs entrées sont valides, et on envoie un AJAX si valides. Sinon message d'erreur
	$("#confirmer").click(function(){ 
		let erreur = false;		
		$('.div-liste bloc').each(function(index, value)
		{		
			let bloc = $(value);	
			if($(bloc).find("#montReliquat").val() < 0 || $(bloc).find("#montReliquat").val() == "")
			{
				alert("Le montant saisi pour l'article "+$(bloc).find("#artReliquat").val()+" est invalide !");
				erreur = true;
			}		
			else
			{
				//On met les données saisies dans un tableau qu'on passera par AJAX ensuite.
				dReliquats['rowCount'] = rowCount;
				$('.div-liste bloc').each(function(i, v)
				{
					dReliquats['action'+i] = $(v).find("#actReliquat").val();
					dReliquats['article'+i] = $(v).find("#artReliquat").val();
					dReliquats['montant'+i] = $(v).find("#montReliquat").val();
					dReliquats['observation'+i] = $(v).find("#obsReliquat").val();						
				});
			}
		});
		if(!erreur)
		{			
			$.ajax({ //AJAX pour envoyer les infos saisies dans la base de données
		        type: "POST",
		        dataType: "json",
		        data:
		    	{
		        	'action':'modifReliquats',
		        	'idV':$("#idVente").val(),
		        	'dReliquats':dReliquats
		    	},
		        url: "../../ajax/detailsReliquatAjax.php",
		        success: function(r) {
		        	console.log(r);
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
