$("document").ready(function(){
	
	var rowCount = $("#details-preparation-articles row").length;//On sauvegarde le nombre d'articles.	
	
	$("#btnAide").click(function(){
		$("aide").css('display', 'none');//Sur clic, masque le texte de présentation
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
				$("#btnArticle"+inc).css('background', '#55dddd');//Sinon couleur = rouge
				$("#rowArticle"+inc).find("#qteFournie").prop("readonly", true);//Et on bloque l'input pour la quantité.			
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
		let reliquat = false;
		let i = 0;
		while(i<=rowCount)
		{
			i++;
			if($("#rowArticle"+i).find("#codeArticle").val() == $("#rowArticle"+i).find("#codeScan").val())
			{
				if(parseInt($("#rowArticle"+i).find("#qteFournie").val()) < parseInt($("#rowArticle"+i).find("#qteDemandee").val()))
				{
					alert("L'article "+$("#btnArticle"+i).html()+" n'a pas été remis en quantité suffisante. Souhaitez-vous continuer ?");
					reliquat = true;			
				}
			}
			else
			{
				alert("L'article "+$("#btnArticle"+i).html()+" n'a pas été prix en compte ! Vérifiez le code scan entré.");				
				error = true;
			}
		}
		
		//If error : Rien
		//If reliquat : action = reliquat, Else : action = pasReliquat 
		
		
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