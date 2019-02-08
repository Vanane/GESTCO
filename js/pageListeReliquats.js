$('document').ready(function(){
	//Permet de changer, au clic sur le bouton de filtre, les blocs à afficher. 
	//On utilise une variable data-id écrite dans le HTML,qui correspond à l'un des index du
	//Tableau texteBtn. Lorsqu'on clique, data-id est multiplié par -1 afin de changer l'index
	//Dans lequel sera pris le texte.
	
	//Ce système permet d'alterner le texte du bouton en même temps que les blocs affichés.
	var texteBtn={'-1':'Afficher les reliquats à résoudre', '1':'Afficher les reliquats résolus'};
	$("#filtre").html(texteBtn[$("#filtre").attr("data-id")]);
		
	$("bloc").each(function(i, v){
	//Pour chaque <bloc>, on masque.
		$(v).css('display', 'none');			
	});
		$("bloc").each(function(i, v){	
		//Pour chaque <bloc> avec tags "non-resolu", on l'affiche. On n'obtient que les reliquats en attente.
			if($(this).attr("tags") == "non-resolu")
			{
				console.log(this);
				$(v).css('display', 'block');			
			}
	});
		
	
	//Quand on clique sur le bouton de filtre
	$("#filtre").click(function()
	{
		//On multiplie data-id par -1 pour changer l'index dans lequel on pioche le texte
		$(this).attr("data-id", $(this).attr("data-id")*(-1)); 
		//Puis on affecte le HTMl du bouton avec la valeur de cet index.
		$(this).html(texteBtn[$(this).attr("data-id")]);
		
		//Pour chaque élément bloc
		$("bloc").each(function(i, v)
		{			
			//S'il est masqué, on l'affiche, sinon, on le masque.
			if($(v).css('display') == 'none')
				$(v).css('display', 'block');	
			else
				$(v).css('display', 'none')
		});
	});		
});
