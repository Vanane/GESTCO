$('document').ready(function(){
	$("bloc").each(function(i, v){
	//Pour chaque <bloc>, on masque.
		$(v).css('display', 'none');			
	});
		$("bloc#valide").each(function(i, v){	
		//Pour chaque <bloc> avec id valide, on l'affiche. On n'obtient que les devis validés.
			$(v).css('display', 'block');			
	});
		
	
	
	//Pour chaque bouton radio dans la div fitre, on affecte un evenement click.
	$.each($("#filtre p input"), function(index, value){
		//Lorsqu'on clique, l'id du bouton cliqué sert de switch.
		$(value).click(function(){
			switch($(value).attr('value'))
			{
				case 'filtreV':					
					//Si l'id est "filtreV", on affiche les blocs avec id "valide"
					$("bloc#non-valide").each(function(i, v){
						let el = v;
						$(v).css('display', 'none');
					});
					
					$("bloc#valide").each(function(i, v){		
						$(v).css('display', 'block');
					});
					break;
				case 'filtreNonV':
					//Si l'id est "filtreNonV", on affiche les blocs sans id.				
					$("bloc#non-valide").each(function(i, v){
						let el = v;
						$(v).css('display', 'block');
					});
					
					$("bloc#valide").each(function(i, v){		
						$(v).css('display', 'none');
					});				
				break;
			}
		});
	});
});
