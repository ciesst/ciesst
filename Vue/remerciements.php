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
                </li>
            </ul>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <li >
                        <a href="presentation.php"><i class="fa fa-fw fa-dashboard"></i> Présentation</a>
                    </li>
                    <li>
                        <a href="geolocaliser.php"><i class="fa fa-fw fa-bar-chart-o"></i> Dimensionner</a>
                    </li>
                    <li class="active">
                        <a href="#"><i class="fa fa-fw fa-table"></i> Remerciements</a>
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
                            Remerciements
                        </h1>                       
                    </div>
                </div>
                <!-- /.row -->
              <div class="col-lg-12 texte_presentation">
                	<p>Nous tenons à remercier l’ensemble des contacts cités dans ce rapport pour le soutien qu’ils nous ont 
                	apporté dans le cadre du projet CIESST. Nous vous remercions pour le précieux temps que vous nous avez 
                	accordé ainsi que pour les nombreux conseils et avis délivrés tout au long du projet. 
                	Nous remercions particulièrement Météofrance et Monsieur Denis Cendrier pour la grande aide apportée dans 
                	la rédaction de la convention et pour nous avoir donné accès aux données météorologiques indispensables à 
                	la mise en place des calculs énergétiques.</p>  
					<p>Nous souhaitons adresser un remerciement tout particulier à Monsieur Jean-José WANEGUE pour 
					avoir été si présent et si impliqué dans notre projet, et grâce à qui nous avons beaucoup appris et fait 
					de ce Projet de Fin d’Etudes un projet fascinant.</p> 
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
