var lesOnglets = Array();
//Tableau contenant les elements de la div onglet.
//Il permettra d'attribuer des évènements en boucle.
$("document").ready(function(){

	lesOnglets.push("on-cmup");
	lesOnglets.push("on-mouv");
	
	$('.tooltip').tooltipster
	({
		trigger:'custom',
		triggerClose:
		{
			click:true
		},
	});	

	
	
	$("#div-"+lesOnglets[1]).addClass("hidden");

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
});




