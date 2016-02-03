
<?php
//return the good models (good type and current user) to the ajax function in a <select> component
session_start();
if(!isset($_SESSION['user'])){
	header('location: index.php');
}
include 'BddConnexion.php';
	
	echo $sql;
	echo "<select class='form-control' name='liste' onchange='go2()'>";
	$conn = new BddConnexion(); 
	$conn ->connect();
	$bdd= $conn->get_bdd();
	
		if($_POST["type_capteur"] == 1){
			$type_name = "plan vitre";
		}
		else{
			$type_name = "sous vide";
		}
		
		
		$sql = "SELECT id,Modele FROM capteurs WHERE Type=\"".$type_name."\" AND (user=\"".$_SESSION['user']."\" OR user=\"admin\") order by Modele";
		
		
		
		$res = $bdd->query($sql);
		
		foreach($res as $row){
			echo "<option value='".$row["id"]."'>".$row["Modele"]."</option>";
		}
		
		
	
	echo "</select>";

	
?>
