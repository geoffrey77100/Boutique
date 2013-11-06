<?php

function connexion()
{
   $hote="localhost";
   $login="root";
   $mdp="";
   $connexion= mysql_connect($hote, $login, $mdp);
   $bd="Boutique";
   $query="SET CHARACTER SET utf8";
   // Modification du jeu de caractères de la connexion
   mysql_query($query, $connexion);
   mysql_select_db($bd, $connexion) or die("erreur select db");
   return $connexion;
}

function getLesCategories()
{
	$connexion = connexion();
	$req="select * from categorie";
   	$rsCategorie = mysql_query($req, $connexion);
   	$lgCategorie = mysql_fetch_array($rsCategorie);
   	$lesCategories=array();
	// Boucle sur les catégories
  	while ($lgCategorie != FALSE)
   	{ $idCategorie = $lgCategorie["idCategorie"];
	  $categorie = new Categorie($idCategorie,$lgCategorie["libelle"]);
      $lesCategories[$idCategorie]=$categorie;
	  $lgCategorie = mysql_fetch_array($rsCategorie);
   	}
	mysql_close();
	return $lesCategories;
}

 function getLesProduits($uneCategorie)
{
	$connexion = connexion();
	$req="select * from produit where idCategorie = '$uneCategorie'";
	//echo $req;
   	$rsProduit = mysql_query($req, $connexion);
    $lgProduit = mysql_fetch_array($rsProduit);
   	$lesProduits = array();
   	while ($lgProduit != FALSE)
   	{
    	$produit = new Produit($lgProduit["idProduit"],$lgProduit["description"],$lgProduit["image"], $lgProduit["prix"]);	
		$lesProduits[$lgProduit["idProduit"]]=$produit;		
		$lgProduit = mysql_fetch_array($rsProduit);
 	}
	mysql_close();
	return $lesProduits;
}
function getProduit($unId)
{
	$connexion = connexion();
	$req="select * from produit where idProduit = '$unId'";
	//echo $req;
   	$rsProduit = mysql_query($req, $connexion);
    $lgProduit = mysql_fetch_array($rsProduit);
	$produit = null;
   	if ($lgProduit != FALSE)
   	{
    	$produit = new Produit($lgProduit["idProduit"],$lgProduit["description"],$lgProduit["image"], $lgProduit["prix"]);	
	}
	mysql_close();
	return $produit;
}
function initPanier()
{
	if(!isset($_SESSION['panier']))
		$_SESSION['panier']= new Panier();
}
function ajouterAuPanier($idProduit, $quantite)
{	
	$_SESSION['panier']->ajoutItem($idProduit,$quantite);	
}
function retirerDuPanier($idProduit, $quantite)
{
	$_SESSION['panier']->suppressionItem($idProduit, $quantite);
}
function getLesProduitsDuPanier()
{	$lesProduits = array();
	if (isset($_SESSION["panier"])){		
		$panier = $_SESSION["panier"]->recupPanier();		
		foreach($panier as $id => $qte)
		{
				$produit = getProduit($id);
				$lesProduits[]=$produit;
		}		
	}
	return $lesProduits;
}
function getLesQuantitesDuPanier()
{	
	$lesQuantites = array();
	if (isset($_SESSION["panier"])){	
		$panier = $_SESSION["panier"]->recupPanier();	
		foreach($panier as $id => $qte)
		{
				$lesQuantites[]=$qte;
		}				
	}
	return $lesQuantites;		
}
function creerCommande($nom,$rue,$cp,$ville,$mail )
{
	$connexion = connexion();
	$req="select max(idCommande) as maxi from commande";
   	$rsCategorie = mysql_query($req, $connexion);
   	$lgCategorie = mysql_fetch_array($rsCategorie);
   	$idCommande = $lgCategorie['maxi'];
   	$idCommande++;
	$date=date("Y-m-j");
   	$req = "insert into commande values ('$idCommande','$date','$nom','$rue','$cp','$ville','$mail')";
   	$rsCommande = mysql_query($req, $connexion);
   	$panier = $_SESSION['panier']->recupPanier();
	foreach($panier as $id=>$qte)
	{
		$req = "insert into contenir values ('$idCommande','$id')";
		$rsCategorie = mysql_query($req, $connexion);	
	}
	
	mysql_close();
	session_destroy();
}
function estUnCp($codePostal)
{
   // Le code postal doit comporter 5 chiffres
   return strlen($codePostal)== 5 && estEntier($codePostal);
}

// Si la valeur transmise ne contient pas d'autres caractères que des chiffres,
// la fonction retourne vrai
function estEntier($valeur)
{
   return !preg_match ("/^[^0-9]./", $valeur);
}
function estUnMail($mail)
{
$regexp="/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i";
return preg_match ($regexp, $mail);
}
function getErreursSaisieCommande($cp,$mail)
{
 $lesErreurs = array();
 if(!estUnCp($cp))
 	$lesErreurs[]= "erreur de code postal";
 if(!estUnMail($mail))
 	$lesErreurs[]= "erreur de mail";
 return $lesErreurs;
}
?>
