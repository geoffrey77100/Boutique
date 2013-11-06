<script>
function transfert(i)
{
var a = i;

	while (i > 1)
	{
		var quantite = document.getElementById("quantite"+i).value;
		if (quantite > 0)
			break;
		i--;
	}
	while (a > 1)
	{
	var id = document.getElementById("id"+a).value;
		if (quantite > 0)
			break;
		a--;
	}
	var idCategorie = document.getElementById("idCategorie").value;
	document.location.href="index.php?uc=voirProduits&idCategorie="+idCategorie+"&produit="+id+/*"&quantite="+quantite+"*/"&action=voirProduitsEtAjouterAuPanier";
}
</script>
<div id="produits">

<?php
	$i = 1;
foreach( $lesProduits as $unProduit) 
{
	$id = $unProduit->getId();
	$description = $unProduit->getDescription();
	$image = $unProduit->getImage();
	$prix = $unProduit->getPrix();
	$url ="<a href=index.php?uc=voirProduits&idCategorie=$idCategorie&produit=$id&action=voirProduitsEtAjouterAuPanier>commander </a>";
	
	echo "<ul>
	<input type='hidden' id='id".$i."' value=$id />
	<input type='hidden' id='idCategorie' value=$idCategorie />
			<tr><td><li><img src=".$image." alt=image width=100	height=100 /></li></td>
			<td><li>$description</li><br />
			<li>$prix  €</li><br />
			<li><input type='button' value='commander' onclick='javascript:transfert($i)' /></li><br />
			<input type='number' id='quantite".$i."'/>
			</td></tr>
		</ul>		
			
			";
			$i++;
}
?>
</div>
