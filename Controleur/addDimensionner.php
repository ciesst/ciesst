<?php

//update the lines in the database with the values of the step 2

include 'BddConnexion.php';


$conn = new BddConnexion();
$conn ->connect();
$bdd= $conn->get_bdd();
session_start();

if(!isset($_SESSION['user'])){
	header('location: ../index.php');
}



	if (isset($_POST['checkbox_ballon'])) {
		
		$surf=$_POST['surface'];
		$usa=$_POST['usage'];
		$tempe=$_POST['temperature'];
		$ann=$_POST['annee_construction'];
		$chauffage=$_POST['type_chauffage'];
		$volume=$_POST['volume_ballon'];
		$last = $bdd->query('SELECT MAX(id) from dimensionnement where name_user=\''.$_SESSION['user'].'\'');;
		$electrique = $_POST['checkbox_electrique'];
		$lastID = $last->fetch();

		if($usa=='Eau Chaude Sanitaire'){
			$usage='ecs';
		}
		else if ($usa == 'Chauffage'){
			$usage='chauffage';
		}
		else{
			$usage='ecs+chauffage';
		}
		
		if ($chauffage =='Chauffage basse temperature') {

			$chauff='basse';

		}

		else {

			$chauff='haute';

		}

		if (isset($electrique))
        	{$bool_electrique = 1;}
        else
        	{$bool_elctrique = 0;};
        




		$req = $bdd->prepare('UPDATE dimensionnement SET surface= :surface, type_usage= :usage, temperature= :temperature, annee_construction= :annee, type_chauffage= :typechauffage, volume_ballon= :volumeballon, chauffage_electrique= :electrique WHERE id='.$lastID[0]);

		try{
			$req->execute(array(
			'surface' => $surf,
			'usage' => $usage,
			'temperature' => $tempe,
			'annee' => $ann,
			'typechauffage' => $chauff,
			'volumeballon' => $volume,
			'electrique' => $bool_electrique
			));	

		}
		catch(Exception $e){
			echo "Probleme: ", $e->getMessage();
			die();
		}

	}
	
	else {
		
		$hab=$_POST['habitants'];
		$surf=$_POST['surface'];
		$usa=$_POST['usage'];
		$tempe=$_POST['temperature'];
		$ann=$_POST['annee_construction'];
		$chauffage=$_POST['type_chauffage'];
		$volume=$_POST['volume_ballon'];
		$last = $bdd->query('SELECT MAX(id) from dimensionnement where name_user=\''.$_SESSION['user'].'\'');
		$electrique = $_POST['checkbox_electrique'];
		$lastID = $last->fetch();

		if($usa=='Eau Chaude Sanitaire'){
			$usage='ecs';
		}
		else if ($usa == 'Chauffage'){
			$usage='chauffage';
		}
		else{
			$usage='ecs+chauffage';
		}

		if ($chauffage =='Chauffage basse temperature') {

			$chauff='basse';

		}

		else {

			$chauff='haute';

		}

		if ($electrique == on)
        	{$bool_electrique = 1;}
        else
        	{$bool_elctrique = 0;};
        




		$req = $bdd->prepare('UPDATE dimensionnement SET nombre_habitant= :habitants, surface= :surface, type_usage= :usage, temperature= :temperature, annee_construction= :annee, type_chauffage= :typechauffage, chauffage_electrique= :electrique WHERE id='.$lastID[0]);

		try{
			$req->execute(array(
			'habitants' => $hab,
			'surface' => $surf,
			'usage' => $usage,
			'temperature' => $tempe,
			'annee' => $ann,
			'typechauffage' => $chauff,
			'electrique' => $bool_electrique
			));	

		}
		catch(Exception $e){
			echo "Probleme: ", $e->getMessage();
			die();
		}
	
	}

header('location: ../Vue/rendement.php');

?>