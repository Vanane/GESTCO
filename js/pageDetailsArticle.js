var lesOnglets = Array();
//Tableau contenant les elements de la div onglet.
//Il permettra d'attribuer des évènements en boucle.
$("document").ready(function(){

	var CMUPActuel = 0;//Sauvegarde le nouveau prix de base calculé
	
	//Ajoute les onglets à un array pour affecter des events en boucle.
	lesOnglets.push("on-cmup");
	lesOnglets.push("on-mouv");
	
	//Initie les tooltips.
	$('.tooltip').tooltipster
	({
		trigger:'custom',
		triggerClose:
		{
			click:true
		},
	});	

	//Détermine le CMUP de l'article en question.
	$.ajax({ //AJAX pour récupérer le CMUP de l'article.
        type: "POST",
        dataType: "json",
        data:
    	{
        	'action':'calculCMUP',
    		'idArticle':$("#idArticle").val()
    	},
        url: "../ajax/detailsArticleAjax.php",
        success: function(r) {
        	$("#nouveauCMUP").val(r['nouveauCMUP']);
        	CMUPActuel = $("#nouveauCMUP").val();
        },
        error: function (xhr, ajaxOptions, thrownError)
        {
            console.log(xhr.status);
            console.log(thrownError);
            console.log(ajaxOptions);
    	}
	});	
	
	

	//Masque le deuxième onglet, le premier étant affiché par défaut.
	$("#div-"+lesOnglets[1]).addClass("hidden");

	
	
	
	
	/****************************************/
	/************** EVENEMENTS **************/
	/****************************************/
	
	//Sur clic sur l'un des onglets : masque l'un et affiche l'autre.
	for(var i=0;i<lesOnglets.length;i++)
	{
		let el = $("#"+lesOnglets[i]);
		el.click(function()
		{
			var laDiv = $(this).attr("id");
			console.log(this);
			console.log(laDiv);
			for(var j=0;j<lesOnglets.length;j++)
			{
					$("#"+lesOnglets[j]).attr("active", "false");
					$("#div-"+lesOnglets[j]).addClass("hidden");
			}			
			$(this).attr("active", "true");		
			$("#div-"+laDiv).removeClass("hidden");
		});
	}
	
	//Sur clic bouton : modifie le CMUP.
	$("#modifierArticle").click(function(){
		if(confirm("Voulez-vous vraiment MODIFIER le CMUP actuel pour le CMUP calculé ?"))
		{
			$.ajax({ //AJAX pour récupérer le CMUP de l'article.
		        type: "POST",
		        dataType: "json",
		        data:
		    	{
		        	'action':'modifCMUP',
		    		'idArticle':$("#idArticle").val(),
		    		'nouveauCMUP':$("#nouveauCMUP").val()
		    	},
		        url: "../ajax/detailsArticleAjax.php",
		        success: function(r) {
		        	alert("CMUP modifié !");
		        	location.reload();
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
	
	
	$("#margeSup").change(function(){
		if($(this).val()>100 || $(this).val()<0)
		{
			$(this).val(0);
		}
		console.log(CMUPActuel);
		$("#nouveauCMUP").val(CMUPActuel*(1+($(this).val()/100)));//nouveau CMUP = CMUP * taux sup
	
	
	
	});
});




