<?php

session_start();

if(!isset($_SESSION['user'])){
	header('location: ../index.php');
}

include '../Controleur/BddConnexion.php';
include '../Controleur/calculs_Chauffage.php';
include '../Controleur/calculs_ECS.php';
include '../Controleur/calculs_IGP.php';
include '../Controleur/triangle.php';
include '../Controleur/F_chart.php';

$month=array('2016-01','2016-02','2016-03','2016-04','2016-05','2016-06','2016-07','2016-08','2016-09','2016-10','2016-11','2016-12');

$conn = new BddConnexion(); 
$conn ->connect();
$bdd= $conn->get_bdd();

$last = $bdd->query('SELECT * from dimensionnement where id=(SELECT MAX(id) from dimensionnement where name_user=\''.$_SESSION['user'].'\')');

$lastDim = $last->fetch();

$id_capteur=$lastDim['id_capteur'];

$usage = $lastDim['type_usage'];

$cap = $bdd->query('SELECT * from capteurs where id='.$id_capteur);

$capteur = $cap->fetch();

$stations_proches = stations_triangle(calculs_distance($lastDim['longitude'],$lastDim['latitude'],$bdd),$lastDim['longitude'],$lastDim['latitude']);



if(sizeof($stations_proches)==3){
	
	//	echo '<br> Stations choisies : <br>';
foreach($stations_proches as $ville){
	//echo $ville[1].' : '.$ville[0]. ' m long : 0'.$ville[2].' lat : '.$ville[3].'<br>'; //affiche les stations de la plus proche � la plus �loign�e
}
	$stations = interpolation($stations_proches,$bdd);
}
else{
	$stations=station_unique($stations_proches,$bdd);
//	echo '<br> Station choisie : <br>';
	//echo $stations[0]['nom'].'<br>';
}



$Coeff_dep = G_dep($lastDim['annee_construction']);

foreach($stations as $row){
	$calc_igp[] = new calculs_IGP($lastDim['longitude'],$lastDim['latitude'],$lastDim['orientation'],$lastDim['inclinaison'],$lastDim['albedo'],$row['mois'],$row['igh_kw']);
	
	if($usage == 'ecs'){
	$calc_ecs[] = new calculs_ECS($lastDim['temperature'],$lastDim['nombre_habitant'],$row['mois'],$row['temp_eau'],$lastDim['volume_ballon'],$lastDim['chauffage_electrique']);
	$calc_chauff[] = new calculs_Chauffage($lastDim['surface'],2.5,$row['mois'],$Coeff_dep,0);	
	}
	elseif($usage=='chauffage'){
		$calc_ecs[] = new calculs_ECS(0,0,$row['mois'],0,$lastDim['volume_ballon'],$lastDim['chauffage_electrique']);
	$calc_chauff[] = new calculs_Chauffage($lastDim['surface'],2.5,$row['mois'],$Coeff_dep,$row['dju_quot']);
	}
	else{
	$calc_ecs[] = new calculs_ECS($lastDim['temperature'],$lastDim['nombre_habitant'],$row['mois'],$row['temp_eau'],$lastDim['volume_ballon'],$lastDim['chauffage_electrique']);
	$calc_chauff[] = new calculs_Chauffage($lastDim['surface'],2.5,$row['mois'],$Coeff_dep,$row['dju_quot']);
	}
}
// var_dump($stations);
$i=1;
$taux_juillet=0;
$moyenne_couverture=0;

