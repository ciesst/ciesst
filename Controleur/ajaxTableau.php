<?php

//return the attributes of the slected model to the ajax function in a <table> component

include 'BddConnexion.php';
	
	
	$conn = new BddConnexion(); 
	$conn ->connect();
	$bdd= $conn->get_bdd();
	$id_model= $_POST['modele'];
		$sql = "SELECT Modele, n0, a1, a2, surface, K1, K2 FROM capteurs WHERE id=".$id_model;
		$res = $bdd->query($sql);
		$row = $res->fetch();
		
	
	echo "<table class='table table-bordered table-hover table-striped' id='tableau_capteur' name='tableau_capteur'><thead>
                                    <tr>
                                        <th>Modèle</th>
                                        <th><div class='infobulle'>
                                			<i>n0</i>
                                			<div>Rendement optique</div>
                                			</div>
                               		 	</th>
                                        <th><div class='infobulle'>
                                			<i>a1 (W/m².°C)</i>
                                			<div>Coefficient linéaire de transfert thermique</div>
                                			</div></th>
                                        <th><div class='infobulle'>
                                			<i>a2 (W/m².°C²)</i>
                                			<div>Coefficient quadratique de transfert thermique</div>
                                			</div></th>
                                        <th>Surface unitaire (m²)</th>
                                        <th><div class='infobulle'>
                                			<i>K1</i>
                                			<div>Facteur d'angle longitudinal</div>
                                			</div></th>
                                        <th><div class='infobulle'>
                                			<i>K2</i>
                                			<div>Facteur d'angle transversal</div>
                                			</div></th>
                                    </tr>
                                </thead>
                                <tbody>
                                	<tr>";
        echo '<tr><td>'.$row['Modele'].'</td><td>'.$row['n0'].'</td><td>'.$row['a1'].'</td><td>'.$row['a2'].'</td><td>'.$row['surface'].'</td><td>'.$row['K1'].'</td><td>'.$row['K2'].'</td></tr></tbody></table>';
        
	
	

	
?>