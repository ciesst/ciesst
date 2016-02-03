<?php
//handle the login system (connection/creation/hash)

include 'BddConnexion.php';


$conn = new BddConnexion(); 
$conn ->connect();
$bdd= $conn->get_bdd();

$name=$_POST['user_id'];
$password=$_POST['password'];

if (isset($_REQUEST['Submit'])) 
{
	 if($_REQUEST['user_id']=="" || $_REQUEST['password']=="")
    {
    echo "Tous les champs doivent être remplis";
    }
	else{
			$req = $bdd->prepare('SELECT * from user where name = :name and login= :password');

		try{
$req->execute(array(
	'name' => $name,
	'password' => hash("ripemd160",$password)
	));	

}
		catch(Exception $e){
	echo "Probleme: ", $e->getMessage();
	die();
}

$count = $req->rowCount();

if($count>0){
	
	session_start();
	$_SESSION['user']=$name;
	header('location: ../Vue/presentation.php');
}
else {
	echo "nom et/ou mot de passe incorrectes";
}

	}	
}

if (isset($_REQUEST['Register'])) 
{
	 if($_REQUEST['user_id']=="" || $_REQUEST['password']=="")
    {
    echo "Tous les champs doivent être remplis";
    }	
	else{
		$req = $bdd->prepare('SELECT * from user where name = :name');

		try{
$req->execute(array(
	'name' => $name
	
	));	

}
		catch(Exception $e){
	echo "Probleme: ", $e->getMessage();
	die();
}
$count = $req->rowCount();

if($count==0 && $name != 'admin'){
			$req = $bdd->prepare('INSERT INTO user (name,login) VALUES(:name,:login)');

		try{
$req->execute(array(
	'name' => $name,
	'login' => hash("ripemd160",$password)	
	));	

}
		catch(Exception $e){
	echo "Probleme: ", $e->getMessage();
	die();
}
session_start();
$_SESSION['user']=$name;
header('location: ../Vue/presentation.php');
}
else{
	echo "Ce nom existe déja";
}
	}
}



?>