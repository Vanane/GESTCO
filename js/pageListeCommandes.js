$('document').ready(function(){
	$("bloc").each(function(i, v){
	//Pour chaque <bloc>, on masque.
		$(v).css('display', 'none');			
	});
		$("bloc#valide").each(function(i, v){	
		//Pour chaque <bloc> avec id valide, on l'affiche. On n'obtient que les devis validés.
			$(v).css('display', 'block');			
	});
		
	
	
	$.each($("#filtre p input"), function(index, value){
		$(value).click(function(){
			switch($(value).attr('value'))
			{
			case 'filtreV':
				
				$("bloc").each(function(i, v){
					let el = v;
					$(v).css('display', 'none');
				});
				
				$("bloc#valide").each(function(i, v){		
					$(v).css('display', 'block');
				});
				break;
			case 'filtreNonV':
				
				$("bloc").each(function(i, v){
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
