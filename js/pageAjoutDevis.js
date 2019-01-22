var lesSelectsArticles = new Array();//Contient tous les selects de chaque ligne article

$('document').ready(function(){
	lesSelectsArticles.push(document.getElementById("idArticle1"));//Ajoute le premier select dans la table	
	ajouteListener();//Puis lui attribue un event au clic.
	
	
	$("#bou-plusLigne").click(function()
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
		tr.insertCell().innerHTML = '<input id="qteArticle'+rowCount+'" type="number" min=0>';
		tr.insertCell().innerHTML = '<input id="txArticle'+rowCount+'" type="number" min=0 max=1>';
		for(i = 0;i<4;i++)
		{
			tr.insertCell().innerHTML = '<input type="number" readonly></td>';
		}
		
		tr.insertCell().innerHTML = '<input id="obsArticle'+rowCount+'" type="text">';

		afficheLesArticles(rowCount);//Affecte au select de la nouvelle ligne, la liste des articles de la première ligne.
		
		lesSelectsArticles.push(document.getElementById("idArticle"+rowCount));//Ajoute le select de la nouvelle ligne à la table des selects.
		
		ajouteListener();//Relance l'affectation des events clic.
	});

	
	function ajouteListener()
	//Récupère tous les éléments de la table lesSelectsArticles
	//Qui sont ajoutés à mesure que les lignes article sont créés,
	//Puis leur attribue à chacun un nouveau listener.
	//Cette méthode permet de ne pas confondre les events entre eux
	//A cause du scope des variables.
	{
		for (var i = 0; i < lesSelectsArticles.length; i++) {
			let item = lesSelectsArticles[i];
			document.getElementById(item.id).onchange = function() {
				let nom = document.getElementById("nomArticle"+item.id.substring(9,10));
				let cmup = document.getElementById("CMUPArticle"+item.id.substring(9,10));
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
	}
	
	$("#bou-confirmerDevis").click(function()
	//Affiche une popup de confirmation au clic sur enregistrer
	{
		if (confirm("Voulez-vous vraiment insérer ce devis dans la base de données ?"))
		{
			location = "Confirmer";
		}
	});

/*		
		/*idA = 'idArticle' + i;  
		txA = 'txArticle'+i;
		CMUP = 'CMUPArticle'+i;
		qte = 'qteArticle'+i;
		obs= 'obsArticle'+i;
		
		datas[idA] = $('#'+idA+'option:checked').val();//Récupère la valeur sélectionnée dans la combobox idArticleX
		datas[txA] = $('#'+txA).val();//Récupère la valeur du taux de remise en %
		datas[CMUP] = $('#'+CMUP).val();//Récupère la valeur du CMUP actuel du produit
		datas[qte] = $('#'+qte).val();//Récupère la quantité du produit
		datas[obs] = $('#'+obs).val();//Récupère les observations éventuelles
	}*/

	
	
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
}