if($usage == 'chauffage'){
$moyenne_couverture=0;

while($moyenne_couverture<=30){
	$mois=0;
	$moyenne_couverture=0;
foreach($stations as $row){	
	
	$couverture = new F_chart($lastDim['surface'],2.5,$row['mois'],$lastDim['nombre_habitant'],'haute',$Coeff_dep,$capteur,$i*$capteur['surface'],$calc_igp[$mois]->Igp,$calc_chauff[$mois]->Besoins_ch,$calc_ecs[$mois]->Becs,$usage,$row['temp_ext'],$row['temp_eau'],$lastDim['temperature'],$lastDim['volume_ballon'],$lastDim['chauffage_electrique']);
	$moyenne_couverture=$moyenne_couverture+$couverture->Taux_couverture;
	$calc_couverture[]= $couverture;			
	$mois++;	
}
$moyenne_couverture=$moyenne_couverture/12;

$i++;
}
}
else{
	while($taux_juillet<100){
	$mois=0;
foreach($stations as $row){
	
	$couverture = new F_chart($lastDim['surface'],2.5,$row['mois'],$lastDim['nombre_habitant'],'haute',$Coeff_dep,$capteur,$i*$capteur['surface'],$calc_igp[$mois]->Igp,$calc_chauff[$mois]->Besoins_ch,$calc_ecs[$mois]->Becs,$usage,$row['temp_ext'],$row['temp_eau'],$lastDim['temperature'],$lastDim['volume_ballon'],$lastDim['chauffage_electrique']);
	$calc_couverture[]= $couverture;
	
	if($row['mois']=='juillet'){
		 $taux_juillet = $couverture->Taux_couverture;		
	}	
	$mois++;	
}
$i++;
}
}

$i=$i-1;
$mois=0;
$moyenne_couverture=0;
$moyenne_couverture_plafond=0;

foreach($stations as $row){
	
	$calc_couverture_final[]=new F_chart($lastDim['surface'],2.5,$row['mois'],$lastDim['nombre_habitant'],$lastDim['type_chauffage'],$Coeff_dep,$capteur,$i*$capteur['surface'],$calc_igp[$mois]->Igp,$calc_chauff[$mois]->Besoins_ch,$calc_ecs[$mois]->Becs,$usage,$row['temp_ext'],$row['temp_eau'],$lastDim['temperature'],$lastDim['volume_ballon'],$lastDim['chauffage_electrique']);
	
	$taux_couverture= $calc_couverture_final[$mois]->Taux_couverture;
	
	$moyenne_couverture=$moyenne_couverture+$taux_couverture;
	
	if($taux_couverture>100){
		
		$taux_couverture=100;
	}
	
	$moyenne_couverture_plafond=$moyenne_couverture_plafond+$taux_couverture;
	
	$mois++;
	}

$moyenne_couverture=$moyenne_couverture/12;
$moyenne_couverture_plafond=$moyenne_couverture_plafond/12;


$incli=array(20,30,40);

foreach($stations as $row){
	$igp1[] = new calculs_IGP($lastDim['longitude'],$lastDim['latitude'],$lastDim['orientation'],$incli[0],$lastDim['albedo'],$row['mois'],$row['igh_kw']);
	$igp2[] = new calculs_IGP($lastDim['longitude'],$lastDim['latitude'],$lastDim['orientation'],$incli[1],$lastDim['albedo'],$row['mois'],$row['igh_kw']);
	$igp3[] = new calculs_IGP($lastDim['longitude'],$lastDim['latitude'],$lastDim['orientation'],$incli[2],$lastDim['albedo'],$row['mois'],$row['igh_kw']);
}

$mois=0;
foreach($stations as $row){	
	$calc1[]=new F_chart($lastDim['surface'],2.5,$row['mois'],$lastDim['nombre_habitant'],$lastDim['type_chauffage'],$Coeff_dep,$capteur,$i*$capteur['surface'],$igp1[$mois]->Igp,$calc_chauff[$mois]->Besoins_ch,$calc_ecs[$mois]->Becs,$usage,$row['temp_ext'],$row['temp_eau'],$lastDim['temperature'],$lastDim['volume_ballon'],$lastDim['chauffage_electrique']);
	$calc2[]=new F_chart($lastDim['surface'],2.5,$row['mois'],$lastDim['nombre_habitant'],$lastDim['type_chauffage'],$Coeff_dep,$capteur,$i*$capteur['surface'],$igp2[$mois]->Igp,$calc_chauff[$mois]->Besoins_ch,$calc_ecs[$mois]->Becs,$usage,$row['temp_ext'],$row['temp_eau'],$lastDim['temperature'],$lastDim['volume_ballon'],$lastDim['chauffage_electrique']);
	$calc3[]=new F_chart($lastDim['surface'],2.5,$row['mois'],$lastDim['nombre_habitant'],$lastDim['type_chauffage'],$Coeff_dep,$capteur,$i*$capteur['surface'],$igp3[$mois]->Igp,$calc_chauff[$mois]->Besoins_ch,$calc_ecs[$mois]->Becs,$usage,$row['temp_ext'],$row['temp_eau'],$lastDim['temperature'],$lastDim['volume_ballon'],$lastDim['chauffage_electrique']);
	
	
	$mois++;
	}
	
