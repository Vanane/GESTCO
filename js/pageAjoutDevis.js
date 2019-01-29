var lesSelectsArticles = new Array();//Contient tous les selects de chaque ligne article.

$('document').ready(function(){	//Lorsque le document sera prêt à exécuter le script.
	
	$.ajax({ //AJAX pour récupérer les infos d'un article.
        type: "POST",
        dataType: "json",
        url: "../../ajax/ajoutDevisAjax.php",
        success: function(r) {
        	document.getElementById("dateDevis").value = r['date'];
        }
	});

	
	ajouteListenerCalculTTC("qteArticle1");//Ajoute un listener qui permet de calculer le prix au numeric qteArticle1
	ajouteListenerCalculTTC("txArticle1");//Pareil pour txArticle1, ces deux éléments devant être initialisés de base.

	$('.tooltip').tooltipster
	({
		trigger:'custom',
		triggerClose:
		{
			click:true
		},
	});	
	
	lesSelectsArticles.push(document.getElementById("idArticle1"));//Ajoute le premier select dans la table	
	ajouteListener();//Puis lui attribue un event au clic.

	$("#ajouteLigne").click(function()
	//Ajoute une ligne dans la table des articles au clic sur le bouton.
	{		
		var element = document.getElementById("table-articles");
		var rowCount = element.rows.length;

		//Permt d'insérer les lignes sans que tout le tableau soit réécrit, contrairement
		//A la solution avec innerHTML+="xxx".
		tr = element.insertRow();//Ajoute une ligne dans le tableau
		
		//Ajoute les cases dans la ligne ainsi que le HTML correspondant, avec
		//L'incrémentation pour gérer chaque article.
		tr.insertCell().innerHTML = '<select id="idArticle'+rowCount+'"></select>';
		tr.insertCell().innerHTML = '<input id="nomArticle'+rowCount+'" type="text" readonly>';
		tr.insertCell().innerHTML = '<input id="CMUPArticle'+rowCount+'" type="number" readonly>';
		tr.insertCell().innerHTML = '<input id="margeArticle'+rowCount+'" type="number" readonly>';
		tr.insertCell().innerHTML = '<input id="qteArticle'+rowCount+'" type="number" min=0 value=0>';
		tr.insertCell().innerHTML = '<input id="txArticle'+rowCount+'" type="number" min="0" max="100" value=0 step:0.5>';
		tr.insertCell().innerHTML = '<td><input id="remise'+rowCount+'" type="number" readonly></td>';
		tr.insertCell().innerHTML = '<td><input id="ht'+rowCount+'" type="number" readonly></td>';
		tr.insertCell().innerHTML = '<td><input id="tva'+rowCount+'" type="number" readonly></td>';
		tr.insertCell().innerHTML = '<td><input id="ttc'+rowCount+'" type="number" readonly></td>';
		
		tr.insertCell().innerHTML = '<input id="obsArticle'+rowCount+'" type="text">';

		afficheLesArticles(rowCount);//Affecte au select de la nouvelle ligne, la liste des articles de la première ligne.
		
		lesSelectsArticles.push(document.getElementById("idArticle"+rowCount));//Ajoute le select de la nouvelle ligne à la table des selects.
		ajouteListenerCalculTTC("txArticle"+rowCount);
		ajouteListenerCalculTTC("qteArticle"+rowCount);

		
		ajouteListener();//Relance l'affectation des events clic.
	});

	
	function ajouteListenerCalculTTC(idElement)	
	{
		let el = document.getElementById(idElement);
		var numElement = el.id.substring(idElement.length-1,idElement.length);
		el.onchange = function()
		{
			let qte = document.getElementById("qteArticle"+numElement);
			let cmup = document.getElementById("CMUPArticle"+numElement);
			let tva = document.getElementById("tva"+numElement);
			let rem = document.getElementById("remise"+numElement);
			let txrem = document.getElementById("txArticle"+numElement);
			let ht = document.getElementById("ht"+numElement);
			let ttc = document.getElementById("ttc"+numElement);
			
			if(qte.value<0 || qte.value=="")
				qte.value=0;
			if(txrem.value<0 || txrem.value>100 || txrem.value=="")
				txrem.value=0;
			
			ht.value = qte.value*cmup.value;
			ttc.value = ht.value*(1+(tva.value/100));
			rem.value = ttc.value*(txrem.value/100);//Math.round et /100 pour arrondir à 2 décimales
			ttc.value = ttc.value-rem.value;
		}
	}
	
	
	function ajouteListener()
	//Récupère tous les éléments de la table lesSelectsArticles
	//Qui sont ajoutés à mesure que les lignes article sont créés,
	//Puis leur attribue à chacun un nouveau listener.
	//Cette méthode permet de ne pas confondre les events entre eux
	//A cause du scope des variables.
	//Même démarche pour les number %remise, qui au changement, vont recalculer le TTC.
	{
		for (var i = 0; i < lesSelectsArticles.length; i++)
		{
			let item = lesSelectsArticles[i];		
			document.getElementById(item.id).onchange = function()
			{
				//Récupérer les élements par leur nom + le numéro du select qui est activé.
				//Pour cela, on récupère l'id du select, auquel on substring à partir du 
				//9ème caractère, jusqu'à la fin (item.id.length). 9ème caractère car il y a
				//9 caractères dans "idArticle".
				
				let nom = document.getElementById("nomArticle"+item.id.substring(9,item.id.length));
				let cmup = document.getElementById("CMUPArticle"+item.id.substring(9,item.id.length));
				let marge = document.getElementById("margeArticle"+item.id.substring(9,item.id.length));
				let tva = document.getElementById("tva"+item.id.substring(9,item.id.length));
				
				$.ajax({ //AJAX pour récupérer les infos d'un article.
			        type: "POST",
			        dataType: "json",
			        data:
			    	{
			        	'action':'infoArticle',
			    		'idArticle':item.value
			    	},
			        url: "../../ajax/ajoutDevisAjax.php",
			        success: function(r) {
			        	nom.value = r['libelle'];
			        	cmup.value = r['cmup'];
			        	marge.value = 100*r['marge'];
			        	tva.value = 100*r['tva'];
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
		}
	}	
	//Affiche une popup de confirmation au clic sur enregistrer
	
	$("#enregistrer").click(function()
	{
		var erreur = false;
		if(confirm("Voulez-vous vraiment insérer ce devis dans la base de données ?"))
		//Si l'utilisateur confirme, on remplit un tableau
		//Datas des informations saisies.
		{						
			var dVente = {};
			var dArticles = {};
			
			var rowCount = document.getElementById("table-articles").rows.length-1;
			var i = 1;
			var compteur = 0;
			while(i<=rowCount)
        	//Boucle qui remplit le tableau de tous les articles.
			//Si l'id Article n'est pas choisi pour la ligne actuelle,
			//Alors on ne la prend pas en compte, en comptant
			//Le nombre de lignes invalides.
        	{				
        		if(dArticles['idArticle'+(i-compteur)] = $("#idArticle"+i).find(":selected").val() == null)
        		{
        			compteur++;
        		}
        		else
        		{	
            		dArticles['idArticle'+(i-compteur)] = $("#idArticle"+i).find(":selected").val();        			
	        		dArticles['CMUPArticle'+(i-compteur)] = $("#CMUPArticle"+i).val();        		
	        		dArticles['qteArticle'+(i-compteur)] = $("#qteArticle"+i).val();
	        		dArticles['txRemise'+(i-compteur)] = $("#txArticle"+i).val();
	        		dArticles['obsArticle'+(i-compteur)] = $("#obsArticle"+i).val();	        		
        		}
        		i++;
    		} 
			
			try
			{
				dVente['idVente'] = $("#idVente").val();
				dVente['idEmploye'] = $("#respDevis").val().split('-')[0].trim();//On split la chaine jusqu'au '-' pour garder le nombre.
				dVente['idClient'] =$("#idClient").val().split('-')[0].trim();
				dVente['dateDevis'] = 
							new Date().getFullYear()+'-'+
							new Date().getMonth()+1+'-'+
							new Date().getDate()+' '+
							new Date().getHours()+':'+
							new Date().getMinutes()+':'+
							new Date().getSeconds();
				//Format de mysql : aaaa-mm-jj hh:mm:ss.
				
				dVente['nbArticles'] = rowCount-compteur//Nombre de lignes comptées - les lignes invalides
			}	
			catch
			{
				$('#ttIdClient').tooltipster('open');//Si le try plante, on affiche des tooltips
				erreur = true;//Et on empêche ajax de se faire.
			}
        	
			console.log(dArticles);
        	if(!erreur)
    		{
		    	$.ajax({
			        type: "POST",
			        dataType: "json",
		            data:
		        	{
			        	'action':'ajoutDevis',  
			        	'dVente':dVente,
			        	'dArticles':dArticles
		        	},
		            url: "../../ajax/ajoutDevisAjax.php",
			        success: function(r) {
			        	alert("Devis créé avec succès !");
			        	console.log(r);
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
	
	
	$('#idClient').on("change", (function()
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
            url: "../../ajax/ajoutDevisAjax.php",
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
    	
	}));
});



function afficheLesArticles(id)
//Remplit le select de la nouvelle ligne article par les options
//De la ligne initiale.
{
	$("#idArticle1 option").each(function(index, option)
	{
		var text = option.value;
		$("#idArticle"+id).append(new Option(text, text));
	});
	$("#idArticle"+id).val(0);
}


