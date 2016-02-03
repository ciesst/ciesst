<?php

//update the lines in the database with the values of the step 3

session_start();

if(!isset($_SESSION['user'])){
	header('location: ../index.php');
}

include 'BddConnexion.php';


$conn = new BddConnexion(); 
$conn ->connect();
$bdd= $conn->get_bdd();

$cap=$_POST['liste'];
$orientation=$_POST['orientation'];
$inclinaison=$_POST['inclinaison'];
$albedo=$_POST['albedo'];

if ($orientation == 'Sud') {
	$orientation = 0;

}

else if ($orientation == 'Ouest') {
	$orientation = 90;

}

else if ($orientation == 'Nord') {
	$orientation = 180;

}

else if ($orientation == 'Est') {
	$orientation = -90;
	
}

$last = $bdd->query('SELECT MAX(id) from dimensionnement where name_user=\''.$_SESSION['user'].'\'');

$lastID = $last->fetch();


$req = $bdd->prepare('UPDATE dimensionnement SET id_capteur= :cap, orientation= :orientation, inclinaison= :inclinaison, albedo= :albedo WHERE id='.$lastID[0]);

try{
$req->execute(array(
	'cap' => $cap,
	'orientation' => $orientation,
	'inclinaison' => $inclinaison,
	'albedo' => $albedo	
	));	

}
catch(Exception $e){
	echo "Probleme: ", $e->getMessage();
	die();
}
header('location: ../Vue/CALC.php');


?>