usort($calc1, "sort_month");
usort($calc2, "sort_month");
usort($calc3, "sort_month");

for($s=0;$s<12;$s++){
	$incli_graph[]=array("month"=>$month[$s],"couv1"=>round($calc1[$s]->Taux_couverture,2),"couv2"=>round($calc2[$s]->Taux_couverture,2),"couv3"=>round($calc3[$s]->Taux_couverture,2));
}

usort($calc_chauff, "sort_month");
usort($calc_igp, "sort_month");
usort($calc_ecs, "sort_month");
usort($calc_couverture_final, "sort_month");

for($s=0;$s<12;$s++){
	if($calc_chauff[$s]->Besoins_ch==1){
$calc_chauff[$s]->Besoins_ch=0;
	}
}




function G_dep($annee){
	switch($annee){
		case $annee <= 1980 : return 0.9;
		case $annee <= 2000 : return 0.75;
		case $annee <= 2005 : return 0.65;
		case $annee <= 2012 : return 0.35;
		case $annee <= 2020 : return 0.1;
	}
}

function sort_month($a, $b)
{
     if ($a->nbJours == $b->nbJours) {
        return 0;
    }
    return ($a->nbJours < $b->nbJours) ? -1 : 1;
}




function calculs_distance($long,$lat,$bdd){
	
$sql = 'SELECT * from position_station GROUP BY longitude,latitude order by nom'; 
// echo 'long : '.$long.' lat : '.$lat.'<br>';
$long_A = $long*pi()/180;
$lat_A = $lat*pi()/180;
$a = 6378137;
$b = $a - ($a/298.257222101);
$e = (pow((pow($a,2)-pow($b,2)),0.5)/$a);



foreach($bdd->query($sql) as $row){
	
$long_B = $row['longitude']*pi()/180;
$lat_B = $row ['latitude']*pi()/180;
$p = ($a*(1-pow($e,2)))/(pow(1-(pow($e,2)*pow(sin($long_B),2)),1.5));
$N = $a/(pow(1-(pow($e,2)*pow(sin($long_B),2)),0.5));
$R = pow($N*$p,0.5);


	if($long_A>$long_B){
	$Delta_long = ($long_A-$long_B);
	}
	else
	{
	$Delta_long = ($long_B-$long_A);
	}

$Dist_ang = acos((sin($lat_A)*sin($lat_B))+(cos($lat_A)*cos($lat_B)*cos($Delta_long)));

$array_position[] = array($Dist_ang*$R,$row['nom'],$row['longitude'],$row['latitude']);

}
usort($array_position,"sort_dist");

foreach($array_position as $ville){
	//echo $ville[1].' : '.$ville[0]. ' m long : 0'.$ville[2].' lat : '.$ville[3].'<br>'; //affiche les stations de la plus proche � la plus �loign�e
}	
return $array_position;
}


function stations_triangle($array_position,$long,$lat){
	
	$stations_proches[]=$array_position[0];
	$stations_proches[]=$array_position[1];
	
	for($i=2;$i<sizeof($array_position);$i++){
		if(pointInTriangle($long,$lat,$array_position[0][2],$array_position[0][3],$array_position[1][2],$array_position[1][3],$array_position[$i][2],$array_position[$i][3])){
		$stations_proches[]=$array_position[$i];
		return $stations_proches;
		}
	}
	
	//$stations_proches[]=$array_position[2]; //si on veut retourner les trois plus proches
	return $stations_proches; 
	
	
}

