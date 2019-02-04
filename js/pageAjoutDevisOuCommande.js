$('document').ready(function(){	//Lorsque le document sera prêt à exécuter le script.

	var rowCount = $("#table-articles").length;

	ajouteListenerCalculTTC(rowCount, "txArticle");//Ajoute un listener qui permet de calculer le prix quand le numeric txArticle change
	ajouteListenerCalculTTC(rowCount, "qteArticle");//Pareil pour qteArticle, ces deux éléments devant être initialisés de base.
	//Ce sont ceux de la première ligne, qui ne sont donc pas ajoutés quand on clique sur le bouton ajoutLigne, il faut les initialiser.
	ajouteListenerChargeArticle(rowCount);//Idem pour l'idArticle d ela ligne 1, qui, au clic, charge le nom et le cmup de l'article


	$('.tooltip').tooltipster
	({
		trigger:'custom',
		triggerClose:
		{
			click:true
		}
	});	
	
	$("#ajouteLigne").click(function()
	//Ajoute une ligne dans la table des articles au clic sur le bouton.
	{		
		//Incrémente le nombre de lignes pour donner un ID incrémenté aux elements.
		rowCount++;
		//Sauvegarde le rowCount actuel pour affecter l'event sur changement d'article
		//En utilisant let pour ne pas overwrite la variable.
		
		let row = rowCount;

		//Ecrit le code HTML dans un string pour l'ajouter plus tard avec JS
		//En utilisant l'incrémentation pour donner des ID.
		var tr ='<tr id="tr'+rowCount+'">';
		tr += '<td><select id="idArticle"></select></td>';
		tr += '<td><input id="nomArticle" type="text" readonly></td>';
		tr += '<td><input id="CMUPArticle" type="number" readonly></td>';
		tr += '<td><input id="margeArticle" type="number" readonly></td>';
		tr += '<td><input id="qteArticle" type="number" min=0 value=0></td>';
		tr += '<td><input id="txArticle" type="number" min="0" max="100" value=0 step:0.5></td>';
		tr += '<td><input id="remise" type="number" readonly></td>';
		tr += '<td><input id="ht" type="number" readonly></td>';
		tr += '<td><input id="tva" type="number" readonly></td>';
		tr += '<td><input id="ttc" type="number" readonly></td>';
		tr += '<td><input id="obsArticle" type="text" readonly></td>';
		tr += '</tr>';		
		
		//Ajoute au tableau le HTML écrit au-dessus
		$("#table-articles").append(tr);	
		
		//Affecte au select de la nouvelle ligne, la liste des articles de la première ligne.
		$("#table-articles #tr1 #idArticle option").each(function(index, option)
		{ 
			var text = option.value;
			$("#tr"+rowCount+" #idArticle").append(new Option(text, text));
		});		
		
		ajouteListenerChargeArticle(rowCount);
		
		ajouteListenerCalculTTC(rowCount, "txArticle");
		ajouteListenerCalculTTC(rowCount, "qteArticle");
		
		
		
	});

	
	//Permet d'ajouter un event onChange sur l'élément mis entre parenthèses
	//Qui va calculer le prix TTC de la ligne correspondante quand il doit être changé.
	function ajouteListenerCalculTTC(idTr,idEl)	
	{
		let id = idEl;
		let tr = $("#tr"+idTr);

		$(tr).find("#"+id).change(function(){
			let qte = $(tr).find("#qteArticle");
			let cmup = $(tr).find("#CMUPArticle");
			let tva = $(tr).find("#tva");
			let rem = $(tr).find("#remise");
			let txrem = $(tr).find("#txArticle");
			let ht = $(tr).find("#ht");
			let ttc = $(tr).find("#ttc");
			if(qte.val()<0 || qte.val()=="")
				qte.val(0);
			if(txrem.val()<0 || txrem.val()>100 || txrem.val()=="")
				txrem.val(0);
			
			ht.val(qte.val()*cmup.val());
			ttc.val(ht.val()*(1+(tva.val()/100)));
			rem.val(ttc.val()*(txrem.val()/100));//Math.round et /100 pour arrondir à 2 décimales
			ttc.val(ttc.val()-rem.val());
			
		});
	}
	
	
	function ajouteListenerChargeArticle(idTr)
	{
		let tr = $("#tr"+idTr);
		$(tr).find("#idArticle").change(function()
		{
			//Récupérer les élements par leur nom + le numéro du select qui est activé.
			//Pour cela, on récupère l'id du select, auquel on substring à partir du 
			let nom =$(tr).find("#nomArticle");
			let cmup = $(tr).find("#CMUPArticle");
			let marge = $(tr).find("#margeArticle");
			let tva = $(tr).find("#tva");

			$.ajax({ //AJAX pour récupérer les infos d'un article.
		        type: "POST",
		        dataType: "json",
		        data:
		    	{
		        	'action':'infoArticle',
		    		'idArticle':$(tr).find("#idArticle").val()
		    	},
		        url: "../../ajax/ajoutDevisOuCommandesAjax.php",
		        success: function(r) {
					$(nom).val(r['libelle']);
					$(cmup).val(r['cmup']);
					$(marge).val(100*r['marge']);
					$(tva).val(100*r['tva']);
		        },
		        error: function (xhr, ajaxOptions, thrownError)
		        {
		        	console.log($(this));
		            console.log(xhr.status);
		            console.log(thrownError);
		            console.log(ajaxOptions);
		    	}
			});
		});			
	}
	
	
	//Affiche une popup de confirmation au clic sur enregistrer
	
	$("#enregistrer").click(function()
	{
		var erreur = false;
		if(confirm("Voulez-vous vraiment insérer ce devis dans la base de données ?"))
		//Si l'utilisateur confirme, on remplit un tableau
		//Datas des informations saisies.
		{	
			//Action prend al valeur de l'avant-dernière partie du lien, qui change entre devis ou commande
			var action = 'ajout'+location.href.split("/")[location.href.split("/").length-2];
			var dVente = {};
			var dArticles = {};
									
			var i = 1;
			var compteur = 0;
			dVente['nbArticles'] = rowCount;
			
			while(i<=rowCount)
        	//Boucle qui remplit le tableau de tous les articles.
			//Si l'id Article n'est pas choisi pour la ligne actuelle,
			//Alors on ne la prend pas en compte, en comptant
			//Le nombre de lignes invalides.
        	{
        		if($("#tr"+i).find("#idArticle").val() == "" || $("#tr"+i).find("#ttc").val() == "")
        		{
        			compteur++;
        			console.log("ligne "+i+ " vide");
        			dVente['nbArticles']--; 
        		}
        		else
        		{	
            		dArticles['idArticle'+(i-compteur)] = $("#tr"+i+" #idArticle").find(":selected").val();        			
	        		dArticles['CMUPArticle'+(i-compteur)] = $("#tr"+i+" #CMUPArticle").val();        		
	        		dArticles['qteArticle'+(i-compteur)] = $("#tr"+i+" #qteArticle").val();
	        		dArticles['txRemise'+(i-compteur)] = $("#tr"+i+" #txArticle").val();
	        		dArticles['obsArticle'+(i-compteur)] = $("#tr"+i+" #obsArticle").val();	        		
        		}
        		i++;
    		} 
			
			dVente['idVente'] = $("#idVente").val();
			dVente['idEmploye'] = $("#resp").val().split('-')[0].trim();//On split la chaine jusqu'au '-' pour garder le nombre.
			
			if($("#idClient").val() == null)
			{					
				$('#ttIdClient').tooltipster('open');//Si le try plante, on affiche des tooltips
				erreur = true;//Et on empêche ajax de se faire.
			}
			else
				dVente['idClient'] = $("#idClient").find(":selected").val();

        	if(!erreur)
    		{
		    	$.ajax({
			        type: "POST",
			        dataType: "json",
		            data:
		        	{
			        	'action':action,  
			        	'dVente':dVente,
			        	'dArticles':dArticles
		        	},
		            url: "../../ajax/ajoutDevisOuCommandesAjax.php",
			        success: function(r) {
			        	alert("Devis créé avec succès !");
			        	console.log(r);
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
		}
	});
	
	
	$('#idClient').change(function()
	//Affiche les informations de l'entreprise au changement de client du select.
	{
    	$.ajax({
	        type: "POST",
	        dataType: "json",
            data:
        	{
            	'action':'infoEntreprise',            	
        		'idClient':$('#idClient option:checked').val()
        	},
            url: "../../ajax/ajoutDevisOuCommandesAjax.php",
	        success: function(r) {
	        	console.log(r);
	        	$('#idSociete').val(r['idE'] + ' - ' + r['nomE']);
	        	$('#adrSociete').val(r['adrE']);
	        	$('#coordSociete').val(r['telE']);
	        },
	        error: function (xhr, ajaxOptions, thrownError)
	        {
	            console.log(xhr.status);
	            console.log(thrownError);
	            console.log(ajaxOptions);
	    	}
	    });	 
    	
	});
});


