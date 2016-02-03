<?php

//add a capteur with the specified values in the database

session_start();
if(!isset($_SESSION['user'])){
	header('location: index.php');
}

include 'BddConnexion.php';
header('cache-control: no-cache');
$conn = new BddConnexion(); 
$conn ->connect();
$bdd= $conn->get_bdd();

$type=$_POST['type'];
$modele=$_POST['modele'];
$n0=$_POST['n0'];
$a1=$_POST['a1'];
$a2=$_POST['a2'];
$surface=$_POST['surface'];
$k1=$_POST['K1'];
$k2=$_POST['K2'];

if($type == 1){
$res = "plan vitre";
}
else{
$res = "sous vide";
}

$req = $bdd->prepare('INSERT INTO capteurs (Type,Modele,n0,a1,a2,surface,K1,K2,user) VALUES(:type,:modele,:n0,:a1,:a2,:surface,:K1,:K2,:user)');

try{
$req->execute(array(
	'type' => $res,
	'modele' => $modele,
	'n0' => $n0,
	'a1' => $a1,
	'a2' => $a2,
	'surface' => $surface,
	'K1' => $k1,
	'K2' => $k2,
	'user' => $_SESSION['user']
	));	

}
catch(Exception $e){
	echo "Probleme: ", $e->getMessage();
	die();
}




?>