function station_unique($array,$bdd){
	
	$req = 'SELECT * from position_station where nom = \''.$array[0][1].'\'';
	
	foreach($bdd->query($req) as $row){
		$res[]=$row;
	}
	// var_dump($res);
	return $res;
}


function interpolation($array,$bdd)
{
	$req = 'SELECT * from position_station where nom = \''.$array[0][1].'\'';
	$req2 = 'SELECT * from position_station where nom = \''.$array[1][1].'\'';
	$req3 = 'SELECT * from position_station where nom = \''.$array[2][1].'\'';
	
	foreach($bdd->query($req) as $row){
		$res[]=$row;
	}
	foreach($bdd->query($req2) as $row){
		$res2[]=$row;
	}
	foreach($bdd->query($req3) as $row){
		$res3[]=$row;
	}	
	// var_dump($res);
	// var_dump($res2);
	// var_dump($res3);
	
	$L=$array[0][0]+$array[1][0]+$array[2][0];
	$LA=($L-$array[0][0])/$L;
	$LB=($L-$array[1][0])/$L;
	$LC=($L-$array[2][0])/$L;
	$D=$LA+$LB+$LC;
	
	$i=0;	
	
	foreach($res as $row){
		
		$station_interpolee[]=array("mois"=>$row['mois'],"igh_kw"=>(($res[$i]['igh_kw']*$LA+$res2[$i]['igh_kw']*$LB+$res3[$i]['igh_kw']*$LC))/$D,
		"dju_quot"=>(($res[$i]['dju_quot']*$LA+$res2[$i]['dju_quot']*$LB+$res3[$i]['dju_quot']*$LC))/$D,"temp_eau"=>(($res[$i]['temp_eau']*$LA+$res2[$i]['temp_eau']*$LB+$res3[$i]['temp_eau']*$LC))/$D,"temp_ext"=>(($res[$i]['temp_ext']*$LA+$res2[$i]['temp_ext']*$LB+$res3[$i]['temp_ext']*$LC))/$D);
		$i++;
	}
	echo '<br>';
	foreach($station_interpolee as $row){
	//	echo $row['mois']." igh: ".$row['igh_kw']." dju_quot: ".$row['dju_quot']." temp eau: ".$row['temp_eau']." temp ext: ".$row['temp_ext'].'<br>';
	}
	
return $station_interpolee;
	
	
}
	
	


function sort_dist($a, $b)
{
     if ($a[0] == $b[0]) {
        return 0;
    }
    return ($a[0]< $b[0]) ? -1 : 1;
}


?>



<!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
	 <title>C.I.E.S.S.T.</title>

    <!-- Bootstrap Core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../css/sb-admin.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->


 <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script> 

</head>
<body style="overflow-X:hidden;">
 <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="presentation.php"><img src="../img/logo.jpg" alt="logo" height="50" width="130"></a>
            </div>
            <!-- Top Menu Items -->
            <ul class="nav navbar-right top-nav">               
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $_SESSION['user']; ?> <b class="caret"></b></a>
                    <ul class="dropdown-menu">                       
                        <li>
                            <a href="../Controleur/logout.php"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                 <ul class="nav navbar-nav side-nav">
                    <li>
                        <a href="presentation.php"><i class="fa fa-fw fa-dashboard"></i> Présentation</a>
                    </li>
                    <li class="active">
                        <a href="geolocaliser.php"><i class="fa fa-fw fa-bar-chart-o"></i> Dimensionner</a>
                    </li>
                    <li>
                        <a href="remerciements.php"><i class="fa fa-fw fa-table"></i> Remerciements</a>
                    </li>
					 <li>
                        <a href="sources.php"><i class="fa fa-fw fa-table"></i> Sources</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Dimensionnement
                            <small>Etape finale : Dimensionnement des panneaux solaires thermiques</small>
                        </h1>                      
						<div><table class="table"><tr class="info"><td>Mois</td><?php

