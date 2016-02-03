<?php 

session_start();

if(!isset($_SESSION['user'])){
	header('location: ../index.php');
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

<body onload= "checkbox()">

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
                            <small>Etape 2 : Calcul des besoins nécessaires</small>
                        </h1>                       
                    </div>
		<div class="col-lg-6">
			 <form role="form" method="post" action="../Controleur/addDimensionner.php">
				<div class="form-group">
                                	<label>Type d'usage</label>
                                	<select class="form-control" name="usage" id= "usage" onchange="blockElectrique()">
							<option>Eau Chaude Sanitaire</option>
							<option>Chauffage</option>
							<option>Eau Chaude Sanitaire + Chauffage</option>
					</select>
				</div>
				<div class="form-group">
                                <label>Connaissez-vous le volume de votre ballon d'eau chaude ?</label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="checkbox_ballon" id="checkbox_ballon" onclick="blockElectrique();checkbox()">oui
                                </label>
                </div>
                <div class="form-group">
                                	<label>Volume du ballon d'eau chaude (en litres)</label>
                                	<input class="form-control" placeholder= "Entrer le volume du ballon d'eau chaude" name= "volume_ballon" id= "volume_ballon" type = "number" min= "0" max="3000" disabled="disabled" required>
            	</div>
                <div class="form-group">
                                <label>Nombre d'occupants</label>
                                <div class="infobulle">
                                <i class="fa fa-info-circle"></i>
                                <div> En moyenne une personne consomme 50L d'eau par jour</div>
                                </div>
                                <input class="form-control" placeholder="Entrer le nombre d'occupants" name="habitants" id= "habitants" disabled="disabled" type="number" min= "1" required>
                </div>
                <div class="form-group">
                                <label>Votre chauffage est-il électrique ?</label>
                                <div class="infobulle">
                                <i class="fa fa-info-circle"></i>
                                <div>En cochant cette case on considère que votre chauffage est entièrement électrique</div>
                                </div>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="checkbox_electrique" id="checkbox_electrique" disabled="disabled" >oui
                                </label>
                </div>                         
				<div class="form-group">
                                <label>Surface au sol (en m²)</label>
                                <input class="form-control" placeholder="Entrer la surface au sol" name="surface" id="surface" type= "number" min = "0" required>
                            </div>
                
				<div class="form-group">
                                <label>Température d'eau chaude souhaitée(en °C)</label>
                                <div class="infobulle">
                                <i class="fa fa-info-circle"></i>
                                <div> En moyenne la température d'utilisation n'excède pas 50-60°C</div>
                                </div>
                                <input class="form-control" placeholder="Entrer la température de l'eau chaude souhaitée" name="temperature" id="temperature" type= "number" min= "0" max= "100" required>
                            </div>
                <div class="form-group">
                                	<label>Année de construction/rénovation du bâtiment</label>
                                	<div class="infobulle">
                                		<i class="fa fa-info-circle"></i>
                                		<div> Les réglementations thermiques dépendent de l'année de contruction </br>=> un bâtiment plus ancien est moins bien isolé</div>
                                		</div>
                                	<input class="form-control" placeholder="Entrer l'année de construction" name="annee_construction" id="annee_construction" type = "number" min= "1900" max= "2020" required>
            	</div>	
             <div class="form-group">
                                	<label>Type de chauffage</label>
                                	<div class="infobulle">
                                		<i class="fa fa-info-circle"></i>
                               		 	<div> Basse Température : 35°C<br/>Haute Température : 80°C</div>
                                	</div>
                                	<select class="form-control" name="type_chauffage" id= "type_chauffage">
							<option>Chauffage haute température </option>
							<option>Chauffage basse température</option>
					</select>
				</div>
				<button type="submit" class="btn btn-primary">Etape suivante</button>
			</form>
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
    <script type="text/javascript">
 
    function checkbox(){
 
        if(document.getElementById('checkbox_ballon').checked){
 
            document.getElementById('volume_ballon').disabled = '';
            document.getElementById('habitants').disabled = 'disabled';
			document.getElementById('checkbox_electrique').disabled = 'disabled';
			document.getElementById('checkbox_electrique').checked=false;
			document.getElementById('habitants').value='';
 
        }
        
        
        

        else{
 
            document.getElementById('volume_ballon').disabled = 'disabled';
			document.getElementById('volume_ballon').value='';
            document.getElementById('habitants').disabled = '';
			document.getElementById('checkbox_electrique').disabled = '';
			
 
        }
        if (document.getElementById('usage').value == 'Chauffage' || document.getElementById('usage').value == 'Eau Chaude Sanitaire + Chauffage') {
        
        	document.getElementById('checkbox_electrique').checked=false;
    		document.getElementById('checkbox_electrique').disabled = 'disabled';
        }
 
    }
    
    function blockElectrique() {
    	 
    	if(document.getElementById('usage').value != 'Eau Chaude Sanitaire') {
    		document.getElementById('checkbox_electrique').checked=false;
    		document.getElementById('checkbox_electrique').disabled = 'disabled';	
    	
    	
    	}
    	else {
    		document.getElementById('checkbox_electrique').disabled = '';
    		
    	}
    	
    
    
    }
 
</script>

</body>

</html>
