<?php 

session_start();

if(!isset($_SESSION['user'])){
	header('location: index.php');
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

</head>

<body onload="go(); go2()">

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
                            <a href="Controleur/logout.php"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
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
                            <small>Etape 3 : Calcul du rendement du capteur</small>
                        </h1>                        
                    </div>
		<div class="col-lg-3">
			 <form role="form" method="post" action="../Controleur/addDimensionner2.php">
				<div class="form-group">
                                	<label>Types</label>
                                	<div class="infobulle">
                                <i class="fa fa-info-circle"></i>
                                <div> Plan vitré : Adapté pour un usage individuel (plage de température : 20 - 70 °C) </br>Sous vide : Adapté pour un usage collectif (plage de température : 60 - 150 °C) </div>
                                </div>
				<select class="form-control" name='capteur' id='capteur' onchange='go(); go2()'>				
				<option value='1'>Capteur plan vitré</option>
				<option value ='2'>Capteur tubes sous vide</option>
				</select>
				</div>
			
				<label>Modèle</label> <label> <i>(Ajouter votre propre capteur <a href="javascript:add_capteur();">ici</a> )</i></label>
				<div class="form-group">
				<select class="form-control" name='liste' id='liste' onchange= 'go2()'>
					<option value='-1'>Choisir un type</option>
					
				</select>			
              
				</div>
				<div class="form-group">
                                	<label>Orientation</label>
                                	<div class="infobulle">
                                	<i class="fa fa-info-circle"></i>
                                	<div>Sud : 0° comptez positivement vers l'ouest</div>
                                </div>
                                	<select class="form-control" name="orientation" id="orientation">
                                		<option>-165</option>
                                		<option>-150</option>
                                		<option>-135</option>
                                		<option>-120</option>
                                		<option>-105</option>
                                		<option>Est</option>
                                		<option>-75</option>
                                		<option>-60</option>
                                		<option>-45</option>
                                		<option>-30</option>
                                		<option>-15</option>
                                		<option selected="">Sud</option>
                                		<option>15</option>
                                		<option>30</option>
                                		<option>45</option>
                                		<option>60</option>
                                		<option>75</option>
                                		<option>Ouest</option>
                                		<option>105</option>
                                		<option>120</option>
                                		<option>135</option>
                                		<option>150</option>
                                		<option>165</option>
                                		<option>Nord</option>
                                	</select>
                </div>
				<div class="form-group">
                                	<label>Inclinaison</label>
                                	<div class="infobulle">
                                		<i class="fa fa-info-circle"></i>
                                		<div>Par rapport à l'horizontal (de 0° à 90° )</div>
                                	</div>
                                	<input class="form-control" placeholder="Entrer l'inclinaison choisie" name ="inclinaison" id="inclinaison" type = "number" min="0" max="90" required>
                </div>
                <div class="form-group">
                                	<label>Albédo</label>
                                		<div class="infobulle">
                                		<i class="fa fa-info-circle"></i>
                                			<div>Pouvoir réfléchissant d'une surface</br>0.1 forêt</br>0.6 glace</br>1 miroir parfait</div>
                               			</div>
                                	<input class="form-control" placeholder="Entrer l'albédo entre 0 et 1" id="albedo" name="albedo" type= "number" min= "0" max= "1" step= "0.01" required>
                </div>
				<button type="submit" class="btn btn-primary">Etape suivante</button>
			 </form>
        </div>
        
        <div class="col-lg-9">
        	<div class="table-responsive">
                            <table id="tableau_capteur" name="tableau_capteur" class="table table-bordered table-hover table-striped">
                            </table>
            </div>
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
	<script src="../js/AjaxCapteur.js"></script>	

    <!-- Bootstrap Core JavaScript -->
    <script src="../js/bootstrap.min.js"></script>
    <script>
    function go2() {
				var xhr = getXhr();
				// On défini ce qu'on va faire quand on aura la réponse
				xhr.onreadystatechange = function(){
					// On ne fait quelque chose que si on a tout reçu et que le serveur est ok
					if(xhr.readyState == 4 && xhr.status == 200){
						leselect = xhr.responseText;
						// On se sert de innerHTML pour rajouter les options a la liste
						document.getElementById('tableau_capteur').innerHTML = leselect;
					}
				}
				
				xhr.open("POST","../Controleur/ajaxTableau.php",true);
				xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
				sel = document.getElementById('liste');
				modele = sel.options[sel.selectedIndex].value;
				xhr.send("modele="+modele);
			}
			
			</script>
	
</body>

</html>
