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
		tr += '<td><input id="qteArticle" type="number" min=1 value=1></td>';
		tr += '<td><input id="txArticle" type="number" min=0 max=100 step=0.5 value=0></td>';
		tr += '<td><input id="remise" type="number" readonly></td>';
		tr += '<td><input id="ht" type="number" readonly></td>';
		tr += '<td><input id="tva" type="number" readonly></td>';
		tr += '<td><input id="ttc" type="number" readonly></td>';
		tr += '<td><input id="obsArticle" type="text"></td>';
		tr += '</tr>';		
		
		//Ajoute au tableau le HTML écrit au-dessus
		$("#table-articles").append(tr);	
		
		//Affecte au select de la nouvelle ligne, la liste des articles de la première ligne.
		$("#table-articles #tr1 #idArticle option").each(function(index, option)
		{ 
			var text = option.text;
			var value = option.value;
			$("#tr"+rowCount+" #idArticle").append(new Option(text, value));
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
			let marge = $(tr).find("#margeArticle");
			let tva = $(tr).find("#tva");
			let rem = $(tr).find("#remise");
			let txrem = $(tr).find("#txArticle");
			let ht = $(tr).find("#ht");
			let ttc = $(tr).find("#ttc");
			if(qte.val()<0 || qte.val()=="")
				qte.val(0);
			if(txrem.val()<0 || txrem.val()>100 || txrem.val()=="")
				txrem.val(0);
			
			ht.val(qte.val()*cmup.val()*(1+marge.val()/100));//Hors-taxe = qte * prix unité * marge à faire.
			ttc.val(ht.val()*(1+(tva.val()/100)));//TTC = HT * tva en %
			rem.val(ttc.val()*(txrem.val()/100));//Remise = TTC * tx Remise en %
			ttc.val(ttc.val()-rem.val());//TTC = TTC - Remise €
			
		});
	}
	
	
	function ajouteListenerChargeArticle(idTr)
	{
		let tr = $("#tr"+idTr);
		$(tr).find("#idArticle").change(function(e)
		{
			let trouve = false;			
			$("#table-articles tr").each(function(index, value){
				if($(tr).find("#idArticle").find(":selected").val() == $(this).find("#idArticle").find(":selected").val() &&
				$(this).attr('id') != $(tr).attr('id') && 
				!trouve)
				//Quand select changé : si l'index choisi est égal à au moins l'un des autres indexs des autres selects, 
				//Et que le select cliqué n'est pas celui auquel on le compare dans le each, 
				//Et qu'il n'y a pas encore eu de condition trouvée,
				//Alors on affiche un message d'erreur et on empêche de sélectionner ledit index, pour éviter les doublons.
				{
					alert("Vous avez déjà choisi cet article ! Pour ajouter plusieurs exemplaires, modifiez la quantité de l'article.");
					$(tr).find("#idArticle").val($(tr).find("#idArticle option")[0]);
					trouve = true;
				}
				
			});

			
			
			if(!trouve)//Si aucun doublon n'a été trouvé, alors on peut faire l'AJAC
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
			        url: "../../ajax/ajoutDevisOuCommandeAjax.php",
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
			}	
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
	        		dArticles['qteArticle'+(i-compteur)] = $("#tr"+i+" #qteArticle").val();
	        		dArticles['txRemise'+(i-compteur)] = ($("#tr"+i+" #txArticle").val()/100);
	        		dArticles['CMUPArticle'+(i-compteur)] = ($("#tr"+i+" #CMUPArticle").val());
	        		dArticles['margeArticle'+(i-compteur)] = ($("#tr"+i+" #margeArticle").val()/100);
	        		dArticles['tva'+(i-compteur)] = ($("#tr"+i+" #tva").val()/100);
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
		            url: "../../ajax/ajoutDevisOuCommandeAjax.php",
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
            url: "../../ajax/ajoutDevisOuCommandeAjax.php",
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


