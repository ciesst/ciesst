<?php


//create a new line in the table "dimensionnement" with the specified location and the current user

session_start();

if(!isset($_SESSION['user'])){
	header('location: ../index.php');
}
include 'BddConnexion.php';
$conn = new BddConnexion(); 
$conn ->connect();
$bdd= $conn->get_bdd();

$lat=$_POST['lat'];
$lng=$_POST['lng'];



$req = $bdd->prepare('INSERT INTO dimensionnement (latitude,longitude,name_user) VALUES(:latitude,:longitude,:user)');

try{
$req->execute(array(
	'latitude' => $lat,
	'longitude' => $lng,
	'user' => $_SESSION['user']
	));	

}
catch(Exception $e){
	echo "Probleme: ", $e->getMessage();
	die();
}


header('location: ../Vue/dimensionner.php');

?>
