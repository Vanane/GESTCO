$("document").ready(function(){
	
	var rowCount = $("#details-preparation-articles row").length;//On sauvegarde le nombre d'articles.	
	var datas = {}; //Tableau de données envoyé à la requête ajax.
	toggleCSS('aide', 'display', 'block', 'none');//Sur clic, masque le texte de présentation

	$("#btnAide").click(function(){
		toggleCSS('aide', 'display', 'none', 'block');//Sur clic, masque le texte de présentation
	});	
	
	for(i=1;i<=rowCount;i++)
	{
		let inc = i;//Pour chaque i, on sauvegarde i dans une variable "let" pour la dupliquer.	
		//Pareil pour les éléments btnArticle, codeScan et qteFournie qui dépendent de i.
		let btn = $("#btnArticle"+i);
		let code = $("#rowArticle"+inc).find("#codeScan");
		let qte = $("#rowArticle"+inc).find("#qteFournie");
		
		btn.click(function(){
			toggleCSS("#rowArticle"+inc, 'display', 'block', 'none');//Sur clic, afifche ou masque la div correspondante
		});
		
		code.change(function(){
			if($(this).val() == $("#rowArticle"+inc).find("#codeArticle").val())//Si les deux codes sont égaux
			{				
				$("#btnArticle"+inc).css('background', '#ffaa00');//Couleur = vert
				$("#rowArticle"+inc).find("#qteFournie").prop("readonly", false);//Et on débloque le input pour la quantité.
			}
			else
			{
				$("#btnArticle"+inc).css('background', '#dd5555');//Sinon couleur = rouge
				$("#rowArticle"+inc).find("#qteFournie").prop("readonly", true);//Et on bloque l'input pour la quantité.	
				alert("Code erroné ! Vérifiez votre saisie.");
			}
		});
		
		qte.change(function(){
			if(parseInt($(this).val()) > parseInt($("#rowArticle"+inc).find("#qteDemandee").val()))//Si le nombre est trop grand
			{
				console.log("change");
				$(this).val($("#rowArticle"+inc).find("#qteDemandee").val());//On le met au nombre demandé.
			}
			
			if($(this).val() == $("#rowArticle"+inc).find("#qteDemandee").val())//Si les deux nombres sont égaux
				$("#btnArticle"+inc).css('background', '#55dd55');//Couleur = vert
			else
				$("#btnArticle"+inc).css('background', '#ffaa00');//Sinon couleur = orange
		});
	}
	
	
	$("#validePrepa").click(function(){
		let error = false;
		let i = 0;
		//Boucle while pour chaque élément d'id rowArticleX.
		while(i<=rowCount)
		{
			i++;
			//Si le code-barre de l'article = le code-barre scanné/saisi, alors
			if($("#rowArticle"+i).find("#codeArticle").val() == $("#rowArticle"+i).find("#codeScan").val())
			{
				//Si le nombre d'articles entré est inférieur au nombre demandé, alors
				if(parseInt($("#rowArticle"+i).find("#qteFournie").val()) < parseInt($("#rowArticle"+i).find("#qteDemandee").val()))
				{
					//Si l'user répond "Oui" à la question s'il est sûr de vouloir poursuivre, alors erreur = false;
					if(confirm("L'article "+$("#btnArticle"+i).html()+" n'a pas été remis en quantité suffisante. Souhaitez-vous continuer ?"))
						error = false;
					else
						//Sinon erreur = true, ce qui empêchera l'AJAX de se faire.
						error = true;
				}
			}
			else
			{
				//Si les codes-barre ne sont pas identiques, erreur = tru et message d'erreur.
				alert("L'article "+$("#btnArticle"+i).html()+" n'a pas été prix en compte ! Vérifiez le code scan entré.");				
				error = true;
			}
		}
		//Si aucune erreur au-dessus n'a eu lieu
		if(!error)
		{		
			//Alors on remplit un tableau JSON des valeurs de chaque préparation
			datas['idV'] = parseInt($('#idVente').val());
			datas['nbArticles'] = rowCount;
			datas['idE'] = $("#idE").attr('data-ide');
			for(i=1;i<=rowCount;i++)
			{
				let inc = i;
				datas['idA'+inc] = $("#btnArticle"+inc).html();
				datas['qteD'+inc] = parseInt($("#rowArticle"+inc).find("#qteDemandee").val());
				datas['qteF'+inc] = parseInt($("#rowArticle"+inc).find("#qteFournie").val());				
			}
			
			//Qu'on envoie par AJAX.
			$.ajax({ //AJAX pour valider la préparation.
		        type: "POST",
		        dataType: "json",
		        data:
		    	{
		        	'action':'validePrepa',
		    		'datas':datas
		    	},
		        url: "../../ajax/detailsPrepaAjax.php",
		        success: function(r) {
		        	alert("Préparation enregistrée !");
		        	console.log(r);
		        	history.back();
		        },
		        error: function (xhr, ajaxOptions, thrownError)
		        {
		        	alert("erreur");
		            console.log(xhr.status);
		            console.log(thrownError);
		            console.log(ajaxOptions);
		    	}
			});	
		}
	});
});


function toggleCSS(el,c,v1,v2)//Inverse les propriétés si l'une est présente
{
	el = $(el);	
	if(el.css(c) == v2)
		el.css(c, v1);
	else
		el.css(c, v2);
}

