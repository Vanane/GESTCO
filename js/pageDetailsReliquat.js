$('document').ready(function(){
	$('.div-liste bloc').each(function(index, value){
		
		let bloc = $(value);
		let text = $(bloc).find("#obsReliquat");
		$(bloc).find('sub').html("("+($(text).prop('maxlength') - $(text).val().length)+" signes restants)");
		
		$(text).keydown(function(){
			$(bloc).find('sub').html("("+($(text).prop('maxlength') - $(text).val().length)+" signes restants)");			
		});	
		
		$(bloc).find
	});
	
	
	$("#confirmer").click(function(){
		$('.div-liste bloc').each(function(index, value){			
			let bloc = $(value);	
			if($(bloc).find("#montReliquat").val() < 0 || $(bloc).find("#montReliquat").val() == "")
			{
				alert("Le montant saisi pour l'article "+$(bloc).find("#artReliquat").val()+" est invalide !");
			}
			else
			{
				
			}
		});
	});
	
	
});