foreach($calc_igp as $calc){
	echo '<td>'.$calc->mois.'</td>';
}
echo '</tr><tr class="success"><td>IGP journalier (KWh/m²) <div class="infobulle"><i class="fa fa-info-circle"></i><div>Irradiation globale dans le plan du capteur</div></div></td>';

foreach($calc_igp as $calc){
	echo '<td>'.round($calc->Igp,2).'</td>';	
}
echo '</tr><tr><td>IGP mensuel (KWh/m²)</td>';
foreach($calc_couverture_final as $calc){
	echo '<td>'.round(($calc->IGP*$calc->NBJoursMois/3600000),2).'</td>';	
}
echo '</tr><tr class="success"><td>Besoins ECS journalier (KWh)</td>';

foreach($calc_ecs as $calc){
	echo '<td>'.round($calc->Becs,2).'</td>';	
}

$mois=0;
echo '</tr><tr><td>Besoins ECS mensuel (KWh)</td>';

foreach($calc_ecs as $calc){
	echo '<td>'.round(($calc->Becs*$calc_couverture_final[$mois]->NBJoursMois),2).'</td>';
		$mois++;
}
$mois=0;
echo '</tr><tr class="success"><td>Besoins chauffage journalier (KWh)</td>';
foreach($calc_chauff as $calc){
	
	echo '<td>'.round($calc->Besoins_ch,2).'</td>';	
	$igp_graph[]=array("month"=>$month[$mois],"igp"=>round(($calc_igp[$mois]->Igp*$calc_couverture_final[$mois]->NBJoursMois),2),"ecs"=>round(($calc_ecs[$mois]->Becs*$calc_couverture_final[$mois]->NBJoursMois),2),"chauff"=>round(($calc->Besoins_ch*$calc_couverture_final[$mois]->NBJoursMois),2));
	$mois++;
	
}

$mois=0;

echo '</tr><tr ><td>Besoins chauffage mensuel (KWh)</td>';

foreach($calc_chauff as $calc){

	echo '<td>'.round(($calc->Besoins_ch*$calc_couverture_final[$mois]->NBJoursMois),2).'</td>';	
	
	$mois++;
}
echo '</tr></table>';
echo '<div  class="col-lg-6" id="igp_graph" style="height: 300px;margin-left:25%;"></div><div  class="col-lg-12" style="margin-left:37%;margin-bottom:5%;">IGP, Besoins en ECS et en chauffage en fonction du mois</div>';
echo '<table class="table" style="margin-bottom:5%;"><CAPTION><a href="../Cours/Algorithmes et calculs energetiques.pdf" target="_blank">MÉTHODE F-CHART</a></CAPTION><tr class="info"><td>Mois</td>';
foreach($calc_igp as $calc){
	echo '<td>'.$calc->mois.'</td>';
}
echo '</tr><tr class="success"><td>X <div class="infobulle"><i class="fa fa-info-circle"></i><div>Ratio des pertes du collecteur par rapport à la charge</div></div></td>';
foreach($calc_couverture_final as $calc){
	echo '<td>'.round($calc->X,2).'</td>';	
}
echo '</tr><tr ><td>Y <div class="infobulle"><i class="fa fa-info-circle"></i><div>Ratio du rayonnement absorbé par rapport à la charge</div></div></td>';
foreach($calc_couverture_final as $calc){
	echo '<td>'.round($calc->Y,2).'</td>';
	
}
echo '</tr><tr class="success"><td>Xc <div class="infobulle"><i class="fa fa-info-circle"></i><div>Coefficient correctif en fonction du volume du ballon</div></div></td>';
foreach($calc_couverture_final as $calc){
	echo '<td>'.round($calc->Xc,2).'</td>';	
}
echo '</tr><tr><td>Xcc <div class="infobulle"><i class="fa fa-info-circle"></i><div>Coefficient correctif en fonction de l\'eau froide, de l\'eau chaude et de la température ambiante </div></div></td>';
foreach($calc_couverture_final as $calc){
	echo '<td>'.round($calc->Xcc,2).'</td>';	
}
echo '</tr><tr class="success"><td>Yc <div class="infobulle"><i class="fa fa-info-circle"></i><div>Coefficient correctif en fonction de l\'échangeur (à prendre en compte que pour un usage de chauffage)</div></div></td>';
foreach($calc_couverture_final as $calc){
	echo '<td>'.round($calc->Yc,2).'</td>';	
}
echo '</tr><tr ><td>X\' <div class="infobulle"><i class="fa fa-info-circle"></i><div>X corrigé avec Xc et Xcc</div></div></td>';
foreach($calc_couverture_final as $calc){
	echo '<td>'.round($calc->X_prime,2).'</td>';	
}
echo '</tr><tr class="success"><td>Y\' <div class="infobulle"><i class="fa fa-info-circle"></i><div>Y corrigé avec Yc</div></div></td>';
foreach($calc_couverture_final as $calc){
	echo '<td>'.round($calc->Y_prime,2).'</td>';	
}
echo '</table><table class="table"><tr class="info"><td>Mois</td>';
foreach($calc_igp as $calc){
	echo '<td>'.$calc->mois.'</td>';
}
echo '</tr><tr class="danger"><td>Couverture (%)</td>';

