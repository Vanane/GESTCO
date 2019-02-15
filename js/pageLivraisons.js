//NC: Save Canvas, fonctionne mais les points ne sont pas très pratique pour signer.
$("document").ready(function(){
	var canvas = document.getElementById("mon_canvas");
	var c = canvas.getContext("2d");
	console.log("cv"+canvas);
	console.log("cv"+canvas.width);
	   mouseX = 0,
	   mouseY = 0,
	   width = 500,
	   height = 200,
	   colour = 'dark',
	   mousedown = false;
	canvas.width = width;
	canvas.height = height;	
	
	
	canvas.addEventListener( 'mousemove', function( event ) {
		  if( event.offsetX ){
		    mouseX = event.offsetX;
		    mouseY = event.offsetY;
		  } else {
		    mouseX = event.pageX - event.target.offsetLeft;
		    mouseY = event.pageY - event.target.offsetTop;
		  }
		  // call the draw function
		  if (mousedown) {
			    // set the colour
			    c.fillStyle = colour; 
			    // start a path and paint a circle of 20 pixels at the mouse position
			    c.beginPath();
			    c.arc( mouseX, mouseY, 5 , 15, Math.PI*2, true );
			    c.closePath();
			    c.fill();
			  }
		}, false );

		canvas.addEventListener( 'mousedown', function( event ) {
		    mousedown = true;
		}, false );
		canvas.addEventListener( 'mouseup', function( event ) {
		    mousedown = false;
		}, false );
		 var link = document.createElement('a');
	     link.innerHTML = 'download image';
	 link.addEventListener('click', function(ev) {
	     link.href = canvas.toDataURL();
	     link.download = "mypainting.png";//NC: a utiliser lors de l'insertion en BDD pour insérer m'image.
	 }, false);
	 document.body.appendChild(link);

});


function aide(){
	let elmt = document.getElementById("afficherAide");
	let elmt1 = document.getElementById("masquerAide");
	let elmt2 = document.getElementById("aide");
	toggleDisplay(elmt);
	toggleDisplay(elmt1);
	toggleDisplay(elmt2);
}
function toggleDisplay(elmt){		
	   if(typeof elmt == "string")		
	      elmt = document.getElementById(elmt);		
	   if(elmt.style.display == "none")		
	      elmt.style.display = "block";		
	   else		
	      elmt.style.display = "none";		
}


function gestionHistorique(){
	var elmt = document.getElementById("historique");
	var elmt1 = document.getElementById("encours");
	var elmt2 = document.getElementById("btn-historique");
	var elmt3 = document.getElementById("btn-encours");
	toggleDisplay(elmt);
	toggleDisplay(elmt1);
	toggleDisplay(elmt2);
	toggleDisplay(elmt3);
}

function ajouterlivraison() {
	if (confirm("Pour confirmer la livraison, cliqué sur 'ok', sinon cliquer sur 'annuler'."))
	{
		var erreur=false;
		let nbArticle= document.getElementById("nbArticle");
		let idEmploye= document.getElementById("idEmploye");
		let idVente = document.getElementById("idVente");
		var redirection =("http://localhost/GESTCO/Ventes/Livraisons/");;
		for(var i=0;i<nbArticle.value;i++)
		{
			console.log("dans le for/avant le if");
			var qteDemandee= document.getElementById("qteDemandee"+i);
			var qteFournie= document.getElementById("qteFournie"+i);
			if (qteDemandee.value != qteFournie.value)			
			{
				console.log("qte différente");
				erreur=true;
				if (confirm("Les quantités fournies sont différentes des quantités demandées, êtes vous sur de vouloir valider cette livraison ?"))
				{
				var idArticle=document.getElementById("idArticle"+i);
				var qte=qteDemandee.value - qteFournie.value;
				console.log(qte);
				erreur=false;
				$.ajax({ 
			        type: "POST",
			        dataType: "json",
			        data:
			    	{
			        	'action':'ajoutReliquat',
			        	'idV':idVente.value,
			        	'idA':idArticle.value,
			        	'idEmploye':idEmploye.value,
			        	'qte':qte,
			    	},
			        url: "../../../ajax/detailsLivraisonAjax.php",
			        success: function(r) {
			        	alert("Cette livraison a été rajouté aux reliquats avec succès");
			        	
			        },
			        error: function (xhr, ajaxOptions, thrownError)			        
			        {
			            console.log(xhr.status);
			            console.log(thrownError);
			            console.log(ajaxOptions);
			        }
				});
				}
				else
				{
				alert("Veuillez modifier les quantités fournies.");
				}
			}
			
		}
		if(!erreur)
		{
			$.ajax({ 
		        type: "POST",
		        dataType: "json",
		        data:
		    	{
		        	'action':'ajoutLivraison',
		        	'idV':idVente.value,
		        	'idEmploye':idEmploye.value,
		    	},
		        url: "../../../ajax/detailsLivraisonAjax.php",
		        success: function(r) {
		        	alert("Livraison confirmée avec succès !");
		        	location.href =(redirection);
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
