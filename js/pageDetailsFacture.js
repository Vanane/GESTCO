$("document").ready(function(){
	$("#validePaiement").click(function(){
		//Ouvre un nouvel onglet vers GESTCO/Facturations/xxx/facture pour afficher la facture de la vente xxx.
		//Le code du générateur de PDF se trouve dans le controleur, est c'est l'index qui se charge de rediriger vers la facture.
		window.open(location.href+'/facture', '_blank');
	});	
});