$j=0;
foreach($calc_couverture_final as $calc){
	echo '<td>'.round($calc->Taux_couverture,2).'</td>';	
	$taux_graph[]=array("month"=>$month[$j],"taux"=>round($calc->Taux_couverture,2));
	
	if($calc->Taux_couverture>100){
		$plaf=100;
	}
	else{
		$plaf=$calc->Taux_couverture;
	}
	$taux_graph_plaf[]=array("month"=>$month[$j],"taux"=>round($plaf,2));
	$j++;
}

	
echo '</tr><tr ><td>Apport solaire mensuel (KWh)</td>';
$mois=0;
$production_annuelle=0;
$somme_igp_mensuel=0;
foreach($calc_couverture_final as $calc){
	$somme_igp_mensuel=$somme_igp_mensuel+($calc->IGP*$calc->NBJoursMois/3600000);
	$production_annuelle=$production_annuelle+($calc_chauff[$mois]->Besoins_ch+$calc_ecs[$mois]->Becs)*$calc->NBJoursMois*($calc->Taux_couverture/100);
	echo '<td>'.round(($calc_chauff[$mois]->Besoins_ch+$calc_ecs[$mois]->Becs)*$calc->NBJoursMois*($calc->Taux_couverture/100),2).'</td>';	
	$mois++;
}
$mois=0;
echo '</tr><tr class="success"><td>Apport solaire ECS mensuel (%)</td>';
foreach($calc_couverture_final as $calc){	
if(($calc_chauff[$mois]->Besoins_ch+$calc_ecs[$mois]->Becs)!=0){
	echo '<td>'.round(($calc_ecs[$mois]->Becs*$calc->NBJoursMois*100)/(($calc_chauff[$mois]->Besoins_ch+$calc_ecs[$mois]->Becs)*$calc->NBJoursMois),2).'</td>';	
}
else{
	echo '<td>0</td>';
}
	$mois++;
}
$mois=0;
echo '</tr><tr ><td>Apport solaire ECS mensuel (KWh)</td>';
foreach($calc_couverture_final as $calc){	
if(($calc_chauff[$mois]->Besoins_ch+$calc_ecs[$mois]->Becs)!=0){
	echo '<td>'.round(($calc_ecs[$mois]->Becs*$calc->NBJoursMois)/(($calc_chauff[$mois]->Besoins_ch+$calc_ecs[$mois]->Becs)*$calc->NBJoursMois)*($calc_chauff[$mois]->Besoins_ch+$calc_ecs[$mois]->Becs)*$calc->NBJoursMois*($calc->Taux_couverture/100),2).'</td>';	
	$taux_graph2[]=array("month"=>$month[$mois],"ecs"=>round(($calc_ecs[$mois]->Becs*$calc->NBJoursMois)/(($calc_chauff[$mois]->Besoins_ch+$calc_ecs[$mois]->Becs)*$calc->NBJoursMois)*($calc_chauff[$mois]->Besoins_ch+$calc_ecs[$mois]->Becs)*$calc->NBJoursMois*($calc->Taux_couverture/100),2),"chauff"=>round(($calc_chauff[$mois]->Besoins_ch*$calc->NBJoursMois)/(($calc_chauff[$mois]->Besoins_ch+$calc_ecs[$mois]->Becs)*$calc->NBJoursMois)*($calc_chauff[$mois]->Besoins_ch+$calc_ecs[$mois]->Becs)*$calc->NBJoursMois*($calc->Taux_couverture/100)),2);
}
else{
	echo '<td>0</td>';
	$taux_graph2[]=array("month"=>$month[$mois],"ecs"=>0,"chauff"=>0);
}

	
	$mois++;
}
$mois=0;
echo '</tr><tr class="success"><td>Apport solaire Chauffage mensuel (%)</td>';
foreach($calc_couverture_final as $calc){	
if(($calc_chauff[$mois]->Besoins_ch+$calc_ecs[$mois]->Becs)!=0){
	echo '<td>'.round(($calc_chauff[$mois]->Besoins_ch*$calc->NBJoursMois*100)/(($calc_chauff[$mois]->Besoins_ch+$calc_ecs[$mois]->Becs)*$calc->NBJoursMois),2).'</td>';	
}else{
	echo '<td>100</td>';
}	
	$mois++;
}
$mois=0;
echo '</tr><tr ><td>Apport solaire Chauffage mensuel (KWh)</td>';
foreach($calc_couverture_final as $calc){	
if(($calc_chauff[$mois]->Besoins_ch+$calc_ecs[$mois]->Becs)!=0){
	echo '<td>'.round(($calc_chauff[$mois]->Besoins_ch*$calc->NBJoursMois)/(($calc_chauff[$mois]->Besoins_ch+$calc_ecs[$mois]->Becs)*$calc->NBJoursMois)*($calc_chauff[$mois]->Besoins_ch+$calc_ecs[$mois]->Becs)*$calc->NBJoursMois*($calc->Taux_couverture/100),2).'</td>';	
}
else{
	echo '<td>0</td>';
}
	$mois++;
}
 
