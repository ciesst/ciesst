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
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

 

</head>

<body style="background: url('img/background.jpg');background-repeat:no-repeat;background-size:100%;" >

<div class="row">

<div style="margin-left:auto;margin-top:10%;margin-right:auto;text-align:center;" >
<img src="img/logo_calque.png" alt="logo" height="250" width="375"></div>
  
<div style="margin-left:auto;margin-right:auto;width:20%;text-align:center;" >
      <form class="form-signin" name="form_login" method="post" action="Controleur/login.php" role="form">
            
        <input name="user_id" type="text" id="user_id" placeholder="Nom" class="form-control" required autofocus> 
        <input type="password" name="password" id="password" placeholder="Mot de passe" class="form-control" required>       
		<input class="btn btn-lg btn-primary btn-block" type="submit" name="Submit" value="Se connecter">
		<input class="btn btn-lg btn-primary btn-block" type="submit" name="Register" value="S'enregistrer">
      </form>
	
	</div>
    </div> 	

</body>
</html>