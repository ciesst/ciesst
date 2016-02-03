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
                    <li >
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

            <div class="container">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Dimensionnement
                            <small>Etape 1 : Où vous trouvez-vous ?</small>
                        </h1>                       
                    </div>
		
		<form action="#" role="form" onsubmit="TrouverAdresse();">
			<div class="form-group">
			<input type="text" id="adresse"/>
			<button type="submit" class="btn btn-primary">Rechercher</button>
			</div>
		</form>
		<div class="col-lg-12">
			<div id="googleMap" style="width:90%;height:380px;"></div>
		</div>
		<div class="col-lg-3">
  			<form action="../Controleur/addLoc.php" method="POST" onsubmit="return verif(this)">
  				<div class="form-group">
                                	<label>Latitude</label>
                                	<input class="form-control" id="lat" name="lat" readonly>
                           	 </div>
				<div class="form-group">
                                	<label>Longitude</label>
                                	<input class="form-control" id="lng" name="lng" readonly>
                           	 </div>
                           	 
                <div class="form-group">
                                	<label>Pays</label>
                                	<input class="form-control" id="pays" name="pays" onblur="verifPays(this)" readonly>
                           	 </div>           	 
				<button type="submit" class="btn btn-primary">Etape suivante</button>
			</form>
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

    <!-- Bootstrap Core JavaScript -->
    <script src="../js/bootstrap.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
    <script src="../js/DisplayMap.js"></script>
    <script src="../js/VerifForm.js"></script>

</body>

</html>