echo '</tr></table>';

if($i<6){
echo '<div style="margin-left: 25%;margin-bottom:5%;font-size:150%;color:green;"><b>'.$i.'</b> panneau(x) (surface unitaire: '.$capteur['surface'].' m²)  MONTAGE SERIE</div>';
}
else{
	echo '<div style="margin-left: 25%;margin-bottom:5%;font-size:150%;color:green;"><b>'.$i.'</b> panneau(x) (surface unitaire: '.$capteur['surface'].' m²) <div style="color:red;"><span class="glyphicon glyphicon-alert"></span>  Il n\'est pas conseillé de raccorder plus de 6 panneaux en série<div class="infobulle"><i class="fa fa-info-circle"></i><div>En parallèle, le rendement est meilleur mais des problèmes de longueur de tuyauterie peuvent se poser!</div></div></div></div>';
}

echo '<table class="table"><tr class="danger"><td>SURFACE TOTALE</td><td>'.$i*$capteur['surface'].' m²</td></tr>';
echo '<tr ><td>TAUX ANNUEL DE COUVERTURE</td><td>'.round($moyenne_couverture,2).'%</td></tr>';
echo '<tr class="danger"><td>TAUX ANNUEL DE COUVERTURE PLAFONNÉ <div class="infobulle"><i class="fa fa-info-circle"></i><div>Moyenne où les taux de couverture mensuels sont plafonnés à 100%</div></div></td><td>'.round($moyenne_couverture_plafond,2).'%</td></tr>';
echo '<tr><td>PRODUCTION ANNUELLE</td><td>'.round($production_annuelle,2).' KWh</td></tr>';
echo '<tr class="danger"><td>RENDEMENT ANNUEL DU CAPTEUR</td><td>'.round((100*$production_annuelle/(($somme_igp_mensuel)*$i*$capteur['surface'])),2).'%</td></tr></table>';?>

						
						</div>
						<div  class="col-lg-6" id="taux_couv" style="height: 300px;"></div>
						<div  class="col-lg-6" id="taux_couv_plaf" style="height: 300px;"></div>
						<div  class="col-lg-1"  ></div><div  class="col-lg-5" >Taux de couverture en fonction du mois</div>
						<div  class="col-lg-1"  ></div><div  class="col-lg-5" >Taux de couverture plafonné en fonction du mois</div>
						<div class="col-lg-6" id="apports_tot" style="height: 300px;"></div>
						<div  class="col-lg-5" id="graph_inc" style="height: 300px;"></div>
						<div  class="col-lg-2"  ></div><div  class="col-lg-5" >Apports en ECS et chauffage en fonction du mois</div>
						<div  class="col-lg-5" >Taux de couverture avec des inclinaisons définies en fonction du mois</div>
						
                    </div>
		<div class="col-lg-6">
			 
                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="../js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../js/bootstrap.min.js"></script>
