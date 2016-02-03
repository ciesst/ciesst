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

<body>

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
                 <a href="#"><img src="../img/logo.jpg" alt="logo" height="50" width="130"></a>
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
                </li>
            </ul>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <li>
                        <a href="presentation.php"><i class="fa fa-fw fa-dashboard"></i> Présentation</a>
                    </li>
                    <li>
                        <a href="geolocaliser.php"><i class="fa fa-fw fa-bar-chart-o"></i> Dimensionner</a>
                    </li>
                    <li>
                        <a href="remerciements.php"><i class="fa fa-fw fa-table"></i> Remerciements</a>
                    </li>
                    <li class="active">
                        <a href="#"><i class="fa fa-fw fa-table"></i> Sources</a>
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
                            Sources
                        </h1>                       
                    </div>
                </div>
                <!-- /.row -->
                <div class="row">
                	<div class="col-lg-12">
                		<h4 class="page-header">Synthèse</h4>
                			<ul>
                				<li> 
                					<a href="../Cours/Algorithmes et calculs energetiques.pdf" target="_blank">
                					Algorithmes et calculs énergétiques</a>
                				</li>
                			</ul>
            		</div>
                	<div class="col-lg-12">
                		<h4 class="page-header">Ressource solaire</h4>
                			<ul>
                				<li> 
                					<a href="http://www2.warwick.ac.uk/fac/sci/eng/staff/dbm/es368/" target="_blank">
                						Cours sur Energie solaire et irradiation sur plan incliné</a>
                				</li>
                				<li>
                					<a href="http://herve.silve.pagesperso-orange.fr/solaire.htm" target="_blank">
                						Description du chauffage, ECS</a>
                				</li>
                				<li>
                					<a href="http://ines.solaire.free.fr/solth/page0.html" target="_blank">
                						Cours de solaire thermique et ressource solaire</a>
                				</li>
                			</ul>
            		</div>
            		<div class="col-lg-12">
                		<h4 class="page-header">F-Chart</h4>
                			<ul>
                				<li>
                					<a href="http://www.retscreen.net/fr/solar_water_heating_e_textbook_chapter.php" target="_blank">
                						Exemple de cette méthode avec de l'eau chaude sanitaire</a>
                				</li>
                				<li>
                					<a href="http://www.batiment-energie.org/doc/16/Annexe-VII-Apports-thermiques-avec-collecteurs.pdf" target="_blank">
                						Exemple complet de chauffage d’ECS dans une maison de retraite</a>
                				</li>
                				<li>
                					<a href="http://www.almamapper.com/media/library/files/ParitoshRane/20151028_173953.830354.pdf" target="_blank">
                						Document sur les différents facteurs de la méthode F-Chart, très complet</a>
                				</li>
                			</ul>
                	</div>
                	<div class="col-lg-12">
                		<h4 class="page-header">Données sur les panneaux solaires thermiques</h4>
                			<ul>
                				<li>
                					<a href="http://www.spf.ch/Capteurs.111.0.html?&L=7" target="_blank">
                						Caractéristiques techniques des panneaux solaires</a>
                				</li>
                				<li>
                					<a href="http://www.tecsol.fr/st_fr/" target="_blank">
                						Autres descriptions de panneaux solaires</a>
                				</li>
                			</ul>
                	</div>
                	
            		
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

</html>
