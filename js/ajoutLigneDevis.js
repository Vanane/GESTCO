function ajouteLigneArticleDevis()
{
	var rowCount = document.getElementById("table-articles").rows.length;
	var element = document.getElementById("table-articles").innerHTML +=
        '<td><select id="idArticle'+rowCount+'"></select></td>'
		+'<td><input type="text" readonly></td>'
		+'<td><input id="CMUPArticle'+rowCount+'" type="number" readonly></td>'
		+'<td><input id="qteArticle'+rowCount+'" type="number" min=0></td>'
		+'<td><input id="txArticle'+rowCount+'" type="number" min=0 max=1></td>'
		+'<td><input type="number" readonly></td>'
		+'<td><input type="number" readonly></td>'
		+'<td><input type="number" readonly></td>'
		+'<td><input type="number" readonly></td>'
		+'<td><input id="obsArticle'+rowCount+'" type="text"></td>';
}