</body>
<script>
var months = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];

$(function () {
	
	 new Morris.Line({
 
  element: 'igp_graph',
 
  data: <?php echo json_encode($igp_graph);?>,
  
  xkey: 'month',
  
  smooth: false,

  ykeys: ['igp','ecs','chauff'],
  
  lineColors: ['purple','orange','red'],
  
  labels: ['IGP mensuel (KWh/m²)','Besoins ECS mensuel (KWh)','Besoins chauffage mensuel (KWh)'],
   xLabelFormat: function(x) { 
    var month = months[x.getMonth()];
    return month;
  },
  dateFormat: function(x) {
    var month = months[new Date(x).getMonth()];
    return month;
  },
});
	
	new Morris.Line({
 
  element: 'taux_couv_plaf',
 
  data: <?php echo json_encode($taux_graph_plaf);?>,
  
  xkey: 'month',
  
  smooth: false,

  ykeys: ['taux'], 
  
  labels: ['Taux de couverture plafonné (%)'],
   xLabelFormat: function(x) { 
    var month = months[x.getMonth()];
    return month;
  },
  dateFormat: function(x) {
    var month = months[new Date(x).getMonth()];
    return month;
  },
});

    new Morris.Line({
 
  element: 'taux_couv',
 
  data: <?php echo json_encode($taux_graph);?>,
  
  xkey: 'month',

  ykeys: ['taux'],
  
  smooth: false,
  
  labels: ['Taux de couverture (%)'],
   xLabelFormat: function(x) { 
    var month = months[x.getMonth()];
    return month;
  },
  dateFormat: function(x) {
    var month = months[new Date(x).getMonth()];
    return month;
  },
});
 new Morris.Line({
 
  element: 'graph_inc',
 
  data: <?php echo json_encode($incli_graph);?>,
  
  xkey: 'month',

  ykeys: ['couv1','couv2','couv3'],
  
  lineColors: ['purple','orange','red'],
  
  smooth: false,
  
  labels: ['Taux de couverture (%) (inclinaison= 20°)','Taux de couverture (%) (inclinaison= 30°)','Taux de couverture (%) (inclinaison= 40°)'],
   xLabelFormat: function(x) { 
    var month = months[x.getMonth()];
    return month;
  },
  dateFormat: function(x) {
    var month = months[new Date(x).getMonth()];
    return month;
  },
});
  new Morris.Line({
 
  element: 'apports_tot',
 
  data: <?php echo json_encode($taux_graph2);?>,
  
  xkey: 'month',

  ykeys: ['ecs','chauff'],
  
  lineColors: ['green','red'],
  
  smooth: false,
  
  labels: ['Apports ECS mensuel (KWh)','Apports chauffage mensuel (KWh)'],
   xLabelFormat: function(x) { 
    var month = months[x.getMonth()];
    return month;
  },
  dateFormat: function(x) {
    var month = months[new Date(x).getMonth()];
    return month;
  },
});
});
</script>
</html>